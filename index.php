<?php
// index.php
require_once 'db_config.php';  
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>NetBandera</title>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
    <link rel="stylesheet" href="/public/assets/css/alerta.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-500 min-h-screen flex flex-col">
    <!-- Overlay de verificación de edad -->
    <div id="ageGate">
        <div class="age-overlay">
            <h2 class="overlay-title">+18 - Zona Exclusiva</h2>
            <p class="overlay-text">Estás a punto de ingresar a un área reservada para adultos. Si tienes +18, haz clic en "Ingresar".</p>
            <div class="overlay-buttons">
                <button id="yesBtn" class="btn-ingresar">Ingresar</button>
                <button id="noBtn" class="btn-salir">Salir</button>
            </div>
        </div>
    </div>

    <?php include 'header.php'; ?>

    <main class="flex-1 p-4">
        <?php include 'banderas.php';?>
    </main>

    <div class="flex justify-center my-8">
        <button onclick="openModal()" class="transform hover:scale-102 transition-transform duration-300 cursor-pointer animate-pulse hover:animate-none">
            <img src="/public/assets/img/globo2.png" alt="Imagen descriptiva" class="max-w-full h-auto hover:opacity-95">
        </button>
    </div>

    <!-- Modal de opciones -->
    <div id="optionsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 transform transition-all">
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Selecciona una opción</h3>
                <p class="text-gray-600">¿A dónde te gustaría ir?</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Opción Red Social -->
                <a href="https://redsocial.netbandera.com" target="_blank" 
                   class="relative group bg-gradient-to-br from-gray-900 via-[#FFD700] to-gray-900 p-[2px] rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="bg-gray-900 rounded-2xl p-6 h-full backdrop-blur-xl backdrop-filter">
                        <div class="relative z-10">
                            <div class="bg-gradient-to-br from-[#FFD700] to-yellow-500 rounded-xl p-3 w-16 h-16 mx-auto mb-4 transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                <svg class="w-10 h-10 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold mb-3 text-white group-hover:text-[#FFD700] transition-colors duration-300">Red Social</h4>
                            <p class="text-gray-300 text-sm group-hover:text-gray-200 transition-colors duration-300">Conéctate con otros usuarios</p>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-br from-[#FFD700]/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                    </div>
                </a>

                <!-- Opción Sitio de Videos -->
                <a href="http://yt.netbandera.com" target="_blank" 
                   class="relative group bg-gradient-to-br from-gray-900 via-red-600 to-gray-900 p-[2px] rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="bg-gray-900 rounded-2xl p-6 h-full backdrop-blur-xl backdrop-filter">
                        <div class="relative z-10">
                            <div class="bg-gradient-to-br from-red-600 to-red-500 rounded-xl p-3 w-16 h-16 mx-auto mb-4 transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                <svg class="w-10 h-10 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold mb-3 text-white group-hover:text-red-500 transition-colors duration-300">Videos</h4>
                            <p class="text-gray-300 text-sm group-hover:text-gray-200 transition-colors duration-300">Explora nuestro contenido en video</p>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-br from-red-600/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                    </div>
                </a>
            </div>

            <!-- Botón cerrar -->
            <button onclick="closeModal()" 
                    class="mt-8 w-full bg-gradient-to-br from-gray-900 via-[#FFD700] to-gray-900 p-[2px] rounded-xl group transition-all duration-300 hover:shadow-lg">
                <div class="bg-gray-900 rounded-xl p-2 h-full transition-all duration-300 group-hover:bg-opacity-90">
                    <span class="text-white group-hover:text-[#FFD700] font-medium transition-colors duration-300 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cerrar ventana
                    </span>
                </div>
            </button>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function openModal() {
            document.getElementById('optionsModal').classList.remove('hidden');
            // Añadir clase para animación de entrada
            setTimeout(() => {
                document.getElementById('optionsModal').querySelector('.transform').classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            document.getElementById('optionsModal').classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('optionsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>

    <script src="/public/assets/js/scripts.js"></script>
</body>
</html>