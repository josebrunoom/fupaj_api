<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovFarmaciaController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth.jwt')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //EMPRESAS
    Route::apiResource('empresas', EmpresaController::class);

    //FARMACIAS
    Route::apiResource('farmacias', FarmaciaController::class);

    //MOV FARMACIAS
    Route::apiResource('mov_farmacia', MovFarmaciaController::class);
});


