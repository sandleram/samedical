<?php

namespace App\Models;

use Database\Factories\UserFactory;
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
        'nome',
        'usuario',
        'senha',
        'email',
        'perfil_id',
        'grupo_empresarial_id',
        'status',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'perfil_id' => 'integer',
            'grupo_empresarial_id' => 'integer',
            'status' => 'integer',
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

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class, 'perfil_id');
    }

    public function grupoEmpresarial(): BelongsTo
    {
        return $this->belongsTo(GrupoEmpresarial::class, 'grupo_empresarial_id');
    }

    public function usuarioClientes(): HasMany
    {
        return $this->hasMany(UsuarioCliente::class, 'usuario_id');
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
