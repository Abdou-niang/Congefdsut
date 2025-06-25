<?php

namespace App\Http\Controllers;

use App\Traits\GenerateApiResponse;
use App\Services\DemandeCongeService;
use Exception;


class DashboardController extends Controller
{
    use GenerateApiResponse;
    //
    public function get_dashboard()
    {
        try {
            // $data = DemandeConge::select('*')->join('type_conges','id_typeconge','=','type_conges.id')->where('id_user',auth()->user()->id)->get();
            $data = DemandeCongeService::getDemandesVisiblePar(auth()->user());
            $getjoursferiers = new GmailController();

            return $this->successResponse([
                "demandes"=>$data,
                "feries"=>$getjoursferiers->getJoursFerier()
            ], 'Récupération réussie');
        } catch (Exception $e) {
            return $this->errorResponse('Récupération échouée', 500, $e->getMessage());
        }
    }
}
