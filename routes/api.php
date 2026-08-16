<?php

use App\Interfaces\Http\Controllers\Api\RestController;
use App\Interfaces\Http\Controllers\Api\WsController;
use Illuminate\Support\Facades\Route;

/*
| REST Proativa — token via query ?token= (config samed.rest.token / SAMED_REST_TOKEN)
| Paths próximos ao legado /rest/* (prefixo /api/rest).
*/
Route::prefix('rest')->name('api.rest.')->group(function () {
    Route::get('/', [RestController::class, 'index'])->name('index');
    Route::get('/bi_proativa_beneficiario', [RestController::class, 'biProativaBeneficiario'])->name('beneficiario');
    Route::get('/bi_proativa_beneficiarios', [RestController::class, 'biProativaBeneficiario']);
    Route::get('/bi_proativa_faturamento', [RestController::class, 'biProativaFaturamento'])->name('faturamento');
    Route::get('/bi_proativa_faturamentos', [RestController::class, 'biProativaFaturamento']);
    Route::get('/bi_proativa_sinistro', [RestController::class, 'biProativaSinistro'])->name('sinistro');
    Route::get('/bi_proativa_sinistros', [RestController::class, 'biProativaSinistro']);
    Route::get('/bi_proativa_beneficio', [RestController::class, 'biProativaBeneficio'])->name('beneficio');
    Route::get('/bi_proativa_cliente', [RestController::class, 'biProativaCliente'])->name('cliente');
    Route::get('/bi_proativa_grupo_estatistico', [RestController::class, 'biProativaGrupoEstatistico'])->name('grupo_estatistico');
    Route::get('/bi_proativa_cronicos', [RestController::class, 'biProativaCronicos'])->name('cronicos');
    Route::get('/bi_proativa_subfaturas', [RestController::class, 'biProativaSubfaturas'])->name('subfaturas');
    Route::get('/bi_proativa_procedimento', [RestController::class, 'biProativaProcedimento'])->name('procedimento');
});

/*
| WS — superfície mínima (call_bi_*). Token: SAMED_WS_TOKEN ou SAMED_REST_TOKEN.
*/
Route::prefix('ws')->name('api.ws.')->group(function () {
    Route::get('/', [WsController::class, 'index'])->name('index');
    Route::get('/call_bi_beneficiarios', [WsController::class, 'callBiBeneficiarios'])->name('call_bi_beneficiarios');
    Route::get('/call_bi_beneficiarios2', [WsController::class, 'callBiBeneficiarios2'])->name('call_bi_beneficiarios2');
});
