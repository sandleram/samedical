<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $table = 'empresa';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'inscricao_estadual',
        'inscricao_municipal',
        'numero_funcionarios',
        'descricao',
        'porte',
        'faturamento',
        'tipo',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'email',
        'telefone',
        'site',
        'usuario_criador_id',
        'data_cadastro',
        'data_atualizacao',
        'status',
        'cliente_id',
    ];

    protected function casts(): array
    {
        return [
            'cliente_id' => 'integer',
            'status' => 'integer',
            'numero_funcionarios' => 'integer',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function beneficiarios(): HasMany
    {
        return $this->hasMany(Beneficiario::class, 'empresa_id');
    }

    /**
     * Filtra por cliente_id e/ou grupo_empresarial_id (via relação cliente) da sessão.
     */
    public function scopeForTenant(Builder $query): Builder
    {
        $grupoId = session('grupo_empresarial_id');
        $clienteId = session('cliente_id');

        if ($clienteId) {
            $query->where($this->getTable().'.cliente_id', (int) $clienteId);
        } elseif ($grupoId) {
            $query->whereHas('cliente', function (Builder $clienteQuery) use ($grupoId) {
                $clienteQuery->where('grupo_empresarial_id', (int) $grupoId);
            });
        }

        return $query;
    }
}
