<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plano extends Model
{
    protected $table = 'plano';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'codigo_operadora',
        'data_cadastro',
        'status',
        'cliente_id',
        'operadora_id',
        'tipo_beneficio_id',
        'ordem',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'cliente_id' => 'integer',
            'operadora_id' => 'integer',
            'tipo_beneficio_id' => 'integer',
            'ordem' => 'integer',
            'data_cadastro' => 'datetime',
        ];
    }

    public function operadora(): BelongsTo
    {
        return $this->belongsTo(Operadora::class, 'operadora_id');
    }

    public function tipoBeneficio(): BelongsTo
    {
        return $this->belongsTo(TipoBeneficio::class, 'tipo_beneficio_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
