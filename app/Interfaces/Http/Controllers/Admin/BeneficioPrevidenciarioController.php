<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\BeneficioPrevidenciario\GetBeneficioPrevidenciario;
use App\Application\BeneficioPrevidenciario\ListBeneficioPrevidenciarios;
use App\Application\BeneficioPrevidenciario\SaveBeneficioPrevidenciario;
use App\Application\BeneficioPrevidenciario\PrepareBeneficioPrevidenciarioForm;
use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciario;
use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciarioSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveBeneficioPrevidenciarioRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class BeneficioPrevidenciarioController extends Controller
{
    public function __construct(
        private readonly ListBeneficioPrevidenciarios $list,
        private readonly GetBeneficioPrevidenciario $get,
        private readonly SaveBeneficioPrevidenciario $save,
        private readonly PrepareBeneficioPrevidenciarioForm $prepare,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'nb' => trim((string) $request->query('nb', '')),
            'status' => trim((string) $request->query('status', '')),
        ];

        $criteria = new BeneficioPrevidenciarioSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            nb: $search['nb'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria, $this->tenantScope());

        return view('admin.beneficio_previdenciario.index', [
            'title' => 'Benefícios Previdenciários',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('beneficio_previdenciario'),
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id, $this->tenantScope());
        if (! $row) {
            return redirect()
                ->route('admin.beneficio_previdenciario.index')
                ->with('status', 'Benefício Previdenciário Inexistente');
        }

        return view('admin.beneficio_previdenciario.show', [
            'title' => 'Benefício Previdenciário',
            'row' => $row,
            'permissao' => $this->permission('beneficio_previdenciario'),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {

        $clienteId = session('cliente_id');
        if (! $clienteId) {
            return redirect()
                ->route('admin.home')
                ->with('status', 'Selecione um cliente para continuar.');
        }

        $prepared = $this->prepare->execute((int) $clienteId, $this->tenantScope(), $id);
        $row = $prepared['row'];
        $options = $prepared['options'];
        if ($id !== null && ! $row) {
            return redirect()
                ->route('admin.beneficio_previdenciario.index')
                ->with('status', 'Benefício Previdenciário Inexistente');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.beneficio_previdenciario.add', [
            'title' => $row ? 'Edição de Benefício Previdenciário' : 'Cadastro de Benefício Previdenciário',
            'row' => $row,
            'permissao' => $this->permission('beneficio_previdenciario'),
            ...$this->flattenOptions($options),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveBeneficioPrevidenciarioRequest $form */
        $form = app(SaveBeneficioPrevidenciarioRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute($validated, $id, new DateTimeImmutable, $request->user()?->id, (int) session('cliente_id'), $this->tenantScope());
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possÃ­vel SALVAR o Benefício Previdenciário, verifique as informaÃ§Ãµes ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.beneficio_previdenciario.add', ['id' => $saved->id])
            ->with('status', 'O Benefício Previdenciário foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<BeneficioPrevidenciario>  $result
     * @return LengthAwarePaginator<int, BeneficioPrevidenciario>
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

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    private function flattenOptions(array $options): array
    {
        return $options;
    }

    private function permission(string $module): int
    {
        $entry = session('permissoes', [])[$module] ?? null;

        return is_array($entry) ? (int) ($entry['permissao'] ?? 0) : (int) ($entry ?? 0);
    }
}
