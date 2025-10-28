<?php

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno desde .env
function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception("El archivo .env no existe. Por favor, copia .env.example a .env y configura las credenciales.");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
}

// Cargar el archivo .env
loadEnv(__DIR__ . '/../.env');

// Función para verificar si el usuario está autenticado
function isAuthenticated()
{
    return isset($_SESSION['user']);
}

// Función para obtener el usuario autenticado
function getUser()
{
    return $_SESSION['user'] ?? null;
}

// Función para redireccionar
function redirect($url)
{
    header("Location: $url");
    exit;
}

// Función para mostrar errores
function showError($message)
{
    $_SESSION['error'] = $message;
}

// Función para obtener y limpiar errores
function getError()
{
    $error = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);
    return $error;
}
