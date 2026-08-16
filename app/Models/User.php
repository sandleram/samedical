<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuario';

    public $timestamps = false;

    protected $fillable = [
        'grupo_empresarial_id',
        'perfil_id',
        'nome',
        'apelido',
        'usuario',
        'senha',
        'email',
        'email_gestao',
        'sexo',
        'rg',
        'cpf',
        'data_nascimento',
        'tel1_tipo',
        'tel1',
        'tel2_tipo',
        'tel2',
        'tel3_tipo',
        'tel3',
        'imagem',
        'cor',
        'observacao',
        'usuario_criador_id',
        'data_cadastro',
        'data_atualizacao',
        'status',
    ];

    protected $hidden = [
        'senha',
    ];

    protected function casts(): array
    {
        return [
            'perfil_id' => 'integer',
            'grupo_empresarial_id' => 'integer',
            'status' => 'integer',
            'data_nascimento' => 'date',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
        ];
    }

    public function getAuthPassword(): string
    {
        return (string) $this->senha;
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    /**
     * Tabela usuario legada não possui remember_token.
     */
    public function getRememberTokenName(): ?string
    {
        return null;
    }

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class, 'perfil_id');
    }

    public function grupoEmpresarial(): BelongsTo
    {
        return $this->belongsTo(GrupoEmpresarial::class, 'grupo_empresarial_id');
    }

    public function usuarioCriador(): BelongsTo
    {
        return $this->belongsTo(self::class, 'usuario_criador_id');
    }

    public function usuarioClientes(): HasMany
    {
        return $this->hasMany(UsuarioCliente::class, 'usuario_id');
    }

    public function usuarioBis(): HasMany
    {
        return $this->hasMany(UsuarioBi::class, 'usuario_id');
    }

    /**
     * Tenant por grupo_empresarial_id (root vê todos).
     */
    public function scopeForTenant(Builder $query): Builder
    {
        $auth = auth()->user();
        if ($auth && $auth->isRoot()) {
            return $query;
        }

        $grupoId = session('grupo_empresarial_id');
        if ($grupoId) {
            $query->where($this->getTable().'.grupo_empresarial_id', $grupoId);
        }

        return $query;
    }

    public function isRoot(): bool
    {
        return (int) $this->id === 1 || (int) $this->perfil_id === 1;
    }

    public function isActive(): bool
    {
        return (int) $this->status === 1;
    }
}
