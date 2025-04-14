    <?php
    session_start();
    require_once 'db_config.php';

    // Detectamos si se ha completado el pago y cuál opción se eligió
    $paid = isset($_GET['paid']) && $_GET['paid'] == 1;
    $option = isset($_GET['option']) ? $_GET['option'] : null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';

        if ($option === 'comentario') {
            // Para la opción de comentario, se recibe el texto del comentario
            $comentario = $_POST['comentario'] ?? '';
            $stmt = $pdo->prepare("INSERT INTO comentarios_pagados (flag, username, comentario) VALUES ('pagado', :username, :comentario)");
            $stmt->execute([
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
            $stmt = $pdo->prepare("INSERT INTO comentarios_pagados (flag, username, media, media_type) VALUES ('pagado', :username, :media, :media_type)");
            $stmt->execute([
                'username'   => $username,
                'media'      => $media,
                'media_type' => $media_type
            ]);
        }
        header("Location: index.php");
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Deja tu Comentario Pagado</title>
        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
    </head>

    <body class="min-h-screen bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center px-4">
        <div class="bg-white rounded-lg shadow-2xl p-8 max-w-lg w-full">
            <h1 class="text-4xl font-extrabold text-center mb-6 text-gray-800">Deja tu comentario pagado</h1>

            <?php if (!$paid || !$option): ?>
                <!-- Modo selección: antes de pagar se muestran los dos botones -->
                <div class="flex flex-col space-y-4">
                    <p class="text-center text-lg font-medium">Elige una opción:</p>
                    <div class="flex justify-around">
                        <!-- El atributo target="_blank" abre el checkout en una nueva pestaña -->
                        <div class="flex space-x-4">
                            <a href="checkout.php?option=comentario" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Subir Comentario Pagado (MXN 1100)
                            </a>
                            <a href="checkout.php?option=media" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Subir Foto o Video (MXN 7000)
                            </a>
                            
                        </div>
                        
                    </div>
                    <p class="text-center text-sm text-gray-600">Debes pagar para habilitar el formulario</p>
                </div>
                <!-- Enlace para volver a la página principal -->
            <div class="mt-6 text-center">
                <a href="index.php" class="text-blue-600 hover:text-blue-800 font-medium transition duration-200">
                    Volver a la página principal
                </a>
            </div>
        </div>
            <?php else: ?>
                <!-- Una vez pagado, se muestra el formulario según la opción seleccionada -->
                <?php if ($option === 'comentario'): ?>
                    <form action="comentarios_pagados.php?paid=1&option=comentario" method="POST" class="space-y-6">
                        <!-- Campo de Nombre -->
                        <div>
                            <label for="username" class="block text-lg font-medium text-gray-700 mb-2">Nombre (opcional)</label>
                            <input type="text" name="username" id="username" placeholder="Tu nombre"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-200">
                        </div>
                        <!-- Campo de Comentario -->
                        <div>
                            <label for="comentario" class="block text-lg font-medium text-gray-700 mb-2">Comentario</label>
                            <textarea name="comentario" id="comentario" rows="4" placeholder="Escribe tu comentario..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-200"></textarea>
                        </div>
                        <div class="flex justify-center">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-all duration-300">
                                Enviar
                            </button>
                        </div>
                    </form>
                <?php elseif ($option === 'media'): ?>
                    <form action="comentarios_pagados.php?paid=1&option=media" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <!-- Campo de Nombre -->
                        <div>
                            <label for="username" class="block text-lg font-medium text-gray-700 mb-2">Nombre (opcional)</label>
                            <input type="text" name="username" id="username" placeholder="Tu nombre"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-200">
                        </div>
                        <!-- Campo para subir foto o video -->
                        <div>
                            <label for="media" class="block text-lg font-medium text-gray-700 mb-2">Sube una foto o video</label>
                            <input type="file" name="media" id="media" accept="image/*,video/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
                        </div>
                        <div class="flex justify-center">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-all duration-300">
                                Enviar
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            <?php endif; ?>

            
        </div>
    </body>

    </html>