
<?php

use App\Mail\conge;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CelluleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeCongeController;
use App\Http\Controllers\HistoriqueDemandeCongeController;
use App\Http\Controllers\PrivilegeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TypeCongeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilisateurPrivilegeController;
use App\Http\Controllers\GmailController;
use Illuminate\Support\Facades\Mail;

// Routes sans middleware
Route::post('/login', [UserController::class, 'login']);

// mail test
// Route::get('/congemail',function(){
//     Mail::to('saloumfall45@gmail.com')->send(new conge('salam',"Ceci est un test de demande de congé"));
// });

// Routes avec middleware
Route::middleware('auth:sanctum')->group(function () {
    // Routes pour le contrôleur CelluleController
    Route::get('/cellules', [CelluleController::class, 'index']);
    Route::post('/cellules', [CelluleController::class, 'store']);
    Route::put('/cellules/{id}', [CelluleController::class, 'update']);
    Route::delete('/cellules/{id}', [CelluleController::class, 'destroy']);
    Route::get('/cellules/{id}', [CelluleController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/cellules/getformdetails', [CelluleController::class, 'getformdetails']);

    // Routes pour le contrôleur DemandeCongeController
    Route::get('/demande_conges', [DemandeCongeController::class, 'index']);
    Route::post('/demande_conges', [DemandeCongeController::class, 'store']);
    Route::post('/demande_conges/{id}', [DemandeCongeController::class, 'update']);
    Route::delete('/demande_conges/{id}', [DemandeCongeController::class, 'destroy']);
    Route::get('/demande_conges/{id}', [DemandeCongeController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/demande_conges/getformdetails', [DemandeCongeController::class, 'getformdetails']);

    // Routes pour le contrôleur HistoriqueDemandeCongeController
    Route::get('/historique_demande_conges', [HistoriqueDemandeCongeController::class, 'index']);
    Route::post('/historique_demande_conges', [HistoriqueDemandeCongeController::class, 'store']);
    Route::put('/historique_demande_conges/{id}', [HistoriqueDemandeCongeController::class, 'update']);
    Route::delete('/historique_demande_conges/{id}', [HistoriqueDemandeCongeController::class, 'destroy']);
    Route::get('/historique_demande_conges/{id}', [HistoriqueDemandeCongeController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/historique_demande_conges/getformdetails', [HistoriqueDemandeCongeController::class, 'getformdetails']);
    Route::get('/historique_demande_conges_by_demande/{id}', [HistoriqueDemandeCongeController::class, 'historique_demande_conges_by_demande']);

    // Routes pour le contrôleur PrivilegeController
    Route::get('/privileges', [PrivilegeController::class, 'index']);
    Route::post('/privileges', [PrivilegeController::class, 'store']);
    Route::put('/privileges/{id}', [PrivilegeController::class, 'update']);
    Route::delete('/privileges/{id}', [PrivilegeController::class, 'destroy']);
    Route::get('/privileges/{id}', [PrivilegeController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/privileges/getformdetails', [PrivilegeController::class, 'getformdetails']);

    // Routes pour le contrôleur ServiceController
    Route::get('/services', [ServiceController::class, 'index']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
    Route::get('/services/{id}', [ServiceController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/services/getformdetails', [ServiceController::class, 'getformdetails']);

    // Routes pour le contrôleur TypeCongeController
    Route::get('/type_conges', [TypeCongeController::class, 'index']);
    Route::post('/type_conges', [TypeCongeController::class, 'store']);
    Route::put('/type_conges/{id}', [TypeCongeController::class, 'update']);
    Route::delete('/type_conges/{id}', [TypeCongeController::class, 'destroy']);
    Route::get('/type_conges/{id}', [TypeCongeController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/type_conges/getformdetails', [TypeCongeController::class, 'getformdetails']);

    // Routes pour le contrôleur UserController
    Route::put('/mot_de_passe_update', [UserController::class, 'mot_de_passe_update']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users/{id}', [UserController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/users/getformdetails', [UserController::class, 'getformdetails']);

    // Routes pour le contrôleur UtilisateurPrivilegeController
    Route::get('/utilisateur_privileges', [UtilisateurPrivilegeController::class, 'index']);
    Route::post('/utilisateur_privileges', [UtilisateurPrivilegeController::class, 'store']);
    Route::put('/utilisateur_privileges/{id}', [UtilisateurPrivilegeController::class, 'update']);
    Route::delete('/utilisateur_privileges/{id}', [UtilisateurPrivilegeController::class, 'destroy']);
    Route::get('/utilisateur_privileges/{id}', [UtilisateurPrivilegeController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/utilisateur_privileges/getformdetails', [UtilisateurPrivilegeController::class, 'getformdetails']);

    // Routes pour la décconnexion
    Route::post('/logout', [UserController::class, 'logout']);

    // Routes pour le dashboard
    Route::get('get_dashboard', [DashboardController::class, 'get_dashboard']);
});


// Mailing
// Route::get('/google/login', [GmailController::class, 'redirectToGoogle']);
// Route::get('/callback', [GmailController::class, 'handleGoogleCallback']);
// Route::get('/send-gmail', [GmailController::class, 'sendMail']);
Route::get('/google/login', [GmailController::class, 'redirectToGoogle']);
Route::get('/callback', [GmailController::class, 'handleGoogleCallback']);
Route::get('/send-gmail', [GmailController::class, 'sendMail']);
Route::get('/jours_feries', [GmailController::class, 'getJoursFerier']);
