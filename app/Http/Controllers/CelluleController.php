<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Cellule;
use Exception;

class CelluleController extends Controller
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
            $data = Cellule::all();
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
            $cellule = new Cellule();
            $cellule->libelle = $request->libelle;
            $cellule->description = $request->description;
            $cellule->id_service = $request->id_service;
            $cellule->save();
                return $this->successResponse($cellule, 'Récupération réussie');
            
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
            $cellule = Cellule::findOrFail($id);
            $cellule->libelle = $request->libelle;
            $cellule->description = $request->description;
            $cellule->id_service = $request->id_service;
            $cellule->save();
                return $this->successResponse($cellule, 'Mise à jour réussie');
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
            $cellule = Cellule::findOrFail($id);
            $cellule->delete();
                return $this->successResponse($cellule, 'Suppression réussie');
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
            $cellule = Cellule::findOrFail($id);
             return $this->successResponse($cellule, 'Ressource trouvée');
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
        $services = \App\Models\Service::all();

            return $this->successResponse([
                'services' => $services
            ], 'Données du formulaire récupérées avec succès');
        } catch (Exception $e) {
            return $this->errorResponse('Erreur lors de la récupération des données du formulaire', 500, $e->getMessage());
        }
    }
}