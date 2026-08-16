<?php

/**
 * Generates Laravel Schema Blueprint migrations from samed_pro_structure.sql.
 * Run: php database/scripts/generate_samed_migrations.php
 */

$schemaPath = dirname(__DIR__) . '/schema/samed_pro_structure.sql';
$outDir = dirname(__DIR__) . '/migrations';

$sql = file_get_contents($schemaPath);
if ($sql === false) {
    fwrite(STDERR, "Cannot read schema file\n");
    exit(1);
}

// Split CREATE TABLE blocks
preg_match_all(
    '/CREATE TABLE IF NOT EXISTS `([^`]+)`\s*\((.*)\)\s*ENGINE=([^;]+);/is',
    $sql,
    $matches,
    PREG_SET_ORDER
);

if (count($matches) === 0) {
    fwrite(STDERR, "No CREATE TABLE found\n");
    exit(1);
}

$order = [
    'grupo_empresarial', 'perfil', 'usuario', 'cliente', 'empresa',
    'modulo', 'perfil_modulo', 'usuario_cliente', 'usuario_bi',
    'operadora', 'tipo_beneficio', 'beneficio', 'plano', 'subfatura', 'empresa_subfatura', 'subfaturas',
    'especie_bp', 'estado', 'cidade', 'faixa_etaria', 'tipo_conta',
    'importacao', 'importacao_nova',
    'beneficiario', 'beneficiario_plano', 'dw_beneficiario',
    'afastado', 'absenteismo', 'beneficio_previdenciario',
    'atendimento', 'agendamento',
    'cid_grupo', 'cid_grupos', 'cid',
    'sinistro_evento', 'indice', 'procedimento', 'procedimento_old', 'amb_proced', 'patologia_clf_proced',
    'sinistro', 'sinistro_old', 'sinistro_internacao_alta',
    'fatura', 'faturamento',
    'dw_fatura_mes', 'dw_populacao', 'dw_robo_atualizacao',
    'dw_sinistro_evento', 'dw_sinistro_paciente', 'dw_sinistro_prestador_evento',
    'bi', 'blob', 'cronicos', 'grupo_estatistico',
    'log', 'log_erro', 'mensagem', 'parametro', 'notificacao', 'notificacao_usuario',
    'mh_prestador', 'mh_critico', 'mh_critico_historico', 'mh_critico_status',
    'mh_negociacao', 'mh_negociacao_historico',
    'cliente_desligamento',
];

$byName = [];
foreach ($matches as $m) {
    $byName[$m[1]] = $m;
}

$ordered = [];
foreach ($order as $name) {
    if (isset($byName[$name])) {
        $ordered[] = $byName[$name];
        unset($byName[$name]);
    }
}
foreach ($byName as $m) {
    $ordered[] = $m;
}

