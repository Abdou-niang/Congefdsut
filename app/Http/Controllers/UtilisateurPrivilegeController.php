<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\UtilisateurPrivilege;
use Exception;

class UtilisateurPrivilegeController extends Controller
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
            $data = UtilisateurPrivilege::all();
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
            $utilisateurPrivilege = new UtilisateurPrivilege();
            $utilisateurPrivilege->date = $request->date;
            $utilisateurPrivilege->status = $request->status;
            $utilisateurPrivilege->id_user = $request->id_user;
            $utilisateurPrivilege->id_privilege = $request->id_privilege;
            $utilisateurPrivilege->id_service = $request->id_service;
            $utilisateurPrivilege->id_cellule = $request->id_cellule;
            if ($utilisateurPrivilege->save()) {
                return $this->successResponse($utilisateurPrivilege, 'Récupération réussie');
            }
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
            $utilisateurPrivilege = UtilisateurPrivilege::findOrFail($id);
            $utilisateurPrivilege->date = $request->date;
            $utilisateurPrivilege->status = $request->status;
            $utilisateurPrivilege->id_user = $request->id_user;
            $utilisateurPrivilege->id_privilege = $request->id_privilege;
            $utilisateurPrivilege->id_service = $request->id_service;
            $utilisateurPrivilege->id_cellule = $request->id_cellule;
            if ($utilisateurPrivilege->save()) {
                return $this->successResponse($utilisateurPrivilege, 'Mise à jour réussie');
            }
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
            $utilisateurPrivilege = UtilisateurPrivilege::findOrFail($id);
            if ($utilisateurPrivilege->delete()) {
                return $this->successResponse($utilisateurPrivilege, 'Suppression réussie');
            }
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
            $utilisateurPrivilege = UtilisateurPrivilege::findOrFail($id);
             return $this->successResponse($utilisateurPrivilege, 'Ressource trouvée');
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
        $users = \App\Models\User::all();
        $privileges = \App\Models\Privilege::all();
        $services = \App\Models\Service::all();
        $cellules = \App\Models\Cellule::all();

            return $this->successResponse([
                'users' => $users,
            'privileges' => $privileges,
            'services' => $services,
            'cellules' => $cellules
            ], 'Données du formulaire récupérées avec succès');
        } catch (Exception $e) {
            return $this->errorResponse('Erreur lors de la récupération des données du formulaire', 500, $e->getMessage());
        }
    }
}