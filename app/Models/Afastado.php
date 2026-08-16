<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenantViaBeneficiario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Afastado extends Model
{
    use BelongsToTenantViaBeneficiario;

    protected $table = 'afastado';

    public $timestamps = false;

    protected $fillable = [
        'importacao_id',
        'empresa_id',
        'beneficiario_id',
        'data_inicio_afastamento',
        'data_fim_afastamento',
        'cid',
        'tipo_afastamento',
        'assistencia_medica',
        'plano_assistencia_medica',
        'situacao',
        'data_atualizacao',
        'usuario_atualizacao_id',
        'usuario_criador_id',
        'data_cadastro',
        'status',
        'anexo',
        'blob_id',
        'acao_trabalhista',
        'acao_inss',
        'limbo_previdenciario',
    ];

    protected function casts(): array
    {
        return [
            'importacao_id' => 'integer',
            'empresa_id' => 'integer',
            'beneficiario_id' => 'integer',
            'usuario_atualizacao_id' => 'integer',
            'usuario_criador_id' => 'integer',
            'status' => 'integer',
            'blob_id' => 'integer',
            'acao_trabalhista' => 'integer',
            'acao_inss' => 'integer',
            'limbo_previdenciario' => 'integer',
            'data_inicio_afastamento' => 'date',
            'data_fim_afastamento' => 'date',
            'data_atualizacao' => 'datetime',
            'data_cadastro' => 'datetime',
        ];
    }

    public function beneficiario(): BelongsTo
    {
        return $this->belongsTo(Beneficiario::class, 'beneficiario_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function importacao(): BelongsTo
    {
        return $this->belongsTo(Importacao::class, 'importacao_id');
    }
}
