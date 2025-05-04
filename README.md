#  Recordatorio de Fechas – Aplicación Segura con PHP y Docker

Esta es una aplicación de recordatorio de fechas, desarrollada en PHP y conectada a una base de datos MariaDB, diseñada y documentada siguiendo el ciclo de vida de desarrollo seguro S-SDLC. 
Está contenerizada con Docker para facilitar su despliegue en cualquier entorno.

##  ¿Cómo ejecutar la aplicación?

###  Requisitos

- Docker
- Docker Compose

###  Estructura del proyecto

├── docker-compose.yml
├── .env
│   └── mariadb.example
├── persistent-data
│   ├── app
│   │   ├── add.php
│   │   ├── db.php
│   │   ├── delete.php
│   │   ├── edit.php
│   │   └── index.php
│   ├── build
│   │   └── lista-php
│   │       └── Dockerfile
│   └── mysql
│       └── conf.d
└── README.md

###  Instrucciones

1. Clona o descomprime el proyecto.
2. Copia `.env.example` a `.env/mariadb.env` y edita las variables si es necesario:
   MYSQL_ROOT_PASSWORD=root_password
   MARIADB_DATABASE=lista
   MARIADB_USER=Lista_User
   MARIADB_PASSWORD=UniversidadEuropea
3. Ejecuta el proyecto:
   docker compose up -d o docker-compose up -d ( Según la versión de docker instalada)
4. Abre el navegador en: http://localhost
5. Si la tabla eventos no existe aún, créala con los siguientes pasos :
   - sudo docker exec -it mariadb mysql -u root -p
   - Introduce tu contraseña de ROOT
   - Pega el siguiente SQL :
     USE lista;
     CREATE TABLE IF NOT EXISTS eventos (
         id INT AUTO_INCREMENT PRIMARY KEY,
         descripcion VARCHAR(255) NOT NULL,
         fecha DATE NOT NULL,
         creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

## Paso a paso según el S-SDLC

1. Planificación:
    - Se identificó la necesidad de validar fechas y prevenir errores comunes.
    - Se decidió usar Docker para aislamiento de servicios.
    - Se eligió PHP como lenguaje y MariaDB como Base de Datos porque el autor está más familiarizado con ello.

2. Diseño:
    - Separación clara entre frontend, backend y base de datos.
    - Se creó la estructura de carpetas: código en `persistent-data/app/`, base de datos en `persistent-data/db/`, configuración en `.env`, Dockerfile en persistent-data/build/.
    - Uso de variables de entorno para evitar credenciales hardcoded.

3. Desarrollo
    - Validación de entrada (formato de fecha, evitar eventos pasados).
    - Escapado de salida para evitar XSS.
    - Se prepararon scripts `add.php`, `delete.php`, `edit.php`, y `db.php` con lógica clara.

4. Pruebas
    - Validación manual del flujo completo.
    - Comprobación de errores forzando entradas inválidas.
    - Logs visibles en consola (docker compose logs).

5. Despliegue
    - Docker Compose permite ejecutar en cualquier entorno de forma segura.
    - Datos sensibles como contraseñas se pasan mediante .env.
    - Se creó un `Dockerfile` personalizado para PHP + Apache con extensiones necesarias.
    - Se diseñó un `docker-compose.yml` para lanzar automáticamente la app y la base de datos.
    - Se configuró un volumen para persistencia de datos de MariaDB.

6. Mantenimiento
    - Fácil de actualizar gracias a contenedores.
    - Separación de código y configuración permite regenerar entorno sin perder datos.

## Entorno de Pruebas

Se han realizado pruebas locales añadiendo, editando y eliminando eventos, y también se ha probado la validación de fechas pasadas y formato incorrecto.

## Autor 

Jaime Alguacil Plaza
