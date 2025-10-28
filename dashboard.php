<?php
require_once __DIR__ . '/config/config.php';

// Verificar si está autenticado
if (!isAuthenticated()) {
    redirect('index.php');
}

$user = getUser();
$loginTime = $_SESSION['login_time'] ?? time();
$sessionDuration = time() - $loginTime;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OAuth Authentication</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
    <div class="container">
        <div class="card dashboard">
            <div class="header">
                <h1>✅ Autenticación Exitosa</h1>
                <a href="logout.php" class="btn btn-logout">Cerrar Sesión</a>
            </div>

            <div class="user-info">
                <div class="profile-section">
                    <?php if (isset($user['picture'])): ?>
                        <img src="<?php echo htmlspecialchars($user['picture']); ?>" 
                             alt="Profile Picture" 
                             class="profile-picture">
                    <?php endif; ?>
                    
                    <div class="profile-details">
                        <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                        <p class="provider-badge">
                            <?php if ($user['provider'] === 'google'): ?>
                                <span class="badge badge-google">🔵 Google</span>
                            <?php else: ?>
                                <span class="badge badge-github">⚫ GitHub</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">📧 Email:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">🆔 ID de Usuario:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['id']); ?></span>
                    </div>

                    <?php if (isset($user['username'])): ?>
                        <div class="info-item">
                            <span class="info-label">👤 Username:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['username']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($user['verified_email'])): ?>
                        <div class="info-item">
                            <span class="info-label">✉️ Email Verificado:</span>
                            <span class="info-value">
                                <?php echo $user['verified_email'] ? '✅ Sí' : '❌ No'; ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="info-item">
                        <span class="info-label">⏱️ Duración de sesión:</span>
                        <span class="info-value"><?php echo gmdate("H:i:s", $sessionDuration); ?></span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">🕐 Hora de login:</span>
                        <span class="info-value"><?php echo date('d/m/Y H:i:s', $loginTime); ?></span>
                    </div>
                </div>
            </div>

            <div class="info-box">
                <h3>🎉 ¡Autenticación OAuth 2.0 Completada!</h3>
                <p>Has iniciado sesión exitosamente usando OAuth 2.0. Aquí puedes ver toda la información de tu perfil obtenida del proveedor de autenticación.</p>
                
                <h4>Datos técnicos de la sesión:</h4>
                <details>
                    <summary>Ver datos completos del usuario (JSON)</summary>
                    <pre class="json-data"><?php echo json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
                </details>
            </div>
        </div>
    </div>
</body>
</html>
