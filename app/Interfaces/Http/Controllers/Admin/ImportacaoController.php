<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Importacao\ListImportacoes;
use App\Application\Importacao\PrepareImportacaoForm;
use App\Application\Importacao\SaveImportacao;
use App\Domain\Importacao\Importacao;
use App\Domain\Importacao\ImportacaoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveImportacaoRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class ImportacaoController extends Controller
{
    public function __construct(
        private readonly ListImportacoes $list,
        private readonly SaveImportacao $save,
        private readonly PrepareImportacaoForm $prepare,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $clienteId = session('cliente_id');
        if (! $clienteId) {
            return redirect()
                ->route('admin.home')
                ->with('error', 'Selecione um cliente para acessar Importações.');
        }

        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'tipo_importacao' => trim((string) $request->query('tipo_importacao', '')),
            'status' => $request->query('status', ''),
        ];

        $criteria = new ImportacaoSearchCriteria(
            id: $search['id_'],
            tipoImportacao: $search['tipo_importacao'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria, $this->tenantScope());
        $tipos = ['' => 'Tipo de Importação...'] + $this->prepare->execute(false)['tipoImportacaoArr'];

        return view('admin.importacao.index', [
            'title' => 'Importações',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'tipoImportacaoArr' => $tipos,
            'permissao' => $this->permission('importacao'),
            'perfil_id' => (int) ($request->user()?->perfil_id ?? 0),
            'perfil_adm' => [1, 2, 3],
        ]);
    }

    public function add(Request $request): View|RedirectResponse
    {
        $clienteId = (int) session('cliente_id');
        if (! $clienteId) {
            return redirect()
                ->route('admin.home')
                ->with('error', 'Selecione um cliente para cadastrar Importação.');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $clienteId);
        }

        $clienteNome = session('selectCliente.'.$clienteId)
            ?? (is_array(session('selectCliente')) ? (session('selectCliente')[$clienteId] ?? null) : null)
            ?? ('Cliente #'.$clienteId);

        return view('admin.importacao.add', [
            'title' => 'Cadastro de Importação',
            'tipoImportacaoArr' => $this->prepare->execute(true)['tipoImportacaoArr'],
            'clienteNome' => $clienteNome,
            'permissao' => $this->permission('importacao'),
        ]);
    }

    public function import(Request $request): View
    {
        return view('admin.importacao.import', [
            'title' => 'Importação',
            'permissao' => $this->permission('importacao'),
            'importacaoId' => session('importacao_id'),
        ]);
    }

    public function validacao(Request $request): View
    {
        $rows = session('erro_validacao', []);

        return view('admin.importacao.validacao', [
            'title' => 'Validação de Importação',
            'rows' => is_array($rows) ? $rows : [],
            'permissao' => $this->permission('importacao'),
        ]);
    }

    private function store(Request $request, int $clienteId): RedirectResponse
    {
        /** @var SaveImportacaoRequest $form */
        $form = app(SaveImportacaoRequest::class);
        $validated = $form->validated();
        $file = $form->file('arquivo');

        try {
            $importacao = $this->save->execute(
                ['tipo_importacao' => $validated['tipo_importacao']],
                (string) $file->getRealPath(),
                (string) $file->getClientOriginalName(),
                (string) $file->getClientOriginalExtension(),
                $clienteId,
                $request->user()?->id,
                new DateTimeImmutable,
            );
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível registrar a Importação.']);
        }

        return redirect()
            ->route('admin.importacao.import')
            ->with('status', 'Importação #'.$importacao->id.' registrada. Processamento detalhado da planilha está deferido.')
            ->with('importacao_id', $importacao->id);
    }

    /**
     * @param  PagedResult<Importacao>  $result
     * @return LengthAwarePaginator<int, Importacao>
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
