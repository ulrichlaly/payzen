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
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\ProfileController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/dashboard', [UserController::class, 'dashboard']);
    Route::get('/collaborators', [CollaboratorController::class, 'index']);
    Route::put('/profile', [CollaboratorController::class, 'updateProfile']);

    // ✅ ROUTES PHOTO DE PROFIL (Tous les utilisateurs authentifiés)
    Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto']);
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto']);

    // ✅ ROUTE CHANGEMENT MOT DE PASSE (Tous les utilisateurs authentifiés)
    Route::post('/change-password', [ProfileController::class, 'changePassword']);

    // ✅ ROUTES PRÊTS/AVANCES (COLLABORATEURS)
    Route::get('/loans/my-loans', [LoanController::class, 'myLoans']);
    Route::post('/loans', [LoanController::class, 'store']);
    Route::delete('/loans/{id}', [LoanController::class, 'destroy']);

    // ✅ ROUTES NOTES DE FRAIS (COLLABORATEURS)
    Route::get('/expenses/my-expenses', [ExpenseController::class, 'myExpenses']);
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);

    // Routes historiques collaborateurs
    Route::get('/collaborators/{id}/conges', [CongeController::class, 'historique']);
    Route::get('/collaborators/{id}/paies', [PaieController::class, 'historique']);

    // ROUTES PARCOURS PROFESSIONNEL (Tous les utilisateurs authentifiés - lecture + ajout pour collaborateurs)
    Route::get('/collaborators/{id}/parcours', [CollaboratorController::class, 'getParcours']);
    Route::post('/collaborators/{id}/parcours', [CollaboratorController::class, 'addParcours']);

    // ROUTES FORMATIONS (Tous les utilisateurs authentifiés - lecture + ajout pour collaborateurs)
    Route::get('/collaborators/{id}/formations', [CollaboratorController::class, 'getFormations']);
    Route::post('/collaborators/{id}/formations', [CollaboratorController::class, 'addFormation']);

    // ROUTES ÉQUIPEMENTS (Lecture pour tous)
    Route::get('/collaborators/{id}/equipements', [CollaboratorController::class, 'getEquipements']);

    // ✅ ROUTES DOCUMENTS (Consultation - Tous les utilisateurs authentifiés)
    Route::get('/collaborators/{collaboratorId}/documents', [DocumentController::class, 'getByCollaborator']);
    Route::get('/documents/{id}', [DocumentController::class, 'show']);
    Route::get('/documents/{id}/download', [DocumentController::class, 'download']);

    // ✅ ANCIENNES ROUTES CONTRATS (À GARDER pour compatibilité)
    Route::get('/collaborators/{id}/contrat', [CollaboratorController::class, 'getContrat']);
    Route::get('/collaborators/{id}/contrat/download', [CollaboratorController::class, 'downloadContrat']);

    // Routes congés
    Route::apiResource('conges', CongeController::class);

    // Routes Admin
    Route::middleware('role:Admin,Administrateur')->group(function () {
        Route::post('/collaborators', [CollaboratorController::class, 'store']);
        Route::get('/collaborators/{id}', [CollaboratorController::class, 'show']);
        Route::put('/collaborators/{id}', [CollaboratorController::class, 'update']);
        Route::delete('/collaborators/{id}', [CollaboratorController::class, 'destroy']);
        Route::get('/users', [UserController::class, 'index']);

        // ROUTES ENTRETIENS ANNUELS (Admin uniquement)
        Route::get('/collaborators/{id}/entretiens', [CollaboratorController::class, 'getEntretiens']);
        Route::post('/collaborators/{id}/entretiens', [CollaboratorController::class, 'addEntretien']);

        // ROUTES ÉQUIPEMENTS (Ajout - Admin uniquement)
        Route::post('/collaborators/{id}/equipements', [CollaboratorController::class, 'addEquipement']);

        // ✅ ROUTES DOCUMENTS (Upload et suppression - Admin uniquement)
        Route::post('/collaborators/{collaboratorId}/documents', [DocumentController::class, 'store']);
        Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);

        // ✅ ANCIENNES ROUTES CONTRATS (ADMIN UNIQUEMENT - À GARDER pour compatibilité)
        Route::post('/collaborators/{id}/contrat', [CollaboratorController::class, 'uploadContrat']);
        Route::delete('/collaborators/{id}/contrat', [CollaboratorController::class, 'deleteContrat']);
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
