<?php

namespace App\Services;

use App\Mail\conge;
use App\Models\DemandeConge;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;

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
        // Mail::to($email)->send(new conge($sujet, $contenu));
        $this->envoyerMailViaGmailApi($email, $sujet, $contenu);
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

    public function notifierCreationDemande(DemandeConge $demande)
    {
        $employe = $demande->user;
        $chefCellule = $this->getChefCellule($employe);

        if ($chefCellule) {
            $this->envoyerMail($chefCellule->email, 'Nouvelle demande de congé à valider', "Une nouvelle demande de congé a été soumise.");
        }
    }

    protected function getChefCellule($employe)
    {
        return User::whereHas('privileges', function ($q) use ($employe) {
            $q->where('id_privilege', 4) // Chef de cellule
                ->where('id_cellule', $employe->privileges->first()->id_cellule ?? null);
        })->first();
    }



    protected function envoyerMailViaGmailApi($email, $sujet, $contenu)
    {
        // 1. Instancier ton Mailable
        $mail = new conge($sujet, $contenu);

        // 2. Générer le HTML du mail
        $html = $mail->render();

        // 3. Utiliser Google API pour envoyer
        $client = new \Google_Client();
        $client->setAuthConfig(public_path('client_secret_808301309884-8ogik6lcrub2kf4741n5k72e4h2obrsf.apps.googleusercontent.com.json'));
        $client->setAccessToken(session('gmail_token'));

        if ($client->isAccessTokenExpired()) {
            return redirect('/google/login');
        }

        $service = new \Google_Service_Gmail($client);
        $message = new \Google_Service_Gmail_Message();

        // Gmail API attend un message "raw" encodé
        $rawMessageString = "To: $email\r\n";
        $rawMessageString .= "Subject: " . $sujet . "\r\n";
        $rawMessageString .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
        $rawMessageString .= $html;

        // Encodage base64 URL-safe
        $raw = base64_encode($rawMessageString);
        $raw = str_replace(['+', '/', '='], ['-', '_', ''], $raw);

        $message->setRaw($raw);

        $service->users_messages->send("me", $message);

        // return "Message envoyé avec Gmail API.";
    }
}
