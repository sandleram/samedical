<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Beneficio extends Model
{
    use BelongsToTenant;

    protected $table = 'beneficio';

    public $timestamps = false;

    protected $fillable = [
        'descricao',
        'breakeven',
        'contrato',
        'data_cadastro',
        'data_atualizacao',
        'data_cancelamento',
        'status',
        'cliente_id',
        'operadora_id',
        'tipo_beneficio_id',
    ];

    protected function casts(): array
    {
        return [
            'breakeven' => 'integer',
            'status' => 'integer',
            'cliente_id' => 'integer',
            'operadora_id' => 'integer',
            'tipo_beneficio_id' => 'integer',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
            'data_cancelamento' => 'date',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function operadora(): BelongsTo
    {
        return $this->belongsTo(Operadora::class, 'operadora_id');
    }

    public function tipoBeneficio(): BelongsTo
    {
        return $this->belongsTo(TipoBeneficio::class, 'tipo_beneficio_id');
    }

    public function subfaturas(): HasMany
    {
        return $this->hasMany(Subfatura::class, 'beneficio_id');
    }
}
