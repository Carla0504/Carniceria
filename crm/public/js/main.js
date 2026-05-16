document.querySelector('.menu-toggle').addEventListener('click', function () {
    document.querySelector('.menu').classList.toggle('open');
});

// cambio automático del fondo del hero cada 4 segundos
document.addEventListener('DOMContentLoaded', function () {
    let slides = document.querySelectorAll('.hero-slide');

    if (slides.length < 2) return;

    let indiceActual = 0;

    setInterval(function () {
        slides[indiceActual].style.opacity = '0';
        indiceActual = (indiceActual + 1) % slides.length;
        slides[indiceActual].style.opacity = '1';
    }, 4000);
});
