<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operadora extends Model
{
    protected $table = 'operadora';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'data_cadastro',
        'data_cancelamento',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'data_cadastro' => 'datetime',
            'data_cancelamento' => 'date',
        ];
    }
}
