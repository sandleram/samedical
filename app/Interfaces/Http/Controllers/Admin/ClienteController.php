<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Cliente\GetCliente;
use App\Application\Cliente\ListClientes;
use App\Application\Cliente\SaveCliente;
use App\Application\Cliente\SaveClienteInput;
use App\Domain\Cliente\Cliente;
use App\Domain\Cliente\ClienteSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveClienteRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: valida input, monta TenantScope, chama UseCases, devolve Blade/redirect.
 */
class ClienteController extends Controller
{
    public function __construct(
        private readonly ListClientes $listClientes,
        private readonly GetCliente $getCliente,
        private readonly SaveCliente $saveCliente,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        if (! session('grupo_empresarial_id')) {
            return redirect()
                ->route('admin.grupo_empresarial.selecione')
                ->with('status', 'Selecione um grupo empresarial / cliente.');
        }

        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'status' => $request->query('status', ''),
        ];

        $user = $request->user();
        $isRoot = $user?->isRoot() ?? false;

        $criteria = new ClienteSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! $isRoot,
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listClientes->execute($criteria, $this->tenantScope());
        $rows = $this->toPaginator($result, $request);

        return view('admin.cliente.index', [
            'title' => 'Clientes',
            'rows' => $rows,
            'search' => $search,
            'permissao' => $this->currentPermissionLevel(),
            'perfil_id' => (int) ($user?->perfil_id ?? 0),
            'perfil_adm' => [1, 2, 3],
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->getCliente->execute($id, $this->tenantScope());

        if (! $row) {
            return redirect()
                ->route('admin.cliente.index')
                ->with('status', 'Cliente Inexistente');
        }

        return view('admin.cliente.show', [
            'title' => 'Cliente',
            'row' => $row,
            'permissao' => $this->currentPermissionLevel(),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        if (! session('grupo_empresarial_id')) {
            return redirect()
                ->route('admin.grupo_empresarial.selecione')
                ->with('status', 'Selecione um grupo empresarial / cliente.');
        }

        $row = null;
        if ($id !== null) {
            $row = $this->getCliente->execute($id, $this->tenantScope());
            if (! $row) {
                return redirect()
                    ->route('admin.cliente.index')
                    ->with('status', 'Cliente Inexistente');
            }
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.cliente.add', [
            'title' => $row ? 'Edição de Cliente' : 'Cadastro de Cliente',
            'row' => $row,
            'permissao' => $this->currentPermissionLevel(),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        $grupoId = (int) session('grupo_empresarial_id');

        /** @var SaveClienteRequest $formRequest */
        $formRequest = app(SaveClienteRequest::class);
        $validated = $formRequest->validated();

        try {
            $saved = $this->saveCliente->execute(
                new SaveClienteInput(
                    attributes: $validated,
                    grupoEmpresarialId: $grupoId,
                    existingId: $id,
                    userId: $request->user()?->id,
                    now: new DateTimeImmutable,
                ),
                $this->tenantScope(),
            );
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR a Cliente, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.cliente.add', ['id' => $saved->id])
            ->with('status', 'A Cliente foi SALVA com sucesso!');
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
     * @param  PagedResult<Cliente>  $result
     * @return LengthAwarePaginator<int, Cliente>
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

    private function currentPermissionLevel(): int
    {
        $permissions = session('permissoes', []);
        $entry = $permissions['cliente'] ?? null;
        if (is_array($entry)) {
            return (int) ($entry['permissao'] ?? 0);
        }

        return (int) ($entry ?? 0);
    }
}
