<?php

namespace App\Providers;

use App\Domain\Absenteismo\AbsenteismoRepositoryInterface;
use App\Domain\Afastado\AfastadoRepositoryInterface;
use App\Domain\Agendamento\AgendamentoRepositoryInterface;
use App\Domain\Atendimento\AtendimentoRepositoryInterface;
use App\Domain\Beneficiario\BeneficiarioRepositoryInterface;
use App\Domain\Beneficio\BeneficioRepositoryInterface;
use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciarioRepositoryInterface;
use App\Domain\Bi\BiRepositoryInterface;
use App\Domain\Blob\BlobRepositoryInterface;
use App\Domain\Cliente\ClienteRepositoryInterface;
use App\Domain\Db\DbSettingsInterface;
use App\Domain\Empresa\EmpresaRepositoryInterface;
use App\Domain\GrupoEmpresarial\GrupoEmpresarialRepositoryInterface;
use App\Domain\Importacao\ImportacaoRepositoryInterface;
use App\Domain\ImportacaoNova\ImportacaoNovaRepositoryInterface;
use App\Domain\Integration\IntegrationTokenSettingsInterface;
use App\Domain\LogEntry\LogEntryRepositoryInterface;
use App\Domain\MhCritico\MhCriticoRepositoryInterface;
use App\Domain\MhCriticoHistorico\MhCriticoHistoricoRepositoryInterface;
use App\Domain\MhNegociacao\MhNegociacaoRepositoryInterface;
use App\Domain\MhPrestador\MhPrestadorRepositoryInterface;
use App\Domain\Modulo\ModuloRepositoryInterface;
use App\Domain\Operadora\OperadoraRepositoryInterface;
use App\Domain\Parametro\ParametroRepositoryInterface;
use App\Domain\Perfil\PerfilRepositoryInterface;
use App\Domain\Plano\PlanoRepositoryInterface;
use App\Domain\Procedimento\ProcedimentoRepositoryInterface;
use App\Domain\Relatorio\RelatorioRepositoryInterface;
use App\Domain\Rest\RestAuditLoggerInterface;
use App\Domain\Rest\RestProativaRepositoryInterface;
use App\Domain\Subfatura\SubfaturaRepositoryInterface;
use App\Domain\TipoBeneficio\TipoBeneficioRepositoryInterface;
use App\Domain\Usuario\UsuarioRepositoryInterface;
use App\Domain\Ws\WsBiRepositoryInterface;
use App\Infrastructure\Config\ConfigDbSettings;
use App\Infrastructure\Config\ConfigIntegrationTokenSettings;
use App\Infrastructure\Persistence\Eloquent\EloquentAbsenteismoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentAfastadoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentAgendamentoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentAtendimentoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentBeneficiarioRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentBeneficioPrevidenciarioRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentBeneficioRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentBiRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentBlobRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentClienteRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEmpresaRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentGrupoEmpresarialRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentImportacaoNovaRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentImportacaoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentLogEntryRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentMhCriticoHistoricoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentMhCriticoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentMhNegociacaoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentMhPrestadorRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentModuloRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentOperadoraRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentParametroRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentPerfilRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentPlanoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentProcedimentoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentRelatorioRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentRestAuditLogger;
use App\Infrastructure\Persistence\Eloquent\EloquentRestProativaRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentSubfaturaRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentTipoBeneficioRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentUsuarioRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentWsBiRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BeneficiarioRepositoryInterface::class, EloquentBeneficiarioRepository::class);
        $this->app->bind(ClienteRepositoryInterface::class, EloquentClienteRepository::class);
        $this->app->bind(EmpresaRepositoryInterface::class, EloquentEmpresaRepository::class);
        $this->app->bind(GrupoEmpresarialRepositoryInterface::class, EloquentGrupoEmpresarialRepository::class);
        $this->app->bind(UsuarioRepositoryInterface::class, EloquentUsuarioRepository::class);
        $this->app->bind(PerfilRepositoryInterface::class, EloquentPerfilRepository::class);
        $this->app->bind(ModuloRepositoryInterface::class, EloquentModuloRepository::class);
        $this->app->bind(OperadoraRepositoryInterface::class, EloquentOperadoraRepository::class);
        $this->app->bind(PlanoRepositoryInterface::class, EloquentPlanoRepository::class);
        $this->app->bind(ParametroRepositoryInterface::class, EloquentParametroRepository::class);
        $this->app->bind(LogEntryRepositoryInterface::class, EloquentLogEntryRepository::class);
        // Onda C
        $this->app->bind(TipoBeneficioRepositoryInterface::class, EloquentTipoBeneficioRepository::class);
        $this->app->bind(ProcedimentoRepositoryInterface::class, EloquentProcedimentoRepository::class);
        $this->app->bind(BeneficioRepositoryInterface::class, EloquentBeneficioRepository::class);
        $this->app->bind(SubfaturaRepositoryInterface::class, EloquentSubfaturaRepository::class);
        $this->app->bind(AfastadoRepositoryInterface::class, EloquentAfastadoRepository::class);
        $this->app->bind(AbsenteismoRepositoryInterface::class, EloquentAbsenteismoRepository::class);
        $this->app->bind(AtendimentoRepositoryInterface::class, EloquentAtendimentoRepository::class);
        $this->app->bind(BeneficioPrevidenciarioRepositoryInterface::class, EloquentBeneficioPrevidenciarioRepository::class);
        $this->app->bind(AgendamentoRepositoryInterface::class, EloquentAgendamentoRepository::class);
        // Onda D — MH
        $this->app->bind(MhPrestadorRepositoryInterface::class, EloquentMhPrestadorRepository::class);
        $this->app->bind(MhNegociacaoRepositoryInterface::class, EloquentMhNegociacaoRepository::class);
        $this->app->bind(MhCriticoRepositoryInterface::class, EloquentMhCriticoRepository::class);
        $this->app->bind(MhCriticoHistoricoRepositoryInterface::class, EloquentMhCriticoHistoricoRepository::class);
        // Onda E — Importação / BI / Relatório
        $this->app->bind(ImportacaoRepositoryInterface::class, EloquentImportacaoRepository::class);
        $this->app->bind(ImportacaoNovaRepositoryInterface::class, EloquentImportacaoNovaRepository::class);
        $this->app->bind(BiRepositoryInterface::class, EloquentBiRepository::class);
        $this->app->bind(RelatorioRepositoryInterface::class, EloquentRelatorioRepository::class);
        // Onda F — REST / WS / Blob / DB
        $this->app->bind(IntegrationTokenSettingsInterface::class, ConfigIntegrationTokenSettings::class);
        $this->app->bind(RestProativaRepositoryInterface::class, EloquentRestProativaRepository::class);
        $this->app->bind(RestAuditLoggerInterface::class, EloquentRestAuditLogger::class);
        $this->app->bind(WsBiRepositoryInterface::class, EloquentWsBiRepository::class);
        $this->app->bind(BlobRepositoryInterface::class, EloquentBlobRepository::class);
        $this->app->bind(DbSettingsInterface::class, ConfigDbSettings::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapThree();
        Paginator::defaultView('pagination.samed');
    }
}
