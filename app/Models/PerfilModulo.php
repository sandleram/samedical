<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerfilModulo extends Model
{
    protected $table = 'perfil_modulo';

    public $timestamps = false;

    protected $fillable = [
        'perfil_id',
        'modulo_id',
        'permissao',
        'status',
        'data_cadastro',
        'data_atualizacao',
    ];

    protected function casts(): array
    {
        return [
            'permissao' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
        ];
    }

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class, 'perfil_id');
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }
}
