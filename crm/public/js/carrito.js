const urlApi = '/Carniceria/crm/public/api/carrito.php';

// cargo el número de productos del carrito al entrar a cualquier página
async function cargarBadge() {
    try {
        let respuesta = await fetch(urlApi);
        if (!respuesta.ok) return;
        let datos = await respuesta.json();
        let badge = document.getElementById('cart-count');
        if (badge) badge.textContent = datos.count > 0 ? datos.count : '';
    } catch (e) {
        // si falla la petición no pasa nada, el carrito sigue funcionando
    }
}

document.addEventListener('DOMContentLoaded', function() {
    cargarBadge();

    // botones "Añadir al carrito" de las páginas del catálogo
    let botones = document.querySelectorAll('.btn-carrito');

    botones.forEach(function(btn) {
        btn.addEventListener('click', async function() {
            let idProducto = btn.dataset.id;
            let selectEl = btn.closest('.carrito-accion') ? btn.closest('.carrito-accion').querySelector('.select-cantidad') : null;
            let incremento = selectEl ? parseFloat(selectEl.value) : 1;

            let formData = new FormData();
            formData.append('_action', 'agregar');
            formData.append('id_producto', idProducto);
            formData.append('incremento', incremento);

            btn.disabled = true;

            try {
                let respuesta = await fetch(urlApi, { method: 'POST', body: formData });
                let datos = await respuesta.json();

                if (datos.ok) {
                    let badge = document.getElementById('cart-count');
                    if (badge) badge.textContent = datos.count;

                    // cambio el texto del botón un momento para dar feedback
                    let textoOriginal = btn.textContent;
                    btn.textContent = '✓ Añadido';
                    setTimeout(function() {
                        btn.textContent = textoOriginal;
                        btn.disabled = false;
                    }, 1500);
                    return;
                }
            } catch (e) {}

            btn.disabled = false;
        });
    });
});
