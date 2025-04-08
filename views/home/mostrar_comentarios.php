<?php
require_once 'db_config.php';

    // Obtener comentarios pagados solo del último mes
    $stmtPaid = $pdo->query("SELECT * FROM comentarios_pagados WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH) ORDER BY fecha DESC");
    $comentarios_pagados = $stmtPaid->fetchAll(PDO::FETCH_ASSOC);

// Obtener comentarios normales
$stmtNormal = $pdo->query("SELECT * FROM comentarios ORDER BY fecha DESC");
$comentarios_normales = $stmtNormal->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comentarios</title>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
    <link rel="stylesheet" href="/public/assets/css/mostrar.css">
</head>
<body>
    <div class="container">
        <h1 class="animate-fadeIn">Comentarios</h1>
        <!-- Enlace para volver al Home -->
        <div class="text-center mt-8">
            <a href="home.php" class="inline-block">Volver a Home</a>
        </div>
        
        <?php if ($comentarios_pagados || $comentarios_normales): ?>
            <div class="comentarios-section">
                <?php if ($comentarios_pagados): ?>
                    <h2>Comentarios Pagados</h2>
                    <div class="comentarios-grid">
                        <?php foreach ($comentarios_pagados as $coment): ?>
                            <div class="comentario-card">
                                <p class="username">Usuario: <?php echo htmlspecialchars($coment['username']); ?></p>
                                <p><strong>Comentario:</strong> <?php echo htmlspecialchars($coment['comentario']); ?></p>
                                <p><strong>Anuncio:</strong> <?php echo htmlspecialchars($coment['anuncio']); ?></p>
                                <?php if (!empty($coment['media'])): ?>
                                    <p><strong>Medio:</strong>
                                        <?php if ($coment['media_type'] === 'imagen'): ?>
                                            <img src="<?php echo htmlspecialchars($coment['media']); ?>" alt="Imagen"
                                                 style="max-width:200px; cursor:pointer;"
                                                 onclick="openModal(this.src)">
                                        <?php elseif ($coment['media_type'] === 'video'): ?>
                                            <video width="320" height="240" controls>
                                                <source src="<?php echo htmlspecialchars($coment['media']); ?>">
                                                Tu navegador no soporta la etiqueta de video.
                                            </video>
                                        <?php endif; ?>
                                    </p>
                                <?php endif; ?>
                                <p class="fecha"><em><?php echo $coment['fecha']; ?></em></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($comentarios_normales): ?>
                    <h2>Comentarios Normales</h2>
                    <div class="comentarios-grid">
                        <?php foreach (array_slice($comentarios_normales, 0, 6) as $coment): ?>
                            <div class="comentario-card">
                                <p class="username">Usuario: <?php echo htmlspecialchars($coment['username']); ?></p>
                                <p><strong>Comentario:</strong> <?php echo htmlspecialchars($coment['comentario']); ?></p>
                                <p><strong>Anuncio:</strong> <?php echo htmlspecialchars($coment['anuncio']); ?></p>
                                <p class="fecha"><em><?php echo $coment['fecha']; ?></em></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="no-comentarios">No hay comentarios aún.</p>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div id="image-modal" class="modal" onclick="closeModal()">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="img-modal-content">
    </div>

    <script>
        function openModal(src) {
            var modal = document.getElementById('image-modal');
            var modalImg = document.getElementById('img-modal-content');
            modal.style.display = "block";
            modalImg.src = src;
        }
        function closeModal() {
            var modal = document.getElementById('image-modal');
            modal.style.display = "none";
        }
    </script>
</body>
</html>
