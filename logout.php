<?php
require_once __DIR__ . '/config/config.php';

// Destruir la sesión
session_unset();
session_destroy();

// Iniciar nueva sesión para mostrar mensaje
session_start();
$_SESSION['info'] = 'Has cerrado sesión correctamente';

// Redirigir al index
redirect('index.php');
