document.addEventListener('DOMContentLoaded', function() {
    if (!localStorage.getItem('edadConfirmada')) {
        let mayorDe18 = confirm("¿Eres mayor de 18 años?");
        if (!mayorDe18) {
            alert("Lo sentimos, no tienes acceso a este sitio.");
            window.location.href = "https://www.google.com";
        } else {
            localStorage.setItem('edadConfirmada', 'true');
        }
    }
});