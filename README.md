# Sistema de Inventario Multicanal

Proyecto final de laboratorio que implementa múltiples canales de comunicación en Laravel 13.

## Tecnologías utilizadas
- Laravel 13 (PHP 8.4)
- REST API
- GraphQL (Lighthouse)
- WebSockets (Laravel Reverb)
- gRPC (Node.js y Python)
- Base de datos SQLite

## Requisitos de instalación
1. Clonar el repositorio.
2. Ejecutar `composer install` y `npm install`.
3. Copiar el archivo `.env.example` a `.env` (en Windows: `copy .env.example .env`).
4. Generar la clave de la aplicación: `php artisan key:generate`.
5. Agregar la variable `GRPC_INTERNAL_KEY=clave-local-larga-de-prueba` en su archivo `.env` local (esta variable es necesaria para el correcto funcionamiento del servicio gRPC).
6. Crear la base de datos SQLite: `New-Item -Path "database\database.sqlite" -ItemType File` (o simplemente crear el archivo vacío).
7. Ejecutar las migraciones: `php artisan migrate`..

## Cómo ejecutar el proyecto
Se necesitan varias terminales:
1. `php artisan serve` (API REST y GraphQL)
2. `php artisan reverb:start` (WebSockets)
3. `php artisan queue:work` (Colas)
4. `npm run dev` (Vite)
5. En la carpeta `/grpc`: `node server.cjs` y `node client.cjs`