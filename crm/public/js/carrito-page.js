const urlCarrito = '/Carniceria/crm/public/api/carrito.php';

// formateo el precio con coma en vez de punto porque en España se usa así
function formatearPrecio(valor) {
    return valor.toFixed(2).replace('.', ',') + ' €';
}

// recorro todos los productos del carrito y sumo para sacar el total
function recalcularTotal() {
    let total = 0;
    let filas = document.querySelectorAll('.carrito-item');

    filas.forEach(function(fila) {
        let id = fila.id.replace('item-', '');
        let precio = parseFloat(fila.dataset.precio);
        let cantidad = parseInt(document.getElementById('cant-' + id).textContent);
        total += precio * cantidad;
    });

    document.getElementById('total-valor').textContent = formatearPrecio(total);
}

// actualiza el numerito que sale en el icono del carrito del menú
function actualizarBadge(num) {
    let badge = document.getElementById('cart-count');
    if (badge) {
        badge.textContent = num > 0 ? num : '';
    }
}

// cuando el usuario pulsa +1 kg mando la petición a la api y actualizo la vista
async function agregarMas(id, idProducto) {
    let formData = new FormData();
    formData.append('_action', 'agregar');
    formData.append('id_producto', idProducto);

    try {
        let respuesta = await fetch(urlCarrito, { method: 'POST', body: formData });
        let resultado = await respuesta.json();

        if (resultado.ok) {
            let cantidadEl = document.getElementById('cant-' + id);
            let nuevaCantidad = parseInt(cantidadEl.textContent) + 1;
            cantidadEl.textContent = nuevaCantidad;

            // solo muestro el botón de quitar si hay más de 1 kg
            let botonMenos = document.getElementById('menos-' + id);
            if (nuevaCantidad >= 2) {
                botonMenos.style.display = '';
            }

            let precio = parseFloat(document.getElementById('item-' + id).dataset.precio);
            document.getElementById('sub-' + id).textContent = formatearPrecio(precio * nuevaCantidad);

            recalcularTotal();
            actualizarBadge(resultado.count);
        }
    } catch (e) {
        console.error('Error al añadir al carrito:', e);
    }
}

// quita 1 kg, si llega a 1 escondo el botón para que no baje de ahí
async function quitarUno(id) {
    let cantidadEl = document.getElementById('cant-' + id);
    let cantidadActual = parseInt(cantidadEl.textContent);

    if (cantidadActual <= 1) return;

    let nuevaCantidad = cantidadActual - 1;

    let formData = new FormData();
    formData.append('_action', 'actualizar');
    formData.append('id', id);
    formData.append('cantidad', nuevaCantidad);

    try {
        let respuesta = await fetch(urlCarrito, { method: 'POST', body: formData });
        let resultado = await respuesta.json();

        if (resultado.ok) {
            cantidadEl.textContent = nuevaCantidad;

            let botonMenos = document.getElementById('menos-' + id);
            if (nuevaCantidad <= 1) {
                botonMenos.style.display = 'none';
            }

            let precio = parseFloat(document.getElementById('item-' + id).dataset.precio);
            document.getElementById('sub-' + id).textContent = formatearPrecio(precio * nuevaCantidad);

            recalcularTotal();
            actualizarBadge(resultado.count);
        }
    } catch (e) {
        console.error('Error al actualizar cantidad:', e);
    }
}

// elimina el producto del carrito y lo quita del html sin recargar
async function eliminarItem(id) {
    let formData = new FormData();
    formData.append('_action', 'eliminar');
    formData.append('id', id);

    try {
        let respuesta = await fetch(urlCarrito, { method: 'POST', body: formData });
        let resultado = await respuesta.json();

        if (resultado.ok) {
            document.getElementById('item-' + id).remove();
            actualizarBadge(resultado.count);
            recalcularTotal();

            // si ya no hay nada recargo para mostrar el mensaje de carrito vacío
            if (document.querySelectorAll('.carrito-item').length === 0) {
                location.reload();
            }
        }
    } catch (e) {
        console.error('Error al eliminar producto:', e);
    }
}

// vacía el carrito entero, primero pregunto por si acaso
async function vaciarCarrito() {
    let confirmar = confirm('¿Seguro que quieres vaciar el carrito?');
    if (!confirmar) return;

    let formData = new FormData();
    formData.append('_action', 'vaciar');

    await fetch(urlCarrito, { method: 'POST', body: formData });
    location.reload();
}
