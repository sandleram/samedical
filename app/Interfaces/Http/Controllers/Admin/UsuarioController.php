<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Perfil\GetPerfilSelectOptions;
use App\Application\Usuario\GetUsuario;
use App\Application\Usuario\ListUsuarios;
use App\Application\Usuario\PrepareUsuarioForm;
use App\Application\Usuario\SaveUsuario;
use App\Application\Usuario\SaveUsuarioInput;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Domain\Usuario\Usuario;
use App\Domain\Usuario\UsuarioRepositoryInterface;
use App\Domain\Usuario\UsuarioSearchCriteria;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveUsuarioRequest;
use DateTimeImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class UsuarioController extends Controller
{
    public function __construct(
        private readonly ListUsuarios $listUsuarios,
        private readonly GetUsuario $getUsuario,
        private readonly PrepareUsuarioForm $prepareForm,
        private readonly SaveUsuario $saveUsuario,
        private readonly GetPerfilSelectOptions $perfilOptions,
        private readonly UsuarioRepositoryInterface $usuarios,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'usuario' => trim((string) $request->query('usuario', '')),
            'email_' => trim((string) $request->query('email_', '')),
            'perfil' => trim((string) $request->query('perfil', '')),
            'status' => $request->query('status', ''),
        ];

        $auth = $request->user();
        $isRoot = $auth?->isRoot() ?? false;

        $criteria = new UsuarioSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            usuario: $search['usuario'],
            email: $search['email_'],
            perfil: is_scalar($search['perfil']) ? (string) $search['perfil'] : '',
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            excludeRootUser: ! $isRoot,
            perPage: 10,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listUsuarios->execute($criteria, $this->tenantScope(), $isRoot);

        return view('admin.usuario.index', [
            'title' => 'Usuários',
            'usuarios' => $this->toPaginator($result, $request),
            'search' => $search,
            'perfilArr' => $this->perfilOptions->execute($isRoot),
            'statusArr' => ['' => 'Status...', '1' => 'Ativo', '0' => 'Inativo', '2' => 'Excluído'],
            'permissao' => $this->currentPermissionLevel(),
            'perfil_id' => (int) ($auth?->perfil_id ?? 0),
            'isRoot' => $isRoot,
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $isRoot = $request->user()?->isRoot() ?? false;

        if ($id === 1 && ! $isRoot) {
            return redirect()
                ->route('admin.usuario.index')
                ->with('status', 'Usuário Inexistente');
        }

        $usuario = $this->getUsuario->execute($id, $this->tenantScope(), $isRoot);

        if (! $usuario) {
            return redirect()
                ->route('admin.usuario.index')
                ->with('status', 'Usuário Inexistente');
        }

        return view('admin.usuario.show', [
            'title' => 'Usuário',
            'usuario' => $usuario,
            'permissao' => $this->currentPermissionLevel(),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        $auth = $request->user();
        $isRoot = $auth?->isRoot() ?? false;

        if ($id === 1 && ! $isRoot) {
            return redirect()
                ->route('admin.usuario.index')
                ->with('status', 'Usuário Inexistente');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        $form = $this->prepareForm->execute($this->tenantScope(), $isRoot, $id);
        $usuario = $form['usuario'];

        if ($id !== null && ! $usuario) {
            return redirect()
                ->route('admin.usuario.index')
                ->with('status', 'Usuário Inexistente');
        }

        $selectedClientes = old('cliente_id', $form['selectedClientes']);
        $selectedBis = old('bi_id', $form['selectedBis']);

        return view('admin.usuario.add', [
            'title' => $usuario ? 'Edição de Usuário' : 'Cadastro de Usuário',
            'usuario' => $usuario,
            'perfilArr' => $form['perfilArr'],
            'selectClienteNew' => $form['selectClienteNew'],
            'selectBi' => $form['selectBi'],
            'selectedClientes' => array_map('intval', (array) $selectedClientes),
            'selectedBis' => array_map('intval', (array) $selectedBis),
            'sexoArr' => ['' => 'Sexo...', 'Masculino' => 'Masculino', 'Feminino' => 'Feminino'],
            'telTipoArr' => [
                '' => 'Tipo...',
                'Residencial' => 'Residencial',
                'Comercial' => 'Comercial',
                'Fax' => 'Fax',
                'Celular' => 'Celular',
            ],
            'statusArr' => ['1' => 'Ativo', '0' => 'Inativo', '2' => 'Excluído'],
            'permissao' => $this->currentPermissionLevel(),
            'isRoot' => $isRoot,
            'perfil_id' => (int) ($auth?->perfil_id ?? 0),
        ]);
    }

    /**
     * Always-allowed: atualiza cliente_id (e GE) na sessão.
     */
    public function atualiza_session_cliente(Request $request): JsonResponse
    {
        $valor = $request->input('valor');
        $tipo = $request->input('tipo');

        if ($valor !== null && $tipo !== null) {
            if ($tipo === 'cliente_id' || $tipo === 'valor') {
                if (! session()->has('old_cliente_id') && session()->has('cliente_id')) {
                    session(['old_cliente_id' => session('cliente_id')]);
                }

                $clienteId = (int) $valor;
                session(['cliente_id' => $clienteId]);

                $geId = $this->usuarios->findGrupoEmpresarialIdByCliente($clienteId);
                if ($geId) {
                    session(['grupo_empresarial_id' => $geId]);
                }
            } elseif ($tipo === 'grupo_empresarial_id') {
                session(['grupo_empresarial_id' => (int) $valor, 'cliente_id' => null]);
            }
        }

        return response()->json(true);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveUsuarioRequest $formRequest */
        $formRequest = app(SaveUsuarioRequest::class);
        $validated = $formRequest->validated();

        $auth = $request->user();
        $isRoot = $auth?->isRoot() ?? false;
        $grupo = session('grupo_empresarial_id') ?: $auth?->grupo_empresarial_id;

        try {
            $saved = $this->saveUsuario->execute(
                new SaveUsuarioInput(
                    attributes: $validated,
                    existingId: $id,
                    userId: $auth?->id,
                    grupoEmpresarialId: $grupo !== null && $grupo !== '' ? (int) $grupo : null,
                    isRoot: $isRoot,
                    now: new DateTimeImmutable,
                ),
                $this->tenantScope(),
            );
        } catch (RuntimeException $e) {
            $message = $e->getMessage();
            if (str_contains($message, 'SENHA')) {
                return redirect()->back()->withInput()->withErrors(['senha' => $message]);
            }
            if (str_contains($message, 'Perfil')) {
                return redirect()->back()->withInput()->withErrors(['perfil_id' => $message]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o Usuário, verifique as informações ou tente mais tarde!']);
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o Usuário, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.usuario.add', ['id' => $saved->id])
            ->with('status', 'O Usuário foi SALVO com sucesso!');
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
     * @param  PagedResult<Usuario>  $result
     * @return LengthAwarePaginator<int, Usuario>
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
        $entry = $permissions['usuario'] ?? null;
        if (is_array($entry)) {
            return (int) ($entry['permissao'] ?? 0);
        }

        return (int) ($entry ?? 0);
    }
}
