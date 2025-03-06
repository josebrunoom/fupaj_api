<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovCrecheAssociado extends Model
{
    use HasFactory;

    protected $table = 'mov_creches_associados';

    protected $fillable = [
        'associado',
        'tipo',
        'parcelas',
        'data_inicio',
        'data_termino',
        'lancamento',
        'observacao',
        'status',
        'usuario',
        'datahora'
    ];
}
