<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\DemandeConge;
use Exception;

class DemandeCongeController extends Controller
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
            $data = DemandeConge::all();
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
            $demandeConge = new DemandeConge();
            $demandeConge->date_debut = $request->date_debut;
            $demandeConge->nombre_jour = $request->nombre_jour;
            $demandeConge->motif = $request->motif;
            $demandeConge->fichier = $request->fichier;
            $demandeConge->id_user = $request->id_user;
            $demandeConge->id_typeconge = $request->id_typeconge;
            if ($demandeConge->save()) {
                return $this->successResponse($demandeConge, 'Récupération réussie');
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
            $demandeConge = DemandeConge::findOrFail($id);
            $demandeConge->date_debut = $request->date_debut;
            $demandeConge->nombre_jour = $request->nombre_jour;
            $demandeConge->motif = $request->motif;
            $demandeConge->fichier = $request->fichier;
            $demandeConge->id_user = $request->id_user;
            $demandeConge->id_typeconge = $request->id_typeconge;
            if ($demandeConge->save()) {
                return $this->successResponse($demandeConge, 'Mise à jour réussie');
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
            $demandeConge = DemandeConge::findOrFail($id);
            if ($demandeConge->delete()) {
                return $this->successResponse($demandeConge, 'Suppression réussie');
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
            $demandeConge = DemandeConge::findOrFail($id);
             return $this->successResponse($demandeConge, 'Ressource trouvée');
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
        $typeconges = \App\Models\Typeconge::all();

            return $this->successResponse([
                'users' => $users,
            'typeconges' => $typeconges
            ], 'Données du formulaire récupérées avec succès');
        } catch (Exception $e) {
            return $this->errorResponse('Erreur lors de la récupération des données du formulaire', 500, $e->getMessage());
        }
    }
}