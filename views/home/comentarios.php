<?php
require_once 'db_config.php';

$flag = $_GET['flag'] ?? null;
if (!$flag) {
    header("Location: home.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $comentario = $_POST['comentario'] ?? '';
    $anuncio = $_POST['anuncio'] ?? '';

    if (!empty($comentario) || !empty($anuncio)) {
        $stmt = $pdo->prepare("
            INSERT INTO comentarios (flag, username, comentario, anuncio)
            VALUES (:flag, :username, :comentario, :anuncio)
        ");
        $stmt->execute([
            'flag' => $flag,
            'username' => $username,
            'comentario' => $comentario,
            'anuncio' => $anuncio
        ]);
        header("Location: home.php");
        exit();
    } else {
        $error = "Por favor, ingresa al menos un comentario o anuncio.";
    }
}
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
    <div class="bg-white rounded-lg shadow-2xl p-8 max-w-lg w-full">
        <h1 class="text-4xl font-extrabold text-center mb-6 text-gray-800">Deja tu comentario</h1>
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form action="comentarios.php?flag=<?php echo htmlspecialchars($flag); ?>" method="POST" class="space-y-6">
            <!-- Campo de Nombre -->
            <div>
                <label for="username" class="block text-lg font-medium text-gray-700 mb-2">Nombre (opcional)</label>
                <input type="text" name="username" id="username" placeholder="Tu nombre"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            </div>
            <!-- Campo de Comentario -->
            <div>
                <label for="comentario" class="block text-lg font-medium text-gray-700 mb-2">Comentario</label>
                <textarea name="comentario" id="comentario" rows="4" placeholder="Escribe tu comentario..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"></textarea>
            </div>
            <!-- Campo de Anuncio -->
            <div>
                <label for="anuncio" class="block text-lg font-medium text-gray-700 mb-2">Anuncio (gratis)</label>
                <textarea name="anuncio" id="anuncio" rows="2" placeholder="Escribe tu anuncio..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"></textarea>
            </div>

            <div class="flex justify-center space-x-4">
    <!-- Formulario principal para enviar comentario -->
    <form method="post" action="comentarios.php?flag=<?php echo htmlspecialchars($flag); ?>">
        <button type="submit"
                class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-all duration-300 transform hover:scale-105">
            Enviar
        </button>
    </form>

    <!-- Botón de Comentario Pagado (Formulario separado, sin validación) -->
    <form action="comentarios_pagados.php" method="get">
        <button type="submit"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105">
            ¿Quieres dejar un comentario pagado?
        </button>
    </form>
</div>
        <!-- Enlace para volver -->
        <div class="mt-6 text-center">
            <a href="home.php" class="text-blue-600 hover:text-blue-800 font-medium transition duration-200">
                Volver a la página principal
            </a>
        </div>
    </div>
</body>
</html>