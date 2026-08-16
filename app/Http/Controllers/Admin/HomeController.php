<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Afastado;
use App\Models\Agendamento;
use App\Models\Beneficiario;
use App\Models\Cliente;
use App\Models\GrupoEmpresarial;
use App\Models\Importacao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $clienteId = session('cliente_id');
        $grupoId = session('grupo_empresarial_id');
        $user = auth()->user();

        $logoGE = '';
        if ($grupoId) {
            $ge = GrupoEmpresarial::query()->find($grupoId);
            if ($ge && filled($ge->img_logo)) {
                $relative = 'img/uploads/grupo_empresarial/'.$ge->img_logo;
                if (is_file(public_path($relative))) {
                    $logoGE = asset($relative);
                }
            }
        }

        $clientes = collect();
        if ($grupoId) {
            $clientes = Cliente::query()
                ->where('grupo_empresarial_id', $grupoId)
                ->orderBy('nome')
                ->pluck('nome', 'id');
        }

        $notificacoes = [
            'afastado' => [],
            'beneficio_previdenciario' => [],
            'absenteismo' => [],
        ];

        if ($clientes->isNotEmpty() && Schema::hasTable('importacao')) {
            $clienteIds = $clientes->keys()->all();
            $notificacoes['afastado'] = $this->importacaoSparkline('afastado', $clienteIds);
            if (Schema::hasTable('beneficio_previdenciario')) {
                $notificacoes['beneficio_previdenciario'] = $this->importacaoSparkline('beneficio_previdenciario', $clienteIds);
            }
            if (Schema::hasTable('absenteismo')) {
                $notificacoes['absenteismo'] = $this->importacaoSparkline('absenteismo', $clienteIds);
            }
        }

        $kpi = [
            'total_beneficiarios' => [
                'titulo' => 'Total de Beneficiários',
                'valor' => 0,
                'url' => route('admin.beneficiario.index'),
            ],
            'beneficiarios_ativos' => [
                'titulo' => 'Beneficiarios Ativos',
                'valor' => 0,
                'url' => route('admin.beneficiario.index', ['situacao' => 'Ativo']),
            ],
            'afastados' => [
                'titulo' => 'Beneficiários Afastados',
                'valor' => 0,
                'url' => url('/admin/afastado'),
            ],
            'importacoes' => [
                'titulo' => 'Importações',
                'valor' => 0,
                'url' => url('/admin/importacao'),
            ],
        ];

        if ($clienteId) {
            $kpi['total_beneficiarios']['valor'] = Beneficiario::query()
                ->where('cliente_id', $clienteId)
                ->where('status', 1)
                ->count();

            $kpi['beneficiarios_ativos']['valor'] = Beneficiario::query()
                ->where('cliente_id', $clienteId)
                ->where('situacao', 'Ativo')
                ->where('status', 1)
                ->count();

            $kpi['importacoes']['valor'] = Importacao::query()
                ->where('cliente_id', $clienteId)
                ->count();

            if (Schema::hasTable('afastado')) {
                $kpi['afastados']['valor'] = Afastado::query()
                    ->where('situacao', 'A')
                    ->whereHas('beneficiario', fn ($q) => $q->where('cliente_id', $clienteId))
                    ->select('beneficiario_id')
                    ->distinct()
                    ->count();
            }
        }

        $perfilId = (int) ($user?->perfil_id ?? 0);
        if (in_array($perfilId, [1, 2, 3], true) && Schema::hasTable('agendamento')) {
            $kpi['agendametos_pendentes_atribuidos'] = [
                'titulo' => 'Agendamentos Pendentes Atribuidos',
                'valor' => Agendamento::query()
                    ->where('usuario_id', $user->id)
                    ->where('status', 0)
                    ->count(),
                'url' => url('/admin/agendamento'),
            ];
        }

        $allowedGerdau = [1, 3, 261, 268, 290, 331];
        if ((int) ($user?->grupo_empresarial_id ?? 0) === 10
            && in_array((int) $user->id, $allowedGerdau, true)
            && Schema::hasTable('agendamento')
        ) {
            $kpi['agendamentos_pendentes_gerdau'] = [
                'titulo' => 'Gerdau - Agendamentos Pendentes',
                'valor' => $this->countGerdauPendentes((int) $user->grupo_empresarial_id),
                'url' => url('/admin/agendamento'),
            ];
        }

        $exibeNotificacao = false;
        if (now()->format('Y-m-d H:i:s') <= '2026-07-12 04:00:00') {
            $chave = 'Notificacao.20260718.'.($user?->id ?? 0);
            if (! session()->has($chave)) {
                session([$chave => true]);
                $exibeNotificacao = true;
            }
        }

        return view('admin.home.index', [
            'title' => 'Dashboard',
            'logoGE' => $logoGE,
            'selectCliente' => $clientes->all(),
            'notificacoes' => $notificacoes,
            'row' => ['charts' => ['kpi' => $kpi]],
            'exibeNotificacao' => $exibeNotificacao,
        ]);
    }

    /**
     * @param  list<int|string>  $clienteIds
     * @return array<int, list<int>>
     */
    private function importacaoSparkline(string $tipoTabela, array $clienteIds): array
    {
        if (! Schema::hasTable($tipoTabela) || ! Schema::hasTable('importacao')) {
            return [];
        }

        $rows = DB::table($tipoTabela.' as t')
            ->join('importacao as i', 'i.id', '=', 't.importacao_id')
            ->whereIn('i.cliente_id', $clienteIds)
            ->selectRaw('i.cliente_id, DATE_FORMAT(i.data_cadastro, "%m/%Y") as data, COUNT(i.cliente_id) as contador')
            ->groupBy('i.cliente_id', DB::raw('DATE_FORMAT(i.data_cadastro, "%m/%Y")'))
            ->get();

        $lista = [];
        foreach ($rows as $row) {
            $lista[(int) $row->cliente_id][] = (int) $row->contador;
        }

        return $lista;
    }

    private function countGerdauPendentes(int $grupoId): int
    {
        $clienteIds = Cliente::query()
            ->where('grupo_empresarial_id', $grupoId)
            ->pluck('id');

        if ($clienteIds->isEmpty()) {
            return 0;
        }

        return (int) DB::table('agendamento as a')
            ->leftJoin('atendimento as at', 'a.atendimento_id', '=', 'at.id')
            ->leftJoin('beneficiario as b', 'at.beneficiario_id', '=', 'b.id')
            ->whereIn('b.cliente_id', $clienteIds)
            ->where('a.status', 0)
            ->count();
    }
}
