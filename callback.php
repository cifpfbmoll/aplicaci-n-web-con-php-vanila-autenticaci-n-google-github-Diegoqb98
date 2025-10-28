<?php
require_once __DIR__ . '/config/config.php';

use App\GoogleOAuth;
use App\GitHubOAuth;

// Obtener el código de autorización
$code = $_GET['code'] ?? null;
$provider = $_GET['provider'] ?? $_SESSION['oauth_provider'] ?? null;

if (!$code || !$provider) {
    showError('Código de autorización no recibido o proveedor no válido');
    redirect('index.php');
}

try {
    if ($provider === 'google') {
        $oauth = new GoogleOAuth();
        
        // Obtener el token de acceso
        $token = $oauth->getAccessToken($code);
        
        if (isset($token['error'])) {
            throw new Exception($token['error_description'] ?? 'Error al obtener el token');
        }
        
        // Obtener información del usuario
        $userInfo = $oauth->getUserInfo($token);
        
    } else if ($provider === 'github') {
        $oauth = new GitHubOAuth();
        
        // Verificar el state para prevenir CSRF
        $state = $_GET['state'] ?? null;
        if (!$state || $state !== ($_SESSION['github_state'] ?? null)) {
            throw new Exception('Estado de sesión no válido. Posible ataque CSRF.');
        }
        
        // Obtener el token de acceso
        $accessToken = $oauth->getAccessToken($code);
        
        // Obtener información del usuario
        $userInfo = $oauth->getUserInfo($accessToken);
        $userInfo['access_token'] = $accessToken;
        
    } else {
        throw new Exception('Proveedor no soportado');
    }

    // Guardar información del usuario en la sesión
    $_SESSION['user'] = $userInfo;
    $_SESSION['login_time'] = time();

    // Limpiar variables temporales
    unset($_SESSION['oauth_provider']);
    unset($_SESSION['github_state']);

    // Redirigir al dashboard
    redirect('dashboard.php');

} catch (Exception $e) {
    showError('Error en la autenticación: ' . $e->getMessage());
    redirect('index.php');
}
