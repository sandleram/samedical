<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Cria as views report_* do schema samed_pro.
 * Fonte: database/schema/samed_pro_structure.sql
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

        // Views no dump terminam com ";" em linha própria após o SELECT.
        if (preg_match_all('/CREATE ALGORITHM[\s\S]*?\n;/i', $sql, $matches)) {
            foreach ($matches[0] as $statement) {
                $statement = preg_replace('/^CREATE ALGORITHM/i', 'CREATE OR REPLACE ALGORITHM', ltrim($statement), 1);
                DB::unprepared(rtrim($statement));
            }
        }
    }

    public function down(): void
    {
        $views = [
            'report_afastado',
            'report_afastado_detalhado',
            'report_agendamento_aberto',
            'report_atend_por_usuario_por_cliente',
            'report_atendim_por_benef',
            'report_atendim_por_usuario',
            'report_atendimento_por_usuario_por_data',
            'report_atendimentos_usuario_por_cliente',
            'report_benef_ativos',
            'report_benef_ativos_com_cnpj',
            'report_benef_por_casa',
            'report_clientes_por_grupo_emp',
            'report_cpf_distinct_por_cliente',
            'report_usuario_por_clientes',
            'report_usuario_por_ge',
        ];

        foreach ($views as $view) {
            DB::statement("DROP VIEW IF EXISTS `{$view}`");
        }
    }
};
