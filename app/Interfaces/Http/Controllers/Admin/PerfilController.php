<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Perfil\GetPerfil;
use App\Application\Perfil\ListPerfis;
use App\Application\Perfil\PreparePerfilForm;
use App\Application\Perfil\SavePerfil;
use App\Application\Perfil\SavePerfilInput;
use App\Domain\Perfil\Perfil;
use App\Domain\Perfil\PerfilSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SavePerfilRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

class PerfilController extends Controller
{
    public function __construct(
        private readonly ListPerfis $listPerfis,
        private readonly GetPerfil $getPerfil,
        private readonly PreparePerfilForm $prepareForm,
        private readonly SavePerfil $savePerfil,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'status' => $request->query('status', ''),
        ];

        $isRoot = $request->user()?->isRoot() ?? false;

        $criteria = new PerfilSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! $isRoot,
            perPage: 10,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listPerfis->execute($criteria);

        return view('admin.perfil.index', [
            'title' => 'Perfis',
            'perfis' => $this->toPaginator($result, $request),
            'search' => $search,
            'tipoLabels' => [0 => 'Operacional', 1 => 'Administrativo', 2 => 'Master'],
            'statusArr' => ['' => 'Status...', '1' => 'Ativo', '0' => 'Inativo', '2' => 'Excluído'],
            'permissao' => $this->currentPermissionLevel(),
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        $perfil = $this->getPerfil->execute($id);

        if (! $perfil) {
            return redirect()
                ->route('admin.perfil.index')
                ->with('status', 'Perfil Inexistente');
        }

        $form = $this->prepareForm->execute($id);

        return view('admin.perfil.show', [
            'title' => 'Perfil',
            'perfil' => $perfil,
            'modulos' => collect($form['modulos']),
            'tipoLabels' => [0 => 'Operacional', 1 => 'Administrativo', 2 => 'Master'],
            'permissao' => $this->currentPermissionLevel(),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        $form = $this->prepareForm->execute($id);
        $perfil = $form['perfil'];

        if ($id !== null && ! $perfil) {
            return redirect()
                ->route('admin.perfil.index')
                ->with('status', 'Perfil Inexistente');
        }

        $permissoesSalvas = $form['permissoesSalvas'];
        $oldPm = old('PerfilModulo');
        if (is_array($oldPm)) {
            foreach ($oldPm as $moduloId => $row) {
                $permissoesSalvas[(int) $moduloId] = [
                    'id' => $row['id'] ?? ($permissoesSalvas[(int) $moduloId]['id'] ?? ''),
                    'permissao' => (int) ($row['permissao'] ?? 0),
                ];
            }
        }

        return view('admin.perfil.add', [
            'title' => $perfil ? 'Edição de Perfil' : 'Cadastro de Perfil',
            'perfil' => $perfil,
            'modulos' => collect($form['modulos']),
            'permissoesSalvas' => $permissoesSalvas,
            'tipoArr' => ['' => 'Selecione...', '0' => 'Operacional', '1' => 'Administrativo', '2' => 'Master'],
            'statusArr' => ['1' => 'Ativo', '0' => 'Inativo', '2' => 'Excluído'],
            'permissao' => $this->currentPermissionLevel(),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SavePerfilRequest $formRequest */
        $formRequest = app(SavePerfilRequest::class);
        $validated = $formRequest->validated();

        try {
            $saved = $this->savePerfil->execute(new SavePerfilInput(
                attributes: $validated,
                existingId: $id,
                userId: $request->user()?->id,
                now: new DateTimeImmutable,
            ));
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o Perfil, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.perfil.add', ['id' => $saved->id])
            ->with('status', 'O Perfil foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<Perfil>  $result
     * @return LengthAwarePaginator<int, Perfil>
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
        $entry = $permissions['perfil'] ?? null;
        if (is_array($entry)) {
            return (int) ($entry['permissao'] ?? 0);
        }

        return (int) ($entry ?? 0);
    }
}
