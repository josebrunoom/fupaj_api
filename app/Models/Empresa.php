<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'cnpj',
        'empresa',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'cep',
        'estado',
        'email',
        'data_arquivo',
        'nome_arquivo',
        'lote',
        'banco',
        'agencia',
        'conta_corrente',
        'digito'
    ];

    protected $casts = [
        'data_arquivo' => 'datetime',
        'lote' => 'integer'
    ];
}
