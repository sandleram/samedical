<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\MhCritico\GetMhCritico;
use App\Application\MhCritico\ListMhCriticos;
use App\Application\MhCritico\PrepareMhCriticoForm;
use App\Application\MhCritico\SaveMhCritico;
use App\Domain\MhCritico\MhCritico;
use App\Domain\MhCritico\MhCriticoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveMhCriticoRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use InvalidArgumentException;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class MhCriticoController extends Controller
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

    /** @var array<int|string, string> */
    private array $arrOpcao = [
        '' => 'Selecione...',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
    ];

    public function __construct(
        private readonly ListMhCriticos $list,
        private readonly GetMhCritico $get,
        private readonly SaveMhCritico $save,
        private readonly PrepareMhCriticoForm $prepare,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'status' => $request->query('status', ''),
        ];

        $criteria = new MhCriticoSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $listed = $this->list->execute($criteria);

        return view('admin.mh_critico.index', [
            'title' => 'MH Crítico',
            'rows' => $this->toPaginator($listed['principals'], $request),
            'rowsSub' => $listed['rowsSub'],
            'search' => $search,
            'permissao' => $this->permission('mh_critico'),
            'ArrCiclo' => $this->labeledSelect($this->arrCiclo, 'Ciclo...'),
            'ArrStatusCiclo' => $this->labeledSelect($this->arrStatusCiclo, 'Status Ciclo...'),
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id);
        if (! $row) {
            return redirect()
                ->route('admin.mh_critico.index')
                ->with('status', 'Crítico Inexistente');
        }

        return view('admin.mh_critico.view', [
            'title' => 'MH Crítico',
            'row' => $row,
            'permissao' => $this->permission('mh_critico'),
            'ArrCiclo' => $this->arrCiclo,
            'ArrStatusCiclo' => $this->arrStatusCiclo,
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        $prepared = $this->prepare->execute($id);
        $row = $prepared['row'];
        if ($id !== null && ! $row) {
            return redirect()
                ->route('admin.mh_critico.index')
                ->with('status', 'Crítico Inexistente');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.mh_critico.add', [
            'title' => $row ? 'Edição de MH Crítico' : 'Cadastro de MH Crítico',
            'row' => $row,
            'permissao' => $this->permission('mh_critico'),
            'listPrestadorAll' => $prepared['options']['listPrestadorAll'],
            'listPrestadorSemUsados' => $prepared['options']['listPrestadorSemUsados'],
            'ArrOpcao' => $this->arrOpcao,
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveMhCriticoRequest $form */
        $form = app(SaveMhCriticoRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute(
                $validated,
                $id,
                new DateTimeImmutable,
                $request->user()?->id !== null ? (string) $request->user()->id : null,
            );
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->withInput()->withErrors([
                'mh_prestador_id' => $e->getMessage(),
            ]);
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o Crítico, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.mh_critico.add', ['id' => $saved->id])
            ->with('status', 'O Crítico foi SALVA com sucesso!');
    }

    /**
     * @param  PagedResult<MhCritico>  $result
     * @return LengthAwarePaginator<int, MhCritico>
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
