<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agendamento extends Model
{
    protected $table = 'agendamento';

    public $timestamps = false;

    protected $fillable = [
        'data_hora',
        'data_cadastro',
        'status',
        'usuario_id',
        'usuario_agendamento_id',
        'tarefa_id',
        'atendimento_id',
        'data_atualizacao',
        'usuario_atualizacao_id',
        'descricao',
    ];

    protected function casts(): array
    {
        return [
            'usuario_id' => 'integer',
            'usuario_agendamento_id' => 'integer',
            'tarefa_id' => 'integer',
            'atendimento_id' => 'integer',
            'usuario_atualizacao_id' => 'integer',
            'status' => 'integer',
            'data_hora' => 'datetime',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function usuarioAgendamento(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_agendamento_id');
    }

    public function atendimento(): BelongsTo
    {
        return $this->belongsTo(Atendimento::class, 'atendimento_id');
    }

    public function scopeForTenant(Builder $query): Builder
    {
        $grupoId = session('grupo_empresarial_id');
        $clienteId = session('cliente_id');

        if ($clienteId) {
            $query->whereHas('atendimento.beneficiario', function (Builder $q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            });
        } elseif ($grupoId) {
            $query->whereHas('atendimento.beneficiario.cliente', function (Builder $q) use ($grupoId) {
                $q->where('grupo_empresarial_id', $grupoId);
            });
        }

        return $query;
    }
}
