<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Parametro\GetParametro;
use App\Application\Parametro\ListParametros;
use App\Application\Parametro\PrepareParametroForm;
use App\Application\Parametro\SaveParametro;
use App\Domain\Parametro\Parametro;
use App\Domain\Parametro\ParametroSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveParametroRequest;
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
class ParametroController extends Controller
{
    public function __construct(
        private readonly ListParametros $listParametros,
        private readonly GetParametro $getParametro,
        private readonly PrepareParametroForm $prepareForm,
        private readonly SaveParametro $saveParametro,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'tipo' => trim((string) $request->query('tipo', '')),
            'valor' => trim((string) $request->query('valor', '')),
            'status' => $request->query('status', ''),
        ];

        $criteria = new ParametroSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            tipo: $search['tipo'],
            valor: $search['valor'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listParametros->execute($criteria);

        return view('admin.parametro.index', [
            'title' => 'Parâmetros',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('parametro'),
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        $row = $this->getParametro->execute($id);
        if (! $row) {
            return redirect()
                ->route('admin.parametro.index')
                ->with('status', 'Parâmetro Inexistente');
        }

        return view('admin.parametro.show', [
            'title' => 'Parâmetro',
            'row' => $row,
            'permissao' => $this->permission('parametro'),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        $form = $this->prepareForm->execute($id);
        $row = $form['parametro'];

        if ($id !== null && ! $row) {
            return redirect()
                ->route('admin.parametro.index')
                ->with('status', 'Parâmetro Inexistente');
        }

        return view('admin.parametro.add', [
            'title' => $row ? 'Edição de Parâmetro' : 'Cadastro de Parâmetro',
            'row' => $row,
            'tipoArr' => $form['tipoArr'],
            'isRoot' => $request->user()?->isRoot() ?? false,
            'permissao' => $this->permission('parametro'),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveParametroRequest $form */
        $form = app(SaveParametroRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->saveParametro->execute(
                $validated,
                $id,
                $request->user()?->id,
                new DateTimeImmutable,
            );
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['tipo' => $e->getMessage()]);
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o Parâmetro, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.parametro.add', ['id' => $saved->id])
            ->with('status', 'O Parâmetro foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<Parametro>  $result
     * @return LengthAwarePaginator<int, Parametro>
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
