<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenantViaBeneficiario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Atendimento extends Model
{
    use BelongsToTenantViaBeneficiario;

    protected $table = 'atendimento';

    public $timestamps = false;

    protected $fillable = [
        'descricao',
        'descricao_origin',
        'tipo_atendimento',
        'hora_inicio',
        'hora_fim',
        'tempo_trabalho',
        'cid',
        'data_cadastro',
        'status',
        'forma_atendimento',
        'beneficiario_id',
        'status_atendimento',
        'at_horas',
        'at_minutos',
        'data_conclusao',
        'usuario_id',
        'anexo',
        'blob_id',
        'data_atualizacao',
        'usuario_atualizacao_id',
        'descricao_agendamento',
    ];

    protected function casts(): array
    {
        return [
            'tipo_atendimento' => 'integer',
            'tempo_trabalho' => 'integer',
            'status' => 'integer',
            'forma_atendimento' => 'integer',
            'beneficiario_id' => 'integer',
            'status_atendimento' => 'integer',
            'at_horas' => 'integer',
            'at_minutos' => 'integer',
            'usuario_id' => 'integer',
            'blob_id' => 'integer',
            'usuario_atualizacao_id' => 'integer',
            'hora_inicio' => 'datetime',
            'hora_fim' => 'datetime',
            'data_cadastro' => 'datetime',
            'data_conclusao' => 'datetime',
            'data_atualizacao' => 'datetime',
        ];
    }

    public function beneficiario(): BelongsTo
    {
        return $this->belongsTo(Beneficiario::class, 'beneficiario_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class, 'atendimento_id');
    }
}
