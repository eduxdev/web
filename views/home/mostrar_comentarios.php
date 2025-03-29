<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comentarios</title>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
</head>
<body class="bg-blue-500 text-white">
<?php
require_once 'db_config.php';

// Obtener todos los comentarios, ordenados por fecha descendente
$stmt = $pdo->query("SELECT * FROM comentarios ORDER BY fecha DESC");
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($comentarios):
?>
    <ul class="flex flex-wrap gap-2 justify-center p-2">
        <?php foreach ($comentarios as $coment): ?>
            <li class="flex flex-col w-32 p-2 bg-white text-black rounded shadow">
                <p class="text-xxs">
                    <strong>Bandera:</strong> <?php echo htmlspecialchars(strtoupper($coment['flag'])); ?>
                </p>
                <p class="text-xxs">
                    <strong>Usuario:</strong> <?php echo htmlspecialchars($coment['username']); ?>
                </p>
                <p class="text-xxs">
                    <strong>Comentario:</strong> <?php echo htmlspecialchars($coment['comentario']); ?>
                </p>
                <p class="text-xxs">
                    <strong>Anuncio:</strong> <?php echo htmlspecialchars($coment['anuncio']); ?>
                </p>
                <p class="text-xxs mt-1"><em>Fecha: <?php echo $coment['fecha']; ?></em></p>
            </li>
            
        <?php endforeach; ?>
        
    </ul>
    
<?php
else:
    echo "<p class='p-2'>No hay comentarios aún.</p>";
endif;
?>
</body>
</html>