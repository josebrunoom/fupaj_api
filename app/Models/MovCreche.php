<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovCreche extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mov_creches';

    protected $fillable = [
        'associado',
        'lancamento',
        'pagamento',
        'valor',
        'observacao',
        'tipo',
        'usuario',
        'datahora',
        'lancto_cheque',
        'cancel_cheque',
        'controle',
        'observacao_delete' // Adicionado para suportar soft delete com observação
    ];

    protected $dates = ['deleted_at'];

    public $timestamps = true;
}
