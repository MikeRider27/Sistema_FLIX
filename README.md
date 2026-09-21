# Sistema FLIX

Sistema de gestión de gimnasio en **PHP 8.3 (MVC propio) + PostgreSQL 16 (PDO)**, dockerizado.

## Módulos

- **Socios**: alta, edición, búsqueda, ficha con membresías, pagos y asistencias.
- **Membresías**: alta desde un plan (fecha de fin y precio se calculan), renovación, cancelación y estado real (vigente / pendiente / vencida / cancelada). PostgreSQL impide membresías activas solapadas del mismo socio.
- **Pagos**: cobros parciales o totales con control de saldo transaccional.
- **Asistencias**: check-in por cédula (solo socios activos con membresía vigente, una entrada por día).
- **Planes, Entrenadores, Usuarios**: administración (solo rol `admin`).
- **Panel**: KPIs, membresías por vencer, saldos pendientes y últimos ingresos.

Roles: `admin` (todo) y `recepcion` (socios, membresías, pagos, asistencias).

## Puesta en marcha

```bash
cp .env.example .env        # y cambiar POSTGRES_PASSWORD
docker compose up -d --build
```

- Aplicación: http://localhost:8080 (`APP_PORT`)
- Usuario inicial: `admin` / `admin123` (**cambiarla al primer ingreso**)
- Datos demo: usuario `recepcion` / `recepcion123`, socios, planes y pagos de ejemplo. Para arrancar sin ellos, borrar `database/init/03_demo.sql` antes del primer `up`.
- Adminer (opcional): `docker compose --profile tools up -d` → http://localhost:8081 (servidor `db`)
- PostgreSQL desde el host: `127.0.0.1:5433` (`DB_HOST_PORT`)

Los scripts de `database/init/` solo corren con el volumen vacío. Para reiniciar la base:

```bash
docker compose down -v && docker compose up -d
```

Si un puerto está ocupado en tu máquina, cambialo en `.env`.

## Estructura

```
public/            document root (index.php = front controller, assets/)
app/
  Core/            Router, Database (PDO), Auth, Csrf, Flash, Validator, View, Controller, Model
  Controllers/     un controlador por módulo
  Models/          consultas con sentencias preparadas
  Views/           layouts + vistas por módulo
  routes.php       tabla de rutas y permisos por rol
config/            configuración desde variables de entorno
database/init/     01_schema.sql, 02_seed.sql, 03_demo.sql
docker/            vhost de Apache y php.ini
```

Solo `public/` es accesible por web; `app/`, `config/` y `database/` quedan fuera del document root.

## Seguridad

Sentencias preparadas (sin emulación) en todas las consultas, contraseñas con `password_hash` (bcrypt), token CSRF en todos los POST, `session_regenerate_id` al ingresar, cookies `HttpOnly` + `SameSite=Lax`, escape de salida en todas las vistas y control de acceso por rol en el router.

## Producción

El compose está pensado para desarrollo (monta el código como volumen). Para producción: quitar el volumen `./:/var/www/html` del servicio `app` (la imagen ya copia el código), no publicar el puerto de PostgreSQL y servir detrás de HTTPS.
