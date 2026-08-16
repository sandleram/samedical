<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Agendamento\GetAgendamento;
use App\Application\Agendamento\ListAgendamentos;
use App\Application\Agendamento\SaveAgendamento;
use App\Application\Agendamento\PrepareAgendamentoForm;
use App\Domain\Agendamento\Agendamento;
use App\Domain\Agendamento\AgendamentoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveAgendamentoRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: FormRequest + UseCase + view/redirect.
 */
class AgendamentoController extends Controller
{
    public function __construct(
        private readonly ListAgendamentos $list,
        private readonly GetAgendamento $get,
        private readonly SaveAgendamento $save,
        private readonly PrepareAgendamentoForm $prepare,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'cod_' => trim((string) $request->query('cod_', '')),
            'usuario_agendamento_id' => trim((string) $request->query('usuario_agendamento_id', '')),
            'status' => trim((string) $request->query('status', '')),
        ];

        $perfilId = (int) ($request->user()?->perfil_id ?? 0);
        $isAdminPerfil = in_array($perfilId, [1, 2, 3, 13], true);
        $restrict = $isAdminPerfil ? null : ($request->user()?->id);

        $criteria = new AgendamentoSearchCriteria(
            cod: $search['cod_'],
            usuarioAgendamentoId: $search['usuario_agendamento_id'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            restrictToUsuarioId: $restrict,
            onlyOpen: true,
            onlyActiveForNonRoot: ! ($request->user()?->isRoot() ?? false),
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->list->execute($criteria, $this->tenantScope());

        $usrList = [];
        if ($isAdminPerfil) {
            $usrList = $this->prepare->execute($this->tenantScope())['options']['usuarios'] ?? [];
        }

        return view('admin.agendamento.index', [
            'title' => 'Agendamentos',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->permission('agendamento'),
            'usrList' => $usrList,
            'isAdminPerfil' => $isAdminPerfil,
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->get->execute($id, $this->tenantScope());
        if (! $row) {
            return redirect()
                ->route('admin.agendamento.index')
                ->with('status', 'Agendamento Inexistente');
        }

        return view('admin.agendamento.show', [
            'title' => 'Agendamento',
            'row' => $row,
            'permissao' => $this->permission('agendamento'),
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
                ->route('admin.agendamento.index')
                ->with('status', 'Agendamento Inexistente');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.agendamento.add', [
            'title' => $row ? 'Edição de Agendamento' : 'Cadastro de Agendamento',
            'row' => $row,
            'permissao' => $this->permission('agendamento'),
            ...$this->flattenOptions($options),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        /** @var SaveAgendamentoRequest $form */
        $form = app(SaveAgendamentoRequest::class);
        $validated = $form->validated();

        try {
            $saved = $this->save->execute($validated, $id, new DateTimeImmutable, $request->user()?->id, $this->tenantScope());
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possÃ­vel SALVAR o Agendamento, verifique as informaÃ§Ãµes ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.agendamento.add', ['id' => $saved->id])
            ->with('status', 'O Agendamento foi SALVO com sucesso!');
    }

    /**
     * @param  PagedResult<Agendamento>  $result
     * @return LengthAwarePaginator<int, Agendamento>
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
