
const API = '/Carniceria/crm/app/api/productos.php';

const overlay = document.getElementById('modal-overlay');
const form = document.getElementById('form-producto');
const modalTitulo = document.getElementById('modal-titulo');

function abrirModal() {
    overlay.hidden = false;
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    overlay.hidden = true;
    document.body.style.overflow = '';
    form.reset();
    document.getElementById('grupo-foto-actual').hidden = true;
}

document.getElementById('modal-cerrar').addEventListener('click', cerrarModal);
document.getElementById('btn-cancelar').addEventListener('click', cerrarModal);
overlay.addEventListener('click', e => { if (e.target === overlay) cerrarModal(); });

function abrirModalCrear() {
    modalTitulo.textContent = 'Nuevo producto';
    document.getElementById('field-action').value = 'crear';
    document.getElementById('field-id').value = '';
    document.getElementById('field-foto-actual').value = '';
    document.getElementById('field-seccion').value = window.SECCION_ID;
    document.getElementById('field-disponible').checked = true;
    abrirModal();
}

async function abrirModalEditar(id) {
    const res = await fetch(`${API}?id=${id}`);
    const p = await res.json();
    if (!p) return;

    modalTitulo.textContent = 'Editar producto';
    document.getElementById('field-action').value = 'actualizar';
    document.getElementById('field-id').value = p.id;
    document.getElementById('field-foto-actual').value = p.foto || '';
    document.getElementById('field-seccion').value = p.id_seccion;
    document.getElementById('field-nombre').value = p.nombre;
    document.getElementById('field-descripcion').value = p.descripcion || '';
    document.getElementById('field-precio').value = p.precio;
    document.getElementById('field-disponible').checked = parseInt(p.disponible) === 1;

    const grupoFoto = document.getElementById('grupo-foto-actual');
    if (p.foto) {
        document.getElementById('texto-foto-actual').textContent = p.foto;
        grupoFoto.hidden = false;
    } else {
        grupoFoto.hidden = true;
    }

    abrirModal();
}

async function eliminarProducto(id, card) {
    if (!confirm('¿Eliminar este producto?')) return;

    const fd = new FormData();
    fd.append('_action', 'eliminar');
    fd.append('id', id);

    const res = await fetch(API, { method: 'POST', body: fd });
    const data = await res.json();

    if (data.ok) {
        card.remove();
    } else {
        alert('Error al eliminar el producto.');
    }
}

form.addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData(form);
    if (!fd.get('disponible')) fd.set('disponible', '0');

    const res = await fetch(API, { method: 'POST', body: fd });
    const data = await res.json();

    if (data.ok) {
        cerrarModal();
        location.reload();
    } else {
        alert('Error al guardar el producto.');
    }
});
