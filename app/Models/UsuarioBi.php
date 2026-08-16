<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioBi extends Model
{
    protected $table = 'usuario_bi';

    public $timestamps = false;

    protected $fillable = [
        'bi_id',
        'usuario_id',
    ];

    protected function casts(): array
    {
        return [
            'bi_id' => 'integer',
            'usuario_id' => 'integer',
        ];
    }

    public function bi(): BelongsTo
    {
        return $this->belongsTo(Bi::class, 'bi_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
