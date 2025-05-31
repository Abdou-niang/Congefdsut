<?php

namespace App\Services;

use App\Mail\Conge;
use App\Models\DemandeConge;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationCongeService
{
    public function notifierApresValidation($historique)
    {
        $demande = DemandeConge::with('user.privileges')->find($historique->id_demandeconge);
        $employe = $demande->user;

        // Refusé → notifier uniquement l'employé
        if ($historique->decision === 'refusée') {
            $this->envoyerMail($employe->email, 'Demande de congé refusée', 'Votre demande de congé a été refusée.');
            return;
        }

        // Approuvée → selon le niveau
        foreach ($historique->user->privileges as $priv) {
            switch ($priv->id_privilege) {
                case 4: // Chef de cellule
                    $chefService = $this->getChefService($employe);
                    if ($chefService) {
                        $this->envoyerMail($chefService->email, 'Nouvelle demande de congé à valider', "Une demande de congé a été approuvée par le chef de cellule.");
                    }
                    break;

                case 3: // Chef de service
                    $rh = $this->getRh();
                    if ($rh) {
                        $this->envoyerMail($rh->email, 'Nouvelle demande de congé à valider RH', "Une demande de congé a été approuvée par le chef de service.");
                    }
                    break;

                case 2: // RH
                    $this->envoyerMail($employe->email, 'Demande de congé approuvée RH', "Votre demande de congé a été définitivement approuvée.");
                    break;
            }
        }
    }

    protected function envoyerMail($email, $sujet, $contenu)
    {
        Mail::to($email)->send(new Conge($sujet, $contenu));
    }

    protected function getChefService($employe)
    {
        return User::whereHas('privileges', function ($q) use ($employe) {
            $q->where('id_privilege', 3)
                ->where('id_service', $employe->privileges->first()->id_service ?? null);
        })->first();
    }

    protected function getRh()
    {
        return User::whereHas('privileges', function ($q) {
            $q->where('id_privilege', 2);
        })->first();
    }
}
