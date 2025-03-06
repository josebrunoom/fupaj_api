<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChqCategoriaAssociado extends Model
{
    use HasFactory;

    protected $table = 'chq_categorias_associado';

    protected $fillable = [
        'categoria',
        'associado',
        'usuario',
        'datahora'
    ];

    public $timestamps = true;
}
