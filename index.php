<?php
require_once __DIR__ . '/config/config.php';

// Si ya está autenticado, redirigir al dashboard
if (isAuthenticated()) {
    redirect('dashboard.php');
}

$error = getError();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticación OAuth - Login</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🔐 Autenticación OAuth</h1>
            <p class="subtitle">Inicia sesión con tu cuenta de Google o GitHub</p>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="login-buttons">
                <a href="login.php?provider=google" class="btn btn-google">
                    <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    Iniciar sesión con Google
                </a>

                <a href="login.php?provider=github" class="btn btn-github">
                    <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                    Iniciar sesión con GitHub
                </a>
            </div>

            <div class="info-box">
                <h3>📋 Información del Proyecto</h3>
                <p>Esta aplicación implementa autenticación OAuth 2.0 con Google y GitHub usando PHP vanilla.</p>
                <ul>
                    <li>✅ Flujo completo OAuth 2.0</li>
                    <li>✅ Gestión segura de tokens</li>
                    <li>✅ Obtención de perfil de usuario</li>
                    <li>✅ Manejo de errores</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
