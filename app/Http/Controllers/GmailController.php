<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;

class GmailController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setAuthConfig(public_path('client_secret_808301309884-8ogik6lcrub2kf4741n5k72e4h2obrsf.apps.googleusercontent.com.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->addScope(Google_Service_Gmail::GMAIL_SEND);
        $client->setRedirectUri(env('GMAIL_REDIRECT_URI'));

        $authUrl = $client->createAuthUrl();
        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setAuthConfig(public_path('client_secret_808301309884-8ogik6lcrub2kf4741n5k72e4h2obrsf.apps.googleusercontent.com.json'));
        $client->setRedirectUri(env('GMAIL_REDIRECT_URI'));

        $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));
        session(['gmail_token' => $token]);

        return redirect('/send-gmail');
    }

    public function sendMail()
    {
        $token = session('gmail_token');
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
        $rawMessageString .= "To: destinataire@example.com\r\n";
        $rawMessageString .= "Subject: Test Gmail API\r\n\r\n";
        $rawMessageString .= "Ceci est un message envoyé depuis Laravel via l'API Gmail.";

        $rawMessage = base64_encode($rawMessageString);
        $rawMessage = str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage);

        $message->setRaw($rawMessage);
        $service->users_messages->send('me', $message);

        return "Message envoyé avec succès !";
    }
}

