# La Dehesa — Web para una carnicería

Proyecto Fin de Ciclo · CFGS Desarrollo de Aplicaciones Web (2º curso)
**Autora:** Carla Fernández Alcázar · IES Laguna de Joatzel · Curso 2025/2026

Aplicación web completa para **La Dehesa**, carnicería y charcutería ubicada en la Calle Seis de Diciembre, Aravaca (Madrid). La web permite a los clientes consultar el catálogo por secciones, añadir productos a un carrito orientativo y ver la información del negocio. El administrador gestiona productos y promociones desde el propio catálogo sin necesidad de un panel separado.

---

## Tecnologías utilizadas

| Capa | Tecnología |
|---|---|
| Backend | PHP 8 (MVC sin framework, PDO, sesiones, bcrypt) |
| Frontend | HTML5, CSS3, JavaScript (Fetch API nativa) |
| Base de datos | MySQL 8 |
| Despliegue | Docker + Docker Compose |
| Control de versiones | Git / GitHub |
| Servidor local | XAMPP (Apache + MySQL) |

---

## Funcionalidades implementadas

- **Autenticación** — registro e inicio de sesión de clientes con bcrypt; rol de administrador diferenciado; cierre de sesión
- **Catálogo por secciones** — Carnicería, Charcutería, Pollería y Conservas; cada producto con nombre, descripción, precio y foto
- **Promociones** — precio rebajado con fecha de inicio y fin; badge "Oferta" y precio original tachado visibles en la card
- **Carrito de consulta** — añadir, ajustar cantidad y eliminar productos; total en tiempo real; persistencia en base de datos
- **Panel de administración inline** — crear, editar y eliminar productos y ofertas desde el catálogo mediante modales y Fetch, sin recargar la página
- **Selector de idioma ES/EN** — archivos `lang/es.php` y `lang/en.php`; persiste en sesión
- **Páginas informativas** — inicio con carrusel dinámico, galería, localización con mapa, contacto
- **Diseño responsive** — menú hamburguesa en móvil, layout adaptado a tablet y escritorio
- **Despliegue Docker** — `Dockerfile` + `docker-compose.yml` con Apache, PHP 8.2 y MySQL 8.0

---

## Estructura del proyecto

```
Carniceria/
├── crm/
│   ├── app/
│   │   ├── controllers/        # loginController, registerController, logoutController
│   │   ├── models/             # Producto.php, CarritoItem.php, Promocion.php
│   │   └── views/
│   │       ├── auth/           # login.php, register.php
│   │       ├── layout/         # header.php, footer.php
│   │       ├── catalogo/       # carniceria.php, charcuteria.php, polleria.php, conservas.php
│   │       ├── carrito/        # carrito.php
│   │       ├── admin/          # formulario.php (modal de gestión)
│   │       ├── galeria/        # galeria.php
│   │       └── contacto/       # contacto.php
│   ├── config/
│   │   └── db.php              # Conexión PDO (lee variables de entorno con fallback a XAMPP)
│   ├── database/
│   │   └── BBDD.sql            # Schema completo + datos iniciales
│   ├── lang/
│   │   ├── es.php              # Traducciones en español
│   │   └── en.php              # Traducciones en inglés
│   └── public/
│       ├── api/                # Endpoints REST (carrito.php, productos.php, promociones.php)
│       ├── css/                # index.css, auth.css, catalogo.css, carrito.css...
│       ├── js/                 # main.js, carrito.js, carrito-page.js, admin.js
│       └── img/                # carrusel/, productos/, galeria/
├── Dockerfile
├── docker-compose.yml
├── .env.example
└── README.md
```

---

## Instalación local (XAMPP)

### Requisitos previos

- XAMPP 8.x con los módulos **Apache** y **MySQL** activos
- Navegador web moderno (Chrome, Firefox, Edge o Safari)

### Pasos

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/Carla0504/Carniceria.git
   ```

2. **Copiar en htdocs:**
   La carpeta del proyecto debe quedar en:
   ```
   C:\xampp\htdocs\Carniceria\
   ```

3. **Importar la base de datos:**
   - Iniciar XAMPP y abrir [phpMyAdmin](http://localhost/phpmyadmin)
   - Pestaña **Importar** → seleccionar `crm/database/BBDD.sql` → **Continuar**
   - Se crea automáticamente la base de datos `la_dehesa` con todas las tablas y datos de ejemplo

4. **Abrir la aplicación:**
   ```
   http://localhost/Carniceria/crm/index.php
   ```

### Credenciales de prueba

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | admin@ladehesa.es | admin123 |
| Cliente | prueba@gmail.com | 1234 |

---

## Despliegue con Docker

### Requisitos previos

- Docker Desktop instalado y en ejecución

### Pasos

1. **Clonar el repositorio** (si no está ya clonado):
   ```bash
   git clone https://github.com/Carla0504/Carniceria.git
   cd Carniceria
   ```

2. **Arrancar los contenedores:**
   ```bash
   docker compose up --build -d
   ```
   Este comando construye la imagen PHP+Apache, lanza el contenedor MySQL, espera a que la base de datos esté lista e importa `BBDD.sql` automáticamente.

3. **Abrir la aplicación:**
   ```
   http://localhost:8080
   ```
   La raíz redirige automáticamente a la aplicación.

4. **Parar los contenedores:**
   ```bash
   docker compose stop
   ```

### Servicios Docker

| Servicio | Imagen | Puerto |
|---|---|---|
| web | php:8.2-apache (imagen personalizada) | 8080 → 80 |
| db | mysql:8.0 | interno |

Las fotos de productos subidas desde el panel de administración se persisten en el directorio local `crm/public/img/productos/` mediante un volumen montado.

---

## Base de datos

La base de datos `la_dehesa` contiene cinco tablas:

| Tabla | Descripción |
|---|---|
| `usuarios` | Clientes y administrador (contraseña bcrypt, rol) |
| `secciones` | Las cuatro categorías del catálogo |
| `productos` | Artículos con sección, precio, foto y disponibilidad |
| `promociones` | Precio rebajado y fechas de vigencia por producto |
| `carrito_items` | Productos en el carrito por usuario (UNIQUE por par usuario-producto) |

---

## Variables de entorno

En Docker las credenciales se inyectan como variables de entorno. Para XAMPP se usan los valores por defecto de `config/db.php`. Copia `.env.example` a `.env` y ajusta si es necesario:

```env
DB_HOST=db
DB_NAME=la_dehesa
DB_USER=root
DB_PASS=rootpassword
```
