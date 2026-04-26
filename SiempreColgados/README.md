# 🚀 SiempreColgados - Sistema de Gestión de Incidencias

> **Proyecto DWES - IES La Marisma**  
> Ciclo Formativo: CFGS Desarrollo de Aplicaciones Web  
> Framework: Laravel 12 | PHP 8.2 | XAMPP

Aplicación web para la gestión de incidencias, empleados y clientes de mantenimiento de ascensores. Permite la asignación de tareas, control de estados, facturación de cuotas y registro público de incidencias.

---

## 📋 Requisitos Previos

Para ejecutar este proyecto en tu máquina local, necesitas:

- [x] **XAMPP** (Apache + MySQL/MariaDB)
- [x] **PHP 8.2+** (incluido en XAMPP)
- [x] **Composer** (Gestor de dependencias PHP)
- [x] **Git** (Para clonar el repositorio)

---

## ⚙️ Instalación y Ejecución

Sigue estos pasos para poner en marcha el proyecto:

### 1. Clonar el Repositorio

Abre tu terminal y clona el proyecto en la carpeta de tu servidor local:

```bash
# Navega a la carpeta de tu servidor local (XAMPP)
cd C:\xampp\htdocs

# Clona el repositorio (cambia 'tu-usuario' por tu usuario real)
git clone https://github.com/tu-usuario/siemprecolgados.git
cd siemprecolgados
```

### 2. Instalar Dependencias

Instala las librerías necesarias de PHP:

```bash
composer install
```

### 3. Configurar Entorno

Copia el archivo de ejemplo y genera la clave de aplicación:

```bash
copy .env.example .env
php artisan key:generate
```

### 4. Configurar Base de Datos

Asegúrate de que tu base de datos en el `.env` coincida con la de tu XAMPP:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siemprecolgados
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Importar Datos

Crea la base de datos en phpMyAdmin (nombre: `siemprecolgados`, intercalación: `utf8mb4_unicode_ci`) e importa el volcado:

```bash
# Opción A: Usando MySQL directo (recomendado)
mysql -u root -p siemprecolgados < install/bd.sql

# Opción B: Importando desde phpMyAdmin
# - Ve a http://localhost/phpmyadmin
# - Selecciona la base de datos
# - Pestaña "Importar" -> Selecciona el archivo install/bd.sql -> Continuar
```

### 6. Inicializar

Genera los enlaces simbólicos y limpia la caché:

```bash
php artisan storage:link
php artisan optimize:clear
```

### 7. Acceder a la Aplicación

Asegúrate de que Apache y MySQL están activos en el Panel de Control de XAMPP.

🌐 **URL:** [http://localhost/siemprecolgados/public](http://localhost/siemprecolgados/public)

---

## 👤 Usuarios de Acceso

Utiliza estas credenciales para probar los roles:

| Rol | Email | Contraseña | Permisos |
| :--- | :--- | :--- | :--- |
| **Administrador** | `admin@test.com` | `password` | Gestión total (Clientes, Empleados, Incidencias, Cuotas) |
| **Operario** | `operario@test.com` | `password` | Solo ve y edita sus propias incidencias asignadas |
| **Cliente** | - | - | Acceso público a formulario de reporte (Sin Login) |

---

## 🧪 Testing

El proyecto incluye tests unitarios y de integración creados con PHPUnit.

Ejecuta todos los tests:
```bash
php artisan test
```

Ejecuta un test específico:
```bash
php artisan test --filter=ClienteTest
```

---

## 📂 Estructura del Proyecto

```text
├── app/
│   ├── Http/Controllers/     # Controladores (Admin, Operario, API)
│   ├── Models/               # Modelos Eloquent con lógica de negocio
│   └── Services/             # Servicios externos (CurrencyService)
├── config/                   # Configuración de la aplicación
├── database/
│   ├── migrations/           # Definición de tablas
│   └── seeders/              # Datos de prueba
├── install/
│   └── bd.sql                # Volcado SQL para instalación rápida
├── public/                   # Punto de entrada y assets
├── resources/views/          # Vistas Blade (Bootstrap 5)
├── routes/
│   ├── api.php               # Rutas API REST (Sanctum)
│   └── web.php               # Rutas web
├── tests/                    # Tests PHPUnit
└── .env                      # Configuración de entorno
```

---

## 🔍 Solución de Problemas Comunes

| Error | Solución |
| :--- | :--- |
| **`No application encryption key`** | Ejecuta `php artisan key:generate` |
| **`Class not found`** | Ejecuta `composer dump-autoload` |
| **`404 Not Found`** | Verifica que entras a `http://localhost/siemprecolgados/public` (con /public) |
| **`Route not defined`** | Ejecuta `php artisan route:clear` |
| **Pantalla en blanco** | Revisa `storage/logs/laravel.log` para ver el error detallado |

---

## 📄 Licencia

Proyecto educativo realizado para la asignatura de Desarrollo Web en Entorno Servidor (DWES).
© 2026 IES La Marisma