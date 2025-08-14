    <?php
    session_start();
    require_once 'db_config.php';

    // Detectamos si se ha completado el pago y cuál opción se eligió
    $paid = isset($_GET['paid']) && $_GET['paid'] == 1;
    $option = isset($_GET['option']) ? $_GET['option'] : null;
    $flag = isset($_GET['flag']) ? $_GET['flag'] : null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $flag = $_POST['flag'] ?? '';

        if ($option === 'comentario') {
            // Para la opción de comentario, se recibe el texto del comentario
            $comentario = $_POST['comentario'] ?? '';
            $stmt = $pdo->prepare("INSERT INTO comentarios_pagados (flag, username, comentario) VALUES (:flag, :username, :comentario)");
            $stmt->execute([
                'flag'      => $flag,
                'username'   => $username,
                'comentario' => $comentario
            ]);
        } elseif ($option === 'media') {
            // Para la opción de foto o video, se procesa la subida del archivo
            $media = null;
            $media_type = null;
            if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/avi'];
                if (in_array($_FILES['media']['type'], $allowed_types)) {
                    $upload_dir = 'uploads/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    $filename = time() . '_' . basename($_FILES['media']['name']);
                    $target_path = $upload_dir . $filename;
                    if (move_uploaded_file($_FILES['media']['tmp_name'], $target_path)) {
                        $media = $target_path;
                        if (strpos($_FILES['media']['type'], 'image') !== false) {
                            $media_type = 'imagen';
                        } elseif (strpos($_FILES['media']['type'], 'video') !== false) {
                            $media_type = 'video';
                        }
                    }
                }
            }
            $stmt = $pdo->prepare("INSERT INTO comentarios_pagados (flag, username, media, media_type) VALUES (:flag, :username, :media, :media_type)");
            $stmt->execute([
                'flag'      => $flag,
                'username'   => $username,
                'media'      => $media,
                'media_type' => $media_type
            ]);
        }
        header("Location: comentarios.php?flag=" . urlencode($flag));
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Deja tu Comentario Pagado</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="min-h-screen bg-gradient-to-br from-gray-900 via-[#FFD700] to-gray-900 py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8">
                <h1 class="text-4xl font-bold text-center mb-8 text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-[#FFD700]">
                    Comentarios Destacados
                </h1>

                <?php if (!$paid || !$option): ?>
                    <!-- Modo selección: antes de pagar se muestran los dos botones -->
                    <div class="space-y-8">
                        <div class="text-center">
                            <p class="text-xl font-medium text-gray-700 mb-2">Elige una opción:</p>
                            <p class="text-sm text-gray-500">Selecciona el tipo de contenido que deseas publicar</p>
                        </div>
                        
                        <div class="grid gap-4 md:grid-cols-2">
                            <a href="checkout.php?option=comentario&flag=<?php echo urlencode($flag); ?>" 
                               class="group relative bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] border border-gray-200">
                                <div class="absolute top-0 right-0 bg-[#FFD700] text-gray-900 px-3 py-1 rounded-bl-lg rounded-tr-xl text-sm font-bold">
                                    MXN 1,100
                                </div>
                                <div class="flex flex-col items-center text-center">
                                    <svg class="w-12 h-12 text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">Comentario Destacado</h3>
                                    <p class="text-gray-600 text-sm">Tu comentario aparecerá en la sección destacada</p>
                                </div>
                            </a>

                            <a href="checkout.php?option=media&flag=<?php echo urlencode($flag); ?>" 
                               class="group relative bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] border border-gray-200">
                                <div class="absolute top-0 right-0 bg-[#FFD700] text-gray-900 px-3 py-1 rounded-bl-lg rounded-tr-xl text-sm font-bold">
                                    MXN 7,000
                                </div>
                                <div class="flex flex-col items-center text-center">
                                    <svg class="w-12 h-12 text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">Foto o Video</h3>
                                    <p class="text-gray-600 text-sm">Comparte contenido multimedia en la sección destacada</p>
                                </div>
                            </a>
                        </div>

                        <p class="text-center text-gray-500 text-sm border-t border-gray-200 pt-6 mt-6">
                            El pago es necesario para habilitar el formulario de publicación
                        </p>
                    </div>

                    <!-- Botón para volver mejorado -->
                    <div class="mt-8 flex justify-center">
                        <a href="comentarios.php?flag=<?php echo urlencode($flag); ?>" 
                           class="inline-flex items-center px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Volver a los comentarios
                        </a>
                    </div>
                <?php else: ?>
                    <?php if ($option === 'comentario'): ?>
                        <form action="comentarios_pagados.php?paid=1&option=comentario" method="POST" class="space-y-6">
                            <input type="hidden" name="flag" value="<?php echo htmlspecialchars($flag); ?>">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Nombre (opcional)</label>
                                <input type="text" name="username" id="username" placeholder="Tu nombre"
                                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#FFD700] focus:border-transparent transition-all duration-300">
                            </div>
                            
                            <div>
                                <label for="comentario" class="block text-sm font-medium text-gray-700 mb-2">Tu Comentario Destacado</label>
                                <textarea name="comentario" id="comentario" rows="6" placeholder="Escribe tu comentario..."
                                          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#FFD700] focus:border-transparent transition-all duration-300"></textarea>
                            </div>

                            <div class="flex justify-center pt-4">
                                <button type="submit"
                                        class="bg-gradient-to-r from-gray-800 to-gray-900 text-white font-bold px-8 py-3 rounded-lg shadow-lg hover:shadow-xl transform transition-all duration-300 hover:scale-105 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Publicar Comentario
                                </button>
                            </div>
                        </form>
                    <?php elseif ($option === 'media'): ?>
                        <form action="comentarios_pagados.php?paid=1&option=media" method="POST" enctype="multipart/form-data" class="space-y-6">
                            <input type="hidden" name="flag" value="<?php echo htmlspecialchars($flag); ?>">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Nombre (opcional)</label>
                                <input type="text" name="username" id="username" placeholder="Tu nombre"
                                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#FFD700] focus:border-transparent transition-all duration-300">
                            </div>

                            <div>
                                <label for="media" class="block text-sm font-medium text-gray-700 mb-2">Sube una foto o video</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#FFD700] transition-colors duration-300">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                                                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="media" class="relative cursor-pointer rounded-md font-medium text-[#FFD700] hover:text-yellow-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#FFD700]">
                                                <span>Sube un archivo</span>
                                                <input id="media" name="media" type="file" accept="image/*,video/*" class="sr-only">
                                            </label>
                                            <p class="pl-1">o arrastra y suelta</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-center pt-4">
                                <button type="submit"
                                        class="bg-gradient-to-r from-gray-800 to-gray-900 text-white font-bold px-8 py-3 rounded-lg shadow-lg hover:shadow-xl transform transition-all duration-300 hover:scale-105 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Publicar Contenido
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </body>

    </html>