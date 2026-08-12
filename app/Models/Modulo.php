<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    protected $table = 'modulo';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'controller',
        'menu',
        'icon',
        'ordem',
        'status',
        'parent_id',
    ];

    public function perfilModulos(): HasMany
    {
        return $this->hasMany(PerfilModulo::class, 'modulo_id');
    }
}