function mapColumn(string $line): ?string
{
    $line = trim($line);
    if ($line === '' || str_starts_with($line, 'PRIMARY KEY') || str_starts_with($line, 'KEY ') || str_starts_with($line, 'UNIQUE KEY') || str_starts_with($line, 'CONSTRAINT ') || str_starts_with($line, 'FULLTEXT')) {
        return null;
    }

    if (!preg_match('/^`([^`]+)`\s+(.+)$/', $line, $m)) {
        return null;
    }

    $name = $m[1];
    $rest = rtrim($m[2], ',');
    $quoted = in_array($name, ['order', 'table', 'blob', 'Grupo', 'Subgrupo', 'Grupo de Exames'], true)
        || str_contains($name, ' ');

    $colRef = $quoted ? "\$table->{$name}" : null;

    // Parse type
    $nullable = stripos($rest, 'NOT NULL') === false;
    $auto = stripos($rest, 'AUTO_INCREMENT') !== false;

    $default = null;
    if (preg_match("/DEFAULT\s+'((?:\\\\'|[^'])*)'/i", $rest, $dm)) {
        $default = $dm[1];
    } elseif (preg_match('/DEFAULT\s+(NULL|CURRENT_TIMESTAMP|[0-9.-]+)/i', $rest, $dm)) {
        $default = strtoupper($dm[1]) === 'NULL' ? null : $dm[1];
        if (strtoupper((string) $dm[1]) === 'CURRENT_TIMESTAMP') {
            $default = 'CURRENT_TIMESTAMP';
        }
    }

    $comment = null;
    if (preg_match("/COMMENT\s+'((?:\\\\'|[^'])*)'/i", $rest, $cm)) {
        $comment = str_replace("\\'", "'", $cm[1]);
    }

    $unsigned = stripos($rest, 'unsigned') !== false;

    $method = null;
    $args = ["'{$name}'"];

    if (preg_match('/^(bigint|int|tinyint|smallint|mediumint)\((\d+)\)/i', $rest, $tm)) {
        $type = strtolower($tm[1]);
        if ($type === 'bigint') {
            $method = $auto ? 'bigIncrements' : 'bigInteger';
            if ($auto) {
                $args = ["'{$name}'"];
            }
        } elseif ($type === 'tinyint') {
            $method = ((int) $tm[2] === 1) ? 'boolean' : 'tinyInteger';
        } elseif ($type === 'int' && $auto) {
            $method = 'increments';
            $args = ["'{$name}'"];
        } else {
            $method = 'integer';
        }
    } elseif (preg_match('/^varchar\((\d+)\)/i', $rest, $tm)) {
        $method = 'string';
        $args = ["'{$name}'", $tm[1]];
    } elseif (preg_match('/^char\((\d+)\)/i', $rest, $tm)) {
        $method = 'char';
        $args = ["'{$name}'", $tm[1]];
    } elseif (preg_match('/^decimal\((\d+),(\d+)\)/i', $rest, $tm)) {
        $method = 'decimal';
        $args = ["'{$name}'", $tm[1], $tm[2]];
    } elseif (preg_match('/^double\((\d+),(\d+)\)/i', $rest, $tm)) {
        $method = 'double';
        $args = ["'{$name}'", $tm[1], $tm[2]];
    } elseif (preg_match('/^float/i', $rest)) {
        $method = 'float';
    } elseif (preg_match('/^datetime/i', $rest)) {
        $method = 'dateTime';
    } elseif (preg_match('/^timestamp/i', $rest)) {
        $method = 'timestamp';
    } elseif (preg_match('/^date/i', $rest)) {
        $method = 'date';
    } elseif (preg_match('/^time/i', $rest)) {
        $method = 'time';
    } elseif (preg_match('/^longtext/i', $rest)) {
        $method = 'longText';
    } elseif (preg_match('/^mediumtext/i', $rest)) {
        $method = 'mediumText';
    } elseif (preg_match('/^text/i', $rest)) {
        $method = 'text';
    } elseif (preg_match('/^longblob/i', $rest)) {
        $method = 'binary'; // approximate
        // use longText for blob content placeholder - better mediumBlob
        $method = 'mediumText'; // fallback won't match longblob well
    } else {
        return "            // TODO unsupported: {$line}";
    }

    if ($method === 'mediumText' && stripos($rest, 'longblob') !== false) {
        // Laravel: binary() doesn't take longblob easily; use DB later
        return "            \$table->binary('{$name}'); // longblob";
    }

    if (in_array($method, ['increments', 'bigIncrements'], true)) {
        return "            \$table->{$method}({$args[0]});";
    }

    $code = "            \$table->{$method}(" . implode(', ', $args) . ')';
    if ($unsigned && !in_array($method, ['increments', 'bigIncrements', 'boolean'], true)) {
        $code .= '->unsigned()';
    }
    if ($nullable && !$auto) {
        $code .= '->nullable()';
    }
    if ($default === 'CURRENT_TIMESTAMP') {
        $code .= '->useCurrent()';
    } elseif ($default !== null && $default !== '') {
        if (is_numeric($default)) {
            $code .= "->default({$default})";
        } else {
            $escaped = str_replace("'", "\\'", $default);
            $code .= "->default('{$escaped}')";
        }
    }
    if ($comment !== null && $comment !== '') {
        $escaped = str_replace("'", "\\'", str_replace(["\n", "\r"], ' ', $comment));
        $code .= "->comment('{$escaped}')";
    }
    $code .= ';';

    // Reserved / spaced column names: use raw if needed
    if ($quoted) {
        return "            \$table->{$method}('{$name}'" . (count($args) > 1 ? ', ' . implode(', ', array_slice($args, 1)) : '') . ')'
            . ($nullable && !$auto ? '->nullable()' : '')
            . ';';
    }

    return $code;
}

