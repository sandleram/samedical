<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedimento extends Model
{
    protected $table = 'procedimento';

    public $timestamps = false;

    protected $fillable = [
        'cod_procedimento',
        'ds_procedimento',
        'Grupo',
        'Subgrupo',
        'Grupo de Exames',
        'usuario_id',
        'usuario_atualizacao_id',
        'data_cadastro',
        'data_atualizacao',
        'status',
        'tipo_procedimento',
        'tipo_servico',
    ];

    protected function casts(): array
    {
        return [
            'usuario_id' => 'integer',
            'usuario_atualizacao_id' => 'integer',
            'status' => 'integer',
            'tipo_servico' => 'integer',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
        ];
    }
}
