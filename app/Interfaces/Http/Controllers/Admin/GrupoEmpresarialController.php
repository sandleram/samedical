<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Cliente\GetClienteForSelecao;
use App\Application\Cliente\ListClientesForSelecao;
use App\Application\GrupoEmpresarial\GetGrupoEmpresarial;
use App\Application\GrupoEmpresarial\ListGrupoEmpresariais;
use App\Application\GrupoEmpresarial\SaveGrupoEmpresarial;
use App\Application\GrupoEmpresarial\SaveGrupoEmpresarialInput;
use App\Domain\GrupoEmpresarial\GrupoEmpresarial;
use App\Domain\GrupoEmpresarial\GrupoEmpresarialSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveGrupoEmpresarialRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: valida input, monta TenantScope, chama UseCases, devolve Blade/redirect.
 * Session writes da ação `selecione` permanecem nesta camada.
 */
class GrupoEmpresarialController extends Controller
{
    public function __construct(
        private readonly ListGrupoEmpresariais $listGrupoEmpresariais,
        private readonly GetGrupoEmpresarial $getGrupoEmpresarial,
        private readonly SaveGrupoEmpresarial $saveGrupoEmpresarial,
        private readonly ListClientesForSelecao $listClientesForSelecao,
        private readonly GetClienteForSelecao $getClienteForSelecao,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'status' => $request->query('status', ''),
        ];

        $user = $request->user();
        $isRoot = $user?->isRoot() ?? false;

        $criteria = new GrupoEmpresarialSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! $isRoot,
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listGrupoEmpresariais->execute($criteria, $this->tenantScope($isRoot));
        $rows = $this->toPaginator($result, $request);

        return view('admin.grupo_empresarial.index', [
            'title' => 'Grupos Empresariais',
            'rows' => $rows,
            'search' => $search,
            'permissao' => $this->currentPermissionLevel(),
            'perfil_id' => (int) ($user?->perfil_id ?? 0),
            'perfil_adm' => [1, 2, 3],
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $isRoot = $request->user()?->isRoot() ?? false;
        $row = $this->getGrupoEmpresarial->execute($id, $this->tenantScope($isRoot));

        if (! $row) {
            return redirect()
                ->route('admin.grupo_empresarial.index')
                ->with('status', 'Grupo Empresarial Inexistente');
        }

        return view('admin.grupo_empresarial.show', [
            'title' => 'Grupo Empresarial',
            'row' => $row,
            'permissao' => $this->currentPermissionLevel(),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        $isRoot = $request->user()?->isRoot() ?? false;
        $row = null;
        if ($id !== null) {
            $row = $this->getGrupoEmpresarial->execute($id, $this->tenantScope($isRoot));
            if (! $row) {
                return redirect()
                    ->route('admin.grupo_empresarial.index')
                    ->with('status', 'Grupo Empresarial Inexistente');
            }
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id, $isRoot);
        }

        return view('admin.grupo_empresarial.add', [
            'title' => $row ? 'Edição de Grupo Empresarial' : 'Cadastro de Grupo Empresarial',
            'row' => $row,
            'permissao' => $this->currentPermissionLevel(),
        ]);
    }

    /**
     * Seleção de contexto tenant (always-allowed via config/samed.php).
     * Escrita de sessão permanece nesta camada Interfaces.
     */
    public function selecione(Request $request): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'cliente_id' => ['nullable', 'integer'],
            ]);

            $clienteId = $validated['cliente_id'] ?? null;
            if (! $clienteId) {
                session()->forget('cliente_id');

                return redirect()
                    ->route('admin.home')
                    ->with('status', 'Cliente desmarcado da sessão.');
            }

            $cliente = $this->getClienteForSelecao->execute((int) $clienteId);
            if (! $cliente) {
                return redirect()
                    ->back()
                    ->withErrors(['cliente_id' => 'Cliente inválido.']);
            }

            if (! session()->has('old_cliente_id')) {
                session(['old_cliente_id' => session('cliente_id')]);
            }

            session([
                'cliente_id' => $cliente->id,
                'grupo_empresarial_id' => $cliente->grupoEmpresarialId,
            ]);

            return redirect()
                ->route('admin.home')
                ->with('status', 'Cliente selecionado: '.$cliente->nome);
        }

        $user = $request->user();
        $perfilId = (int) ($user?->perfil_id ?? 0);
        $isRoot = $user?->isRoot() ?? false;

        $selection = $this->listClientesForSelecao->execute(
            usuarioId: (int) ($user?->id ?? 0),
            perfilId: $perfilId,
            isRoot: $isRoot,
        );

        session(['selectClienteGENew' => $selection['selectClienteGENew']]);

        return view('admin.grupo_empresarial.selecione', [
            'title' => 'Selecione um cliente',
            'selectClienteNew' => $selection['selectClienteNew'],
            'perfil_id' => $perfilId,
            'perfil_root' => 1,
            'perfil_administrador' => 2,
            'grupo_empresarial_id' => session('grupo_empresarial_id'),
            'cliente_id' => session('cliente_id'),
        ]);
    }

    private function store(Request $request, ?int $id, bool $isRoot): RedirectResponse
    {
        /** @var SaveGrupoEmpresarialRequest $formRequest */
        $formRequest = app(SaveGrupoEmpresarialRequest::class);
        $validated = $formRequest->validated();

        try {
            $saved = $this->saveGrupoEmpresarial->execute(
                new SaveGrupoEmpresarialInput(
                    attributes: $validated,
                    existingId: $id,
                    now: new DateTimeImmutable,
                ),
                $this->tenantScope($isRoot),
            );
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR a Grupo Empresarial, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.grupo_empresarial.add', ['id' => $saved->id])
            ->with('status', 'A Grupo Empresarial foi SALVA com sucesso!');
    }

    /**
     * Root ignora filtro de GE (equivale ao forTenant legado).
     */
    private function tenantScope(bool $isRoot): TenantScope
    {
        if ($isRoot) {
            return new TenantScope(grupoEmpresarialId: null, clienteId: null);
        }

        $grupo = session('grupo_empresarial_id');

        return new TenantScope(
            grupoEmpresarialId: $grupo !== null && $grupo !== '' ? (int) $grupo : null,
            clienteId: null,
        );
    }

    /**
     * @param  PagedResult<GrupoEmpresarial>  $result
     * @return LengthAwarePaginator<int, GrupoEmpresarial>
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
        $entry = $permissions['grupo_empresarial'] ?? null;
        if (is_array($entry)) {
            return (int) ($entry['permissao'] ?? 0);
        }

        return (int) ($entry ?? 0);
    }
}
