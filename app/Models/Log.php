<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tabela legada `log`. Preferir este model (ou AuditLog) em vez do facade Log.
 */
class Log extends Model
{
    protected $table = 'log';

    public $timestamps = false;

    protected $fillable = [
        'log',
        'mensagem',
        'description',
        'server_description',
        'data_cadastro',
        'usuario_id',
    ];

    protected function casts(): array
    {
        return [
            'usuario_id' => 'integer',
            'data_cadastro' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
