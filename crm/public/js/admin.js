const urlProductos = '/Carniceria/crm/public/api/productos.php';
const urlPromos = '/Carniceria/crm/public/api/promociones.php';
let overlay = document.getElementById('modal-overlay');
let formulario = document.getElementById('form-producto');
let tituloModal = document.getElementById('modal-titulo');

// abro y cierro el modal
function abrirModal() {
    overlay.hidden = false;
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    overlay.hidden = true;
    document.body.style.overflow = '';
    formulario.reset();
    document.getElementById('grupo-foto-actual').hidden = true;
}

document.getElementById('modal-cerrar').addEventListener('click', cerrarModal);
document.getElementById('btn-cancelar').addEventListener('click', cerrarModal);

// cierro el modal si el usuario hace clic fuera
overlay.addEventListener('click', function(e) {
    if (e.target === overlay) cerrarModal();
});

// abro el modal para crear un producto nuevo
function abrirModalCrear(idSeccion) {
    tituloModal.textContent = 'Nuevo producto';
    document.getElementById('field-action').value = 'crear';
    document.getElementById('field-id').value = '';
    document.getElementById('field-foto-actual').value = '';
    document.getElementById('field-seccion').value = idSeccion;
    document.getElementById('field-disponible').checked = true;
    abrirModal();
}

// abro el modal para editar, primero cargo los datos del producto desde la api
async function abrirModalEditar(id) {
    try {
        let respuesta = await fetch(urlProductos + '?id=' + id);
        let producto = await respuesta.json();
        
        if (!producto) return;
        tituloModal.textContent = 'Editar producto';
        document.getElementById('field-action').value = 'actualizar';
        document.getElementById('field-id').value = producto.id;
        document.getElementById('field-foto-actual').value = producto.foto || '';
        document.getElementById('field-seccion').value = producto.id_seccion;
        document.getElementById('field-nombre').value = producto.nombre;
        document.getElementById('field-descripcion').value = producto.descripcion || '';
        document.getElementById('field-precio').value = producto.precio;
        document.getElementById('field-disponible').checked = parseInt(producto.disponible) === 1;

        let grupoFoto = document.getElementById('grupo-foto-actual');
        if (producto.foto) {
            document.getElementById('texto-foto-actual').textContent = producto.foto;
            grupoFoto.hidden = false;
        } else {
            grupoFoto.hidden = true;
        }
        abrirModal();
    } catch (e) {
        console.error('Error al cargar el producto:', e);
    }
}

// elimina el producto y lo quita del html sin recargar la página
async function eliminarProducto(id, card) {
    if (!confirm('¿Eliminar este producto?')) return;
    let formData = new FormData();
    formData.append('_action', 'eliminar');
    formData.append('id', id);
    
    try {
        let respuesta = await fetch(urlProductos, { method: 'POST', body: formData });
        let resultado = await respuesta.json();

        if (resultado.ok) {
            card.remove();
        } else {
            alert('No se ha podido eliminar el producto.');
        }
    } catch (e) {
        console.error('Error al eliminar:', e);
    }
}

// ── Modal de promociones ─────────────────────────────────────────────────────
let overlayPromo = document.getElementById('modal-overlay-promo');
let formularioPromo = document.getElementById('form-promo');
let tituloModalPromo = document.getElementById('modal-titulo-promo');

function abrirModalPromo() {
    overlayPromo.hidden = false;
    document.body.style.overflow = 'hidden';
}

function cerrarModalPromo() {
    overlayPromo.hidden = true;
    document.body.style.overflow = '';
    formularioPromo.reset();
}

document.getElementById('modal-cerrar-promo').addEventListener('click', cerrarModalPromo);
document.getElementById('btn-cancelar-promo').addEventListener('click', cerrarModalPromo);

overlayPromo.addEventListener('click', function(e) {
    if (e.target === overlayPromo) cerrarModalPromo();
});

// abro el modal para añadir una oferta nueva a un producto
function abrirModalCrearPromo(idProducto) {
    tituloModalPromo.textContent = 'Añadir oferta';
    document.getElementById('field-promo-action').value = 'crear';
    document.getElementById('field-promo-id').value = '';
    document.getElementById('field-promo-id-producto').value = idProducto;
    abrirModalPromo();
}

// cargo los datos de la promoción existente para editarla
async function abrirModalEditarPromo(idProducto, idPromo) {
    try {
        let respuesta = await fetch(urlPromos + '?id_producto=' + idProducto);
        let promo = await respuesta.json();

        if (!promo) return;

        tituloModalPromo.textContent = 'Editar oferta';
        document.getElementById('field-promo-action').value = 'actualizar';
        document.getElementById('field-promo-id').value = idPromo;
        document.getElementById('field-promo-id-producto').value = idProducto;
        document.getElementById('field-promo-precio').value = promo.precio_promocional;
        document.getElementById('field-promo-inicio').value = promo.fecha_inicio || '';
        document.getElementById('field-promo-fin').value = promo.fecha_fin || '';
        abrirModalPromo();
    } catch (e) {
        console.error('Error al cargar la oferta:', e);
    }
}

// elimina la promoción y recarga para que desaparezca el badge y el precio tachado
async function quitarPromo(id) {
    if (!confirm('¿Quitar la oferta de este producto?')) return;

    let formData = new FormData();
    formData.append('_action', 'eliminar');
    formData.append('id', id);

    try {
        let respuesta = await fetch(urlPromos, { method: 'POST', body: formData });
        let resultado = await respuesta.json();

        if (resultado.ok) {
            location.reload();
        } else {
            alert('No se ha podido quitar la oferta.');
        }
    } catch (e) {
        console.error('Error al quitar la oferta:', e);
    }
}

formularioPromo.addEventListener('submit', async function(e) {
    e.preventDefault();
    let formData = new FormData(formularioPromo);

    try {
        let respuesta = await fetch(urlPromos, { method: 'POST', body: formData });
        let resultado = await respuesta.json();

        if (resultado.ok) {
            cerrarModalPromo();
            location.reload();
        } else {
            alert('Error al guardar la oferta.');
        }
    } catch (e) {
        console.error('Error al guardar la oferta:', e);
    }
});

// guardo el formulario (crear o editar) y recargo para ver los cambios
formulario.addEventListener('submit', async function(e) {
    e.preventDefault();
    let formData = new FormData(formulario);
    if (!formData.get('disponible')) formData.set('disponible', '0');
    try {
        let respuesta = await fetch(urlProductos, { method: 'POST', body: formData });
        let resultado = await respuesta.json();

        if (resultado.ok) {
            cerrarModal();
            location.reload();
        } else {
            alert('Error al guardar el producto.');
        }
    } catch (e) {
        console.error('Error al guardar:', e);
    }
});
