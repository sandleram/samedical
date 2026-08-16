<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Bi\GetBi;
use App\Application\Bi\GetBiIframeUrl;
use App\Application\Bi\ListBiDashboards;
use App\Application\Bi\ListBis;
use App\Application\Bi\SaveBi;
use App\Domain\Bi\Bi;
use App\Domain\Bi\BiSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveBiRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class BiController extends Controller
{
    public function __construct(
        private readonly ListBis $list,
        private readonly GetBi $get,
        private readonly SaveBi $save,
        private readonly ListBiDashboards $listDashboards,
        private readonly GetBiIframeUrl $iframeUrl,
    ) {}

    public function lista(Request $request): View
    {
        $list = $this->listDashboards->execute(
            (int) $request->user()->id,
            $this->tenantScope(),
        );

        return view('admin.bi.lista', [
            'title' => 'Dashboards BI',
            'list' => $list,
            'permissao' => $this->permission('bi'),
        ]);
    }

    public function gerencial(Request $request): View
    {
        return view('admin.bi.iframe', [
            'title' => 'BI Gerencial',
            'url' => $this->iframeUrl->execute('gerencial', $this->tenantScope()),
            'permissao' => $this->permission('bi'),
        ]);
    }

    public function medico(Request $request): View
    {
        return view('admin.bi.iframe', [
            'title' => 'BI Médico',
            'url' => $this->iframeUrl->execute('medico', $this->tenantScope()),
            'permissao' => $this->permission('bi'),
        ]);
    }

    public function rh(Request $request): View
    {
        return view('admin.bi.iframe', [
            'title' => 'BI RH',
            'url' => $this->iframeUrl->execute('rh', $this->tenantScope()),
            'permissao' => $this->permission('bi'),
        ]);
    }

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'titulo' => trim((string) $request->query('titulo', '')),
            'status' => $request->query('status', ''),
        ];

        $user = $request->user();
        $criteria = new BiSearchCriteria(
            id: $search['id_'],
            titulo: $search['titulo'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($user?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria, $this->tenantScope());

        return view('admin.bi.index', [
            'title' => 'BI',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('bi'),
            'perfil_id' => (int) ($user?->perfil_id ?? 0),
            'perfil_adm' => [1, 2, 3],
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id, $this->tenantScope());
        if (! $row) {
            return redirect()->route('admin.bi.index')->with('error', 'Bi Inexistente');
        }

        return view('admin.bi.view', [
            'title' => 'BI',
            'row' => $row,
            'permissao' => $this->permission('bi'),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        $row = null;
        if ($id !== null) {
            $row = $this->get->execute($id, $this->tenantScope());
            if (! $row) {
                return redirect()->route('admin.bi.index')->with('error', 'Bi Inexistente');
            }
        }

        if ($request->isMethod('post') || $request->isMethod('put')) {
            return $this->store($request, $id);
        }

        return view('admin.bi.add', [
            'title' => $row ? 'Edição de BI' : 'Cadastro de BI',
            'row' => $row,
            'permissao' => $this->permission('bi'),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveBiRequest $form */
        $form = app(SaveBiRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute(
                $validated,
                $id,
                new DateTimeImmutable,
                $request->user()?->id,
                $this->tenantScope(),
            );
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o Bi.']);
        }

        return redirect()
            ->route('admin.bi.add', ['id' => $saved->id])
            ->with('status', 'O Bi foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<Bi>  $result
     * @return LengthAwarePaginator<int, Bi>
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
