<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenantViaBeneficiario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absenteismo extends Model
{
    use BelongsToTenantViaBeneficiario;

    protected $table = 'absenteismo';

    public $timestamps = false;

    protected $fillable = [
        'importacao_id',
        'beneficiario_id',
        'empresa_id',
        'matricula',
        'documento_id',
        'motivo_id',
        'hospital_clinica',
        'nome_colaborador',
        'data_saida',
        'data_retorno',
        'dias_calculados',
        'hora_saida',
        'hora_retorno',
        'horas_calculadas',
        'qtde_dias_atestado',
        'cid',
        'cid_id',
        'especialidade_id',
        'emissor_id',
        'profissional',
        'num_crm',
        'tipo_absenteismo_id',
        'departamento_id',
        'cargo_id',
        'setor_id',
        'parte_corpo_id',
        'observacao',
        'arquivo',
        'situacao',
        'data_atualizacao',
        'usuario_atualizacao_id',
        'data_cadastro',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'importacao_id' => 'integer',
            'beneficiario_id' => 'integer',
            'empresa_id' => 'integer',
            'dias_calculados' => 'integer',
            'qtde_dias_atestado' => 'integer',
            'cid_id' => 'integer',
            'parte_corpo_id' => 'integer',
            'usuario_atualizacao_id' => 'integer',
            'status' => 'integer',
            'data_saida' => 'date',
            'data_retorno' => 'date',
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
