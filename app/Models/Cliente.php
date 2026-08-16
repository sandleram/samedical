<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'cliente';

    public $timestamps = false;

    protected $fillable = [
        'grupo_empresarial_id',
        'nome',
        'img_logo',
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'descricao',
        'email',
        'telefone',
        'usuario_criador_id',
        'data_cadastro',
        'data_atualizacao',
        'data_cancelamento',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'grupo_empresarial_id' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
            'data_cancelamento' => 'date',
        ];
    }

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

    /**
     * Filtra pelo grupo_empresarial_id da sessão quando definido.
     */
    public function scopeForTenant(Builder $query): Builder
    {
        $grupoId = session('grupo_empresarial_id');
        if ($grupoId) {
            $query->where($this->getTable().'.grupo_empresarial_id', (int) $grupoId);
        }

        return $query;
    }
}
