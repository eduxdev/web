<?php
// db_config.php

// Cargar variables de entorno desde .env
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Ignorar comentarios
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

$host = "localhost";
$dbname = "netbandera";
$user = "root";
$pass = "";

// Configuración de Stripe
$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'] ?? null;

if (!$stripe_secret_key) {
    die('Error: STRIPE_SECRET_KEY no está configurada. Crea un archivo .env con tu clave de Stripe.');
}

// Crear la conexión
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Opciones para el manejo de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}
?>
