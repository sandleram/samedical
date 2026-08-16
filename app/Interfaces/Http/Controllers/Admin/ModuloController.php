<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Modulo\GetModulo;
use App\Application\Modulo\ListModulos;
use App\Application\Modulo\PrepareModuloForm;
use App\Application\Modulo\SaveModulo;
use App\Application\Modulo\SaveModuloInput;
use App\Domain\Modulo\Modulo;
use App\Domain\Modulo\ModuloSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveModuloRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

class ModuloController extends Controller
{
    public function __construct(
        private readonly ListModulos $listModulos,
        private readonly GetModulo $getModulo,
        private readonly PrepareModuloForm $prepareForm,
        private readonly SaveModulo $saveModulo,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'modulo_id' => $request->query('modulo_id', ''),
            'nome' => trim((string) $request->query('nome', '')),
            'controller' => trim((string) $request->query('controller', '')),
            'status' => $request->query('status', ''),
        ];

        $isRoot = $request->user()?->isRoot() ?? false;

        $criteria = new ModuloSearchCriteria(
            id: $search['id_'],
            moduloId: is_scalar($search['modulo_id']) ? (string) $search['modulo_id'] : '',
            nome: $search['nome'],
            controller: $search['controller'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! $isRoot,
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $form = $this->prepareForm->execute(null);
        $result = $this->listModulos->execute($criteria);

        return view('admin.modulo.index', [
            'title' => 'Módulos',
            'modulos' => $this->toPaginator($result, $request),
            'search' => $search,
            'moduloArr' => $form['moduloArr'],
            'statusArr' => ['' => 'Status...', '1' => 'Ativo', '0' => 'Inativo', '2' => 'Excluído'],
            'permissao' => $this->currentPermissionLevel(),
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        $modulo = $this->getModulo->execute($id);

        if (! $modulo) {
            return redirect()
                ->route('admin.modulo.index')
                ->with('status', 'Módulo Inexistente');
        }

        return view('admin.modulo.show', [
            'title' => 'Módulo',
            'modulo' => $modulo,
            'permissao' => $this->currentPermissionLevel(),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        $form = $this->prepareForm->execute($id);
        $modulo = $form['modulo'];

        if ($id !== null && ! $modulo) {
            return redirect()
                ->route('admin.modulo.index')
                ->with('status', 'Módulo Inexistente');
        }

        return view('admin.modulo.add', [
            'title' => $modulo ? 'Edição de Módulo' : 'Cadastro de Módulo',
            'modulo' => $modulo,
            'moduloArr' => $form['moduloArr'],
            'statusArr' => ['1' => 'Ativo', '0' => 'Inativo', '2' => 'Excluído'],
            'permissao' => $this->currentPermissionLevel(),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveModuloRequest $formRequest */
        $formRequest = app(SaveModuloRequest::class);
        $validated = $formRequest->validated();

        try {
            $saved = $this->saveModulo->execute(new SaveModuloInput(
                attributes: $validated,
                existingId: $id,
                userId: $request->user()?->id,
                now: new DateTimeImmutable,
            ));
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o Módulo, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.modulo.add', ['id' => $saved->id])
            ->with('status', 'O Módulo foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<Modulo>  $result
     * @return LengthAwarePaginator<int, Modulo>
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
        $entry = $permissions['modulo'] ?? null;
        if (is_array($entry)) {
            return (int) ($entry['permissao'] ?? 0);
        }

        return (int) ($entry ?? 0);
    }
}
