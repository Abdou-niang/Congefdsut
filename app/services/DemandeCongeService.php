<?php

namespace App\Services;

use App\Models\DemandeConge;
use App\Models\User;

class DemandeCongeService
{
    public static function getDemandesVisiblePar(User $user)
    {
        // Si l'utilisateur est admin (id_privilege = 1), il peut tout voir
        $isAdmin = $user->privileges()->where('id_privilege', 1)->exists();
        if ($isAdmin) {
            // return DemandeConge::with('typeconge')->get();
            // return DemandeConge::select('*','demande_conges.id as id_demande_conge')->join('type_conges', 'id_typeconge', '=', 'type_conges.id')->leftJoin('historique_demande_conges', 'id_demandeconge', '=', 'demande_conges.id')->get();
            return DemandeConge::select('demande_conges.*', 'demande_conges.id as id_demande_conge', 'type_conges.libelle as type_conge_nom')     ->join('type_conges', 'demande_conges.id_typeconge', '=', 'type_conges.id')
            ->with('historiquedemandeconges')->orderBy('demande_conges.created_at','desc')->get();
        }

        // Construction de la requête dynamique
        $query = DemandeConge::select('demande_conges.*', 'demande_conges.id as id_demande_conge', 'type_conges.libelle as type_conge_nom')
            ->join('type_conges', 'demande_conges.id_typeconge', '=', 'type_conges.id')
            ->with('historiquedemandeconges');

        foreach ($user->privileges as $priv) {
            switch ($priv->id_privilege) {
                case 2: // RH
                    $query->orWhereHas('historiquedemandeconges', function ($q) {
                        $q->where('decision', 'approuvée')
                            ->whereHas('user.privileges', function ($qq) {
                                $qq->where('id_privilege', 3); // Chef de service
                            });
                    });
                    break;

                case 3: // Chef de service
                    $query->orWhere(function ($q) use ($priv) {
                        $q->whereHas('historiquedemandeconges', function ($hq) {
                            $hq->where('decision', 'approuvée');
                        })->whereHas('user.privileges', function ($pq) use ($priv) {
                            $pq->where('id_service', $priv->id_service);
                        });
                    });
                    break;

                case 4: // Chef de cellule
                    $query->orWhereHas('user.privileges', function ($q) use ($priv) {
                        $q->where('id_cellule', $priv->id_cellule);
                    });
                    break;

                default: // Employé
                    $query->orWhere('id_user', $user->id);
                    break;
            }
        }


        return $query->orderBy('demande_conges.created_at','desc')->get();
    }
}
