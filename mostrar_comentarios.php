<?php
require_once 'db_config.php';

// Array asociativo de países
$paises = [
    // América
    'br' => 'Brasil',
    'ca' => 'Canadá',
    'us' => 'Estados Unidos',
    'mx' => 'México',
    'ar' => 'Argentina',
    've' => 'Venezuela',
    'cu' => 'Cuba',
    'gt' => 'Guatemala',
    'cl' => 'Chile',
    'cr' => 'Costa Rica',
    'co' => 'Colombia',
    'dm' => 'Dominica',
    'ec' => 'Ecuador',
    'hn' => 'Honduras',
    'bo' => 'Bolivia',
    'bs' => 'Bahamas',
    'gy' => 'Guyana',
    'ht' => 'Haití',
    'ni' => 'Nicaragua',
    'jm' => 'Jamaica',
    'pa' => 'Panamá',
    'pe' => 'Perú',
    'do' => 'República Dominicana',
    'kn' => 'San Cristóbal y Nieves',
    'vc' => 'San Vicente y las Granadinas',
    'bz' => 'Belice',
    'tt' => 'Trinidad y Tobago',
    'uy' => 'Uruguay',
    'py' => 'Paraguay',
    
    // Europa
    'es' => 'España',
    'fr' => 'Francia',
    'de' => 'Alemania',
    'it' => 'Italia',
    'gb' => 'Reino Unido',
    'pt' => 'Portugal',
    'al' => 'Albania',
    'ad' => 'Andorra',
    'at' => 'Austria',
    'be' => 'Bélgica',
    'ba' => 'Bosnia y Herzegovina',
    'bg' => 'Bulgaria',
    'hr' => 'Croacia',
    'dk' => 'Dinamarca',
    'sk' => 'Eslovaquia',
    'si' => 'Eslovenia',
    'ee' => 'Estonia',
    'fi' => 'Finlandia',
    'gr' => 'Grecia',
    'hu' => 'Hungría',
    'ie' => 'Irlanda',
    'is' => 'Islandia',
    'lv' => 'Letonia',
    'li' => 'Liechtenstein',
    'lt' => 'Lituania',
    'lu' => 'Luxemburgo',
    'mt' => 'Malta',
    'md' => 'Moldavia',
    'mc' => 'Mónaco',
    'me' => 'Montenegro',
    'no' => 'Noruega',
    'nl' => 'Países Bajos',
    'pl' => 'Polonia',
    'ro' => 'Rumanía',
    'ru' => 'Rusia',
    'sm' => 'San Marino',
    'rs' => 'Serbia',
    'se' => 'Suecia',
    'ch' => 'Suiza',
    'ua' => 'Ucrania',
    'va' => 'Ciudad del Vaticano',
    'by' => 'Bielorrusia',
    'cz' => 'República Checa',
    'mk' => 'Macedonia del Norte',
    
    // Asia
    'cn' => 'China',
    'jp' => 'Japón',
    'kr' => 'Corea del Sur',
    'in' => 'India',
    'id' => 'Indonesia',
    'my' => 'Malasia',
    'ph' => 'Filipinas',
    'sg' => 'Singapur',
    'th' => 'Tailandia',
    'vn' => 'Vietnam',
    'af' => 'Afganistán',
    'sa' => 'Arabia Saudita',
    'am' => 'Armenia',
    'az' => 'Azerbaiyán',
    'bh' => 'Baréin',
    'bd' => 'Bangladés',
    'bt' => 'Bután',
    'ae' => 'Emiratos Árabes Unidos',
    'ge' => 'Georgia',
    'ir' => 'Irán',
    'iq' => 'Irak',
    'il' => 'Israel',
    'jo' => 'Jordania',
    'kz' => 'Kazajistán',
    'kg' => 'Kirguistán',
    'kw' => 'Kuwait',
    'la' => 'Laos',
    'lb' => 'Líbano',
    'mv' => 'Maldivas',
    'mn' => 'Mongolia',
    'mm' => 'Myanmar',
    'np' => 'Nepal',
    'om' => 'Omán',
    'pk' => 'Pakistán',
    'ps' => 'Palestina',
    'qa' => 'Catar',
    'sy' => 'Siria',
    'lk' => 'Sri Lanka',
    'tj' => 'Tayikistán',
    'tl' => 'Timor Oriental',
    'tm' => 'Turkmenistán',
    'tr' => 'Turquía',
    'uz' => 'Uzbekistán',
    'ye' => 'Yemen',
    
    // África
    'za' => 'Sudáfrica',
    'dz' => 'Argelia',
    'ao' => 'Angola',
    'bj' => 'Benín',
    'bw' => 'Botsuana',
    'bf' => 'Burkina Faso',
    'bi' => 'Burundi',
    'cm' => 'Camerún',
    'td' => 'Chad',
    'km' => 'Comoras',
    'cg' => 'Congo',
    'ci' => 'Costa de Marfil',
    'dj' => 'Yibuti',
    'eg' => 'Egipto',
    'er' => 'Eritrea',
    'et' => 'Etiopía',
    'ga' => 'Gabón',
    'gm' => 'Gambia',
    'gh' => 'Ghana',
    'gn' => 'Guinea',
    'gw' => 'Guinea-Bisáu',
    'gq' => 'Guinea Ecuatorial',
    'ke' => 'Kenia',
    'ls' => 'Lesoto',
    'lr' => 'Liberia',
    'ly' => 'Libia',
    'mg' => 'Madagascar',
    'mw' => 'Malaui',
    'ml' => 'Malí',
    'ma' => 'Marruecos',
    'mu' => 'Mauricio',
    'mr' => 'Mauritania',
    'mz' => 'Mozambique',
    'na' => 'Namibia',
    'ne' => 'Níger',
    'ng' => 'Nigeria',
    'ug' => 'Uganda',
    'rw' => 'Ruanda',
    'st' => 'Santo Tomé y Príncipe',
    'sn' => 'Senegal',
    'sc' => 'Seychelles',
    'sl' => 'Sierra Leona',
    'so' => 'Somalia',
    'sd' => 'Sudán',
    'ss' => 'Sudán del Sur',
    'sz' => 'Esuatini',
    'tz' => 'Tanzania',
    'tg' => 'Togo',
    'tn' => 'Túnez',
    'zm' => 'Zambia',
    'zw' => 'Zimbabue',
    
    // Oceanía
    'au' => 'Australia',
    'nz' => 'Nueva Zelanda',
    'fj' => 'Fiyi',
    'pg' => 'Papúa Nueva Guinea',
    'sb' => 'Islas Salomón',
    'vu' => 'Vanuatu',
    'ki' => 'Kiribati',
    'mh' => 'Islas Marshall',
    'fm' => 'Micronesia',
    'nr' => 'Nauru',
    'pw' => 'Palaos',
    'ws' => 'Samoa',
    'to' => 'Tonga',
    'tv' => 'Tuvalu'
];

