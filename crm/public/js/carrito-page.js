const urlCarrito = '/Carniceria/crm/public/api/carrito.php';

// formateo el precio con coma en vez de punto porque en España se usa así
function formatearPrecio(valor) {
    return valor.toFixed(2).replace('.', ',') + ' €';
}

// recorro todos los productos del carrito y sumo para sacar el total
function recalcularTotal() {
    let total = 0;
    document.querySelectorAll('.carrito-item').forEach(function(fila) {
        let id = fila.id.replace('item-', '');
        let precio = parseFloat(fila.dataset.precio);
        let cantTexto = document.getElementById('cant-' + id).textContent.replace(',', '.');
        let cantidad = parseFloat(cantTexto);
        total += precio * cantidad;
    });
    document.getElementById('total-valor').textContent = formatearPrecio(total);
}

function pasoDeItem(id) {
    let fila = document.getElementById('item-' + id);
    return fila ? parseFloat(fila.dataset.paso || 1) : 1;
}

function mostrarCantidad(id, valor) {
    let paso = pasoDeItem(id);
    let el = document.getElementById('cant-' + id);
    el.textContent = paso < 1 ? valor.toFixed(2).replace('.', ',') : Math.round(valor);
}

// actualiza el numerito que sale en el icono del carrito del menú
function actualizarBadge(num) {
    let badge = document.getElementById('cart-count');
    if (badge) {
        badge.textContent = num > 0 ? num : '';
    }
}

// suma una unidad/kg/etc al producto del carrito
async function agregarMas(id, idProducto, paso) {
    paso = paso || pasoDeItem(id);
    let formData = new FormData();
    formData.append('_action', 'agregar');
    formData.append('id_producto', idProducto);
    formData.append('incremento', paso);

    try {
        let respuesta = await fetch(urlCarrito, { method: 'POST', body: formData });
        let resultado = await respuesta.json();

        if (resultado.ok) {
            let cantTexto = document.getElementById('cant-' + id).textContent.replace(',', '.');
            let nuevaCantidad = parseFloat(cantTexto) + paso;
            mostrarCantidad(id, nuevaCantidad);

            document.getElementById('menos-' + id).style.display = '';

            let precio = parseFloat(document.getElementById('item-' + id).dataset.precio);
            document.getElementById('sub-' + id).textContent = formatearPrecio(precio * nuevaCantidad);

            recalcularTotal();
            actualizarBadge(resultado.count);
        }
    } catch (e) {
        console.error('Error al añadir al carrito:', e);
    }
}

// resta una unidad/kg/etc; si llega al mínimo oculta el botón
async function quitarUno(id) {
    let paso = pasoDeItem(id);
    let cantTexto = document.getElementById('cant-' + id).textContent.replace(',', '.');
    let cantidadActual = parseFloat(cantTexto);

    if (cantidadActual <= paso) return;

    // sin esto da 0.30000000000000004 por culpa del float
    let nuevaCantidad = Math.round((cantidadActual - paso) * 1000) / 1000;

    let formData = new FormData();
    formData.append('_action', 'actualizar');
    formData.append('id', id);
    formData.append('cantidad', nuevaCantidad);

    try {
        let respuesta = await fetch(urlCarrito, { method: 'POST', body: formData });
        let resultado = await respuesta.json();

        if (resultado.ok) {
            mostrarCantidad(id, nuevaCantidad);

            if (nuevaCantidad <= paso) {
                document.getElementById('menos-' + id).style.display = 'none';
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
