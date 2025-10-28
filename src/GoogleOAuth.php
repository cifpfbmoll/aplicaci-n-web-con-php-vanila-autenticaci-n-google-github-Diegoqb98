<?php

namespace App;

class GoogleOAuth
{
    private $client;
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = $_ENV['GOOGLE_CLIENT_ID'];
        $this->clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
        $this->redirectUri = $_ENV['GOOGLE_REDIRECT_URI'];

        $this->client = new \Google_Client();
        $this->client->setClientId($this->clientId);
        $this->client->setClientSecret($this->clientSecret);
        $this->client->setRedirectUri($this->redirectUri);
        $this->client->addScope("email");
        $this->client->addScope("profile");
    }

    /**
     * Genera la URL de autorización de Google
     * @return string URL de autorización
     */
    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Obtiene el token de acceso usando el código de autorización
     * @param string $code Código de autorización
     * @return array Token de acceso
     */
    public function getAccessToken($code)
    {
        return $this->client->fetchAccessTokenWithAuthCode($code);
    }

    /**
     * Obtiene la información del usuario autenticado
     * @param string $accessToken Token de acceso
     * @return array Información del usuario
     */
    public function getUserInfo($accessToken)
    {
        $this->client->setAccessToken($accessToken);
        
        $google_oauth = new \Google_Service_Oauth2($this->client);
        $google_account_info = $google_oauth->userinfo->get();
        
        return [
            'id' => $google_account_info->id,
            'email' => $google_account_info->email,
            'name' => $google_account_info->name,
            'picture' => $google_account_info->picture,
            'verified_email' => $google_account_info->verifiedEmail,
            'provider' => 'google'
        ];
    }

    /**
     * Verifica si el token es válido
     * @return bool True si es válido
     */
    public function isAccessTokenValid()
    {
        return !$this->client->isAccessTokenExpired();
    }
}
