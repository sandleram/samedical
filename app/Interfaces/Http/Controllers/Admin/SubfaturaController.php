<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Subfatura\GetSubfatura;
use App\Application\Subfatura\ListSubfaturas;
use App\Application\Subfatura\SaveSubfatura;
use App\Application\Subfatura\PrepareSubfaturaForm;
use App\Domain\Subfatura\Subfatura;
use App\Domain\Subfatura\SubfaturaSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveSubfaturaRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class SubfaturaController extends Controller
{
    public function __construct(
        private readonly ListSubfaturas $list,
        private readonly GetSubfatura $get,
        private readonly SaveSubfatura $save,
        private readonly PrepareSubfaturaForm $prepare,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'descricao' => trim((string) $request->query('descricao', '')),
            'codigo' => trim((string) $request->query('codigo', '')),
            'status' => trim((string) $request->query('status', '')),
        ];

        $criteria = new SubfaturaSearchCriteria(
            id: $search['id_'],
            descricao: $search['descricao'],
            codigo: $search['codigo'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria, $this->tenantScope());

        return view('admin.subfatura.index', [
            'title' => 'Subfaturas',
            'subfaturas' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('subfatura'),
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id, $this->tenantScope());
        if (! $row) {
            return redirect()
                ->route('admin.subfatura.index')
                ->with('status', 'Subfatura Inexistente');
        }

        return view('admin.subfatura.show', [
            'title' => 'Subfatura',
            'subfatura' => $row,
            'permissao' => $this->permission('subfatura'),
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
                ->route('admin.subfatura.index')
                ->with('status', 'Subfatura Inexistente');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.subfatura.add', [
            'title' => $row ? 'Edição de Subfatura' : 'Cadastro de Subfatura',
            'subfatura' => $row,
            'permissao' => $this->permission('subfatura'),
            ...$this->flattenOptions($options),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveSubfaturaRequest $form */
        $form = app(SaveSubfaturaRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute($validated, $id, new DateTimeImmutable, (int) session('cliente_id'), $this->tenantScope());
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possÃ­vel SALVAR a Subfatura, verifique as informaÃ§Ãµes ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.subfatura.add', ['id' => $saved->id])
            ->with('status', 'A Subfatura foi SALVA com sucesso!');
    }

    /**
     * @param  PagedResult<Subfatura>  $result
     * @return LengthAwarePaginator<int, Subfatura>
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
