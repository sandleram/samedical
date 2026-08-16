<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Relatorio\RelatorioAfastadoRow;
use App\Domain\Relatorio\RelatorioAfastadoSearchCriteria;
use App\Domain\Relatorio\RelatorioAtendimentoPendenteRow;
use App\Domain\Relatorio\RelatorioAtendimentoPendenteSearchCriteria;
use App\Domain\Relatorio\RelatorioBeneficiarioRow;
use App\Domain\Relatorio\RelatorioBeneficiarioSearchCriteria;
use App\Domain\Relatorio\RelatorioRepositoryInterface;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Afastado;
use App\Models\Agendamento;
use App\Models\Beneficiario;
use App\Models\Beneficio;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;

final class EloquentRelatorioRepository implements RelatorioRepositoryInterface
{
    private const ADMIN_PERFIS = [1, 2, 3, 13];

    public function searchAfastados(RelatorioAfastadoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = Afastado::query()
            ->with(['beneficiario.cliente'])
            ->where('situacao', 'A')
            ->where('status', 1)
            ->whereHas('beneficiario', function (Builder $q) use ($tenant) {
                if ($tenant->clienteId) {
                    $q->where('cliente_id', $tenant->clienteId);
                } elseif ($tenant->grupoEmpresarialId) {
                    $q->whereHas('cliente', fn (Builder $cq) => $cq->where('grupo_empresarial_id', $tenant->grupoEmpresarialId));
                }
            });

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('id', (int) $criteria->id);
        }

        if ($criteria->nome !== '') {
            $query->whereHas('beneficiario', function (Builder $bq) use ($criteria) {
                foreach (preg_split('/\s+/', $criteria->nome) ?: [] as $part) {
                    if ($part !== '') {
                        $bq->where('nome', 'like', '%'.$part.'%');
                    }
                }
            });
        }

        if ($criteria->cpf !== '') {
            $cpf = str_replace(['.', '-'], '', $criteria->cpf);
            $query->whereHas('beneficiario', fn (Builder $bq) => $bq->where('cpf', $cpf));
        }

        $paginator = $query->orderByDesc('id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (Afastado $m) => $this->toAfastadoRow($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function searchBeneficiarios(RelatorioBeneficiarioSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = Beneficiario::query()
            ->with(['cliente', 'empresa'])
            ->where('status', 1);

        if ($tenant->clienteId) {
            $query->where('cliente_id', $tenant->clienteId);
        } elseif ($tenant->grupoEmpresarialId) {
            $query->whereHas('cliente', fn (Builder $q) => $q->where('grupo_empresarial_id', $tenant->grupoEmpresarialId));
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

        if ($criteria->cpf !== '') {
            $cpf = str_replace(['.', '-'], '', $criteria->cpf);
            $query->where('cpf', $cpf);
        }

        $paginator = $query->orderByDesc('id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (Beneficiario $m) => $this->toBeneficiarioRow($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function searchAtendimentosPendentes(RelatorioAtendimentoPendenteSearchCriteria $criteria): PagedResult
    {
        $query = Agendamento::query()
            ->with(['usuario', 'usuarioAgendamento'])
            ->where('status', 0);

        if (! in_array($criteria->perfilId, self::ADMIN_PERFIS, true) && $criteria->usuarioId) {
            $query->where('usuario_agendamento_id', $criteria->usuarioId);
        }

        if ($criteria->cod !== '' && is_numeric($criteria->cod)) {
            $query->where('id', (int) $criteria->cod);
        }

        if (
            in_array($criteria->perfilId, self::ADMIN_PERFIS, true)
            && $criteria->usuarioAgendamentoId !== ''
        ) {
            $query->where('usuario_agendamento_id', (int) $criteria->usuarioAgendamentoId);
        }

        $paginator = $query->orderBy('status')->orderBy('data_hora')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (Agendamento $m) => $this->toAtendimentoRow($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function beneficioOptions(TenantScope $tenant, bool $withPlaceholder = false): array
    {
        $options = Beneficio::query()
            ->when($tenant->clienteId, fn (Builder $q) => $q->where('cliente_id', $tenant->clienteId))
            ->where('status', 1)
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->all();

        if ($withPlaceholder) {
            return ['' => 'Beneficio...'] + $options;
        }

        return $options;
    }

    public function anoOptions(): array
    {
        $arr = ['' => 'Ano...'];
        for ($ano = (int) date('Y'); $ano >= 2000; $ano--) {
            $arr[$ano] = (string) $ano;
        }

        return $arr;
    }

    public function mesOptions(): array
    {
        return [
            '' => 'Mês...',
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];
    }

    private function toAfastadoRow(Afastado $model): RelatorioAfastadoRow
    {
        return new RelatorioAfastadoRow(
            id: $model->id !== null ? (int) $model->id : null,
            cid: $model->cid !== null ? (string) $model->cid : null,
            situacao: $model->situacao !== null ? (string) $model->situacao : null,
            dataInicioAfastamento: $this->toDate($model->data_inicio_afastamento ?? null),
            dataFimAfastamento: $this->toDate($model->data_fim_afastamento ?? null),
            beneficiarioNome: $model->beneficiario->nome ?? null,
            beneficiarioCpf: $model->beneficiario->cpf ?? null,
            clienteNome: $model->beneficiario->cliente->nome ?? null,
        );
    }

    private function toBeneficiarioRow(Beneficiario $model): RelatorioBeneficiarioRow
    {
        return new RelatorioBeneficiarioRow(
            id: $model->id !== null ? (int) $model->id : null,
            cpf: $model->cpf !== null ? (string) $model->cpf : null,
            nome: $model->nome !== null ? (string) $model->nome : null,
            situacao: $model->situacao !== null ? (string) $model->situacao : null,
            clienteNome: $model->cliente->nome ?? null,
        );
    }

    private function toAtendimentoRow(Agendamento $model): RelatorioAtendimentoPendenteRow
    {
        return new RelatorioAtendimentoPendenteRow(
            id: $model->id !== null ? (int) $model->id : null,
            dataHora: $this->toDate($model->data_hora ?? null),
            usuarioId: $model->usuario_id !== null ? (int) $model->usuario_id : null,
            usuarioAgendamentoId: $model->usuario_agendamento_id !== null ? (int) $model->usuario_agendamento_id : null,
            status: $model->status,
            usuarioNome: $model->usuario->nome ?? null,
            usuarioAgendamentoNome: $model->usuarioAgendamento->nome ?? null,
        );
    }

    private function toDate(mixed $value): ?DateTimeImmutable
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
