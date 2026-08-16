<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Beneficiario\Beneficiario as BeneficiarioEntity;
use App\Domain\Beneficiario\BeneficiarioRepositoryInterface;
use App\Domain\Beneficiario\BeneficiarioSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Beneficiario as BeneficiarioModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentBeneficiarioRepository implements BeneficiarioRepositoryInterface
{
    public function search(BeneficiarioSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = BeneficiarioModel::query()
            ->with(['cliente', 'empresa'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('status', 1);
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

        if ($criteria->nomeSocial !== '') {
            foreach (preg_split('/\s+/', $criteria->nomeSocial) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('nome_social', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->cpf !== '') {
            $cpf = str_replace(['.', '-'], '', $criteria->cpf);
            $query->where('cpf', $cpf);
        }

        if ($criteria->situacao !== '') {
            $query->where('situacao', $criteria->situacao);
        }

        if ($criteria->status !== '') {
            $query->where('status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        $items = array_map(
            fn (BeneficiarioModel $model) => $this->toEntity($model),
            $paginator->items(),
        );

        return new PagedResult(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?BeneficiarioEntity
    {
        $model = BeneficiarioModel::query()
            ->with(['cliente.grupoEmpresarial', 'empresa'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): BeneficiarioEntity
    {
        $model = BeneficiarioModel::query()->create($data);
        $model->load(['cliente.grupoEmpresarial', 'empresa']);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): BeneficiarioEntity
    {
        $model = BeneficiarioModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->find($id);

        if (! $model) {
            throw new RuntimeException('Beneficiario Inexistente');
        }

        $model->fill($data);
        $model->save();
        $model->load(['cliente.grupoEmpresarial', 'empresa']);

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

    private function toEntity(BeneficiarioModel $model): BeneficiarioEntity
    {
        $dataNascimento = null;
        if ($model->data_nascimento) {
            $dataNascimento = $model->data_nascimento instanceof \DateTimeInterface
                ? DateTimeImmutable::createFromInterface($model->data_nascimento)
                : new DateTimeImmutable((string) $model->data_nascimento);
        }

        return new BeneficiarioEntity(
            id: $model->id ? (int) $model->id : null,
            clienteId: $model->cliente_id !== null ? (int) $model->cliente_id : null,
            empresaId: $model->empresa_id !== null ? (int) $model->empresa_id : null,
            nome: (string) ($model->nome ?? ''),
            nomeSocial: $model->nome_social,
            email: $model->email,
            cpf: $model->cpf,
            rg: $model->rg,
            pis: $model->pis,
            sexo: $model->sexo,
            estadoCivil: $model->estado_civil,
            dataNascimento: $dataNascimento,
            altura: $model->altura !== null ? (int) $model->altura : null,
            peso: $model->peso !== null ? (float) $model->peso : null,
            imc: $model->imc !== null ? (float) $model->imc : null,
            endereco: $model->endereco,
            bairro: $model->bairro,
            cidade: $model->cidade,
            estado: $model->estado,
            cep: $model->cep,
            telefoneTipo: $model->telefone_tipo,
            telefone: $model->telefone,
            telefone1Tipo: $model->telefone1_tipo,
            telefone1: $model->telefone1,
            agencia: $model->agencia,
            conta: $model->conta,
            tipoDeConta: $model->tipo_de_conta,
            profissao: $model->profissao,
            ocupacao: $model->ocupacao,
            pessoaPoliticamenteExposta: $model->pessoa_politicamente_exposta,
            atividadePerigosa: $model->realiza_alguma_atividade_perigosa_na_profissao,
            possuiDeficiencia: $model->possui_deficiencia,
            observacao: $model->observacao,
            situacao: $model->situacao,
            beneficio: $model->beneficio,
            valorDoSeguro: $model->valor_do_seguro !== null ? (float) $model->valor_do_seguro : null,
            codMatricula: $model->cod_matricula,
            status: (int) ($model->status ?? 0),
            clienteNome: $model->cliente?->nome,
            empresaRazaoSocial: $model->empresa?->razao_social,
            empresaNome: $model->empresa?->nome ?? $model->empresa?->nome_fantasia,
            grupoEmpresarialNome: $model->cliente?->grupoEmpresarial?->nome,
        );
    }
}
