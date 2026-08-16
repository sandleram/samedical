<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Operadora\GetOperadora;
use App\Application\Operadora\ListOperadoras;
use App\Application\Operadora\SaveOperadora;
use App\Domain\Operadora\Operadora;
use App\Domain\Operadora\OperadoraSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveOperadoraRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class OperadoraController extends Controller
{
    public function __construct(
        private readonly ListOperadoras $listOperadoras,
        private readonly GetOperadora $getOperadora,
        private readonly SaveOperadora $saveOperadora,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'status' => $request->query('status', ''),
        ];

        $criteria = new OperadoraSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listOperadoras->execute($criteria);

        return view('admin.operadora.index', [
            'title' => 'Operadoras',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('operadora'),
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        $row = $this->getOperadora->execute($id);
        if (! $row) {
            return redirect()
                ->route('admin.operadora.index')
                ->with('status', 'Operadora Inexistente');
        }

        return view('admin.operadora.show', [
            'title' => 'Operadora',
            'row' => $row,
            'permissao' => $this->permission('operadora'),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        $row = null;
        if ($id !== null) {
            $row = $this->getOperadora->execute($id);
            if (! $row) {
                return redirect()
                    ->route('admin.operadora.index')
                    ->with('status', 'Operadora Inexistente');
            }
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.operadora.add', [
            'title' => $row ? 'Edição de Operadora' : 'Cadastro de Operadora',
            'row' => $row,
            'permissao' => $this->permission('operadora'),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveOperadoraRequest $form */
        $form = app(SaveOperadoraRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->saveOperadora->execute($validated, $id, new DateTimeImmutable);
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o Operadora, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.operadora.add', ['id' => $saved->id])
            ->with('status', 'O Operadora foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<Operadora>  $result
     * @return LengthAwarePaginator<int, Operadora>
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
