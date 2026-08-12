<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('grupo_empresarial')) {
            Schema::create('grupo_empresarial', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nome', 255);
                $table->tinyInteger('status')->default(1);
            });
        }

        if (! Schema::hasTable('perfil')) {
            Schema::create('perfil', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nome', 100);
                $table->string('descricao', 255)->nullable();
                $table->string('tipo', 50)->nullable();
                $table->tinyInteger('status')->default(1);
            });
        }

        if (! Schema::hasTable('modulo')) {
            Schema::create('modulo', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nome', 150);
                $table->string('controller', 100)->nullable();
                $table->string('menu', 150)->nullable();
                $table->string('icon', 50)->nullable();
                $table->integer('ordem')->default(0);
                $table->unsignedInteger('parent_id')->nullable();
                $table->tinyInteger('status')->default(1);
            });
        }

        if (! Schema::hasTable('perfil_modulo')) {
            Schema::create('perfil_modulo', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('perfil_id');
                $table->unsignedInteger('modulo_id');
                $table->tinyInteger('nivel')->default(0);
            });
        }

        if (! Schema::hasTable('cliente')) {
            Schema::create('cliente', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nome', 255);
                $table->unsignedInteger('grupo_empresarial_id')->nullable();
                $table->tinyInteger('status')->default(1);
            });
        }

        if (! Schema::hasTable('empresa')) {
            Schema::create('empresa', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nome', 255);
                $table->string('cnpj', 20)->nullable();
                $table->unsignedInteger('cliente_id')->nullable();
                $table->tinyInteger('status')->default(1);
            });
        }

        if (! Schema::hasTable('usuario')) {
            Schema::create('usuario', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nome', 255);
                $table->string('usuario', 100)->unique();
                $table->string('senha', 255);
                $table->string('email', 255)->nullable();
                $table->unsignedInteger('perfil_id')->nullable();
                $table->unsignedInteger('grupo_empresarial_id')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->string('remember_token', 100)->nullable();
            });
        }

        if (! Schema::hasTable('usuario_cliente')) {
            Schema::create('usuario_cliente', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('usuario_id');
                $table->unsignedInteger('cliente_id');
            });
        }

        if (! Schema::hasTable('beneficiario')) {
            Schema::create('beneficiario', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nome', 255);
                $table->string('cpf', 20)->nullable();
                $table->string('matricula', 50)->nullable();
                $table->date('data_nascimento')->nullable();
                $table->unsignedInteger('cliente_id')->nullable();
                $table->unsignedInteger('empresa_id')->nullable();
                $table->unsignedInteger('grupo_empresarial_id')->nullable();
                $table->tinyInteger('status')->default(1);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiario');
        Schema::dropIfExists('usuario_cliente');
        Schema::dropIfExists('usuario');
        Schema::dropIfExists('empresa');
        Schema::dropIfExists('cliente');
        Schema::dropIfExists('perfil_modulo');
        Schema::dropIfExists('modulo');
        Schema::dropIfExists('perfil');
        Schema::dropIfExists('grupo_empresarial');
    }
};
