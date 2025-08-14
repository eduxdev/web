<?php
require_once 'db_config.php';

$flag = $_GET['flag'] ?? null;
if (!$flag) {
    header("Location: index.php");
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-[#FFD700] to-gray-900 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8">
            
            <!-- Sección de comentarios pagados (último mes) -->
            <?php if (count($comentarios_pagados) > 0): ?>
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-center text-gray-800 border-b-2 border-[#FFD700] pb-2">
                        Comentarios Destacados
                    </h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        <?php foreach ($comentarios_pagados as $comentario): ?>
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 shadow-lg transform transition-all duration-300 hover:scale-[1.02]">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 rounded-full bg-[#FFD700] flex items-center justify-center text-gray-800">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                        </svg>
                                    </div>
                                    <p class="ml-3 text-lg font-semibold text-gray-800">
                                        <?php echo htmlspecialchars($comentario['username'] ?: 'Anónimo'); ?>
                                    </p>
                                </div>
                                <?php if (!empty($comentario['comentario'])): ?>
                                    <p class="text-gray-700 mb-4">
                                        <?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($comentario['media'])): ?>
                                    <div class="mt-4">
                                        <?php if ($comentario['media_type'] === 'imagen'): ?>
                                            <div class="relative group">
                                                <img src="<?php echo htmlspecialchars($comentario['media']); ?>" alt="Imagen" 
                                                     class="w-full h-48 object-cover rounded-lg cursor-pointer transition-all duration-300 group-hover:opacity-90"
                                                     onclick="openModal('<?php echo htmlspecialchars($comentario['media']); ?>','imagen')">
                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <span class="bg-black/50 text-white px-4 py-2 rounded-lg">Ver imagen</span>
                                                </div>
                                            </div>
                                        <?php elseif ($comentario['media_type'] === 'video'): ?>
                                            <div class="relative group">
                                                <div class="w-full h-48 bg-gray-200 rounded-lg cursor-pointer overflow-hidden"
                                                     onclick="openModal('<?php echo htmlspecialchars($comentario['media']); ?>','video')">
                                                    <video poster="<?php echo isset($comentario['poster']) ? htmlspecialchars($comentario['poster']) : 'img/video-placeholder.jpg'; ?>" 
                                                           class="w-full h-full object-cover" muted playsinline>
                                                        <source src="<?php echo htmlspecialchars($comentario['media']); ?>" type="video/mp4">
                                                    </video>
                                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/50 transition-all">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M8 5v14l11-7z"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Paginación mejorada -->
                    <div class="flex justify-center items-center space-x-2 mt-8">
                        <?php if ($page > 1): ?>
                            <a href="?flag=<?php echo urlencode($flag); ?>&page=<?php echo $page - 1; ?>" 
                               class="flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Anterior
                            </a>
                        <?php endif; ?>
                        
                        <div class="flex space-x-1">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?flag=<?php echo urlencode($flag); ?>&page=<?php echo $i; ?>" 
                                   class="px-4 py-2 rounded-lg <?php echo ($i == $page) ? 'bg-[#FFD700] text-gray-900 font-bold' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?> transition-colors">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                        </div>

                        <?php if ($page < $total_pages): ?>
                            <a href="?flag=<?php echo urlencode($flag); ?>&page=<?php echo $page + 1; ?>" 
                               class="flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                Siguiente
                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                    <p class="font-medium">Error</p>
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <!-- Formulario principal mejorado -->
            <div class="bg-gray-50 rounded-xl p-6 shadow-lg mb-8">
                <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Deja tu Comentario</h2>
                <form action="comentarios.php?flag=<?php echo htmlspecialchars($flag); ?>" method="POST" class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Nombre (opcional)</label>
                        <input type="text" name="username" id="username" placeholder="Tu nombre" 
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#FFD700] focus:border-transparent transition-all duration-300">
                    </div>
                    
                    <div>
                        <label for="comentario" class="block text-sm font-medium text-gray-700 mb-2">Comentario</label>
                        <textarea name="comentario" id="comentario" rows="4" placeholder="Escribe tu comentario..." 
                                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#FFD700] focus:border-transparent transition-all duration-300"></textarea>
                    </div>
                    
                    <div>
                        <label for="anuncio" class="block text-sm font-medium text-gray-700 mb-2">Anuncio (gratis)</label>
                        <textarea name="anuncio" id="anuncio" rows="3" placeholder="Escribe tu anuncio..." 
                                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#FFD700] focus:border-transparent transition-all duration-300"></textarea>
                    </div>
                    
                    <div class="flex justify-center pt-4">
                        <button type="submit" 
                                class="bg-gradient-to-r from-gray-800 to-gray-900 text-white font-bold px-8 py-3 rounded-lg shadow-lg hover:shadow-xl transform transition-all duration-300 hover:scale-105">
                            Enviar Comentario
                        </button>
                    </div>
                </form>
            </div>

            <!-- Botón para comentarios pagados -->
            <div class="flex justify-center mb-12">
                <form action="comentarios_pagados.php" method="get">
                    <input type="hidden" name="flag" value="<?php echo htmlspecialchars($flag); ?>">
                    <button type="submit" 
                            class="bg-gradient-to-r from-[#FFD700] to-yellow-500 text-gray-900 font-bold px-8 py-3 rounded-lg shadow-lg hover:shadow-xl transform transition-all duration-300 hover:scale-105">
                        ¿Quieres dejar un comentario destacado?
                    </button>
                </form>
            </div>

            <!-- Sección de comentarios normales -->
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 border-b-2 border-gray-200 pb-2">Anuncios Gratis</h2>
                <?php if (count($comentarios) > 0): ?>
                    <div class="grid gap-6 md:grid-cols-2">
                        <?php foreach ($comentarios as $comentario): ?>
                            <div class="bg-gray-50 rounded-lg p-6 shadow transform transition-all duration-300 hover:shadow-md">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                        </svg>
                                    </div>
                                    <p class="ml-3 font-medium text-gray-700">
                                        <?php echo htmlspecialchars($comentario['username'] ?: 'Anónimo'); ?>
                                    </p>
                                </div>
                                <?php if (!empty($comentario['comentario'])): ?>
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-500">Comentario:</p>
                                        <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($comentario['anuncio'])): ?>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Anuncio:</p>
                                        <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($comentario['anuncio'])); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-600">No hay comentarios para esta bandera.</p>
                <?php endif; ?>
            </div>

            <!-- Botón para volver mejorado -->
            <div class="mt-8 flex justify-center">
                <button onclick="window.location.href='index.php'" 
                        class="bg-gray-800 text-white font-bold px-6 py-3 rounded-lg shadow-lg hover:bg-gray-700 transform transition-all duration-300 hover:scale-105 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a la página principal
                </button>
            </div>
        </div>
    </div>

    <!-- Modal mejorado -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-2xl p-4 max-w-4xl max-h-[90vh] overflow-auto mx-4">
            <button onclick="closeModal()" 
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div id="modal-content" class="mt-4"></div>
        </div>
    </div>

    <script>
        function openModal(src, mediaType) {
            const modalContent = document.getElementById('modal-content');
            if (mediaType === 'imagen') {
                modalContent.innerHTML = `<img src="${src}" class="max-w-full h-auto rounded-lg" alt="Imagen ampliada">`;
            } else if (mediaType === 'video') {
                modalContent.innerHTML = `
                    <video controls class="max-w-full h-auto rounded-lg">
                        <source src="${src}" type="video/mp4">
                        Tu navegador no soporta video.
                    </video>`;
            }
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            const modalContent = document.getElementById('modal-content');
            modalContent.innerHTML = ''; // Limpia el contenido al cerrar
        }
    </script>
</body>
</html>