<?php

namespace App;

use GuzzleHttp\Client;

class GitHubOAuth
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $httpClient;

    public function __construct()
    {
        $this->clientId = $_ENV['GITHUB_CLIENT_ID'];
        $this->clientSecret = $_ENV['GITHUB_CLIENT_SECRET'];
        $this->redirectUri = $_ENV['GITHUB_REDIRECT_URI'];
        $this->httpClient = new Client();
    }

    /**
     * Genera la URL de autorización de GitHub
     * @return string URL de autorización
     */
    public function getAuthUrl()
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => 'user:email',
            'state' => bin2hex(random_bytes(16))
        ];

        $_SESSION['github_state'] = $params['state'];
        
        return 'https://github.com/login/oauth/authorize?' . http_build_query($params);
    }

    /**
     * Obtiene el token de acceso usando el código de autorización
     * @param string $code Código de autorización
     * @return string Token de acceso
     * @throws \Exception Si hay un error al obtener el token
     */
    public function getAccessToken($code)
    {
        try {
            $response = $this->httpClient->post('https://github.com/login/oauth/access_token', [
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'form_params' => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'code' => $code,
                    'redirect_uri' => $this->redirectUri
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            if (isset($data['error'])) {
                throw new \Exception('Error al obtener token: ' . $data['error_description']);
            }
            
            return $data['access_token'];
        } catch (\Exception $e) {
            throw new \Exception('Error en la autenticación con GitHub: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene la información del usuario autenticado
     * @param string $accessToken Token de acceso
     * @return array Información del usuario
     * @throws \Exception Si hay un error al obtener la información
     */
    public function getUserInfo($accessToken)
    {
        try {
            // Obtener información del usuario
            $response = $this->httpClient->get('https://api.github.com/user', [
                'headers' => [
                    'Authorization' => 'token ' . $accessToken,
                    'Accept' => 'application/json'
                ]
            ]);

            $userData = json_decode($response->getBody(), true);

            // Obtener emails del usuario
            $emailResponse = $this->httpClient->get('https://api.github.com/user/emails', [
                'headers' => [
                    'Authorization' => 'token ' . $accessToken,
                    'Accept' => 'application/json'
                ]
            ]);

            $emails = json_decode($emailResponse->getBody(), true);
            $primaryEmail = '';
            
            foreach ($emails as $email) {
                if ($email['primary']) {
                    $primaryEmail = $email['email'];
                    break;
                }
            }

            return [
                'id' => $userData['id'],
                'email' => $primaryEmail ?: $userData['email'],
                'name' => $userData['name'] ?: $userData['login'],
                'picture' => $userData['avatar_url'],
                'username' => $userData['login'],
                'provider' => 'github'
            ];
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener información del usuario: ' . $e->getMessage());
        }
    }
}
