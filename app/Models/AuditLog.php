<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tabela legada `log` (evita conflito com facade Log do Laravel).
 */
class AuditLog extends Model
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
}
