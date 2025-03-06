<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChqCategoria extends Model
{
    use HasFactory;

    protected $table = 'chq_categorias';

    protected $fillable = [
        'descricao',
        'gravarchq_cursos',
        'gravarchq_oticas',
        'usuario',
        'datahora',
    ];
}
