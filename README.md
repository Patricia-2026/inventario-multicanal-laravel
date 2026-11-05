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
3. Copiar el archivo `.env.example` a `.env` y agregar la línea: `GRPC_INTERNAL_KEY=clave-local-larga-de-prueba`.
4. Ejecutar `php artisan migrate`.

## Cómo ejecutar el proyecto
Se necesitan varias terminales:
1. `php artisan serve` (API REST y GraphQL)
2. `php artisan reverb:start` (WebSockets)
3. `php artisan queue:work` (Colas)
4. `npm run dev` (Vite)
5. En la carpeta `/grpc`: `node server.cjs` y `node client.cjs`