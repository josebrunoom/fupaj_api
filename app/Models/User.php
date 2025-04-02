<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ChqCategoria;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        // Campos padrão
        'name',
        'email',
        'password',
        'role',
        // Novos campos adicionados
        'CODIGO',
        'SITUACAO',
        'SEXO',
        'NOME',
        'ENDERECO',
        'BAIRRO',
        'CIDADE',
        'ESTADO',
        'CEP',
        'ESTADOCIVIL',
        'TELEFONE',
        'CELULAR',
        'NACIONALIDADE',
        'IDENTIDADE',
        'CPF',
        'DATAADMISSAO_NU',
        'CARTPROFISSIONAL',
        'FUNCAO',
        'PLANOSAUDE',
        'PIS',
        'DATADEMISSAO',
        'USUARIO',
        'DATAHORA',
        'BANCO',
        'AGENCIA',
        'CONTACORRENTE',
        'DIGITOCONTA',
        'CONTAPOUPANCA',
        'NASCIMENTO',
        'EMPRESA',
        'DATAADMISSAO',
        'DOCTO_SUS',
        'NOME_PAI',
        'NOME_MAE',
        'DATA_IDENTIDADE',
        'ORGAO_IDENTIDADE'

    ];

    protected $hidden = ['password'];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [
            'usr'=> [
                'id' => $this->id,
                'nome' => $this->NOME,
                'email' => $this->email,
                'role' => $this->role
            ]
        ];
    }

    public function categorias(){
        return $this->belongsToMany(ChqCategoria::class, 'user_categoria', 'user_id', 'categoria_id');
    }

    public function movFarmacias(){
        return $this->hasMany(MovFarmacia::class, 'associado', 'id');
    }

}
