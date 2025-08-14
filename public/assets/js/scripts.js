// Función para establecer una cookie
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/;`;
}

// Función para obtener una cookie
function getCookie(name) {
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        if (cookie.startsWith(name + '=')) {
            return cookie.substring(name.length + 1);
        }
    }
    return null;
}

// Verifica si la cookie "isAdult" está configurada
document.addEventListener('DOMContentLoaded', function () {
    const ageGate = document.getElementById('ageGate');
    if (getCookie('isAdult') === 'true') {
        ageGate.style.display = 'none';
    } else {
        ageGate.classList.add('visible'); // Muestra el overlay si no hay cookie
    }
});

// Si el usuario es mayor de 18, guarda la cookie y oculta el overlay
document.getElementById('yesBtn').addEventListener('click', function() {
    setCookie('isAdult', 'true', 30); // Guarda la cookie por 30 días
    document.getElementById('ageGate').style.display = 'none';
});

// Si no es mayor de 18, redirige a otra página (por ejemplo, Google)
document.getElementById('noBtn').addEventListener('click', function() {
    window.location.href = "https://www.google.com";
});
