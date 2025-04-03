<?php
// index.php
require_once 'db_config.php';  
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>NetBandera</title>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
    <link rel="stylesheet" href="/public/assets/css/alerta.css">
</head>
<body class="bg-blue-500 min-h-screen flex flex-col">
    <!-- Overlay de verificación de edad -->
    <div id="ageGate">
        <div class="age-overlay">
            <h2 class="overlay-title">+18 - Zona Exclusiva</h2>
            <p class="overlay-text">Estás a punto de ingresar a un área reservada para adultos. Si tienes +18, haz clic en "Ingresar".</p>
            <div class="overlay-buttons">
                <button id="yesBtn" class="btn-ingresar">Ingresar</button>
                <button id="noBtn" class="btn-salir">Salir</button>
            </div>
        </div>
    </div>

    <?php include 'header.php'; ?>

    <main class="flex-1 p-4">
        <?php include 'banderas.php';?>
    </main>

    <div class="flex justify-center my-8">
        <img src="/public/assets/img/globo2.png" alt="Imagen descriptiva" class="max-w-full h-auto">
    </div>

    <?php include 'footer.php'; ?>

    <script src="/public/assets/js/scripts.js"></script>
</body>
</html>