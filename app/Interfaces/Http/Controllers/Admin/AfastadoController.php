<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Afastado\GetAfastado;
use App\Application\Afastado\ListAfastados;
use App\Application\Afastado\SaveAfastado;
use App\Application\Afastado\PrepareAfastadoForm;
use App\Domain\Afastado\Afastado;
use App\Domain\Afastado\AfastadoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveAfastadoRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class AfastadoController extends Controller
{
    public function __construct(
        private readonly ListAfastados $list,
        private readonly GetAfastado $get,
        private readonly SaveAfastado $save,
        private readonly PrepareAfastadoForm $prepare,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'cpf' => trim((string) $request->query('cpf', '')),
            'status' => trim((string) $request->query('status', '')),
        ];

        $criteria = new AfastadoSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            cpf: $search['cpf'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria, $this->tenantScope());

        return view('admin.afastado.index', [
            'title' => 'Afastados',
            'afastados' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('afastado'),
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id, $this->tenantScope());
        if (! $row) {
            return redirect()
                ->route('admin.afastado.index')
                ->with('status', 'Afastamento Inexistente');
        }

        return view('admin.afastado.show', [
            'title' => 'Afastado',
            'afastado' => $row,
            'permissao' => $this->permission('afastado'),
            'simNaoArr' => [0 => 'Não', 1 => 'Sim'],
            'simNaoAcaoInssArr' => [0 => 'Não', 1 => 'Sim, ação judicial', 2 => 'Sim, recurso administrativo'],
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
                ->route('admin.afastado.index')
                ->with('status', 'Afastamento Inexistente');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.afastado.add', [
            'title' => $row ? 'Edição de Afastado' : 'Cadastro de Afastado',
            'afastado' => $row,
            'permissao' => $this->permission('afastado'),
            ...$this->flattenOptions($options),
            'situacaoArr' => ['' => 'Situação...', 'A' => 'Afastado', 'RT' => 'Retorno ao Trabalho'],
            'simNaoArr' => ['' => 'Selecione...', 0 => 'Não', 1 => 'Sim'],
            'simNaoAcaoInssArr' => ['' => 'Selecione...', 0 => 'Não', 1 => 'Sim, ação judicial', 2 => 'Sim, recurso administrativo'],
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveAfastadoRequest $form */
        $form = app(SaveAfastadoRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute($validated, $id, new DateTimeImmutable, $request->user()?->id, (int) session('cliente_id'), $this->tenantScope());
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possÃ­vel SALVAR o Afastamento, verifique as informaÃ§Ãµes ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.afastado.add', ['id' => $saved->id])
            ->with('status', 'O Afastamento foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<Afastado>  $result
     * @return LengthAwarePaginator<int, Afastado>
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
