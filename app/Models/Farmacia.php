<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmacia extends Model
{
    use HasFactory;

    protected $table = 'farmacias';
    protected $primaryKey = 'codigo';
    public $timestamps = true;

    protected $fillable = [
        'cnpj', 'nome', 'endereco', 'bairro', 'cidade', 'uf', 
        'cep', 'telefone', 'fax', 'inscricao_estadual', 'usuario', 'data_hora'
    ];
}

