<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\HistoriqueDemandeConge;
use Exception;

class HistoriqueDemandeCongeController extends Controller
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
            $data = HistoriqueDemandeConge::all();
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
            $historiqueDemandeConge = new HistoriqueDemandeConge();
            $historiqueDemandeConge->niveau_validation = $request->niveau_validation;
            $historiqueDemandeConge->decision = $request->decision;
            $historiqueDemandeConge->commentaire = $request->commentaire;
            $historiqueDemandeConge->date_validation = $request->date_validation;
            $historiqueDemandeConge->id_user = $request->id_user;
            $historiqueDemandeConge->id_demandeconge = $request->id_demandeconge;
            if ($historiqueDemandeConge->save()) {
                return $this->successResponse($historiqueDemandeConge, 'Récupération réussie');
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
            $historiqueDemandeConge = HistoriqueDemandeConge::findOrFail($id);
            $historiqueDemandeConge->niveau_validation = $request->niveau_validation;
            $historiqueDemandeConge->decision = $request->decision;
            $historiqueDemandeConge->commentaire = $request->commentaire;
            $historiqueDemandeConge->date_validation = $request->date_validation;
            $historiqueDemandeConge->id_user = $request->id_user;
            $historiqueDemandeConge->id_demandeconge = $request->id_demandeconge;
            if ($historiqueDemandeConge->save()) {
                return $this->successResponse($historiqueDemandeConge, 'Mise à jour réussie');
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
            $historiqueDemandeConge = HistoriqueDemandeConge::findOrFail($id);
            if ($historiqueDemandeConge->delete()) {
                return $this->successResponse($historiqueDemandeConge, 'Suppression réussie');
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
            $historiqueDemandeConge = HistoriqueDemandeConge::findOrFail($id);
             return $this->successResponse($historiqueDemandeConge, 'Ressource trouvée');
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
        $demandeconges = \App\Models\Demandeconge::all();

            return $this->successResponse([
                'users' => $users,
            'demandeconges' => $demandeconges
            ], 'Données du formulaire récupérées avec succès');
        } catch (Exception $e) {
            return $this->errorResponse('Erreur lors de la récupération des données du formulaire', 500, $e->getMessage());
        }
    }
}