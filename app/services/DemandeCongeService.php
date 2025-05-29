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
            return DemandeConge::all();
        }

        // Construction de la requête dynamique
        $query = DemandeConge::query();

        foreach ($user->privileges as $priv) {
            switch ($priv->id_privilege) {
                case 2: // RH
                    $query->orWhere('statut_chef_service', 'approuvé');
                    break;

                case 3: // Chef de service
                    $query->orWhere(function ($q) use ($priv) {
                        $q->where('statut_chef_cellule', 'approuvé')
                          ->whereHas('user.privileges', function ($qq) use ($priv) {
                              $qq->where('id_service', $priv->id_service);
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

        return $query->get();
    }
}
