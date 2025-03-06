<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovFarmaciaController;
use App\Http\Controllers\FarmaciaController;
use App\Http\Controllers\MovFiltroSolarController;
use App\Http\Controllers\MovChequeController;
use App\Http\Controllers\MovChequeCrecheController;
use App\Http\Controllers\ChqCategoriaController;
use App\Http\Controllers\ChqCategoriaAssociadoController;
use App\Http\Controllers\MovCrecheController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth.jwt')->group(function () {

    //USERS
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/users/{id}', [AuthController::class, 'show']);
    Route::put('/users/{id}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    Route::delete('/users/{id}', [AuthController::class, 'destroy']);

    //EMPRESAS
    Route::apiResource('empresas', EmpresaController::class);

    //FARMACIAS
    Route::apiResource('farmacias', FarmaciaController::class);

    //MOV FARMACIAS
    Route::apiResource('mov_farmacia', MovFarmaciaController::class);

    Route::get('/mov_farmacia/farmacia/{id}', [MovFarmaciaController::class, 'showByFarmancia']);

    //FILTRO SOLAR
    Route::apiResource('mov_filtrosolar', MovFiltroSolarController::class);

    //MOV CHEQUES
    Route::apiResource('mov_cheques', MovChequeController::class);

    //MOV CHEQUES
    Route::apiResource('mov_chequescreches', MovChequeCrecheController::class);

    //CHQ_CATEGORIAS
    Route::apiResource('chq_categorias', ChqCategoriaController::class);

    //CHQ_CATEGORIAS_ASSOCIADOS
    Route::apiResource('chq_categorias_associados', ChqCategoriaAssociadoController::class);

    //MOV_CRECHES
    Route::apiResource('mov_creches', MovCrecheController::class);


    
});


