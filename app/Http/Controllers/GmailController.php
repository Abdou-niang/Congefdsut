<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;
use Google_Service_Calendar;
use App\Models\GmailToken;

class GmailController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setAuthConfig(public_path('client_secret_808301309884-8ogik6lcrub2kf4741n5k72e4h2obrsf.apps.googleusercontent.com.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->addScope(Google_Service_Gmail::GMAIL_SEND);
        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
        $client->setRedirectUri(env('GMAIL_REDIRECT_URI'));

        $authUrl = $client->createAuthUrl();
        return redirect()->away($authUrl);
    }

    // public function handleGoogleCallback(Request $request)
    // {
    //     $client = new Google_Client();
    //     $client->setAuthConfig(public_path('client_secret_808301309884-8ogik6lcrub2kf4741n5k72e4h2obrsf.apps.googleusercontent.com.json'));
    //     $client->setRedirectUri(env('GMAIL_REDIRECT_URI'));

    //     $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));
    //     session(['gmail_token' => $token]);

    //     return redirect('/send-gmail');
    // }

    public function handleGoogleCallback(Request $request)
    {
        $client = new \Google_Client();
        $client->setAuthConfig(public_path('client_secret_808301309884-8ogik6lcrub2kf4741n5k72e4h2obrsf.apps.googleusercontent.com.json'));
        $client->setRedirectUri(env('GMAIL_REDIRECT_URI'));

        $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

        if (!isset($token['access_token'])) {
            return response()->json(['error' => 'Échec de récupération du token'], 400);
        }

        GmailToken::updateOrCreate(
            ['email' => ""],
            ['token' => $token]
        );

        // return redirect('/send-gmail')->with('success', 'Authentification Gmail réussie.');
        return redirect('/send-gmail');
        // return json_encode([
        //     "message" => "Merci verifier votre base de donnée",
        //     "token" => $token
        // ]);
    }


    public function sendMail()
    {
        // $token = session('gmail_token');
        $token = GmailToken::value('token');
        if (!$token) {
            return redirect('/google/login');
        }

        $client = new Google_Client();
        $client->setAuthConfig(public_path('client_secret_808301309884-8ogik6lcrub2kf4741n5k72e4h2obrsf.apps.googleusercontent.com.json'));
        $client->setAccessToken($token);

        if ($client->isAccessTokenExpired()) {
            return redirect('/google/login');
        }

        $service = new Google_Service_Gmail($client);

        $message = new Google_Service_Gmail_Message();

        $rawMessageString = "From: me\r\n";
        $rawMessageString .= "To: saloumfall45@gmail.com\r\n";
        $rawMessageString .= "Subject: Test Gmail API\r\n\r\n";
        $rawMessageString .= "Ceci est un message envoyé depuis Laravel via l'API Gmail.";

        $rawMessage = base64_encode($rawMessageString);
        $rawMessage = str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage);

        $message->setRaw($rawMessage);
        $service->users_messages->send('me', $message);

        return $token;
    }
    public function getJoursFerier()
    {
        // $token = session('gmail_token');
        $token = GmailToken::value('token');
        if (!$token) {
            return redirect('/google/login');
        }

        $client = new Google_Client();
        $client->setAuthConfig(public_path('client_secret_808301309884-8ogik6lcrub2kf4741n5k72e4h2obrsf.apps.googleusercontent.com.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);

        $client->setAccessToken($token);


        // 4. Rafraîchir si expiré
        // Récupère le token de la base
        $gmailUser = GmailToken::first();
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());

            // Sauvegarder le nouveau token
            $gmailUser->token = $client->getAccessToken();
            $gmailUser->save();
        }

        $service = new Google_Service_Calendar($client);

        // Exemple pour la France
        $calendarId = 'fr.sn#holiday@group.v.calendar.google.com';
        $optParams = array(
            'timeMin' => date('c', strtotime('2025-01-01')),
            'timeMax' => date('c', strtotime('2025-12-31')),
            'singleEvents' => true,
            'orderBy' => 'startTime'
        );
        $results = $service->events->listEvents($calendarId, $optParams);

        $joursFeries = [];
        foreach ($results->getItems() as $event) {
            $joursFeries[] = $event->getStart()->getDate(); // format YYYY-MM-DD
        }
        return json_encode([
            "jours_feries" => $joursFeries
        ]);
    }
}
