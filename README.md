# API de Gestión de Biblioteca - XYZ

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)
![Railway](https://img.shields.io/badge/Deploy-Railway-0B0D0E?style=for-the-badge&logo=railway)

[cite_start]Este repositorio contiene el backend (API RESTful) para el sistema de gestión de la biblioteca de la Universidad Tecnológica XYZ, como parte del Trabajo Final del curso Gestores de Administración Web[cite: 1].

## 1. Contexto del Proyecto

[cite_start]La biblioteca de la Universidad XYZ gestiona más de 10,000 libros y 5,000 usuarios [cite: 4] [cite_start]mediante un sistema obsoleto basado en hojas de cálculo[cite: 4]. [cite_start]Esto ha generado problemas significativos, incluyendo un 30% de retrasos en devoluciones y un 15% de pérdida de registros[cite: 5].

[cite_start]Esta API RESTful sirve como el cerebro central para una nueva aplicación web moderna, reemplazando los procesos manuales y optimizando la carga de trabajo de los 5 empleados de la biblioteca[cite: 5, 7].

## 2. Stack Tecnológico

* **Framework Backend:** Laravel 12
* [cite_start]**Base de Datos:** MySQL [cite: 19, 50]
* [cite_start]**Autenticación:** Laravel Sanctum (Autenticación basada en Tokens) [cite: 16]
* [cite_start]**Servidor de Despliegue:** Railway [cite: 14]
* **Constructor de Despliegue:** Nixpacks

## 3. Características Principales

Esta API proporciona *endpoints* seguros para gestionar todos los recursos de la biblioteca:

* [cite_start]**Autenticación:** Sistema de Login (`POST /api/login`) para empleados[cite: 16].
* **Control de Acceso por Roles:** Middleware `is.admin` que restringe acciones sensibles (como crear empleados o libros) solo a usuarios administradores.
* **Gestión de Empleados (CRUD Admin):** Endpoints protegidos para crear, leer, actualizar y eliminar cuentas de empleados.
* **Gestión de Libros (CRUD):** Endpoints para gestionar el inventario de 10,000 libros.
    * Búsqueda optimizada por `titulo`, `autor` y `categoria`.
    * Ordenamiento dinámico (A-Z, Z-A) por cualquier columna.
    * Paginación automática (20 resultados por página) para un rendimiento eficiente.
* **Gestión de Usuarios (Estudiantes):** CRUD completo para los 5,000 usuarios registrados.
    * Cálculo de "Préstamos Activos" (`prestamos_activos_count`) en la misma consulta.
* **Gestión de Préstamos:**
    * Lógica de negocio para `store` (crear) que descuenta el stock de un libro.
    * Ruta personalizada `PUT /prestamos/{id}/devolver` que incrementa el stock.
    * Ambas acciones protegidas por transacciones de base de datos (`DB::commit/rollback`) para garantizar la integridad de los datos.
* [cite_start]**Dashboard de Reportes:** Un *endpoint* (`GET /api/reportes/dashboard`) que calcula y entrega todas las estadísticas clave [cite: 8] para el frontend:
    * KPIs (Total Préstamos Mes, Activos, Retrasados).
    * Datos para gráfico de Préstamos por Mes.
    * Datos para gráfico de Categorías Más Populares.

## 4. Instalación Local

Para probar o continuar el desarrollo de esta API en un entorno local:

1.  **Clonar el repositorio:**
    ```bash
    git clone [URL-DE-TU-REPOSITORIO]
    cd Biblioteca-API
    ```

2.  **Instalar dependencias:**
    ```bash
    composer install
    ```

3.  **Configurar el Entorno:**
    * Copia el archivo de ejemplo: `cp .env.example .env`
    * Genera la clave de la aplicación: `php artisan key:generate`

4.  **Configurar Base de Datos (.env):**
    * Abre el archivo `.env` y configura tus credenciales de MySQL local:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=biblioteca_local
    DB_USERNAME=root
    DB_PASSWORD= (tu contraseña local)
    ```

5.  **Migrar y Sembrar (¡Importante!):**
    Este comando creará todas las tablas y ejecutará el `EmpleadoSeeder` para crear al usuario administrador (`admin@biblioteca.com`).
    ```bash
    php artisan migrate --seed
    ```

6.  **Iniciar el servidor:**
    ```bash
    php artisan serve
    ```

## 5. Endpoints Principales de la API

La URL base de producción es: `https://[tu-url-de-railway].up.railway.app`

**Nota:** Todas las peticiones (excepto `/login`) deben incluir los headers:
* `Accept: application/json`
* `Authorization: Bearer [TU_TOKEN]`

---

### Autenticación
| Método | Ruta | Descripción |
| :--- | :--- | :--- |
| `POST` | `/api/login` | Inicia sesión. Devuelve un `access_token`. |

---

### Libros
| Método | Ruta | Descripción |
| :--- | :--- | :--- |
| `GET` | `/api/libros` | Lista todos los libros (paginado). |
| | `.../libros?titulo=Duna` | Filtra por título. |
| | `.../libros?categoria=Ciencia` | Filtra por categoría. |
| | `.../libros?sort=autor&direction=desc` | Ordena por autor Z-A. |
| `POST` | `/api/libros` | (Admin) Crea un nuevo libro. |
| `PUT` | `/api/libros/{id}` | (Admin) Actualiza un libro. |
| `GET` | `/api/categorias` | Devuelve una lista única de categorías. |

---

### Préstamos
| Método | Ruta | Descripción |
| :--- | :--- | :--- |
| `GET` | `/api/prestamos` | Lista todos los préstamos (paginado). |
| | `.../prestamos?estado=retrasados` | Filtra por `activos`, `devueltos` o `retrasados`. |
| | `.../prestamos?search=Josue` | Busca por nombre de libro o estudiante. |
| `POST` | `/api/prestamos` | Registra un nuevo préstamo. |
| `PUT` | `/api/prestamos/{id}/devolver` | Marca un préstamo como devuelto. |

---

### Reportes
| Método | Ruta | Descripción |
| :--- | :--- | :--- |
| `GET` | `/api/reportes/dashboard` | (Admin) Obtiene todos los KPIs y datos de gráficos. |

---

## 6. Créditos

* [cite_start]**Instructor:** Giancarlos Barboza N. [cite: 26, 62]
* **Desarrollador Backend:** [Tu Nombre Aquí]
