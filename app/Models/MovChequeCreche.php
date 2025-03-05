<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovChequeCreche extends Model
{
    use HasFactory;

    protected $table = 'mov_chequescreches';  // Define a tabela associada ao modelo

    protected $fillable = [
        'NUMCHEQUE',
        'VALOR',
        'CATEGORIA',
        'IMPRESSO',
        'CANCELADO',
        'DATA',
        'NOMINAL',
        'DATACADASTRO',
        'ASSOCIADO',
        'OBSERVACAO',
        'USUARIO',
        'DATAHORA',
        'ENVIARITAU',
        'NOME_TXT_ITAU',
        'OBSERVACAO1',
    ];
}
