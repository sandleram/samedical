<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Cria as tabelas do schema de produção samed_pro (somente estrutura).
 * Fonte canônica: database/schema/samed_pro_structure.sql
 *
 * Tabelas na ordem do dump HeidiSQL; FKs habilitadas ao final.
 */
return new class extends Migration
{
    public function up(): void
    {
        $path = database_path('schema/samed_pro_structure.sql');
        if (! is_file($path)) {
            throw new RuntimeException('Missing database/schema/samed_pro_structure.sql');
        }

        $sql = file_get_contents($path);
        if ($sql === false || $sql === '') {
            throw new RuntimeException('Unable to read samed_pro_structure.sql');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        if (preg_match_all('/CREATE TABLE IF NOT EXISTS[\s\S]*?;/i', $sql, $matches)) {
            foreach ($matches[0] as $statement) {
                if (preg_match('/CREATE TABLE IF NOT EXISTS\s+`report_/i', $statement)) {
                    continue;
                }
                DB::unprepared($statement);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = [
            'cliente_desligamento',
            'mh_negociacao_historico',
            'mh_negociacao',
            'mh_critico_status',
            'mh_critico_historico',
            'mh_critico',
            'mh_prestador',
            'notificacao_usuario',
            'notificacao',
            'parametro',
            'mensagem',
            'log_erro',
            'log',
            'grupo_estatistico',
            'cronicos',
            'blob',
            'bi',
            'dw_sinistro_prestador_evento',
            'dw_sinistro_paciente',
            'dw_sinistro_evento',
            'dw_robo_atualizacao',
            'dw_populacao',
            'dw_fatura_mes',
            'faturamento',
            'fatura',
            'sinistro_internacao_alta',
            'sinistro_old',
            'sinistro',
            'patologia_clf_proced',
            'amb_proced',
            'procedimento_old',
            'procedimento',
            'indice',
            'sinistro_evento',
            'cid',
            'cid_grupos',
            'cid_grupo',
            'agendamento',
            'atendimento',
            'beneficio_previdenciario',
            'absenteismo',
            'afastado',
            'dw_beneficiario',
            'beneficiario_plano',
            'beneficiario',
            'importacao_nova',
            'importacao',
            'tipo_conta',
            'faixa_etaria',
            'cidade',
            'estado',
            'especie_bp',
            'subfaturas',
            'empresa_subfatura',
            'subfatura',
            'plano',
            'beneficio',
            'tipo_beneficio',
            'operadora',
            'usuario_bi',
            'usuario_cliente',
            'perfil_modulo',
            'modulo',
            'empresa',
            'cliente',
            'usuario',
            'perfil',
            'grupo_empresarial',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
