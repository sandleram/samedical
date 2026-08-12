<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perfil extends Model
{
    protected $table = 'perfil';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'descricao',
        'tipo',
        'status',
    ];

    public function perfilModulos(): HasMany
    {
        return $this->hasMany(PerfilModulo::class, 'perfil_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'perfil_id');
    }
}
