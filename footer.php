<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetBandera - Footer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
    <style>
        /* Opcional: transiciones para el cambio de texto */
        #footer-text {
            transition: opacity 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <footer class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-8 text-center">
        <p id="footer-text" class="text-sm font-medium"></p>
    </footer>
    <script>
        const messages = [
            "&copy; <?php echo date('Y'); ?> NetBandera. Todos los derechos reservados.",
            "Hecho en México",
            "Realizado para Carlos Agustín Franco García"
        ];
        let index = 0;
        const footerText = document.getElementById('footer-text');

        function changeText() {
            // Se aplica una transición de opacidad al cambiar el mensaje
            footerText.style.opacity = 0;
            setTimeout(() => {
                footerText.innerHTML = messages[index];
                footerText.style.opacity = 1;
                index = (index + 1) % messages.length;
            }, 500);
        }
        changeText();
        setInterval(changeText, 4000); // Cambia cada 3 segundos
    </script>
    <script src="js/scripts.js"></script>
</body>
</html>