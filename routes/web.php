<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Interfaces\Http\Controllers\Admin\AbsenteismoController;
use App\Interfaces\Http\Controllers\Admin\BlobController;
use App\Interfaces\Http\Controllers\Admin\AfastadoController;
use App\Interfaces\Http\Controllers\Admin\AgendamentoController;
use App\Interfaces\Http\Controllers\Admin\AtendimentoController;
use App\Interfaces\Http\Controllers\Admin\BeneficiarioController;
use App\Interfaces\Http\Controllers\Admin\BeneficioController;
use App\Interfaces\Http\Controllers\Admin\BeneficioPrevidenciarioController;
use App\Interfaces\Http\Controllers\Admin\BiController;
use App\Interfaces\Http\Controllers\Admin\ClienteController;
use App\Interfaces\Http\Controllers\Admin\DbController;
use App\Interfaces\Http\Controllers\Admin\EmpresaController;
use App\Interfaces\Http\Controllers\Admin\GrupoEmpresarialController;
use App\Interfaces\Http\Controllers\Admin\ImportacaoController;
use App\Interfaces\Http\Controllers\Admin\ImportacaoNovaController;
use App\Interfaces\Http\Controllers\Admin\LogController;
use App\Interfaces\Http\Controllers\Admin\MhCriticoController;
use App\Interfaces\Http\Controllers\Admin\MhCriticoHistoricoController;
use App\Interfaces\Http\Controllers\Admin\MhNegociacaoController;
use App\Interfaces\Http\Controllers\Admin\MhPrestadorController;
use App\Interfaces\Http\Controllers\Admin\ModuloController;
use App\Interfaces\Http\Controllers\Admin\OperadoraController;
use App\Interfaces\Http\Controllers\Admin\ParametroController;
use App\Interfaces\Http\Controllers\Admin\PerfilController;
use App\Interfaces\Http\Controllers\Admin\PlanoController;
use App\Interfaces\Http\Controllers\Admin\ProcedimentoController;
use App\Interfaces\Http\Controllers\Admin\RelatorioController;
use App\Interfaces\Http\Controllers\Admin\SubfaturaController;
use App\Interfaces\Http\Controllers\Admin\TipoBeneficioController;
use App\Interfaces\Http\Controllers\Admin\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [LoginController::class, 'create']);

    Route::middleware(['auth', 'tenant'])->group(function () {
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

        Route::get('/home', [HomeController::class, 'index'])
            ->middleware('modulo:home')
            ->name('home');

        // —— Beneficiário ——
        Route::get('/beneficiario', [BeneficiarioController::class, 'index'])->middleware('modulo:beneficiario')->name('beneficiario.index');
        Route::get('/beneficiario/view/{id}', [BeneficiarioController::class, 'show'])->middleware('modulo:beneficiario')->whereNumber('id')->name('beneficiario.view');
        Route::match(['get', 'post'], '/beneficiario/add/{id?}', [BeneficiarioController::class, 'add'])->middleware('modulo:beneficiario')->whereNumber('id')->name('beneficiario.add');
        Route::get('/beneficiarios', fn () => redirect()->route('admin.beneficiario.index'))->name('beneficiarios.index');
        Route::get('/beneficiarios/{id}', fn (int $id) => redirect()->route('admin.beneficiario.view', ['id' => $id]))->whereNumber('id')->name('beneficiarios.show');

        // —— Cadastros / ACL (outras ondas) ——
        Route::get('/cliente', [ClienteController::class, 'index'])->middleware('modulo:cliente')->name('cliente.index');
        Route::get('/cliente/view/{id}', [ClienteController::class, 'show'])->middleware('modulo:cliente')->whereNumber('id')->name('cliente.view');
        Route::match(['get', 'post'], '/cliente/add/{id?}', [ClienteController::class, 'add'])->middleware('modulo:cliente')->whereNumber('id')->name('cliente.add');

        Route::get('/empresa', [EmpresaController::class, 'index'])->middleware('modulo:empresa')->name('empresa.index');
        Route::get('/empresa/view/{id}', [EmpresaController::class, 'show'])->middleware('modulo:empresa')->whereNumber('id')->name('empresa.view');
        Route::match(['get', 'post'], '/empresa/add/{id?}', [EmpresaController::class, 'add'])->middleware('modulo:empresa')->whereNumber('id')->name('empresa.add');

        Route::get('/grupo_empresarial', [GrupoEmpresarialController::class, 'index'])->middleware('modulo:grupo_empresarial')->name('grupo_empresarial.index');
        Route::get('/grupo_empresarial/view/{id}', [GrupoEmpresarialController::class, 'show'])->middleware('modulo:grupo_empresarial')->whereNumber('id')->name('grupo_empresarial.view');
        Route::match(['get', 'post'], '/grupo_empresarial/add/{id?}', [GrupoEmpresarialController::class, 'add'])->middleware('modulo:grupo_empresarial')->whereNumber('id')->name('grupo_empresarial.add');
        Route::match(['get', 'post'], '/grupo_empresarial/selecione', [GrupoEmpresarialController::class, 'selecione'])->name('grupo_empresarial.selecione');

        Route::get('/usuario', [UsuarioController::class, 'index'])->middleware('modulo:usuario')->name('usuario.index');
        Route::get('/usuario/view/{id}', [UsuarioController::class, 'show'])->middleware('modulo:usuario')->whereNumber('id')->name('usuario.view');
        Route::match(['get', 'post'], '/usuario/add/{id?}', [UsuarioController::class, 'add'])->middleware('modulo:usuario')->whereNumber('id')->name('usuario.add');
        Route::match(['get', 'post'], '/usuario/atualiza_session_cliente', [UsuarioController::class, 'atualiza_session_cliente'])
            ->middleware('modulo:usuario')
            ->name('usuario.atualiza_session_cliente');

        Route::get('/perfil', [PerfilController::class, 'index'])->middleware('modulo:perfil')->name('perfil.index');
        Route::get('/perfil/view/{id}', [PerfilController::class, 'show'])->middleware('modulo:perfil')->whereNumber('id')->name('perfil.view');
        Route::match(['get', 'post'], '/perfil/add/{id?}', [PerfilController::class, 'add'])->middleware('modulo:perfil')->whereNumber('id')->name('perfil.add');

        Route::get('/modulo', [ModuloController::class, 'index'])->middleware('modulo:modulo')->name('modulo.index');
        Route::get('/modulo/view/{id}', [ModuloController::class, 'show'])->middleware('modulo:modulo')->whereNumber('id')->name('modulo.view');
        Route::match(['get', 'post'], '/modulo/add/{id?}', [ModuloController::class, 'add'])->middleware('modulo:modulo')->whereNumber('id')->name('modulo.add');

        Route::get('/parametro', [ParametroController::class, 'index'])->middleware('modulo:parametro')->name('parametro.index');
        Route::get('/parametro/view/{id}', [ParametroController::class, 'show'])->middleware('modulo:parametro')->whereNumber('id')->name('parametro.view');
        Route::match(['get', 'post'], '/parametro/add/{id?}', [ParametroController::class, 'add'])->middleware('modulo:parametro')->whereNumber('id')->name('parametro.add');

        Route::get('/plano', [PlanoController::class, 'index'])->middleware('modulo:plano')->name('plano.index');
        Route::get('/plano/view/{id}', [PlanoController::class, 'show'])->middleware('modulo:plano')->whereNumber('id')->name('plano.view');
        Route::match(['get', 'post'], '/plano/add/{id?}', [PlanoController::class, 'add'])->middleware('modulo:plano')->whereNumber('id')->name('plano.add');

        Route::get('/operadora', [OperadoraController::class, 'index'])->middleware('modulo:operadora')->name('operadora.index');
        Route::get('/operadora/view/{id}', [OperadoraController::class, 'show'])->middleware('modulo:operadora')->whereNumber('id')->name('operadora.view');
        Route::match(['get', 'post'], '/operadora/add/{id?}', [OperadoraController::class, 'add'])->middleware('modulo:operadora')->whereNumber('id')->name('operadora.add');

        // —— Onda C: operacionais / cadastros auxiliares ——
        Route::get('/tipo_beneficio', [TipoBeneficioController::class, 'index'])->middleware('modulo:tipo_beneficio')->name('tipo_beneficio.index');
        Route::get('/tipo_beneficio/view/{id}', [TipoBeneficioController::class, 'show'])->middleware('modulo:tipo_beneficio')->whereNumber('id')->name('tipo_beneficio.view');
        Route::match(['get', 'post'], '/tipo_beneficio/add/{id?}', [TipoBeneficioController::class, 'add'])->middleware('modulo:tipo_beneficio')->whereNumber('id')->name('tipo_beneficio.add');

        Route::get('/procedimento', [ProcedimentoController::class, 'index'])->middleware('modulo:procedimento')->name('procedimento.index');
        Route::get('/procedimento/view/{id}', [ProcedimentoController::class, 'show'])->middleware('modulo:procedimento')->whereNumber('id')->name('procedimento.view');
        Route::match(['get', 'post'], '/procedimento/add/{id?}', [ProcedimentoController::class, 'add'])->middleware('modulo:procedimento')->whereNumber('id')->name('procedimento.add');

        Route::get('/beneficio', [BeneficioController::class, 'index'])->middleware('modulo:beneficio')->name('beneficio.index');
        Route::get('/beneficio/view/{id}', [BeneficioController::class, 'show'])->middleware('modulo:beneficio')->whereNumber('id')->name('beneficio.view');
        Route::match(['get', 'post'], '/beneficio/add/{id?}', [BeneficioController::class, 'add'])->middleware('modulo:beneficio')->whereNumber('id')->name('beneficio.add');

        Route::get('/subfatura', [SubfaturaController::class, 'index'])->middleware('modulo:subfatura')->name('subfatura.index');
        Route::get('/subfatura/view/{id}', [SubfaturaController::class, 'show'])->middleware('modulo:subfatura')->whereNumber('id')->name('subfatura.view');
        Route::match(['get', 'post'], '/subfatura/add/{id?}', [SubfaturaController::class, 'add'])->middleware('modulo:subfatura')->whereNumber('id')->name('subfatura.add');

        Route::get('/afastado', [AfastadoController::class, 'index'])->middleware('modulo:afastado')->name('afastado.index');
        Route::get('/afastado/view/{id}', [AfastadoController::class, 'show'])->middleware('modulo:afastado')->whereNumber('id')->name('afastado.view');
        Route::match(['get', 'post'], '/afastado/add/{id?}', [AfastadoController::class, 'add'])->middleware('modulo:afastado')->whereNumber('id')->name('afastado.add');

        Route::get('/absenteismo', [AbsenteismoController::class, 'index'])->middleware('modulo:absenteismo')->name('absenteismo.index');
        Route::get('/absenteismo/view/{id}', [AbsenteismoController::class, 'show'])->middleware('modulo:absenteismo')->whereNumber('id')->name('absenteismo.view');
        Route::match(['get', 'post'], '/absenteismo/add/{id?}', [AbsenteismoController::class, 'add'])->middleware('modulo:absenteismo')->whereNumber('id')->name('absenteismo.add');

        Route::get('/atendimento', [AtendimentoController::class, 'index'])->middleware('modulo:atendimento')->name('atendimento.index');
        Route::get('/atendimento/view/{id}', [AtendimentoController::class, 'show'])->middleware('modulo:atendimento')->whereNumber('id')->name('atendimento.view');
        Route::match(['get', 'post'], '/atendimento/add/{id?}', [AtendimentoController::class, 'add'])->middleware('modulo:atendimento')->whereNumber('id')->name('atendimento.add');

        Route::get('/beneficio_previdenciario', [BeneficioPrevidenciarioController::class, 'index'])->middleware('modulo:beneficio_previdenciario')->name('beneficio_previdenciario.index');
        Route::get('/beneficio_previdenciario/view/{id}', [BeneficioPrevidenciarioController::class, 'show'])->middleware('modulo:beneficio_previdenciario')->whereNumber('id')->name('beneficio_previdenciario.view');
        Route::match(['get', 'post'], '/beneficio_previdenciario/add/{id?}', [BeneficioPrevidenciarioController::class, 'add'])->middleware('modulo:beneficio_previdenciario')->whereNumber('id')->name('beneficio_previdenciario.add');

        Route::get('/agendamento', [AgendamentoController::class, 'index'])->middleware('modulo:agendamento')->name('agendamento.index');
        Route::get('/agendamento/view/{id}', [AgendamentoController::class, 'show'])->middleware('modulo:agendamento')->whereNumber('id')->name('agendamento.view');
        Route::match(['get', 'post'], '/agendamento/add/{id?}', [AgendamentoController::class, 'add'])->middleware('modulo:agendamento')->whereNumber('id')->name('agendamento.add');

        Route::get('/log', [LogController::class, 'index'])->middleware('modulo:log')->name('log.index');

        Route::get('/db', [DbController::class, 'index'])->middleware('modulo:db')->name('db.index');
        Route::get('/blob/download/{id}', [BlobController::class, 'download'])->name('blob.download');

        // —— MH ——
        Route::get('/mh_critico', [MhCriticoController::class, 'index'])->middleware('modulo:mh_critico')->name('mh_critico.index');
        Route::get('/mh_critico/view/{id}', [MhCriticoController::class, 'show'])->middleware('modulo:mh_critico')->whereNumber('id')->name('mh_critico.view');
        Route::match(['get', 'post'], '/mh_critico/add/{id?}', [MhCriticoController::class, 'add'])->middleware('modulo:mh_critico')->whereNumber('id')->name('mh_critico.add');

        Route::get('/mh_critico_historico/{mh_critico_id}', [MhCriticoHistoricoController::class, 'index'])->middleware('modulo:mh_critico_historico')->whereNumber('mh_critico_id')->name('mh_critico_historico.index');
        Route::get('/mh_critico_historico/{mh_critico_id}/view/{id}', [MhCriticoHistoricoController::class, 'show'])->middleware('modulo:mh_critico_historico')->whereNumber(['mh_critico_id', 'id'])->name('mh_critico_historico.view');
        Route::match(['get', 'post'], '/mh_critico_historico/{mh_critico_id}/add/{id?}', [MhCriticoHistoricoController::class, 'add'])->middleware('modulo:mh_critico_historico')->whereNumber(['mh_critico_id', 'id'])->name('mh_critico_historico.add');

        Route::get('/mh_negociacao', [MhNegociacaoController::class, 'index'])->middleware('modulo:mh_negociacao')->name('mh_negociacao.index');
        Route::get('/mh_negociacao/view/{id}', [MhNegociacaoController::class, 'show'])->middleware('modulo:mh_negociacao')->whereNumber('id')->name('mh_negociacao.view');
        Route::match(['get', 'post'], '/mh_negociacao/add/{id?}', [MhNegociacaoController::class, 'add'])->middleware('modulo:mh_negociacao')->whereNumber('id')->name('mh_negociacao.add');

        Route::get('/mh_prestador', [MhPrestadorController::class, 'index'])->middleware('modulo:mh_prestador')->name('mh_prestador.index');
        Route::get('/mh_prestador/view/{id}', [MhPrestadorController::class, 'show'])->middleware('modulo:mh_prestador')->whereNumber('id')->name('mh_prestador.view');
        Route::match(['get', 'post'], '/mh_prestador/add/{id?}', [MhPrestadorController::class, 'add'])->middleware('modulo:mh_prestador')->whereNumber('id')->name('mh_prestador.add');

        // —— Onda E: importacao / importacao_nova / bi / relatorio ——
        Route::get('/importacao', [ImportacaoController::class, 'index'])->middleware('modulo:importacao')->name('importacao.index');
        Route::match(['get', 'post'], '/importacao/add', [ImportacaoController::class, 'add'])->middleware('modulo:importacao')->name('importacao.add');
        Route::get('/importacao/import', [ImportacaoController::class, 'import'])->middleware('modulo:importacao')->name('importacao.import');
        Route::get('/importacao/validacao', [ImportacaoController::class, 'validacao'])->middleware('modulo:importacao')->name('importacao.validacao');

        Route::get('/importacao_nova', [ImportacaoNovaController::class, 'index'])->middleware('modulo:importacao_nova')->name('importacao_nova.index');
        Route::get('/importacao_nova/view/{id}', [ImportacaoNovaController::class, 'show'])->middleware('modulo:importacao_nova')->whereNumber('id')->name('importacao_nova.view');
        Route::match(['get', 'post'], '/importacao_nova/add', [ImportacaoNovaController::class, 'add'])->middleware('modulo:importacao_nova')->name('importacao_nova.add');
        Route::get('/importacao_nova/import', [ImportacaoNovaController::class, 'import'])->middleware('modulo:importacao_nova')->name('importacao_nova.import');
        Route::get('/importacao_nova/validacao', [ImportacaoNovaController::class, 'validacao'])->middleware('modulo:importacao_nova')->name('importacao_nova.validacao');
        Route::get('/importacao_nova/status/{id}', [ImportacaoNovaController::class, 'status'])->middleware('modulo:importacao_nova')->whereNumber('id')->name('importacao_nova.status');
        Route::get('/importacao_nova/processar_arquivo/{id}', [ImportacaoNovaController::class, 'processarArquivo'])->middleware('modulo:importacao_nova')->whereNumber('id')->name('importacao_nova.processar_arquivo');

        Route::get('/bi', [BiController::class, 'index'])->middleware('modulo:bi')->name('bi.index');
        Route::get('/bi/lista', [BiController::class, 'lista'])->middleware('modulo:bi')->name('bi.lista');
        Route::get('/bi/gerencial', [BiController::class, 'gerencial'])->middleware('modulo:bi')->name('bi.gerencial');
        Route::get('/bi/medico', [BiController::class, 'medico'])->middleware('modulo:bi')->name('bi.medico');
        Route::get('/bi/rh', [BiController::class, 'rh'])->middleware('modulo:bi')->name('bi.rh');
        Route::get('/bi/view/{id}', [BiController::class, 'show'])->middleware('modulo:bi')->whereNumber('id')->name('bi.view');
        Route::match(['get', 'post'], '/bi/add/{id?}', [BiController::class, 'add'])->middleware('modulo:bi')->whereNumber('id')->name('bi.add');

        Route::get('/relatorio', [RelatorioController::class, 'index'])->middleware('modulo:relatorio')->name('relatorio.index');
        Route::get('/relatorio/afastados', [RelatorioController::class, 'afastados'])->middleware('modulo:relatorio')->name('relatorio.afastados');
        Route::get('/relatorio/beneficiarios', [RelatorioController::class, 'beneficiarios'])->middleware('modulo:relatorio')->name('relatorio.beneficiarios');
        Route::get('/relatorio/atendimentos_pendentes', [RelatorioController::class, 'atendimentosPendentes'])->middleware('modulo:relatorio')->name('relatorio.atendimentos_pendentes');
        Route::match(['get', 'post'], '/relatorio/gerencial', [RelatorioController::class, 'gerencial'])->middleware('modulo:relatorio')->name('relatorio.gerencial');
        Route::match(['get', 'post'], '/relatorio/exportacao', [RelatorioController::class, 'exportacao'])->middleware('modulo:relatorio')->name('relatorio.exportacao');
        Route::get('/relatorio/fatura', [RelatorioController::class, 'fatura'])->middleware('modulo:relatorio')->name('relatorio.fatura');
        Route::get('/relatorio/sinistro', [RelatorioController::class, 'sinistro'])->middleware('modulo:relatorio')->name('relatorio.sinistro');
        Route::get('/relatorio/movimentacao_beneficiario', [RelatorioController::class, 'movimentacaoBeneficiario'])->middleware('modulo:relatorio')->name('relatorio.movimentacao_beneficiario');
        Route::get('/relatorio/movimentacao_sinistro', [RelatorioController::class, 'movimentacaoSinistro'])->middleware('modulo:relatorio')->name('relatorio.movimentacao_sinistro');
        Route::get('/relatorio/movimentacao_fatura', [RelatorioController::class, 'movimentacaoFatura'])->middleware('modulo:relatorio')->name('relatorio.movimentacao_fatura');
        Route::get('/relatorio/{tipo}_down', [RelatorioController::class, 'deferredDown'])
            ->middleware('modulo:relatorio')
            ->where('tipo', 'fatura|sinistro|movimentacao_beneficiario|movimentacao_sinistro|movimentacao_fatura')
            ->name('relatorio.down');
    });
});
