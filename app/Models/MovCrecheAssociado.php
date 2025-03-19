<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovCrecheAssociado extends Model
{
    use HasFactory, SoftDeletes;

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
        'datahora',
        'observacao_delete' // Adicionado para suportar soft delete com observação
    ];
    
    protected $dates = ['deleted_at'];

    public $timestamps = true;
}