function parseIndexesAndFks(string $body): array
{
    $indexes = [];
    $fks = [];
    $pk = null;

    foreach (preg_split('/\n/', $body) as $raw) {
        $line = trim($raw);
        $line = rtrim($line, ',');

        if (preg_match('/^PRIMARY KEY\s*\((.+)\)/i', $line, $m)) {
            $pk = $m[1];
            continue;
        }
        if (preg_match('/^(?:UNIQUE\s+)?KEY\s+`([^`]+)`\s*\((.+)\)/i', $line, $m)) {
            $cols = array_map(function ($c) {
                return trim(str_replace('`', '', $c));
            }, explode(',', $m[2]));
            // strip prefix lengths like (255)
            $cols = array_map(function ($c) {
                return preg_replace('/\(\d+\)$/', '', $c);
            }, $cols);
            $indexes[] = ['name' => $m[1], 'cols' => $cols, 'unique' => stripos($line, 'UNIQUE') === 0];
            continue;
        }
        if (preg_match('/^CONSTRAINT\s+`([^`]+)`\s+FOREIGN KEY\s*\(`([^`]+)`\)\s+REFERENCES\s+`([^`]+)`\s*\(`([^`]+)`\)(.*)$/i', $line, $m)) {
            $fks[] = [
                'name' => $m[1],
                'column' => $m[2],
                'ref_table' => $m[3],
                'ref_column' => $m[4],
                'extra' => $m[5],
            ];
        }
    }

    return [$pk, $indexes, $fks];
}

// For reliability with complex DDL (longblob, spaced columns, FKs), emit a single migration
// that executes CREATE TABLE statements from the schema file in dependency order.

$createStmts = [];
foreach ($ordered as $m) {
    $createStmts[] = "CREATE TABLE IF NOT EXISTS `{$m[1]}` ({$m[2]}) ENGINE={$m[3]}";
}

$migration = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Creates samed_pro production tables (structure only).
 * Source of truth: database/schema/samed_pro_structure.sql
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $sql = file_get_contents(database_path('schema/samed_pro_structure.sql'));
        if ($sql === false) {
            throw new RuntimeException('Missing database/schema/samed_pro_structure.sql');
        }

        // Execute CREATE TABLE statements in file order (IF NOT EXISTS).
        if (preg_match_all('/CREATE TABLE IF NOT EXISTS[\s\S]*?;/i', $sql, $matches)) {
            foreach ($matches[0] as $statement) {
                // Skip accidental view stubs if any slipped in
                if (preg_match('/CREATE TABLE IF NOT EXISTS `report_/i', $statement)) {
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
PHP;

$tableList = array_map(fn ($m) => $m[1], array_reverse($ordered));
foreach ($tableList as $t) {
    if (str_starts_with($t, 'report_')) {
        continue;
    }
    $migration .= "            '{$t}',\n";
}

$migration .= <<<'PHP'
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};

PHP;

$outFile = $outDir . '/2026_08_15_100000_create_samed_pro_tables.php';
file_put_contents($outFile, $migration);
echo "Wrote {$outFile} (" . count($ordered) . " tables)\n";
