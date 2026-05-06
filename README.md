# La Dehesa — Carnicería Online

Aplicación web desarrollada como Trabajo de Fin de Grado del ciclo **DAW (Desarrollo de Aplicaciones Web)**.

El proyecto consiste en la web oficial de **La Dehesa**, carnicería ubicada en la **Calle Seis de Diciembre, Aravaca (Madrid)**. Además de presentar el negocio, la aplicación permite a los clientes consultar el catálogo de productos y calcular el importe de su selección, y al administrador gestionar todo el contenido desde un panel privado.


## Funcionalidades principales

### Autenticación
- Registro e inicio de sesión para clientes.
- Acceso diferenciado para el administrador con panel de gestión.
- Cierre de sesión seguro.
- Inicio de sesión con Google (en evaluación).

### Catálogo de productos
Productos organizados por sección, cada uno con nombre, descripción, precio y fotografía:
- Carnicería
- Charcutería
- Pollería
- Conservas

### Carrito de compra
- Añadir y eliminar productos.
- Cálculo del importe total de la selección.
- Sin tramitación de pedido online — orientado a consulta previa a la visita a la tienda.

### Panel de administración
- Gestión de productos: alta, edición y baja por sección.
- Gestión de pedidos recibidos.

### Promociones
- Sección destacada con ofertas y productos en promoción.
- Gestionable desde el panel de administración.

### Páginas informativas
- Inicio / Sobre nosotros.
- Localización (Calle Seis de Diciembre, Aravaca) con horario de apertura y cierre.
- Galería de imágenes.
- Formulario de contacto.

### Idioma
- Selector de idioma para cambiar toda la interfaz entre **español** e **inglés**.


## Tecnologías utilizadas

| Capa | Tecnología |
|------|-----------|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP 8 / Laravel (en evaluación) |
| Base de datos | MySQL |
| Control de versiones | Git / GitHub |
| Despliegue | Docker |



## Estructura del proyecto

```
Carniceria/
├── app/
│   ├── controllers/       # Lógica de negocio (login, registro, productos...)
│   └── views/             # Plantillas HTML/PHP
├── public/
│   ├── css/               # Hojas de estilo
│   ├── js/                # Scripts del cliente
│   └── img/               # Imágenes de productos
├── index.php              # Punto de entrada
└── README.md
```



## Instalación local

1. Clona el repositorio:
   ```bash
   git clone https://github.com/Carla0504/Carniceria.git
   ```
2. Crea la base de datos MySQL e importa el esquema (pendiente: `database/schema.sql`).
3. Configura las credenciales de BD en el archivo de configuración.
4. Lanza el proyecto con un servidor local (XAMPP, Laragon, etc.) apuntando a la carpeta `Carniceria/`.



## Estado del proyecto

> En desarrollo — TFG DAW curso 2025/2026.

- [x] Autenticación de clientes
- [ ] Catálogo de productos por sección
- [ ] Carrito de compra
- [ ] Apartado de promociones
- [ ] Panel de administración (alta, edición y baja de productos)
- [ ] Selector de idioma (ES / EN)
- [ ] Inicio de sesión con Google (en evaluación)
- [ ] Despliegue con Docker