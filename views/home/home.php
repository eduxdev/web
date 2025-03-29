<?php
// index.php
require_once 'db_config.php';  // Por si necesitas la conexión
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>NetBandera</title>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
    <script src="js/scripts.js"></script>
</head>
<body class="bg-blue-500 min-h-screen flex flex-col">
    <?php include 'header.php'; ?>

    <main class="flex-1 p-4">
        <?php include 'banderas.php';?>
    </main>

    <div class="flex justify-center my-8">
        <img src="/public/assets/img/globo2.png" alt="Imagen descriptiva" class="max-w-full h-auto">
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
