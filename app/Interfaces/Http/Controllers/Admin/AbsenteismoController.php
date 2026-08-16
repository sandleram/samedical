<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Absenteismo\GetAbsenteismo;
use App\Application\Absenteismo\ListAbsenteismos;
use App\Application\Absenteismo\SaveAbsenteismo;
use App\Application\Absenteismo\PrepareAbsenteismoForm;
use App\Domain\Absenteismo\Absenteismo;
use App\Domain\Absenteismo\AbsenteismoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveAbsenteismoRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class AbsenteismoController extends Controller
{
    public function __construct(
        private readonly ListAbsenteismos $list,
        private readonly GetAbsenteismo $get,
        private readonly SaveAbsenteismo $save,
        private readonly PrepareAbsenteismoForm $prepare,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'cid' => trim((string) $request->query('cid', '')),
            'status' => trim((string) $request->query('status', '')),
        ];

        $criteria = new AbsenteismoSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            cid: $search['cid'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria, $this->tenantScope());

        return view('admin.absenteismo.index', [
            'title' => 'Absenteísmo',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('absenteismo'),
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id, $this->tenantScope());
        if (! $row) {
            return redirect()
                ->route('admin.absenteismo.index')
                ->with('status', 'Absenteísmo Inexistente');
        }

        return view('admin.absenteismo.show', [
            'title' => 'Absenteísmo',
            'row' => $row,
            'permissao' => $this->permission('absenteismo'),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {

        $clienteId = session('cliente_id');
        if (! $clienteId) {
            return redirect()
                ->route('admin.home')
                ->with('status', 'Selecione um cliente para continuar.');
        }

        $prepared = $this->prepare->execute((int) $clienteId, $this->tenantScope(), $id);
        $row = $prepared['row'];
        $options = $prepared['options'];
        if ($id !== null && ! $row) {
            return redirect()
                ->route('admin.absenteismo.index')
                ->with('status', 'Absenteísmo Inexistente');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.absenteismo.add', [
            'title' => $row ? 'Edição de Absenteísmo' : 'Cadastro de Absenteísmo',
            'row' => $row,
            'permissao' => $this->permission('absenteismo'),
            ...$this->flattenOptions($options),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveAbsenteismoRequest $form */
        $form = app(SaveAbsenteismoRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute($validated, $id, new DateTimeImmutable, $request->user()?->id, (int) session('cliente_id'), $this->tenantScope());
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possÃ­vel SALVAR o Absenteísmo, verifique as informaÃ§Ãµes ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.absenteismo.add', ['id' => $saved->id])
            ->with('status', 'O Absenteísmo foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<Absenteismo>  $result
     * @return LengthAwarePaginator<int, Absenteismo>
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

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    private function flattenOptions(array $options): array
    {
        return $options;
    }

    private function permission(string $module): int
    {
        $entry = session('permissoes', [])[$module] ?? null;

        return is_array($entry) ? (int) ($entry['permissao'] ?? 0) : (int) ($entry ?? 0);
    }
}
