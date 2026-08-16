<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenantViaBeneficiario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeneficioPrevidenciario extends Model
{
    use BelongsToTenantViaBeneficiario;

    protected $table = 'beneficio_previdenciario';

    public $timestamps = false;

    protected $fillable = [
        'importacao_id',
        'empresa_id',
        'beneficiario_id',
        'data_proxima_pericia',
        'num_requerimento',
        'nb',
        'nit',
        'especie',
        'especie_bp_id',
        'situacao',
        'data_entrada_requerimento',
        'data_inicio',
        'data_despacho',
        'data_realizacao_pericia',
        'conclusao_pericia_medica',
        'data_limite',
        'data_indeferimento',
        'data_cessacao',
        'nexo_tecnico',
        'data_atualizacao',
        'usuario_atualizacao_id',
        'data_cadastro',
        'status',
        'contestado',
        'contestado_protocolo',
        'cat',
        'cat_tipo_acidente',
    ];

    protected function casts(): array
    {
        return [
            'importacao_id' => 'integer',
            'empresa_id' => 'integer',
            'beneficiario_id' => 'integer',
            'num_requerimento' => 'integer',
            'nb' => 'integer',
            'nit' => 'integer',
            'especie_bp_id' => 'integer',
            'usuario_atualizacao_id' => 'integer',
            'status' => 'integer',
            'contestado' => 'integer',
            'cat' => 'integer',
            'data_proxima_pericia' => 'date',
            'data_entrada_requerimento' => 'date',
            'data_inicio' => 'date',
            'data_despacho' => 'date',
            'data_realizacao_pericia' => 'date',
            'data_limite' => 'date',
            'data_indeferimento' => 'date',
            'data_cessacao' => 'date',
            'data_atualizacao' => 'date',
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
}
