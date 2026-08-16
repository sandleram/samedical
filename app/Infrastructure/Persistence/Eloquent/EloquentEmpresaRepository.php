<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Empresa\Empresa as EmpresaEntity;
use App\Domain\Empresa\EmpresaRepositoryInterface;
use App\Domain\Empresa\EmpresaSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Empresa as EmpresaModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentEmpresaRepository implements EmpresaRepositoryInterface
{
    public function optionsForCliente(int $clienteId): array
    {
        return EmpresaModel::query()
            ->where('cliente_id', $clienteId)
            ->orderBy('razao_social')
            ->get()
            ->mapWithKeys(function (EmpresaModel $e) {
                $label = trim(($e->razao_social ?: $e->nome_fantasia ?: $e->nome ?: 'Empresa').' '.($e->cnpj ?: ''));

                return [$e->id => $label];
            })
            ->all();
    }

    public function belongsToCliente(int $empresaId, int $clienteId): bool
    {
        return EmpresaModel::query()
            ->where('id', $empresaId)
            ->where('cliente_id', $clienteId)
            ->exists();
    }

    public function search(EmpresaSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = EmpresaModel::query()
            ->with('cliente')
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('status', '<', 2);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('id', (int) $criteria->id);
        }

        if ($criteria->nome !== '') {
            foreach (preg_split('/\s+/', $criteria->nome) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('nome', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->razaoSocial !== '') {
            foreach (preg_split('/\s+/', $criteria->razaoSocial) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('razao_social', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->cnpj !== '') {
            $cnpj = preg_replace('/\D+/', '', $criteria->cnpj) ?? '';
            $query->where('cnpj', $cnpj);
        }

        if ($criteria->status !== '') {
            $query->where('status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        $items = array_map(
            fn (EmpresaModel $model) => $this->toEntity($model),
            $paginator->items(),
        );

        return new PagedResult(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?EmpresaEntity
    {
        $model = EmpresaModel::query()
            ->with('cliente')
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): EmpresaEntity
    {
        $model = EmpresaModel::query()->create($data);
        $model->load('cliente');

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): EmpresaEntity
    {
        $model = EmpresaModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->find($id);

        if (! $model) {
            throw new RuntimeException('Empresa Inexistente');
        }

        $model->fill($data);
        $model->save();
        $model->load('cliente');

        return $this->toEntity($model);
    }

    private function applyTenant(Builder $query, TenantScope $tenant): void
    {
        if ($tenant->clienteId) {
            $query->where($query->getModel()->getTable().'.cliente_id', $tenant->clienteId);
        } elseif ($tenant->grupoEmpresarialId) {
            $query->whereHas('cliente', function (Builder $clienteQuery) use ($tenant) {
                $clienteQuery->where('grupo_empresarial_id', $tenant->grupoEmpresarialId);
            });
        }
    }

    private function toEntity(EmpresaModel $model): EmpresaEntity
    {
        return new EmpresaEntity(
            id: $model->id ? (int) $model->id : null,
            clienteId: $model->cliente_id !== null ? (int) $model->cliente_id : null,
            nome: (string) ($model->nome ?? ''),
            razaoSocial: $model->razao_social,
            nomeFantasia: $model->nome_fantasia,
            cnpj: $model->cnpj,
            inscricaoEstadual: $model->inscricao_estadual,
            inscricaoMunicipal: $model->inscricao_municipal,
            numeroFuncionarios: $model->numero_funcionarios !== null ? (int) $model->numero_funcionarios : null,
            descricao: $model->descricao,
            porte: $model->porte,
            faturamento: $model->faturamento,
            tipo: $model->tipo,
            endereco: $model->endereco,
            numero: $model->numero,
            complemento: $model->complemento,
            bairro: $model->bairro,
            cidade: $model->cidade,
            estado: $model->estado,
            cep: $model->cep,
            telefone: $model->telefone,
            email: $model->email,
            site: $model->site,
            status: (int) ($model->status ?? 0),
            dataCadastro: $this->toDateTime($model->data_cadastro),
            dataAtualizacao: $this->toDateTime($model->data_atualizacao),
            clienteNome: $model->cliente?->nome,
        );
    }

    private function toDateTime(mixed $value): ?DateTimeImmutable
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
