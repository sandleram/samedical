<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Procedimento\GetProcedimento;
use App\Application\Procedimento\ListProcedimentos;
use App\Application\Procedimento\SaveProcedimento;
use App\Domain\Procedimento\Procedimento;
use App\Domain\Procedimento\ProcedimentoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveProcedimentoRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class ProcedimentoController extends Controller
{
    public function __construct(
        private readonly ListProcedimentos $list,
        private readonly GetProcedimento $get,
        private readonly SaveProcedimento $save,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'cod_procedimento' => trim((string) $request->query('cod_procedimento', '')),
            'ds_procedimento' => trim((string) $request->query('ds_procedimento', '')),
            'status' => trim((string) $request->query('status', '')),
        ];

        $criteria = new ProcedimentoSearchCriteria(
            id: $search['id_'],
            codProcedimento: $search['cod_procedimento'],
            dsProcedimento: $search['ds_procedimento'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria);

        return view('admin.procedimento.index', [
            'title' => 'Procedimentos',
            'procedimentos' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('procedimento'),
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id);
        if (! $row) {
            return redirect()
                ->route('admin.procedimento.index')
                ->with('status', 'Procedimento Inexistente');
        }

        return view('admin.procedimento.show', [
            'title' => 'Procedimento',
            'procedimento' => $row,
            'permissao' => $this->permission('procedimento'),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {

        $row = null;
        if ($id !== null) {
            $row = $this->get->execute($id);
            if (! $row) {
                return redirect()
                    ->route('admin.procedimento.index')
                    ->with('status', 'Procedimento Inexistente');
            }
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.procedimento.add', [
            'title' => $row ? 'Edição de Procedimento' : 'Cadastro de Procedimento',
            'procedimento' => $row,
            'permissao' => $this->permission('procedimento'),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveProcedimentoRequest $form */
        $form = app(SaveProcedimentoRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute($validated, $id, new DateTimeImmutable, $request->user()?->id);
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possÃ­vel SALVAR a Procedimento, verifique as informaÃ§Ãµes ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.procedimento.add', ['id' => $saved->id])
            ->with('status', 'A Procedimento foi SALVA com sucesso!');
    }

    /**
     * @param  PagedResult<Procedimento>  $result
     * @return LengthAwarePaginator<int, Procedimento>
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

    private function permission(string $module): int
    {
        $entry = session('permissoes', [])[$module] ?? null;

        return is_array($entry) ? (int) ($entry['permissao'] ?? 0) : (int) ($entry ?? 0);
    }
}
