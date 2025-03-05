<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovCheque extends Model
{
    use HasFactory;

    protected $table = 'mov_cheques';
    protected $fillable = [
        'numcheque', 'valor', 'categoria', 'impresso', 'cancelado', 
        'data', 'nominal', 'datacadastro', 'associado', 'observacao', 
        'usuario', 'datahora', 'enviaritau', 'nome_txt_itau'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria');
    }

    public function associado()
    {
        return $this->belongsTo(Associado::class, 'associado');
    }
}

