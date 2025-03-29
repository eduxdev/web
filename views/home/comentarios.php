<?php
require_once 'db_config.php';

// Verificar si se recibió la bandera por GET
$flag = $_GET['flag'] ?? null;
if (!$flag) {
    header("Location: home.php");
    exit();
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $comentario = $_POST['comentario'] ?? '';
    $anuncio = $_POST['anuncio'] ?? '';

    // Validar: al menos comentario o anuncio
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
    <!-- Se incluye la viewport para que responda correctamente en dispositivos -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentario para la bandera <?php echo htmlspecialchars($flag); ?></title>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
</head>
<body class="flex items-center justify-center min-h-screen bg-blue-500">
    <!-- Contenedor central que se adapta a distintos tamaños -->
    <div class="bg-white rounded-lg shadow-lg p-4 max-w-lg w-full space-y-4">
        <h1 class="text-2xl font-bold text-center text-gray-800">
            Comentario para la bandera: <?php echo strtoupper(htmlspecialchars($flag)); ?>
        </h1>
        <?php if (isset($error)): ?>
            <p class="text-red-500 text-center"><?php echo $error; ?></p>
        <?php endif; ?>
        <!-- Formulario responsive -->
        <form action="comentario.php?flag=<?php echo htmlspecialchars($flag); ?>" method="POST" class="space-y-4 flex flex-col">
            <!-- Campo de nombre en línea en pantallas pequeñas a medias -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <label for="username" class="block text-sm font-medium text-gray-600">Nombre (opcional)</label>
                <input type="text" name="username" id="username" placeholder="Tu nombre"
                       class="mt-1 flex-1 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="comentario" class="block text-sm font-medium text-gray-600">Comentario</label>
                <textarea name="comentario" id="comentario" rows="4" placeholder="Escribe tu comentario..."
                          class="mt-1 w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-500"></textarea>
            </div>
            <div>
                <label for="anuncio" class="block text-sm font-medium text-gray-600">Anuncio (gratis)</label>
                <textarea name="anuncio" id="anuncio" rows="2" placeholder="Escribe tu anuncio..."
                          class="mt-1 w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-500"></textarea>
            </div>
            <div class="flex justify-center">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition">
                    Enviar
                </button>
            </div>
        </form>
        <div class="text-center">
            <a href="home.php" class="text-blue-500 hover:text-blue-700 text-sm">
                Volver a la página principal
            </a>
        </div>
    </div>
</body>
</html>