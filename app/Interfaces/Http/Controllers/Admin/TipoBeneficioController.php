<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\TipoBeneficio\GetTipoBeneficio;
use App\Application\TipoBeneficio\ListTipoBeneficios;
use App\Application\TipoBeneficio\SaveTipoBeneficio;
use App\Domain\TipoBeneficio\TipoBeneficio;
use App\Domain\TipoBeneficio\TipoBeneficioSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveTipoBeneficioRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class TipoBeneficioController extends Controller
{
    public function __construct(
        private readonly ListTipoBeneficios $list,
        private readonly GetTipoBeneficio $get,
        private readonly SaveTipoBeneficio $save,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'descricao' => trim((string) $request->query('descricao', '')),
            'status' => trim((string) $request->query('status', '')),
        ];

        $criteria = new TipoBeneficioSearchCriteria(
            id: $search['id_'],
            descricao: $search['descricao'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria);

        return view('admin.tipo_beneficio.index', [
            'title' => 'Tipos de Benefício',
            'tipos' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('tipo_beneficio'),
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id);
        if (! $row) {
            return redirect()
                ->route('admin.tipo_beneficio.index')
                ->with('status', 'Tipo Beneficio Inexistente');
        }

        return view('admin.tipo_beneficio.show', [
            'title' => 'Tipo de Benefício',
            'tipoBeneficio' => $row,
            'permissao' => $this->permission('tipo_beneficio'),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {

        $row = null;
        if ($id !== null) {
            $row = $this->get->execute($id);
            if (! $row) {
                return redirect()
                    ->route('admin.tipo_beneficio.index')
                    ->with('status', 'Tipo Beneficio Inexistente');
            }
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.tipo_beneficio.add', [
            'title' => $row ? 'Edição de Tipo de Benefício' : 'Cadastro de Tipo de Benefício',
            'tipoBeneficio' => $row,
            'permissao' => $this->permission('tipo_beneficio'),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveTipoBeneficioRequest $form */
        $form = app(SaveTipoBeneficioRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute($validated, $id, new DateTimeImmutable);
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possÃ­vel SALVAR o Tipo Beneficio, verifique as informaÃ§Ãµes ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.tipo_beneficio.add', ['id' => $saved->id])
            ->with('status', 'O Tipo Beneficio foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<TipoBeneficio>  $result
     * @return LengthAwarePaginator<int, TipoBeneficio>
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