// Obtener el código del país de la URL
$flag = $_GET['flag'] ?? '';
$nombre_pais = $paises[$flag] ?? 'País Desconocido';

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
    <title>Comentarios - <?php echo htmlspecialchars($nombre_pais); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
    <link rel="stylesheet" href="/public/assets/css/mostrar.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Banner del país -->
        <div class="bg-gradient-to-r from-gray-900 via-[#FFD700] to-gray-900 text-white p-8 rounded-xl shadow-2xl mb-8">
            <div class="flex items-center justify-center space-x-4">
                <img src="https://flagcdn.com/w80/<?php echo strtolower($flag); ?>.png" 
                     alt="Bandera de <?php echo htmlspecialchars($nombre_pais); ?>" 
                     class="w-20 h-auto rounded-lg shadow-lg">
                <h1 class="text-5xl font-bold"><?php echo htmlspecialchars($nombre_pais); ?></h1>
            </div>
        </div>

        <h2 class="text-4xl font-bold text-center text-gray-800 mb-8 animate-fadeIn">Comentarios</h2>
        
        <div class="text-center mb-8">
            <a href="index.php" class="bg-[#FFD700] hover:bg-yellow-500 text-gray-900 font-bold py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a Home
            </a>
        </div>
        
        <?php if ($comentarios_pagados || $comentarios_normales): ?>
            <div class="space-y-12">
                <?php if ($comentarios_pagados): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">Comentarios Destacados</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($comentarios_pagados as $coment): ?>
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                                    <p class="text-lg font-semibold text-[#FFD700] mb-2">
                                        <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                        </svg>
                                        <?php echo htmlspecialchars($coment['username']); ?>
                                    </p>
                                    <p class="text-gray-700 mb-3"><span class="font-medium">Comentario:</span> <?php echo htmlspecialchars($coment['comentario']); ?></p>
                                    <p class="text-gray-700 mb-3"><span class="font-medium">Anuncio:</span> <?php echo htmlspecialchars($coment['anuncio']); ?></p>
                                    <?php if (!empty($coment['media'])): ?>
                                        <div class="mt-4">
                                            <?php if ($coment['media_type'] === 'imagen'): ?>
                                                <img src="<?php echo htmlspecialchars($coment['media']); ?>" alt="Imagen"
                                                     class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition duration-300"
                                                     onclick="openModal(this.src)">
                                            <?php elseif ($coment['media_type'] === 'video'): ?>
                                                <video class="w-full rounded-lg" controls>
                                                    <source src="<?php echo htmlspecialchars($coment['media']); ?>">
                                                    Tu navegador no soporta la etiqueta de video.
                                                </video>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <p class="text-sm text-gray-500 mt-4 italic"><?php echo $coment['fecha']; ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($comentarios_normales): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">Comentarios Recientes</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach (array_slice($comentarios_normales, 0, 6) as $coment): ?>
                                <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition duration-300 border border-gray-200">
                                    <p class="text-lg font-semibold text-gray-800 mb-2">
                                        <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                        </svg>
                                        <?php echo htmlspecialchars($coment['username']); ?>
                                    </p>
                                    <p class="text-gray-700 mb-3"><span class="font-medium">Comentario:</span> <?php echo htmlspecialchars($coment['comentario']); ?></p>
                                    <p class="text-gray-700 mb-3"><span class="font-medium">Anuncio:</span> <?php echo htmlspecialchars($coment['anuncio']); ?></p>
                                    <p class="text-sm text-gray-500 mt-4 italic"><?php echo $coment['fecha']; ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <p class="text-xl text-gray-600">No hay comentarios aún.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal mejorado -->
    <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50" onclick="closeModal()">
        <div class="max-w-4xl mx-auto p-4">
            <button class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300" onclick="closeModal()">&times;</button>
            <img class="max-h-[90vh] max-w-full rounded-lg" id="img-modal-content">
        </div>
    </div>

    <script>
        function openModal(src) {
            var modal = document.getElementById('image-modal');
            var modalImg = document.getElementById('img-modal-content');
            modal.classList.remove('hidden');
            modalImg.src = src;
        }
        
        function closeModal() {
            var modal = document.getElementById('image-modal');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>
