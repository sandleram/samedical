<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Empresa\GetEmpresa;
use App\Application\Empresa\ListEmpresas;
use App\Application\Empresa\SaveEmpresa;
use App\Application\Empresa\SaveEmpresaInput;
use App\Domain\Empresa\Empresa;
use App\Domain\Empresa\EmpresaSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveEmpresaRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

/**
 * Controller fino: valida input, monta TenantScope, chama UseCases, devolve Blade/redirect.
 */
class EmpresaController extends Controller
{
    public function __construct(
        private readonly ListEmpresas $listEmpresas,
        private readonly GetEmpresa $getEmpresa,
        private readonly SaveEmpresa $saveEmpresa,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        if (! session('cliente_id') && ! session('grupo_empresarial_id')) {
            return redirect()
                ->route('admin.grupo_empresarial.selecione')
                ->with('status', 'Selecione um cliente.');
        }

        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'razao_social' => trim((string) $request->query('razao_social', '')),
            'cnpj' => trim((string) $request->query('cnpj', '')),
            'status' => $request->query('status', ''),
        ];

        $user = $request->user();
        $isRoot = $user?->isRoot() ?? false;

        $criteria = new EmpresaSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            razaoSocial: $search['razao_social'],
            cnpj: $search['cnpj'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! $isRoot,
            perPage: 10,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listEmpresas->execute($criteria, $this->tenantScope());
        $rows = $this->toPaginator($result, $request);

        return view('admin.empresa.index', [
            'title' => 'Empresas',
            'rows' => $rows,
            'search' => $search,
            'permissao' => $this->currentPermissionLevel(),
            'perfil_id' => (int) ($user?->perfil_id ?? 0),
            'perfil_adm' => [1, 2, 3],
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $row = $this->getEmpresa->execute($id, $this->tenantScope());

        if (! $row) {
            return redirect()
                ->route('admin.empresa.index')
                ->with('status', 'Empresa Inexistente');
        }

        return view('admin.empresa.show', [
            'title' => 'Empresa',
            'row' => $row,
            'permissao' => $this->currentPermissionLevel(),
            'porteArr' => $this->porteOptions(),
            'faturamentoArr' => $this->faturamentoOptions(),
            'tipoArr' => $this->tipoOptions(),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        if (! session('cliente_id')) {
            return redirect()
                ->route('admin.grupo_empresarial.selecione')
                ->with('status', 'Selecione um cliente para cadastrar empresas.');
        }

        $row = null;
        if ($id !== null) {
            $row = $this->getEmpresa->execute($id, $this->tenantScope());
            if (! $row) {
                return redirect()
                    ->route('admin.empresa.index')
                    ->with('status', 'Empresa Inexistente');
            }
        }

        if ($request->isMethod('post')) {
            return $this->store($request, $id);
        }

        return view('admin.empresa.add', [
            'title' => $row ? 'Edição de Empresa' : 'Cadastro de Empresa',
            'row' => $row,
            'permissao' => $this->currentPermissionLevel(),
            'porteArr' => $this->porteOptions(true),
            'faturamentoArr' => $this->faturamentoOptions(true),
            'tipoArr' => $this->tipoOptions(true),
        ]);
    }

    private function store(Request $request, ?int $id): RedirectResponse
    {
        $clienteId = (int) session('cliente_id');

        /** @var SaveEmpresaRequest $formRequest */
        $formRequest = app(SaveEmpresaRequest::class);
        $validated = $formRequest->validated();

        try {
            $saved = $this->saveEmpresa->execute(
                new SaveEmpresaInput(
                    attributes: $validated,
                    clienteId: $clienteId,
                    existingId: $id,
                    userId: $request->user()?->id,
                    now: new DateTimeImmutable,
                ),
                $this->tenantScope(),
            );
        } catch (Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR a Empresa, verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.empresa.add', ['id' => $saved->id])
            ->with('status', 'A Empresa foi SALVA com sucesso!');
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
     * @param  PagedResult<Empresa>  $result
     * @return LengthAwarePaginator<int, Empresa>
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

    /** @return array<string, string> */
    private function porteOptions(bool $withPlaceholder = false): array
    {
        $opts = [
            '1' => 'Pequena',
            '2' => 'Média',
            '3' => 'Grande',
        ];

        return $withPlaceholder ? ['' => 'Porte...'] + $opts : $opts;
    }

    /** @return array<string, string> */
    private function faturamentoOptions(bool $withPlaceholder = false): array
    {
        $opts = [
            '1' => 'até 120.000,00',
            '2' => '120.001,00 a 720.000,00',
            '3' => '720.001,00 a 5.000.000,00',
            '4' => 'mais de 5.000.000,00',
            '5' => 'Não informado',
        ];

        return $withPlaceholder ? ['' => 'Faturamento...'] + $opts : $opts;
    }

    /** @return array<string, string> */
    private function tipoOptions(bool $withPlaceholder = false): array
    {
        $opts = [
            '1' => 'RH',
            '2' => 'Nacional',
            '3' => 'Multinacional',
        ];

        return $withPlaceholder ? ['' => 'Tipo...'] + $opts : $opts;
    }

    private function currentPermissionLevel(): int
    {
        $permissions = session('permissoes', []);
        $entry = $permissions['empresa'] ?? null;
        if (is_array($entry)) {
            return (int) ($entry['permissao'] ?? 0);
        }

        return (int) ($entry ?? 0);
    }
}
