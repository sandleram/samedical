<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blob extends Model
{
    protected $table = 'blob';

    public $timestamps = false;

    protected $fillable = [
        'table',
        'nome',
        'tipo',
        'tamanho',
        'extensao',
        'blob',
        'data_atualizacao',
        'data_cadastro',
        'usuario_id',
        'usuario_id_atualizacao',
        'status',
    ];

    protected $hidden = [
        'blob',
    ];

    protected function casts(): array
    {
        return [
            'tamanho' => 'integer',
            'usuario_id' => 'integer',
            'usuario_id_atualizacao' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
        ];
    }

    public function binaryContent(): ?string
    {
        $value = $this->attributes['blob'] ?? null;

        return $value === null || $value === '' ? null : (string) $value;
    }
}
