<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beneficiario extends Model
{
    use BelongsToTenant;

    protected $table = 'beneficiario';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'cpf',
        'cliente_id',
        'empresa_id',
        'grupo_empresarial_id',
        'status',
        'matricula',
        'data_nascimento',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function grupoEmpresarial(): BelongsTo
    {
        return $this->belongsTo(GrupoEmpresarial::class, 'grupo_empresarial_id');
    }
}
