<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovFiltroSolar extends Model
{
    protected $table = 'mov_filtrosolar';

    protected $fillable = [
        'associado',
        'farmacia',
        'numnota',
        'dataemissao',
        'lancamento',
        'valorfiltro',
        'valorfundacao',
        'valorassociado',
        'usuario',
        'datahora',
        'cancelamento'
    ];
}
