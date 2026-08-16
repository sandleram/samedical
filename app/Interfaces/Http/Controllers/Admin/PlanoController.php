<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Plano\GetPlano;
use App\Application\Plano\ListPlanos;
use App\Application\Plano\PreparePlanoForm;
use App\Application\Plano\SavePlano;
use App\Domain\Plano\Plano;
use App\Domain\Plano\PlanoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SavePlanoRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class PlanoController extends Controller
{
    public function __construct(
        private readonly ListPlanos $listPlanos,
        private readonly GetPlano $getPlano,
        private readonly PreparePlanoForm $prepareForm,
        private readonly SavePlano $savePlano,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'tipo_beneficio_id' => $request->query('tipo_beneficio_id', ''),
            'operadora_id' => $request->query('operadora_id', ''),
            'status' => $request->query('status', ''),
        ];

        $criteria = new PlanoSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            tipoBeneficioId: is_scalar($search['tipo_beneficio_id']) ? (string) $search['tipo_beneficio_id'] : '',
            operadoraId: is_scalar($search['operadora_id']) ? (string) $search['operadora_id'] : '',
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listPlanos->execute($criteria);
        $form = $this->prepareForm->execute(null, true);

        return view('admin.plano.index', [
            'title' => 'Planos',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'operadoraArr' => $form['operadoraArr'],
            'tipoBeneficioArr' => $form['tipoBeneficioArr'],
            'permissao' => $this->permission('plano'),
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        $row = $this->getPlano->execute($id);
        if (! $row) {
            return redirect()
                ->route('admin.plano.index')
                ->with('status', 'Plano Inexistente');
        }

        return view('admin.plano.show', [
            'title' => 'Plano',
            'row' => $row,
            'permissao' => $this->permission('plano'),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        $form = $this->prepareForm->execute($id, false);
        $row = $form['plano'];

        if ($id !== null && ! $row) {
            return redirect()
                ->route('admin.plano.index')
                ->with('status', 'Plano Inexistente');
        }

        return view('admin.plano.add', [
            'title' => $row ? 'Edição de Plano' : 'Cadastro de Plano',
            'row' => $row,
            'operadoraArr' => $form['operadoraArr'],
            'tipoBeneficioArr' => $form['tipoBeneficioArr'],
            'permissao' => $this->permission('plano'),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SavePlanoRequest $form */
        $form = app(SavePlanoRequest::class);
        $validated = $form->validated();

        $clienteId = session('cliente_id');
        $clienteId = $clienteId !== null && $clienteId !== '' ? (int) $clienteId : null;

        try {
            $saved = $this->savePlano->execute($validated, $id, $clienteId, new DateTimeImmutable);
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o Plano, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.plano.add', ['id' => $saved->id])
            ->with('status', 'O Plano foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<Plano>  $result
     * @return LengthAwarePaginator<int, Plano>
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
