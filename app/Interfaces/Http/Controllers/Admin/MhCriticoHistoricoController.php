<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\MhCriticoHistorico\GetMhCriticoHistorico;
use App\Application\MhCriticoHistorico\ListMhCriticoHistoricos;
use App\Application\MhCriticoHistorico\PrepareMhCriticoHistoricoForm;
use App\Application\MhCriticoHistorico\SaveMhCriticoHistorico;
use App\Domain\MhCriticoHistorico\MhCriticoHistorico;
use App\Domain\MhCriticoHistorico\MhCriticoHistoricoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveMhCriticoHistoricoRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class MhCriticoHistoricoController extends Controller
{
    /** @var array<int|string, string> */
    private array $arrStatusCiclo = [
        '' => 'Selecione...',
        0 => 'Não Iniciada',
        1 => 'Em Andamento',
        2 => 'Concluída',
    ];

    /** @var array<int|string, string> */
    private array $arrCiclo = [
        '' => 'Selecione...',
        0 => 'Prospecção',
        1 => 'Contato',
        2 => 'Mapeameno',
        3 => 'Negociação',
        4 => 'Insucesso',
    ];

    public function __construct(
        private readonly ListMhCriticoHistoricos $list,
        private readonly GetMhCriticoHistorico $get,
        private readonly SaveMhCriticoHistorico $save,
        private readonly PrepareMhCriticoHistoricoForm $prepare,
    ) {}

    public function index(Request $request, int $mh_critico_id): View|RedirectResponse
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'status' => $request->query('status', ''),
        ];

        $criteria = new MhCriticoHistoricoSearchCriteria(
            mhCriticoId: $mh_critico_id,
            id: $search['id_'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $listed = $this->list->execute($criteria);
        if (! $listed['critico']) {
            return redirect()
                ->route('admin.mh_critico.index')
                ->with('status', 'Crítico Histórico  Inexistente');
        }

        return view('admin.mh_critico_historico.index', [
            'title' => 'MH Crítico Histórico',
            'rows' => $this->toPaginator($listed['rows'], $request),
            'search' => $search,
            'mh_critico_id' => $mh_critico_id,
            'critico' => $listed['critico'],
            'permissao' => $this->permission('mh_critico_historico'),
            'ArrCiclo' => $this->labeledSelect($this->arrCiclo, 'Ciclo...'),
            'ArrStatusCiclo' => $this->labeledSelect($this->arrStatusCiclo, 'Status Ciclo...'),
        ]);
    }

    public function show(int $mh_critico_id, int $id): View|RedirectResponse
    {
        if (! $this->get->criticoExists($mh_critico_id)) {
            return redirect()
                ->route('admin.mh_critico.index')
                ->with('status', 'Crítico Histórico  Inexistente');
        }

        $row = $this->get->execute($mh_critico_id, $id);
        if (! $row) {
            return redirect()
                ->route('admin.mh_critico_historico.index', ['mh_critico_id' => $mh_critico_id])
                ->with('status', 'Crítico Histórico  Inexistente');
        }

        return view('admin.mh_critico_historico.view', [
            'title' => 'MH Crítico Histórico',
            'row' => $row,
            'mh_critico_id' => $mh_critico_id,
            'permissao' => $this->permission('mh_critico_historico'),
            'ArrCiclo' => $this->arrCiclo,
            'ArrStatusCiclo' => $this->arrStatusCiclo,
        ]);
    }

    public function add(Request $request, int $mh_critico_id, ?int $id = null): View|RedirectResponse
    {
        $prepared = $this->prepare->execute($mh_critico_id, $id);
        if (! $prepared['criticoExists']) {
            return redirect()
                ->route('admin.mh_critico.index')
                ->with('status', 'Crítico Histórico  Inexistente');
        }

        $row = $prepared['row'];
        if ($id !== null && ! $row) {
            return redirect()
                ->route('admin.mh_critico_historico.index', ['mh_critico_id' => $mh_critico_id])
                ->with('status', 'Crítico Histórico  Inexistente');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $mh_critico_id, $id);
        }

        return view('admin.mh_critico_historico.add', [
            'title' => $row ? 'Edição de MH Crítico Histórico' : 'Cadastro de MH Crítico Histórico',
            'row' => $row,
            'mh_critico_id' => $mh_critico_id,
            'permissao' => $this->permission('mh_critico_historico'),
            'ArrCiclo' => $this->arrCiclo,
            'ArrStatusCiclo' => $this->arrStatusCiclo,
        ]);
    }

    private function store(Request $request, int $mhCriticoId, ?int $id): RedirectResponse
    {
        /** @var SaveMhCriticoHistoricoRequest $form */
        $form = app(SaveMhCriticoHistoricoRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute($validated, $mhCriticoId, $id, new DateTimeImmutable, $request->user()?->id);
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o Crítico Histórico , verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.mh_critico_historico.add', ['mh_critico_id' => $mhCriticoId, 'id' => $saved->id])
            ->with('status', 'O Crítico Histórico  foi SALVA com sucesso!');
    }

    /**
     * @param  PagedResult<MhCriticoHistorico>  $result
     * @return LengthAwarePaginator<int, MhCriticoHistorico>
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

    /**
     * @param  array<int|string, string>  $options
     * @return array<int|string, string>
     */
    private function labeledSelect(array $options, string $emptyLabel): array
    {
        $options[''] = $emptyLabel;

        return $options;
    }
}
