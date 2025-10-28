<?php
require_once __DIR__ . '/config/config.php';

use App\GoogleOAuth;
use App\GitHubOAuth;

// Obtener el proveedor
$provider = $_GET['provider'] ?? null;

if (!$provider || !in_array($provider, ['google', 'github'])) {
    showError('Proveedor de autenticación no válido');
    redirect('index.php');
}

try {
    if ($provider === 'google') {
        $oauth = new GoogleOAuth();
        $authUrl = $oauth->getAuthUrl();
    } else if ($provider === 'github') {
        $oauth = new GitHubOAuth();
        $authUrl = $oauth->getAuthUrl();
    }

    // Guardar el proveedor en la sesión
    $_SESSION['oauth_provider'] = $provider;

    // Redirigir a la URL de autenticación
    redirect($authUrl);

} catch (Exception $e) {
    showError('Error al iniciar la autenticación: ' . $e->getMessage());
    redirect('index.php');
}
