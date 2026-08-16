<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\MhPrestador\GetMhPrestador;
use App\Application\MhPrestador\ListMhPrestadores;
use App\Application\MhPrestador\SaveMhPrestador;
use App\Domain\MhPrestador\MhPrestador;
use App\Domain\MhPrestador\MhPrestadorSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveMhPrestadorRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class MhPrestadorController extends Controller
{
    public function __construct(
        private readonly ListMhPrestadores $list,
        private readonly GetMhPrestador $get,
        private readonly SaveMhPrestador $save,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'status' => $request->query('status', ''),
        ];

        $criteria = new MhPrestadorSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria);

        return view('admin.mh_prestador.index', [
            'title' => 'MH Prestador',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('mh_prestador'),
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id);
        if (! $row) {
            return redirect()
                ->route('admin.mh_prestador.index')
                ->with('status', 'Prestador Inexistente');
        }

        return view('admin.mh_prestador.view', [
            'title' => 'MH Prestador',
            'row' => $row,
            'permissao' => $this->permission('mh_prestador'),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        $row = null;
        if ($id !== null) {
            $row = $this->get->execute($id);
            if (! $row) {
                return redirect()
                    ->route('admin.mh_prestador.index')
                    ->with('status', 'Prestador Inexistente');
            }
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.mh_prestador.add', [
            'title' => $row ? 'Edição de MH Prestador' : 'Cadastro de MH Prestador',
            'row' => $row,
            'permissao' => $this->permission('mh_prestador'),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveMhPrestadorRequest $form */
        $form = app(SaveMhPrestadorRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute($validated, $id, new DateTimeImmutable, $request->user()?->id);
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o Prestador, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.mh_prestador.add', ['id' => $saved->id])
            ->with('status', 'O Prestador foi SALVA com sucesso!');
    }

    /**
     * @param  PagedResult<MhPrestador>  $result
     * @return LengthAwarePaginator<int, MhPrestador>
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
