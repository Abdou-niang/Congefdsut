
<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CelluleController;
use App\Http\Controllers\DemandeCongeController;
use App\Http\Controllers\HistoriqueDemandeCongeController;
use App\Http\Controllers\PrivilegeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TypeCongeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilisateurPrivilegeController;

// Routes pour le contrôleur CelluleController
Route::get('/cellules', [CelluleController::class,'index']);
Route::post('/cellules', [CelluleController::class,'store']);
Route::put('/cellules/{id}', [CelluleController::class,'update']);
Route::delete('/cellules/{id}', [CelluleController::class,'destroy']);
Route::get('/cellules/{id}', [CelluleController::class, 'show'])->where('id', '[0-9]+');
Route::get('/cellules/getformdetails', [CelluleController::class, 'getformdetails']);

// Routes pour le contrôleur DemandeCongeController
Route::get('/demande-conges', [DemandeCongeController::class,'index']);
Route::post('/demande-conges', [DemandeCongeController::class,'store']);
Route::put('/demande-conges/{id}', [DemandeCongeController::class,'update']);
Route::delete('/demande-conges/{id}', [DemandeCongeController::class,'destroy']);
Route::get('/demande-conges/{id}', [DemandeCongeController::class, 'show'])->where('id', '[0-9]+');
Route::get('/demande-conges/getformdetails', [DemandeCongeController::class, 'getformdetails']);

// Routes pour le contrôleur HistoriqueDemandeCongeController
Route::get('/historique-demande-conges', [HistoriqueDemandeCongeController::class,'index']);
Route::post('/historique-demande-conges', [HistoriqueDemandeCongeController::class,'store']);
Route::put('/historique-demande-conges/{id}', [HistoriqueDemandeCongeController::class,'update']);
Route::delete('/historique-demande-conges/{id}', [HistoriqueDemandeCongeController::class,'destroy']);
Route::get('/historique-demande-conges/{id}', [HistoriqueDemandeCongeController::class, 'show'])->where('id', '[0-9]+');
Route::get('/historique-demande-conges/getformdetails', [HistoriqueDemandeCongeController::class, 'getformdetails']);

// Routes pour le contrôleur PrivilegeController
Route::get('/privileges', [PrivilegeController::class,'index']);
Route::post('/privileges', [PrivilegeController::class,'store']);
Route::put('/privileges/{id}', [PrivilegeController::class,'update']);
Route::delete('/privileges/{id}', [PrivilegeController::class,'destroy']);
Route::get('/privileges/{id}', [PrivilegeController::class, 'show'])->where('id', '[0-9]+');
Route::get('/privileges/getformdetails', [PrivilegeController::class, 'getformdetails']);

// Routes pour le contrôleur ServiceController
Route::get('/services', [ServiceController::class,'index']);
Route::post('/services', [ServiceController::class,'store']);
Route::put('/services/{id}', [ServiceController::class,'update']);
Route::delete('/services/{id}', [ServiceController::class,'destroy']);
Route::get('/services/{id}', [ServiceController::class, 'show'])->where('id', '[0-9]+');
Route::get('/services/getformdetails', [ServiceController::class, 'getformdetails']);

// Routes pour le contrôleur TypeCongeController
Route::get('/type-conges', [TypeCongeController::class,'index']);
Route::post('/type-conges', [TypeCongeController::class,'store']);
Route::put('/type-conges/{id}', [TypeCongeController::class,'update']);
Route::delete('/type-conges/{id}', [TypeCongeController::class,'destroy']);
Route::get('/type-conges/{id}', [TypeCongeController::class, 'show'])->where('id', '[0-9]+');
Route::get('/type-conges/getformdetails', [TypeCongeController::class, 'getformdetails']);

// Routes pour le contrôleur UserController
Route::get('/users', [UserController::class,'index']);
Route::post('/users', [UserController::class,'store']);
Route::put('/users/{id}', [UserController::class,'update']);
Route::delete('/users/{id}', [UserController::class,'destroy']);
Route::get('/users/{id}', [UserController::class, 'show'])->where('id', '[0-9]+');
Route::get('/users/getformdetails', [UserController::class, 'getformdetails']);

// Routes pour le contrôleur UtilisateurPrivilegeController
Route::get('/utilisateur-privileges', [UtilisateurPrivilegeController::class,'index']);
Route::post('/utilisateur-privileges', [UtilisateurPrivilegeController::class,'store']);
Route::put('/utilisateur-privileges/{id}', [UtilisateurPrivilegeController::class,'update']);
Route::delete('/utilisateur-privileges/{id}', [UtilisateurPrivilegeController::class,'destroy']);
Route::get('/utilisateur-privileges/{id}', [UtilisateurPrivilegeController::class, 'show'])->where('id', '[0-9]+');
Route::get('/utilisateur-privileges/getformdetails', [UtilisateurPrivilegeController::class, 'getformdetails']);

