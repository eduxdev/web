<?php
session_start();
require_once 'db_config.php';

// Detectamos si se ha completado el pago (por ejemplo, mediante ?paid=1 en la URL)
$paid = isset($_GET['paid']) && $_GET['paid'] == 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = $_POST['username'] ?? '';
    $comentario = $_POST['comentario'] ?? '';
    $anuncio    = $_POST['anuncio'] ?? '';

    // Manejo de archivo (imagen o video)
    $media = null;
    $media_type = null;
    if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
        // Tipos permitidos
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

    if (!empty($comentario) || !empty($anuncio) || !empty($media)) {
        $stmt = $pdo->prepare("
            INSERT INTO comentarios_pagados (flag, username, comentario, anuncio, media, media_type)
            VALUES (:flag, :username, :comentario, :anuncio, :media, :media_type)
        ");
        $stmt->execute([
            'flag'         => 'pagado',
            'username'     => $username,
            'comentario'   => $comentario,
            'anuncio'      => $anuncio,
            'media'        => $media,
            'media_type'   => $media_type
        ]);
        header("Location: mostrar_comentarios.php");
        exit();
    } else {
        $error = "Por favor, ingresa al menos un comentario, anuncio o sube un archivo.";
    }
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Recuperar datos guardados en localStorage (si existen)
            if(localStorage.getItem('username')) {
                document.getElementById('username').value = localStorage.getItem('username');
            }
            if(localStorage.getItem('comentario')) {
                document.getElementById('comentario').value = localStorage.getItem('comentario');
            }
            if(localStorage.getItem('anuncio')) {
                document.getElementById('anuncio').value = localStorage.getItem('anuncio');
            }

            // Guardar cambios en localStorage a medida que el usuario escribe
            document.getElementById('username').addEventListener('input', function() {
                localStorage.setItem('username', this.value);
            });
            document.getElementById('comentario').addEventListener('input', function() {
                localStorage.setItem('comentario', this.value);
            });
            document.getElementById('anuncio').addEventListener('input', function() {
                localStorage.setItem('anuncio', this.value);
            });

            // Si el pago se completó, habilitar el botón de "Enviar"
            <?php if($paid): ?>
                document.getElementById('submitBtn').disabled = false;
            <?php else: ?>
                document.getElementById('submitBtn').disabled = true;
            <?php endif; ?>
        });
    </script>
</head>
<body class="min-h-screen bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center px-4">
    <div class="bg-white rounded-lg shadow-2xl p-8 max-w-lg w-full">
        <h1 class="text-4xl font-extrabold text-center mb-6 text-gray-800">Deja tu comentario pagado</h1>
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <!-- Formulario de comentario -->
        <form action="comentarios_pagados.php" method="POST" enctype="multipart/form-data" class="space-y-6">
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
            <!-- Campo de Anuncio -->
            <div>
                <label for="anuncio" class="block text-lg font-medium text-gray-700 mb-2">Anuncio</label>
                <textarea name="anuncio" id="anuncio" rows="2" placeholder="Escribe tu anuncio..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-200"></textarea>
            </div>
            <!-- Campo para subir foto o video -->
            <div>
                <label for="media" class="block text-lg font-medium text-gray-700 mb-2">Sube una foto o video (opcional)</label>
                <input type="file" name="media" id="media" accept="image/*,video/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
            </div>
            <!-- Botón de Enviar (inicialmente deshabilitado) -->
            <div class="flex justify-center">
                <button type="submit" id="submitBtn" disabled
                        class="bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-all duration-300 transform">
                    Enviar
                </button>
            </div>
        </form>
        <!-- Botón de Pagar (se muestra solo si aún no se realizó el pago) -->
        <?php if(!$paid): ?>
        <form method="post" action="checkout.php" target="_blank" class="mt-4 flex justify-center">
            <button type="submit"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Pagar
            </button>
        </form>
        <?php endif; ?>
        <!-- Enlace para volver -->
        <div class="mt-6 text-center">
            <a href="home.php" class="text-green-600 hover:text-green-800 font-medium transition duration-200">
                Volver a la página principal
            </a>
        </div>
    </div>
</body>
</html>
