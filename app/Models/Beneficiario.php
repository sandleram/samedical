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
        'cliente_id',
        'empresa_id',
        'nome',
        'nome_social',
        'rg',
        'cpf',
        'pis',
        'nome_mae',
        'estado_civil',
        'email',
        'beneficio',
        'valor_do_seguro',
        'data_nascimento',
        'sexo',
        'altura',
        'peso',
        'imc',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cep',
        'cidade',
        'estado',
        'telefone_tipo',
        'telefone',
        'telefone1_tipo',
        'telefone1',
        'agencia',
        'conta',
        'tipo_de_conta',
        'profissao',
        'ocupacao',
        'pessoa_politicamente_exposta',
        'realiza_alguma_atividade_perigosa_na_profissao',
        'possui_deficiencia',
        'observacao',
        'situacao',
        'cod_matricula',
        'carteirinha',
        'chave_beneficiario',
        'operadora',
        'ds_plano',
        'elegibilidade',
        'dt_inclusao',
        'dt_exclusao',
        'dt_admissao',
        'importacao_id',
        'usuario_criador_id',
        'usuario_atualizacao_id',
        'data_cadastro',
        'data_atualizacao',
        'status',
        'processo',
        'vl_ambulatorio',
    ];

    protected function casts(): array
    {
        return [
            'cliente_id' => 'integer',
            'empresa_id' => 'integer',
            'status' => 'integer',
            'altura' => 'integer',
            'peso' => 'decimal:2',
            'imc' => 'float',
            'valor_do_seguro' => 'decimal:2',
            'data_nascimento' => 'date',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
            'vl_ambulatorio' => 'decimal:2',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
