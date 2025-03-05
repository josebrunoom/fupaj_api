<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovFiltroSolar extends Model
{
    use HasFactory;

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
