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

## Fundamentos Teóricos y Arquitectura

A continuación se detallan las justificaciones técnicas y teóricas de las decisiones tomadas en el desarrollo de este sistema multicanal.

### 1. ¿Por qué se utilizó un `ProductoService` compartido?
Se aplicó el patrón de diseño *Service Layer* y el principio **DRY** (Don't Repeat Yourself). Tanto la API REST como GraphQL consumen la misma lógica de negocio desde `ProductoService`. Esto garantiza que si una regla cambia (por ejemplo, que el stock no pueda ser negativo), se modifique en un solo lugar y ambos canales se mantengan sincronizados, evitando la duplicación de código y posibles errores.

### 2. Diferencias entre REST y GraphQL en este proyecto
*   **REST:** Utiliza múltiples endpoints (ej. `/api/productos`). El servidor define la estructura de los datos, lo que puede provocar *over-fetching* (traer más datos de los necesarios).
*   **GraphQL:** Utiliza un único endpoint (`/graphql`). El cliente tiene el control y solicita exactamente los campos que necesita (ej. solo `id` y `nombre`). Esto optimiza el rendimiento de la red y es ideal para aplicaciones con múltiples clientes (móviles, web, etc.).

### 3. ¿Cómo funciona la comunicación en tiempo real (WebSockets)?
Se utilizó **Laravel Reverb** para implementar WebSockets. A diferencia de HTTP (donde el cliente debe preguntar constantemente), WebSockets mantiene una conexión bidireccional abierta. Cuando `ProductoService` crea o actualiza un producto, dispara el evento `ProductoActualizado`. Reverb lo transmite al canal público `inventario`, y el frontend (usando Laravel Echo) lo escucha y actualiza la tabla HTML sin necesidad de recargar la página.

### 4. ¿Qué es gRPC y por qué se implementó?
gRPC es un framework de comunicación remota de alto rendimiento creado por Google. Utiliza **Protocol Buffers** (archivos `.proto`) para definir el contrato de datos y viaja sobre **HTTP/2**. Se implementó porque es mucho más rápido y ligero que REST o GraphQL, siendo el estándar para la comunicación entre microservicios (en este caso, un servidor en Node.js comunicándose con el backend en Laravel).

### 5. Seguridad: ¿Por qué se protegió la ruta interna de gRPC?
El servidor de Node.js necesita consultar datos a Laravel, pero esa ruta no debe ser accesible públicamente. Se implementó una capa de seguridad utilizando una **clave secreta compartida** (`GRPC_INTERNAL_KEY`) enviada a través de los *headers* HTTP (`X-Internal-Key`). Además, en Laravel se utilizó la función `hash_equals()` para comparar las claves, lo que previene ataques de temporización (*Timing Attacks*). Esta buena práctica de seguridad fue replicada dejando la clave vacía en el archivo `.env.example`.