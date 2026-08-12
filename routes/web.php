<?php

use App\Http\Controllers\Admin\BeneficiarioController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [LoginController::class, 'create']);

    Route::middleware(['auth', 'tenant'])->group(function () {
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

        Route::get('/home', [HomeController::class, 'index'])
            ->middleware('modulo:home,1')
            ->name('home');

        Route::get('/beneficiarios', [BeneficiarioController::class, 'index'])
            ->middleware('modulo:beneficiario,1')
            ->name('beneficiarios.index');

        Route::get('/beneficiarios/{id}', [BeneficiarioController::class, 'show'])
            ->middleware('modulo:beneficiario,1')
            ->whereNumber('id')
            ->name('beneficiarios.show');
    });
});
