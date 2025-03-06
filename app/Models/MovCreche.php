<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovCreche extends Model
{
    use HasFactory;

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
        'controle'
    ];

    public $timestamps = true;
}
