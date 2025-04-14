<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetBandera - Header</title>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
    <script src="js/scripts.js"></script>
</head>
<body>
<header class="flex justify-between items-center p-4 bg-gray-900">
    <h1 class="text-3xl font-bold text-[#FFD700]">NetBandera</h1>
    <span id="mexico-text" class="text-white text-sm font-medium">Hecho en México</span>
</header>
<script>
    const mexicoMessages = [
        "Hecho en México",
        "Orgullosamente Mexicano",
        "Calidad Mexicana",
        "Innovación desde México",
        "México - Tierra de Oportunidades",
    ];
    let mexicoIndex = 0;
    const mexicoText = document.getElementById('mexico-text');

    function changeMexicoText() {
        // Se aplica una transición de opacidad al cambiar el mensaje
        mexicoText.style.opacity = 0;
        setTimeout(() => {
            mexicoText.innerHTML = mexicoMessages[mexicoIndex];
            mexicoText.style.opacity = 1;
            mexicoIndex = (mexicoIndex + 1) % mexicoMessages.length;
        }, 500);
    }
    changeMexicoText();
    setInterval(changeMexicoText, 4000); // Cambia cada 4 segundos
</script>
</body>
</html>