<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    protected $table = 'modulo';

    public $timestamps = false;

    protected $fillable = [
        'modulo_id',
        'nome',
        'controller',
        'action',
        'menu',
        'order',
        'icon',
        'usuario_id',
        'data_cadastro',
        'data_atualizacao',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'modulo_id' => 'integer',
            'menu' => 'integer',
            'order' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'modulo_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'modulo_id');
    }

    public function perfilModulos(): HasMany
    {
        return $this->hasMany(PerfilModulo::class, 'modulo_id');
    }
}
