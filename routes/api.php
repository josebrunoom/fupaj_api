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
use App\Http\Controllers\MovCrecheAssociadoController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth.jwt')->group(function () {
    
    //USERS
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/users/{id}', [AuthController::class, 'show']);
    Route::put('/users/{id}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    //USUÁRIOS PRESTES A FAZER 21, 24 E 40 ANOS
    Route::delete('/users/{id}', [AuthController::class, 'destroy']);
    Route::get('/users_near_21', [AuthController::class, 'getUsersNearAgeLimits']);

    //ENDPOINTS PARA ASSOCIADOS, AGREGADOS E DEPENDENTES
    Route::get('/associados', [AuthController::class, 'getAssociados']);
    Route::get('/dependentes', [AuthController::class, 'getDependentes']);
    Route::get('/agregados', [AuthController::class, 'getAgregados']);

    //EMPRESAS
    Route::apiResource('empresas', EmpresaController::class);

    //FARMACIAS
    Route::apiResource('farmacias', FarmaciaController::class);

    //MOV FARMACIAS
    Route::get('mov_farmacia/trashed', [MovCrecheController::class, 'trashed']);
    Route::apiResource('mov_farmacia', MovFarmaciaController::class);
    Route::delete('mov_farmacia/{id}', [MovCrecheController::class, 'destroy']); // Soft delete com observação
    Route::patch('mov_farmacia/{id}/restore', [MovCrecheController::class, 'restore']); // Restaura um registro deletado
    Route::get('/mov_farmacia/farmacia/{id}', [MovFarmaciaController::class, 'showByFarmacia']);
    Route::put('/mov_farmacia/{id}', [MovFarmaciaController::class, 'update']);


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
    Route::get('mov_creches/trashed', [MovCrecheController::class, 'trashed']);
    Route::apiResource('mov_creches', MovCrecheController::class);
    Route::delete('mov_creches/{id}', [MovCrecheController::class, 'destroy']); // Soft delete com observação
    Route::patch('mov_creches/{id}/restore', [MovCrecheController::class, 'restore']); // Restaura um registro deletado

    //MOV_CRECHES_ASSOCIADOS
    Route::get('mov_creches_associados/trashed', [MovCrecheController::class, 'trashed']);
    Route::apiResource('mov_creches_associados', MovCrecheAssociadoController::class);
    Route::delete('mov_creches_associados/{id}', [MovCrecheController::class, 'destroy']); // Soft delete com observação
    Route::patch('mov_creches_associados/{id}/restore', [MovCrecheController::class, 'restore']); // Restaura um registro deletado
    
});


