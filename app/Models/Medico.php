<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model {
    use HasFactory;

    protected $table = 'medicos';

    protected $fillable = [
        'especialidade', 'nome', 'endereco', 'bairro', 'cidade', 'uf',
        'cep', 'telefone', 'crm', 'cpf', 'usuario', 'datahora', 'cnpj'
    ];

    protected $casts = [
        'datahora' => 'datetime',
    ];
}

