#  Amalgama de APPs – Aplicación Segura con PHP y Docker

Esta es una aplicación de recordatorio de fechas y agenda de personas, desarrollada en PHP y conectada a una base de datos MariaDB, diseñada y documentada siguiendo el ciclo de vida de desarrollo seguro S-SDLC. 
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

## Consideraciones de seguridad aplicadas

- Validación del formato de fecha y restricción de fechas pasadas.
- Escapado de contenido para prevenir ataques XSS (`htmlspecialchars()`).
- Separación de lógica (PHP) y presentación (HTML).
- Uso de variables de entorno para evitar credenciales hardcoded.
- Tablas con claves primarias seguras y campos restringidos.
- Revisión manual de entradas inválidas para prevenir inyección.
- Conexión a base de datos con usuario limitado (`Lista_User`), no root.

## Paso a paso según el S-SDLC

1. Planificación:
    - Se identificó la necesidad de una herramienta modular para la gestión personal (recordatorios y contactos).
    - Se decidió usar Docker para aislamiento de servicios.
    - Se establecieron requisitos funcionales mínimos y controles de seguridad básicos (validación de fechas, entradas limpias).
    - Se eligió PHP como lenguaje y MariaDB como Base de Datos porque el autor está más familiarizado con ello.

2. Diseño:
    - Separación clara entre frontend, backend y base de datos.
    - Arquitectura modular: cada función (recordatorio / agenda) está separada en su carpeta (proyecto_jaime/, proyecto_airam/).
    - Se creó la estructura de carpetas: código en `persistent-data/app/`, base de datos en `persistent-data/db/`, configuración en `.env`, Dockerfile en persistent-data/build/.
    - El diseño prevé la posibilidad de ampliar módulos en el futuro.

3. Desarrollo:
    - Se implementó validación de entradas: fechas válidas, campos requeridos, rechazo de datos inválidos.
    - Escapado de salida para evitar XSS.
    - Se prepararon scripts `add.php`, `delete.php`, `edit.php`, y `db.php` con lógica clara dentro de cada módulo implementado.
    - Se creó un contenedor PHP personalizado (Dockerfile) con Apache.

4. Pruebas:
    - Validación manual del flujo completo.
    - Comprobación de errores forzando entradas inválidas.
    - Logs visibles en consola (docker compose logs).
    - Se comprobó el funcionamiento de la app al reiniciar los contenedores (persistencia de datos).
    - Pruebas básicas de seguridad: intentar inyecciones simples, campos vacíos, y formatos incorrectos.

5. Despliegue:
    - Docker Compose permite ejecutar en cualquier entorno de forma segura.
    - Datos sensibles como contraseñas se pasan mediante .env.
    - Se creó un `Dockerfile` personalizado para PHP + Apache con extensiones necesarias.
    - Se diseñó un `docker-compose.yml` para lanzar automáticamente la app y la base de datos.
    - Se configuró un volumen para persistencia de datos de MariaDB.

6. Mantenimiento:
    - Fácil de actualizar gracias a contenedores.
    - Separación de código y configuración permite regenerar entorno sin perder datos.
    - Los módulos pueden escalar o duplicarse sin romper la arquitectura.

## DevSecOps aplicado

- Contenerización: uso de Docker para aislar servicios, controlar versiones y facilitar despliegue seguro.

- Control de credenciales: .env y usuarios limitados para base de datos.

- Automatización: despliegue completo con Docker Compose.

- Código seguro desde el desarrollo: validaciones tempranas, sanitización, uso de usuarios no root.

- Observación y logging: revisión de errores y comportamiento mediante consola y logs de contenedores.

## Entorno de Pruebas

Las pruebas funcionales se realizaron en entorno local con Docker, verificando:

- Añadir, editar y eliminar eventos con fechas válidas.
- Rechazo de eventos con fechas pasadas o formato incorrecto.
- Añadir y eliminar contactos en la agenda.
- Validación de campos requeridos y formatos incorrectos en ambos módulos.
- Persistencia de datos tras reiniciar contenedores.

## Autores 

Jaime Alguacil Plaza
  [Repositorio individual anterior](https://github.com/Waksford/recordatorio-fechas)
