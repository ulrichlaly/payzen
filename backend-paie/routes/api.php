<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\PaieController;
use App\Http\Controllers\DeclarationController;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LoanController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/dashboard', [UserController::class, 'dashboard']);
    Route::get('/collaborators', [CollaboratorController::class, 'index']);
    Route::put('/profile', [CollaboratorController::class, 'updateProfile']);

    // ✅ ROUTES PRÊTS/AVANCES (COLLABORATEURS) - AVANT MIDDLEWARE
    Route::get('/loans/my-loans', [LoanController::class, 'myLoans']);
    Route::post('/loans', [LoanController::class, 'store']);
    Route::delete('/loans/{id}', [LoanController::class, 'destroy']);

    // ✅ ROUTES NOTES DE FRAIS (COLLABORATEURS)
    Route::get('/expenses/my-expenses', [ExpenseController::class, 'myExpenses']);
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);

    Route::get('/collaborators/{id}/conges', [CongeController::class, 'historique']);
    Route::get('/collaborators/{id}/paies', [PaieController::class, 'historique']);
    Route::apiResource('conges', CongeController::class);

    // Routes Admin
    Route::middleware('role:Admin,Administrateur')->group(function () {
        Route::post('/collaborators', [CollaboratorController::class, 'store']);
        Route::get('/collaborators/{id}', [CollaboratorController::class, 'show']);
        Route::put('/collaborators/{id}', [CollaboratorController::class, 'update']);
        Route::delete('/collaborators/{id}', [CollaboratorController::class, 'destroy']);
        Route::get('/users', [UserController::class, 'index']);
    });

    // Routes Admin et Comptable
    Route::middleware('role:Admin,Administrateur,Comptable')->group(function () {
        // Gestion des paies
        Route::apiResource('paies', PaieController::class);
        Route::post('/paies/generate-auto', [PaieController::class, 'generateAuto']);

        // Gestion des déclarations
        Route::apiResource('declarations', DeclarationController::class);
        Route::post('/declarations/generate-auto', [DeclarationController::class, 'generateAuto']);

        // ✅ ROUTES PRÊTS (ADMIN/COMPTABLE)
        Route::get('/loans', [LoanController::class, 'index']);
        Route::patch('/loans/{id}', [LoanController::class, 'update']);
        Route::post('/loans/{id}/approve', [LoanController::class, 'approve']);
        Route::post('/loans/{id}/reject', [LoanController::class, 'reject']);

        // ✅ ROUTES NOTES DE FRAIS (ADMIN/COMPTABLE)
        Route::get('/expenses', [ExpenseController::class, 'index']);
        Route::patch('/expenses/{id}', [ExpenseController::class, 'update']);
        Route::post('/expenses/{id}/approve', [ExpenseController::class, 'approve']);
        Route::post('/expenses/{id}/reject', [ExpenseController::class, 'reject']);
    });
});
