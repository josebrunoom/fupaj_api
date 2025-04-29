<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChqCategoriaAssociado extends Model
{
    protected $table = 'chq_categorias_associados';

    protected $fillable = [
        'categoria',
        'associado',
        'usuario',
        'datahora'
    ];

    public $timestamps = true;
}
