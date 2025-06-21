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
use App\Models\GmailToken;

class NotificationCongeService
{
    public function notifierApresValidation($historique)
    {
        $demande = DemandeConge::with('user.privileges')->find($historique->id_demandeconge);
        $employe = $demande->user;

        // Refusé → notifier uniquement l'employé
        if ($historique->decision === 'refusée') {
            $this->envoyerMail($employe->email, 'Demande de congé refusée', 'Votre demande de congé a été refusée. Connectez-vous pour plus de détails.');
            return;
        }

        // Approuvée → selon le niveau
        foreach ($historique->user->privileges as $priv) {
            switch ($priv->id_privilege) {
                case 4: // Chef de cellule
                    $chefService = $this->getChefService($employe);
                    if ($chefService) {
                        $this->envoyerMail($chefService->email, 'Nouvelle demande de congé à valider', "Une demande de congé a été approuvée par le chef de cellule. Connectez-vous pour plus de détails.");
                    }
                    break;

                case 3: // Chef de service
                    $rh = $this->getRh();
                    if ($rh) {
                        $this->envoyerMail($rh->email, 'Nouvelle demande de congé à valider RH', "Une demande de congé a été approuvée par le chef de service. Connectez-vous pour plus de détails.");
                    }
                    break;

                case 2: // RH
                    $this->envoyerMail($employe->email, 'Demande de congé approuvée RH', "Votre demande de congé a été définitivement approuvée. Connectez-vous pour plus de détails.");
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
            $this->envoyerMail($chefCellule->email, 'Nouvelle demande de congé à valider', "Une nouvelle demande de congé a été soumise. Connectez-vous pour plus de détails.");
        }
    }

    protected function getChefCellule($employe)
    {
        return User::whereHas('privileges', function ($q) use ($employe) {
            $q->where('id_privilege', 4) // Chef de cellule
                ->where('id_cellule', $employe->privileges->first()->id_cellule ?? null);
        })->first();
    }





    public function envoyerMailViaGmailApi($email, $sujet, $contenu)
    {
        // Récupère le token de la base
        $gmailUser = GmailToken::first(); // ou ->where('email', 'ton-compte@gmail.com')->first()

        if (!$gmailUser) {
            throw new \Exception("Aucun token Gmail trouvé. Connectez-vous via /google/login.");
        }

        // 1. Instancier le Mailable
        $mail = new conge($sujet, $contenu);

        // 2. Générer le HTML
        $html = $mail->render();

        // 3. Initialiser Google Client
        $client = new \Google_Client();
        $client->setAuthConfig(public_path('client_secret_808301309884-8ogik6lcrub2kf4741n5k72e4h2obrsf.apps.googleusercontent.com.json'));
        $client->addScope(\Google_Service_Gmail::GMAIL_SEND);
        $client->setAccessToken($gmailUser->token);

        // 4. Rafraîchir si expiré
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());

            // Sauvegarder le nouveau token
            $gmailUser->token = $client->getAccessToken();
            $gmailUser->save();
        }

        // 5. Préparer le message
        $service = new \Google_Service_Gmail($client);
        $message = new \Google_Service_Gmail_Message();
        $encodedSubject = '=?UTF-8?B?' . base64_encode($sujet) . '?=';
        $rawMessage = "To: $email\r\n";
        $rawMessage .= "Subject: $encodedSubject\r\n";
        $rawMessage .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
        $rawMessage .= $html;

        $encodedMessage = base64_encode($rawMessage);
        $encodedMessage = str_replace(['+', '/', '='], ['-', '_', ''], $encodedMessage);
        $message->setRaw($encodedMessage);

        // 6. Envoi
        $service->users_messages->send('me', $message);
    }
}
