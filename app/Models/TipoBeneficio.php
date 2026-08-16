<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoBeneficio extends Model
{
    protected $table = 'tipo_beneficio';

    public $timestamps = false;

    protected $fillable = [
        'descricao',
        'data_cadastro',
        'data_atualizacao',
        'data_cancelamento',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
            'data_cancelamento' => 'date',
        ];
    }

    public function beneficios(): HasMany
    {
        return $this->hasMany(Beneficio::class, 'tipo_beneficio_id');
    }
}
