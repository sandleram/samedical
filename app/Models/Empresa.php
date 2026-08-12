<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $table = 'empresa';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'cnpj',
        'cliente_id',
        'status',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function beneficiarios(): HasMany
    {
        return $this->hasMany(Beneficiario::class, 'empresa_id');
    }
}
