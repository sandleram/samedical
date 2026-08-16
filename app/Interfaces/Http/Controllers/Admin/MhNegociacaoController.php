<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\MhNegociacao\GetMhNegociacao;
use App\Application\MhNegociacao\ListMhNegociacoes;
use App\Application\MhNegociacao\PrepareMhNegociacaoForm;
use App\Application\MhNegociacao\SaveMhNegociacao;
use App\Domain\MhNegociacao\MhNegociacao;
use App\Domain\MhNegociacao\MhNegociacaoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveMhNegociacaoRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class MhNegociacaoController extends Controller
{
    /** @var array<int, string> */
    private array $arrTipoNegocio = [
        0 => 'Lista de desejos',
        1 => 'Críticos',
        2 => 'Novos Produtos',
        3 => 'Suficiência de rede',
    ];

    public function __construct(
        private readonly ListMhNegociacoes $list,
        private readonly GetMhNegociacao $get,
        private readonly SaveMhNegociacao $save,
        private readonly PrepareMhNegociacaoForm $prepare,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'status' => $request->query('status', ''),
        ];

        $criteria = new MhNegociacaoSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria);

        return view('admin.mh_negociacao.index', [
            'title' => 'MH Negociação',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('mh_negociacao'),
            'ArrTipoNegocio' => $this->arrTipoNegocio,
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id);
        if (! $row) {
            return redirect()
                ->route('admin.mh_negociacao.index')
                ->with('status', 'Negociação Inexistente');
        }

        return view('admin.mh_negociacao.view', [
            'title' => 'MH Negociação',
            'row' => $row,
            'permissao' => $this->permission('mh_negociacao'),
            'ArrTipoNegocio' => $this->arrTipoNegocio,
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        $prepared = $this->prepare->execute($id);
        $row = $prepared['row'];
        if ($id !== null && ! $row) {
            return redirect()
                ->route('admin.mh_negociacao.index')
                ->with('status', 'Negociação Inexistente');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.mh_negociacao.add', [
            'title' => $row ? 'Edição de MH Negociação' : 'Cadastro de MH Negociação',
            'row' => $row,
            'listPrestador' => $prepared['options']['listPrestador'],
            'ArrTipoNegocio' => $this->arrTipoNegocio,
            'permissao' => $this->permission('mh_negociacao'),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveMhNegociacaoRequest $form */
        $form = app(SaveMhNegociacaoRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute($validated, $id, new DateTimeImmutable, $request->user()?->id);
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR a Negociação, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.mh_negociacao.add', ['id' => $saved->id])
            ->with('status', 'A Negociação foi SALVA com sucesso!');
    }

    /**
     * @param  PagedResult<MhNegociacao>  $result
     * @return LengthAwarePaginator<int, MhNegociacao>
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
