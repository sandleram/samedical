<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Domain\Usuario\Usuario as UsuarioEntity;
use App\Domain\Usuario\UsuarioRepositoryInterface;
use App\Domain\Usuario\UsuarioSearchCriteria;
use App\Models\Bi;
use App\Models\Cliente;
use App\Models\User as UsuarioModel;
use App\Models\UsuarioBi;
use App\Models\UsuarioCliente;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

final class EloquentUsuarioRepository implements UsuarioRepositoryInterface
{
    public function search(UsuarioSearchCriteria $criteria, TenantScope $tenant, bool $isRoot): PagedResult
    {
        $query = UsuarioModel::query()
            ->with(['perfil', 'grupoEmpresarial'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant, $isRoot));

        if ($criteria->excludeRootUser) {
            $query->where('usuario.id', '<>', 1);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('usuario.id', (int) $criteria->id);
        }

        if ($criteria->nome !== '') {
            foreach (preg_split('/\s+/', $criteria->nome) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('usuario.nome', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->usuario !== '') {
            foreach (preg_split('/\s+/', $criteria->usuario) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('usuario.usuario', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->email !== '') {
            foreach (preg_split('/\s+/', $criteria->email) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('usuario.email', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->perfil !== '' && is_numeric($criteria->perfil)) {
            $query->where('usuario.perfil_id', (int) $criteria->perfil);
        }

        if ($criteria->status !== '') {
            $query->where('usuario.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('usuario.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        $items = array_map(
            fn (UsuarioModel $model) => $this->toEntity($model),
            $paginator->items(),
        );

        return new PagedResult(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant, bool $isRoot): ?UsuarioEntity
    {
        $model = UsuarioModel::query()
            ->with(['perfil', 'grupoEmpresarial', 'usuarioCriador', 'usuarioClientes', 'usuarioBis'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant, $isRoot))
            ->find($id);

        return $model ? $this->toEntity($model, withLinks: true) : null;
    }

    public function save(
        array $data,
        array $clienteIds,
        array $biIds,
        ?int $existingId,
        TenantScope $tenant,
        bool $isRoot,
    ): UsuarioEntity {
        $id = DB::transaction(function () use ($data, $clienteIds, $biIds, $existingId, $tenant, $isRoot) {
            if ($existingId !== null) {
                $model = UsuarioModel::query()
                    ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant, $isRoot))
                    ->find($existingId);

                if (! $model) {
                    throw new RuntimeException('Usuário Inexistente');
                }

                if (isset($data['senha']) && $data['senha'] !== '') {
                    $data['senha'] = Hash::make((string) $data['senha']);
                } else {
                    unset($data['senha']);
                }

                $model->fill($data);
                $model->save();
                $id = (int) $model->id;
            } else {
                if (isset($data['senha'])) {
                    $data['senha'] = Hash::make((string) $data['senha']);
                }
                $created = UsuarioModel::query()->create($data);
                $id = (int) $created->id;
            }

            UsuarioCliente::query()->where('usuario_id', $id)->delete();
            foreach ($clienteIds as $clienteId) {
                UsuarioCliente::query()->create([
                    'usuario_id' => $id,
                    'cliente_id' => (int) $clienteId,
                ]);
            }

            UsuarioBi::query()->where('usuario_id', $id)->delete();
            foreach ($biIds as $biId) {
                UsuarioBi::query()->create([
                    'usuario_id' => $id,
                    'bi_id' => (int) $biId,
                ]);
            }

            return $id;
        });

        $entity = $this->findById($id, $tenant, $isRoot);
        if (! $entity) {
            throw new RuntimeException('Usuário Inexistente');
        }

        return $entity;
    }

    public function clienteMatrix(?int $grupoEmpresarialId, bool $isRoot): array
    {
        $clienteQuery = Cliente::query()
            ->with('grupoEmpresarial')
            ->whereIn('status', [0, 1, 2])
            ->orderBy('nome');

        if (! $isRoot && $grupoEmpresarialId) {
            $clienteQuery->where('grupo_empresarial_id', $grupoEmpresarialId);
        }

        $selectClienteNew = [];
        foreach ($clienteQuery->get() as $cliente) {
            $geId = (int) ($cliente->grupo_empresarial_id ?? 0);
            $selectClienteNew[$geId][] = [
                'ge_id' => $geId,
                'ge_nome' => $cliente->grupoEmpresarial->nome ?? 'GE',
                'cliente_id' => $cliente->id,
                'cliente_nome' => $cliente->nome,
                'cliente_status' => (int) $cliente->status,
            ];
        }

        return $selectClienteNew;
    }

    public function biMatrix(): array
    {
        $selectBi = [];
        $bis = Bi::query()
            ->with(['grupoEmpresarial', 'cliente'])
            ->where('status', '<', 2)
            ->orderBy('titulo')
            ->get();

        foreach ($bis as $bi) {
            $geId = (int) ($bi->grupo_empresarial_id ?? 0);
            $selectBi[$geId][] = [
                'ge_id' => $geId,
                'ge_nome' => $bi->grupoEmpresarial->nome ?? 'GE',
                'cliente_id' => $bi->cliente_id,
                'cliente_nome' => $bi->cliente->nome ?? '',
                'bi_id' => $bi->id,
                'titulo' => $bi->titulo,
                'subtitulo' => $bi->subtitulo,
                'status' => (int) $bi->status,
            ];
        }

        return $selectBi;
    }

    public function findGrupoEmpresarialIdByCliente(int $clienteId): ?int
    {
        $cliente = Cliente::query()->find($clienteId);
        if (! $cliente?->grupo_empresarial_id) {
            return null;
        }

        return (int) $cliente->grupo_empresarial_id;
    }

    private function applyTenant(Builder $query, TenantScope $tenant, bool $isRoot): void
    {
        if ($isRoot) {
            return;
        }

        if ($tenant->grupoEmpresarialId) {
            $query->where($query->getModel()->getTable().'.grupo_empresarial_id', $tenant->grupoEmpresarialId);
        }
    }

    private function toEntity(UsuarioModel $model, bool $withLinks = false): UsuarioEntity
    {
        return new UsuarioEntity(
            id: $model->id ? (int) $model->id : null,
            grupoEmpresarialId: $model->grupo_empresarial_id !== null ? (int) $model->grupo_empresarial_id : null,
            perfilId: $model->perfil_id !== null ? (int) $model->perfil_id : null,
            nome: (string) ($model->nome ?? ''),
            apelido: $model->apelido,
            usuario: (string) ($model->usuario ?? ''),
            email: (string) ($model->email ?? ''),
            emailGestao: $model->email_gestao,
            sexo: $model->sexo,
            rg: $model->rg,
            cpf: $model->cpf,
            dataNascimento: $this->toImmutable($model->data_nascimento),
            tel1Tipo: $model->tel1_tipo,
            tel1: $model->tel1,
            tel2Tipo: $model->tel2_tipo,
            tel2: $model->tel2,
            tel3Tipo: $model->tel3_tipo,
            tel3: $model->tel3,
            observacao: $model->observacao,
            status: (int) ($model->status ?? 0),
            dataCadastro: $this->toImmutable($model->data_cadastro),
            dataAtualizacao: $this->toImmutable($model->data_atualizacao),
            perfilNome: $model->perfil?->nome,
            grupoEmpresarialNome: $model->grupoEmpresarial?->nome,
            usuarioCriadorNome: $model->usuarioCriador?->nome,
            clienteIds: $withLinks
                ? $model->usuarioClientes->pluck('cliente_id')->map(fn ($v) => (int) $v)->all()
                : [],
            biIds: $withLinks
                ? $model->usuarioBis->pluck('bi_id')->map(fn ($v) => (int) $v)->all()
                : [],
        );
    }

    private function toImmutable(mixed $value): ?DateTimeImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        return new DateTimeImmutable((string) $value);
    }
}
