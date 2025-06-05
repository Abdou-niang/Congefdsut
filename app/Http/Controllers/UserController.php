<?php

namespace App\Http\Controllers;

use App\Mail\conge;
use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    use GenerateApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = User::select('*')->with('privileges.service', 'privileges.cellule', 'demandes.historiquedemandeconges')->orderBy('users.id','desc')->get();
            return $this->successResponse($data, 'Récupération réussie');
        } catch (Exception $e) {
            return $this->errorResponse('Récupération échouée', 500, $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $user = new User();
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->matricule = $request->matricule;
            $user->adresse = $request->adresse;
            $user->telephone = $request->telephone;
            $user->email = $request->email;
            $user->password = $request->password;
            if (User::where('email','=',$request->email)->first()) {
                # code...
                return $this->successResponse($user, 'Un employé avec ce mail exist déja',409);
            }
            $user->save();
            // insert utilisateur_privilege
            $utilisateur_privilege = new UtilisateurPrivilegeController();
            $request_utilisateur_privilege = new Request([
                'id_user' => $user->id,
                'id_privilege' => $request->id_privilege,
                'id_service' => $request->id_service,
                'id_cellule' => $request->id_cellule,
                'status' => 1,
                'date' => now(),
            ]);
            $utilisateur_privilege->store($request_utilisateur_privilege);

            // Préparation du message avec retour à la ligne
            $message = "Dans le cadre de l'application demande de congé (FDSUT), voici vos informations de connexion :\n\n"
                . "Email : {$request->email}\n"
                . "Mot de passe : {$request->password}\n\n"
                . " ,Veuillez accéder à l'application en cliquant sur le bouton ci-dessous et renseigner vos informations.";
            Mail::to($request->email)->send(new conge("Bonjour, {$request->prenom} {$request->nom}", $message));

            return $this->successResponse($user, 'Récupération réussie');
        } catch (Exception $e) {
            return $this->errorResponse('Insertion échouée', 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->matricule = $request->matricule;
            $user->adresse = $request->adresse;
            $user->telephone = $request->telephone;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();
            return $this->successResponse($user, 'Mise à jour réussie');
        } catch (Exception $e) {
            return $this->errorResponse('Mise à jour échouée', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return $this->successResponse($user, 'Suppression réussie');
        } catch (Exception $e) {
            return $this->errorResponse('Suppression échouée', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->successResponse($user, 'Ressource trouvée');
        } catch (Exception $e) {
            return $this->errorResponse('Ressource non trouvée', 404, $e->getMessage());
        }
    }

    /**
     * Get related form details for foreign keys.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getformdetails()
    {
        try {
            $privileges = \App\Models\Privilege::all();
            $services = \App\Models\Service::all();
            $cellules = \App\Models\Cellule::all();
            return $this->successResponse([
                'privileges' => $privileges,
                'services' => $services,
                'cellules' => $cellules,
            ], 'Données du formulaire récupérées avec succès');
        } catch (Exception $e) {
            return $this->errorResponse('Erreur lors de la récupération des données du formulaire', 500, $e->getMessage());
        }
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'mot_de_passe' => 'required|string',
        ]);

        try {

            // $user = User::where('email', $request->email)->first();
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->mot_de_passe, $user->password)) {
                return response()->json([
                    'status_code' => 401,
                    'status_message' => 'telephone ou mot de passe incorrect.'
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            $user = User::select('*', 'privileges.nom as nom_privilege', 'users.nom as nom_user')->join('utilisateur_privileges', 'id_user', '=', 'users.id')->join('privileges', 'id_privilege', '=', 'privileges.id')->where('email', $request->email)->first();
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Connexion réussie',
                'data' => $user,
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la connexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Déconnexion réussie'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la déconnexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
