<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\ImportacaoNova\GetImportacaoNova;
use App\Application\ImportacaoNova\ListImportacaoNovas;
use App\Application\ImportacaoNova\PrepareImportacaoNovaForm;
use App\Application\ImportacaoNova\QueueImportacaoNovaReprocess;
use App\Application\ImportacaoNova\SaveImportacaoNova;
use App\Domain\ImportacaoNova\ImportacaoNova;
use App\Domain\ImportacaoNova\ImportacaoNovaSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveImportacaoNovaRequest;
use DateTimeImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class ImportacaoNovaController extends Controller
{
    public function __construct(
        private readonly ListImportacaoNovas $list,
        private readonly GetImportacaoNova $get,
        private readonly SaveImportacaoNova $save,
        private readonly PrepareImportacaoNovaForm $prepare,
        private readonly QueueImportacaoNovaReprocess $queueReprocess,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $clienteId = session('cliente_id');
        if (! $clienteId) {
            return redirect()
                ->route('admin.home')
                ->with('error', 'Selecione um cliente para acessar Importação Nova.');
        }

        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'tipo_importacao' => trim((string) $request->query('tipo_importacao', '')),
            'status' => $request->query('status', ''),
            'status_processo' => $request->query('status_processo', ''),
        ];

        $criteria = new ImportacaoNovaSearchCriteria(
            id: $search['id_'],
            tipoImportacao: $search['tipo_importacao'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            statusProcesso: is_scalar($search['status_processo']) ? (string) $search['status_processo'] : '',
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria, $this->tenantScope());
        $options = $this->prepare->execute(false);
        $tipos = ['' => 'Tipo de Importação...'] + $options['tipoImportacaoArr'];

        return view('admin.importacao_nova.index', [
            'title' => 'Importação Nova',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'tipoImportacaoArr' => $tipos,
            'statusProcessoArr' => $options['statusProcessoArr'],
            'permissao' => $this->permission('importacao_nova'),
            'perfil_id' => (int) ($request->user()?->perfil_id ?? 0),
            'perfil_adm' => [1, 2, 3],
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id, $this->tenantScope());
        if (! $row) {
            return redirect()
                ->route('admin.importacao_nova.index')
                ->with('error', 'Importação Inexistente');
        }

        return view('admin.importacao_nova.view', [
            'title' => 'Importação Nova',
            'row' => $row,
            'statusProcessoArr' => $this->prepare->execute(false)['statusProcessoArr'],
            'permissao' => $this->permission('importacao_nova'),
        ]);
    }

    public function add(Request $request): View|RedirectResponse
    {
        $clienteId = (int) session('cliente_id');
        if (! $clienteId) {
            return redirect()
                ->route('admin.home')
                ->with('error', 'Selecione um cliente para cadastrar Importação Nova.');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $clienteId);
        }

        return view('admin.importacao_nova.add', [
            'title' => 'Cadastro de Importação Nova',
            'tipoImportacaoArr' => $this->prepare->execute(true)['tipoImportacaoArr'],
            'permissao' => $this->permission('importacao_nova'),
        ]);
    }

    public function import(Request $request): View
    {
        return view('admin.importacao_nova.import', [
            'title' => 'Importação Nova',
            'permissao' => $this->permission('importacao_nova'),
        ]);
    }

    public function validacao(Request $request): View
    {
        $rows = session('erro_validacao', []);

        return view('admin.importacao_nova.validacao', [
            'title' => 'Validação de Importação Nova',
            'rows' => is_array($rows) ? $rows : [],
            'permissao' => $this->permission('importacao_nova'),
        ]);
    }

    public function status(Request $request, int $id): JsonResponse
    {
        $row = $this->get->execute($id, $this->tenantScope());
        if (! $row) {
            return response()->json(['error' => 'not_found'], 404);
        }

        return response()->json([
            'status_processo' => $row->statusProcesso,
            'linhas_processadas' => (string) ($row->linhasProcessadas ?? '0'),
            'linhas_totais' => (string) ($row->linhasTotais ?? '0'),
            'data_atualizacao' => $row->dataAtualizacao?->format('d/m/Y H:i:s'),
        ]);
    }

    public function processarArquivo(Request $request, int $id): RedirectResponse
    {
        try {
            $this->queueReprocess->execute(
                $id,
                $this->tenantScope(),
                $request->user()?->id,
                new DateTimeImmutable,
            );
        } catch (RuntimeException) {
            return redirect()
                ->route('admin.importacao_nova.index')
                ->with('error', 'Importação Inexistente');
        }

        return redirect()
            ->route('admin.importacao_nova.view', ['id' => $id])
            ->with('status', 'Reprocessamento enfileirado (processamento completo deferido).');
    }

    private function store(Request $request, int $clienteId): RedirectResponse
    {
        /** @var SaveImportacaoNovaRequest $form */
        $form = app(SaveImportacaoNovaRequest::class);
        $validated = $form->validated();
        $file = $form->file('arquivo');

        try {
            $this->save->execute(
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
                ->withErrors(['form' => 'Não foi possível registrar a Importação Nova.']);
        }

        return redirect()
            ->route('admin.importacao_nova.index')
            ->with('status', "Importação de {$validated['tipo_importacao']} concluída com sucesso (arquivo em fila).");
    }

    /**
     * @param  PagedResult<ImportacaoNova>  $result
     * @return LengthAwarePaginator<int, ImportacaoNova>
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
