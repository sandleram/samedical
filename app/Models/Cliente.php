<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'cliente';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'grupo_empresarial_id',
        'status',
    ];

    public function grupoEmpresarial(): BelongsTo
    {
        return $this->belongsTo(GrupoEmpresarial::class, 'grupo_empresarial_id');
    }

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class, 'cliente_id');
    }

    public function beneficiarios(): HasMany
    {
        return $this->hasMany(Beneficiario::class, 'cliente_id');
    }
}
