<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Relatorio\ListRelatorioAfastados;
use App\Application\Relatorio\ListRelatorioAtendimentosPendentes;
use App\Application\Relatorio\ListRelatorioBeneficiarios;
use App\Application\Relatorio\PrepareRelatorioForm;
use App\Domain\Relatorio\RelatorioAfastadoSearchCriteria;
use App\Domain\Relatorio\RelatorioAtendimentoPendenteSearchCriteria;
use App\Domain\Relatorio\RelatorioBeneficiarioSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

/**
 * Controller fino: UseCase + view/redirect. Excel *_down permanece deferido.
 */
class RelatorioController extends Controller
{
    public function __construct(
        private readonly ListRelatorioAfastados $listAfastados,
        private readonly ListRelatorioBeneficiarios $listBeneficiarios,
        private readonly ListRelatorioAtendimentosPendentes $listAtendimentos,
        private readonly PrepareRelatorioForm $prepare,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        return view('admin.relatorio.index', [
            'title' => 'Relatórios',
            'permissoes' => session('permissoes', []),
            'perfil_id' => (int) ($user?->perfil_id ?? 0),
            'isRoot' => $user?->isRoot() ?? false,
            'permissao' => $this->permission('relatorio'),
        ]);
    }

    public function afastados(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'cpf' => trim((string) $request->query('cpf', '')),
        ];

        $criteria = new RelatorioAfastadoSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            cpf: $search['cpf'],
            perPage: 30,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listAfastados->execute($criteria, $this->tenantScope());

        return view('admin.relatorio.afastados', [
            'title' => 'Relatório de Afastados',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('relatorio'),
            'downloadDeferred' => true,
        ]);
    }

    public function beneficiarios(Request $request): View|RedirectResponse
    {
        $clienteId = session('cliente_id');
        if (! $clienteId) {
            return redirect()->route('admin.home')->with('error', 'Selecione um cliente.');
        }

        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'cpf' => trim((string) $request->query('cpf', '')),
        ];

        $criteria = new RelatorioBeneficiarioSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            cpf: $search['cpf'],
            perPage: 30,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listBeneficiarios->execute($criteria, $this->tenantScope());

        return view('admin.relatorio.beneficiarios', [
            'title' => 'Relatório de Beneficiários',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('relatorio'),
            'downloadDeferred' => true,
        ]);
    }

    public function atendimentosPendentes(Request $request): View
    {
        $user = $request->user();
        $search = [
            'cod_' => trim((string) $request->query('cod_', '')),
            'usuario_agendamento_id' => $request->query('usuario_agendamento_id', ''),
            'status' => $request->query('status', ''),
        ];

        $criteria = new RelatorioAtendimentoPendenteSearchCriteria(
            cod: $search['cod_'],
            usuarioAgendamentoId: is_scalar($search['usuario_agendamento_id']) ? (string) $search['usuario_agendamento_id'] : '',
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            perfilId: (int) ($user?->perfil_id ?? 0),
            usuarioId: $user?->id,
            perPage: 30,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listAtendimentos->execute($criteria);

        return view('admin.relatorio.atendimentos_pendentes', [
            'title' => 'Atendimentos Pendentes',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('relatorio'),
            'downloadDeferred' => true,
        ]);
    }

    public function gerencial(Request $request): View
    {
        $options = $this->prepare->execute($this->tenantScope(), false);

        return view('admin.relatorio.gerencial', [
            'title' => 'Relatório Gerencial',
            'beneficioArr' => $options['beneficioArr'],
            'permissao' => $this->permission('relatorio'),
            'exportDeferred' => true,
        ]);
    }

    public function exportacao(Request $request): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            return redirect()
                ->route('admin.relatorio.exportacao')
                ->with('error', 'Exportação externa (curl excel) deferida na Onda E. Tela de filtros disponível.');
        }

        $options = $this->prepare->execute($this->tenantScope(), true);

        return view('admin.relatorio.exportacao', [
            'title' => 'Exportação Sinistro ou Fatura',
            'beneficioArr' => $options['beneficioArr'],
            'tipoExportacaoArr' => $options['tipoExportacaoArr'],
            'anoArr' => $options['anoArr'],
            'mesArr' => $options['mesArr'],
            'permissao' => $this->permission('relatorio'),
            'exportDeferred' => true,
        ]);
    }

    public function fatura(Request $request): View
    {
        return $this->deferredReportScreen('Fatura', 'fatura');
    }

    public function sinistro(Request $request): View
    {
        return $this->deferredReportScreen('Sinistro', 'sinistro');
    }

    public function movimentacaoBeneficiario(Request $request): View
    {
        return $this->deferredReportScreen('Movimentação de Beneficiários', 'movimentacao_beneficiario');
    }

    public function movimentacaoSinistro(Request $request): View
    {
        return $this->deferredReportScreen('Movimentação de Sinistro', 'movimentacao_sinistro');
    }

    public function movimentacaoFatura(Request $request): View
    {
        return $this->deferredReportScreen('Movimentação de Fatura', 'movimentacao_fatura');
    }

    public function deferredDown(Request $request, string $tipo): RedirectResponse
    {
        return redirect()
            ->route('admin.relatorio.index')
            ->with('error', "Download/exportação «{$tipo}_down» deferida na Onda E (prioridade: telas de entrada).");
    }

    private function deferredReportScreen(string $title, string $slug): View
    {
        return view('admin.relatorio.deferred', [
            'title' => $title,
            'slug' => $slug,
            'permissao' => $this->permission('relatorio'),
        ]);
    }

    /**
     * @param  PagedResult<mixed>  $result
     * @return LengthAwarePaginator<int, mixed>
     */
    private function toPaginator(PagedResult $result, Request $request): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            items: $result->items,
            total: $result->total,
            perPage: $result->perPage,
            currentPage: $result->currentPage,
            options: [
                'path' => $request->url(),
                'query' => $request->query(),
            ],
        );
    }

    private function tenantScope(): TenantScope
    {
        $grupo = session('grupo_empresarial_id');
        $cliente = session('cliente_id');

        return new TenantScope(
            grupoEmpresarialId: $grupo !== null && $grupo !== '' ? (int) $grupo : null,
            clienteId: $cliente !== null && $cliente !== '' ? (int) $cliente : null,
        );
    }

    private function permission(string $module): int
    {
        $entry = session('permissoes', [])[$module] ?? null;

        return is_array($entry) ? (int) ($entry['permissao'] ?? 0) : (int) ($entry ?? 0);
    }
}
