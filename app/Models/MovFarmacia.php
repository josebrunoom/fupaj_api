<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovFarmacia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mov_farmacia';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'numnota', 'farmacia', 'associado', 'valornota', 
        'valorfundacao', 'valorassociado', 'lancamento', 'emissao',
        'receita_sn', 'usuario', 'datahora', 'observacao_delete'
    ];

    protected $dates = ['deleted_at'];
}
