<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Atendimento\GetAtendimento;
use App\Application\Atendimento\ListAtendimentos;
use App\Application\Atendimento\SaveAtendimento;
use App\Application\Atendimento\PrepareAtendimentoForm;
use App\Domain\Atendimento\Atendimento;
use App\Domain\Atendimento\AtendimentoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveAtendimentoRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class AtendimentoController extends Controller
{
    public function __construct(
        private readonly ListAtendimentos $list,
        private readonly GetAtendimento $get,
        private readonly SaveAtendimento $save,
        private readonly PrepareAtendimentoForm $prepare,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'status' => trim((string) $request->query('status', '')),
        ];

        $criteria = new AtendimentoSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria, $this->tenantScope());

        $tipoAtendimentoArr = \App\Domain\Atendimento\TipoAtendimento::labels();

        return view('admin.atendimento.index', [
            'title' => 'Atendimentos',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('atendimento'),
            'tipoAtendimentoArr' => $tipoAtendimentoArr,
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id, $this->tenantScope());
        if (! $row) {
            return redirect()
                ->route('admin.atendimento.index')
                ->with('status', 'Atendimento Inexistente');
        }

        return view('admin.atendimento.show', [
            'title' => 'Atendimento',
            'row' => $row,
            'permissao' => $this->permission('atendimento'),
            'tipoAtendimentoArr' => \App\Domain\Atendimento\TipoAtendimento::labels(),
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

        $prepared = $this->prepare->execute($this->tenantScope(), $id);
        $row = $prepared['row'];
        $options = $prepared['options'];
        if ($id !== null && ! $row) {
            return redirect()
                ->route('admin.atendimento.index')
                ->with('status', 'Atendimento Inexistente');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.atendimento.add', [
            'title' => $row ? 'Edição de Atendimento' : 'Cadastro de Atendimento',
            'row' => $row,
            'permissao' => $this->permission('atendimento'),
            ...$this->flattenOptions($options),
            'tipoAtendimentoArr' => \App\Domain\Atendimento\TipoAtendimento::labels(true),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveAtendimentoRequest $form */
        $form = app(SaveAtendimentoRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute($validated, $id, new DateTimeImmutable, $request->user()?->id, (int) session('cliente_id'), $this->tenantScope());
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possÃ­vel SALVAR o Atendimento, verifique as informaÃ§Ãµes ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.atendimento.add', ['id' => $saved->id])
            ->with('status', 'O Atendimento foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<Atendimento>  $result
     * @return LengthAwarePaginator<int, Atendimento>
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
