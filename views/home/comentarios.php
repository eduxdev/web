<?php
require_once 'db_config.php';

$flag = $_GET['flag'] ?? null;
if (!$flag) {
    header("Location: home.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = $_POST['username']  ?? '';
    $comentario = $_POST['comentario'] ?? '';
    $anuncio    = $_POST['anuncio']    ?? '';

    if (!empty($comentario) || !empty($anuncio)) {
        $stmt = $pdo->prepare("
            INSERT INTO comentarios (flag, username, comentario, anuncio)
            VALUES (:flag, :username, :comentario, :anuncio)
        ");
        $stmt->execute([
            'flag'       => $flag,
            'username'   => $username,
            'comentario' => $comentario,
            'anuncio'    => $anuncio
        ]);
        // Redirige a la misma página para evitar el reenvío del formulario
        header("Location: comentarios.php?flag=" . urlencode($flag));
        exit();
    } else {
        $error = "Por favor, ingresa al menos un comentario o anuncio.";
    }
}

// Consulta para obtener los comentarios normales de la bandera actual
$stmt = $pdo->prepare("SELECT username, comentario, anuncio FROM comentarios WHERE flag = :flag ORDER BY id DESC");
$stmt->execute(['flag' => $flag]);
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ---------------------------
// Paginación para comentarios pagados (último mes)
// ---------------------------
$per_page = 5; // Cantidad de comentarios pagados por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page < 1) { $page = 1; }
$offset = ($page - 1) * $per_page;

// Contar el total de comentarios pagados en el último mes
$stmtCount = $pdo->prepare("
    SELECT COUNT(*) 
    FROM comentarios_pagados 
    WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
");
$stmtCount->execute();
$total_paid = (int)$stmtCount->fetchColumn();
$total_pages = ceil($total_paid / $per_page);

// Consulta con LIMIT y OFFSET para obtener comentarios pagados
$stmtPaid = $pdo->prepare("
    SELECT * FROM comentarios_pagados
    WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
    ORDER BY fecha DESC
    LIMIT :offset, :per_page
");
$stmtPaid->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtPaid->bindValue(':per_page', $per_page, PDO::PARAM_INT);
$stmtPaid->execute();
$comentarios_pagados = $stmtPaid->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deja tu Comentario</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center px-4">
    <div class="bg-white rounded-lg shadow-2xl p-4 max-w-lg w-full">
        <h1 class="text-2xl font-bold text-center mb-4 text-gray-800">Deja tu comentario</h1>

        <!-- Sección de comentarios pagados (último mes) sin filtrar por flag -->
        <?php if (count($comentarios_pagados) > 0): ?>
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Comentarios Pagados (Último Mes)</h2>
                <?php foreach ($comentarios_pagados as $comentario): ?>
                    <div class="border rounded p-2 mb-2 shadow-sm">
                        <p class="text-xs text-gray-600">
                            <?php echo htmlspecialchars($comentario['username'] ?: 'Anónimo'); ?>
                            <?php if (!empty($comentario['fecha'])): ?>
                                &mdash; <?php echo htmlspecialchars($comentario['fecha']); ?>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($comentario['comentario'])): ?>
                            <p class="mt-1 text-xs"><?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($comentario['media'])): ?>
                            <div class="mt-1">
                                <?php if ($comentario['media_type'] === 'imagen'): ?>
                                    <!-- Imagen pequeña y responsive; al hacer clic abre el modal -->
                                    <img src="<?php echo htmlspecialchars($comentario['media']); ?>" alt="Imagen" 
                                         class="w-24 md:w-32 lg:w-40 h-auto rounded cursor-pointer"
                                         onclick="openModal('<?php echo htmlspecialchars($comentario['media']); ?>','imagen')">
                                <?php elseif ($comentario['media_type'] === 'video'): ?>
                                    <!-- Video: se muestra un thumbnail con poster (si existe) y un ícono de play -->
                                    <div class="relative w-24 md:w-32 lg:w-40 h-auto bg-gray-200 rounded cursor-pointer"
                                         onclick="openModal('<?php echo htmlspecialchars($comentario['media']); ?>','video')">
                                        <video poster="<?php echo isset($comentario['poster']) ? htmlspecialchars($comentario['poster']) : 'img/video-placeholder.jpg'; ?>" 
                                               class="w-full h-auto rounded" muted playsinline>
                                            <source src="<?php echo htmlspecialchars($comentario['media']); ?>" type="video/mp4">
                                        </video>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="text-xs"><strong>Media:</strong> <?php echo nl2br(htmlspecialchars($comentario['media'])); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Navegación de paginación para comentarios pagados -->
                <div class="flex justify-center space-x-2 mt-2">
                    <?php if ($page > 1): ?>
                        <a href="?flag=<?php echo urlencode($flag); ?>&page=<?php echo $page - 1; ?>" class="px-2 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300">Anterior</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?flag=<?php echo urlencode($flag); ?>&page=<?php echo $i; ?>" class="px-2 py-1 text-xs rounded <?php echo ($i == $page) ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="?flag=<?php echo urlencode($flag); ?>&page=<?php echo $page + 1; ?>" class="px-2 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300">Siguiente</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 rounded mb-2" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Formulario principal para enviar comentario normal -->
        <form action="comentarios.php?flag=<?php echo htmlspecialchars($flag); ?>" method="POST" class="space-y-3">
            <!-- Campo de Nombre -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nombre (opcional)</label>
                <input type="text" name="username" id="username" placeholder="Tu nombre" class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm">
            </div>
            <!-- Campo de Comentario -->
            <div>
                <label for="comentario" class="block text-sm font-medium text-gray-700 mb-1">Comentario</label>
                <textarea name="comentario" id="comentario" rows="3" placeholder="Escribe tu comentario..." class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm"></textarea>
            </div>
            <!-- Campo de Anuncio -->
            <div>
                <label for="anuncio" class="block text-sm font-medium text-gray-700 mb-1">Anuncio (gratis)</label>
                <textarea name="anuncio" id="anuncio" rows="2" placeholder="Escribe tu anuncio..." class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm"></textarea>
            </div>
            <!-- Botón para enviar -->
            <div class="flex justify-center">
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold px-4 py-1 rounded shadow transition-all duration-300 transform hover:scale-105 text-sm">
                    Enviar
                </button>
            </div>
        </form>

        <!-- Botón para acceder a comentarios pagados -->
        <div class="flex justify-center mt-3">
            <form action="comentarios_pagados.php" method="get">
                <input type="hidden" name="flag" value="<?php echo htmlspecialchars($flag); ?>">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold px-4 py-1 rounded shadow transform transition-all duration-300 hover:scale-105 text-sm">
                    ¿Quieres dejar un comentario pagado?
                </button>
            </form>
        </div>

        <!-- Sección de comentarios normales -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Comentarios</h2>
            <?php if (count($comentarios) > 0): ?>
                <?php foreach ($comentarios as $comentario): ?>
                    <div class="border rounded p-4 mb-4 shadow-sm">
                        <p class="text-sm text-gray-600">
                            <?php echo htmlspecialchars($comentario['username'] ?: 'Anónimo'); ?>
                        </p>
                        <?php if (!empty($comentario['comentario'])): ?>
                            <p class="mt-2"><strong>COMENTARIO:</strong> <?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($comentario['anuncio'])): ?>
                            <p class="mt-2"><strong>ANUNCIO:</strong> <?php echo nl2br(htmlspecialchars($comentario['anuncio'])); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-600">No hay comentarios para esta bandera.</p>
            <?php endif; ?>
        </div>

        <!-- Botón para regresar a la página principal -->
        <div class="mt-3 flex justify-center">
            <button onclick="window.location.href='home.php'" class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded shadow transition-all duration-300">
                Volver a la página principal
            </button>
        </div>
    </div>

    <!-- Modal para mostrar imágenes y videos -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="absolute inset-0 bg-black opacity-50" onclick="closeModal()"></div>
        <div class="bg-white rounded p-4 relative max-w-full max-h-full overflow-auto">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700 text-2xl">&times;</button>
            <div id="modal-content"></div>
        </div>
    </div>

    <script>
        function openModal(src, mediaType) {
            var modalContent = document.getElementById('modal-content');
            if (mediaType === 'imagen') {
                modalContent.innerHTML = '<img src="' + src + '" class="max-w-full h-auto rounded">';
            } else if (mediaType === 'video') {
                modalContent.innerHTML = '<video controls class="max-w-full h-auto rounded"><source src="' + src + '" type="video/mp4">Tu navegador no soporta video.</video>';
            }
            document.getElementById('modal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
</body>
</html>
