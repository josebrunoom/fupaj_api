<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovFarmacia extends Model
{
    use HasFactory;

    protected $table = 'mov_farmacia';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'numnota', 'farmacia', 'associado', 'valornota', 
        'valorfundacao', 'valorassociado', 'lancamento', 'emissao',
        'receita_sn', 'usuario', 'datahora'
    ];
}
