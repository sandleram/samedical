<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrupoEmpresarial extends Model
{
    protected $table = 'grupo_empresarial';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'status',
    ];

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'grupo_empresarial_id');
    }
}
