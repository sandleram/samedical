<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportacaoNova extends Model
{
    use BelongsToTenant;

    protected $table = 'importacao_nova';

    public $timestamps = false;

    protected $fillable = [
        'cliente_id',
        'nome_arquivo',
        'tipo_importacao',
        'arquivo_importado',
        'avisos',
        'linhas_totais',
        'linhas_processadas',
        'data_cadastro',
        'data_atualizacao',
        'usuario_criador_id',
        'usuario_atualizacao_id',
        'status_processo',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'cliente_id' => 'integer',
            'usuario_criador_id' => 'integer',
            'usuario_atualizacao_id' => 'integer',
            'status_processo' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function usuarioCriador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_criador_id');
    }
}
