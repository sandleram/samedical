<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Beneficiario\ListBeneficiarios;
use App\Application\Beneficiario\PrepareBeneficiarioForm;
use App\Application\Beneficiario\PrepareBeneficiarioShow;
use App\Application\Beneficiario\SaveBeneficiario;
use App\Application\Beneficiario\SaveBeneficiarioInput;
use App\Domain\Beneficiario\Beneficiario;
use App\Domain\Beneficiario\BeneficiarioSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Admin\SaveBeneficiarioRequest;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

/**
 * Controller fino: valida input, monta TenantScope, chama UseCases, devolve Blade/redirect.
 * Regras de negócio ficam em Application/Domain.
 */
class BeneficiarioController extends Controller
{
    public function __construct(
        private readonly ListBeneficiarios $listBeneficiarios,
        private readonly PrepareBeneficiarioShow $prepareShow,
        private readonly PrepareBeneficiarioForm $prepareForm,
        private readonly SaveBeneficiario $saveBeneficiario,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'nome' => trim((string) $request->query('nome', '')),
            'nome_social' => trim((string) $request->query('nome_social', '')),
            'cpf' => trim((string) $request->query('cpf', '')),
            'situacao' => trim((string) $request->query('situacao', '')),
            'status' => $request->query('status', ''),
        ];

        $user = $request->user();
        $isRoot = $user?->isRoot() ?? false;

        $criteria = new BeneficiarioSearchCriteria(
            id: $search['id_'],
            nome: $search['nome'],
            nomeSocial: $search['nome_social'],
            cpf: $search['cpf'],
            situacao: $search['situacao'],
            status: is_scalar($search['status']) ? (string) $search['status'] : '',
            onlyActiveForNonRoot: ! $isRoot,
            perPage: 15,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listBeneficiarios->execute($criteria, $this->tenantScope());
        $beneficiarios = $this->toPaginator($result, $request);

        return view('admin.beneficiario.index', [
            'title' => 'Beneficiários',
            'beneficiarios' => $beneficiarios,
            'search' => $search,
            'permissao' => $this->currentPermissionLevel(),
            'perfil_id' => (int) ($user?->perfil_id ?? 0),
            'perfil_adm' => [1, 2, 3],
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $payload = $this->prepareShow->execute($id, $this->tenantScope());

        if (! $payload) {
            return redirect()
                ->route('admin.beneficiario.index')
                ->with('status', 'Beneficiario Inexistente');
        }

        $beneficiario = $payload['beneficiario'];

        if ($beneficiario->clienteId && (int) session('cliente_id') !== $beneficiario->clienteId) {
            session(['cliente_id' => $beneficiario->clienteId]);
        }

        [$alturaFmt, $pesoFmt] = $this->formatAlturaPeso($beneficiario);
        $nomeParts = preg_split('/\s+/', $beneficiario->nome, 2) ?: [];

        return view('admin.beneficiario.show', [
            'title' => 'Beneficiário',
            'beneficiario' => $beneficiario,
            'related' => $payload['related'],
            'alturaFmt' => $alturaFmt,
            'pesoFmt' => $pesoFmt,
            'firstName' => $nomeParts[0] ?? '',
            'lastName' => $nomeParts[1] ?? '',
            'permissao' => $this->currentPermissionLevel(),
            'permissoes' => session('permissoes', []),
        ]);
    }

    public function add(Request $request, ?int $id = null): View|RedirectResponse
    {
        $clienteId = session('cliente_id');
        if (! $clienteId) {
            return redirect()
                ->route('admin.home')
                ->with('status', 'Selecione um cliente para cadastrar beneficiários.');
        }

        if ($request->isMethod('post')) {
            return $this->store($request, (int) $clienteId, $id);
        }

        $form = $this->prepareForm->execute((int) $clienteId, $this->tenantScope(), $id);
        $beneficiario = $form['beneficiario'];

        if ($id !== null && ! $beneficiario) {
            return redirect()
                ->route('admin.beneficiario.index')
                ->with('status', 'Beneficiario Inexistente');
        }

        [$alturaDisplay, $pesoDisplay] = $this->displayAlturaPeso($beneficiario);

        return view('admin.beneficiario.add', [
            'title' => $beneficiario ? 'Edição de Beneficiário' : 'Cadastro de Beneficiário',
            'beneficiario' => $beneficiario,
            'empresas' => $form['empresas'],
            'alturaDisplay' => $alturaDisplay,
            'pesoDisplay' => $pesoDisplay,
            'permissao' => $this->currentPermissionLevel(),
            'sexoArr' => ['' => 'Sexo...', 'M' => 'Masculino', 'F' => 'Feminino'],
            'telTipoArr' => [
                '' => 'Tipo...',
                'Residencial' => 'Residencial',
                'Comercial' => 'Comercial',
                'Fax' => 'Fax',
                'Celular' => 'Celular',
            ],
            'estadoCivilArr' => [
                '' => 'Estado Civil...',
                'Solteiro(a)' => 'Solteiro(a)',
                'Casado(a)' => 'Casado(a)',
                'Divorciado(a)' => 'Divorciado(a)',
                'Viúvo(a)' => 'Viúvo(a)',
                'União Estável' => 'União Estável',
            ],
        ]);
    }

    private function store(Request $request, int $clienteId, ?int $id): RedirectResponse
    {
        /** @var SaveBeneficiarioRequest $formRequest */
        $formRequest = app(SaveBeneficiarioRequest::class);
        $validated = $formRequest->validated();

        try {
            $saved = $this->saveBeneficiario->execute(
                new SaveBeneficiarioInput(
                    attributes: $validated,
                    clienteId: $clienteId,
                    existingId: $id,
                    userId: $request->user()?->id,
                    now: new DateTimeImmutable,
                ),
                $this->tenantScope(),
            );
        } catch (RuntimeException|Throwable) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['form' => 'Não foi possível SALVAR o(a) Beneficiario(a), verifique as informações ou tente mais tarde!']);
        }

        return redirect()
            ->route('admin.beneficiario.add', ['id' => $saved->id])
            ->with('status', 'A Beneficiario foi SALVO com sucesso!');
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
     * @param  PagedResult<Beneficiario>  $result
     * @return LengthAwarePaginator<int, Beneficiario>
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

    /**
     * @return array{0: string, 1: string}
     */
    private function formatAlturaPeso(Beneficiario $beneficiario): array
    {
        $altura = '-';
        $peso = '-';

        if ($beneficiario->altura && $beneficiario->altura > 0) {
            $alturaVal = $beneficiario->altura > 100
                ? $beneficiario->altura / 100
                : (float) $beneficiario->altura;
            $altura = str_pad((string) $alturaVal, 4, '0', STR_PAD_RIGHT);
        }

        if ($beneficiario->peso && $beneficiario->peso > 0) {
            $peso = str_replace('.', ',', (string) $beneficiario->peso);
        }

        return [$altura, $peso];
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function displayAlturaPeso(?Beneficiario $beneficiario): array
    {
        if (! $beneficiario) {
            return ['', ''];
        }

        $alturaDisplay = '';
        $pesoDisplay = '';

        if ($beneficiario->altura && $beneficiario->altura > 100) {
            $alturaDisplay = str_pad((string) ($beneficiario->altura / 100), 4, '0', STR_PAD_RIGHT);
        } elseif ($beneficiario->altura) {
            $alturaDisplay = (string) $beneficiario->altura;
        }

        if ($beneficiario->peso) {
            $pesoDisplay = str_replace('.', ',', (string) $beneficiario->peso);
        }

        return [$alturaDisplay, $pesoDisplay];
    }

    private function currentPermissionLevel(): int
    {
        $permissions = session('permissoes', []);
        $entry = $permissions['beneficiario'] ?? null;
        if (is_array($entry)) {
            return (int) ($entry['permissao'] ?? 0);
        }

        return (int) ($entry ?? 0);
    }
}
