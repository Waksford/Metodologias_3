
##  Herramientas y Técnicas de Seguridad Seleccionadas

Durante el desarrollo de la aplicación "Amalgama de APPs", se han introducido deliberadamente ciertas vulnerabilidades con fines educativos para ser detectadas por herramientas gratuitas de evaluación de seguridad. Esto permite mejorar la comprensión del ciclo de vida de desarrollo seguro (S-SDLC) y aplicar principios de DevSecOps.

Las herramientas seleccionadas cubren distintos tipos de pruebas:

| Técnica | Herramienta Gratuita | Motivo de Selección |
|--------|------------------------|----------------------|
| **SAST** (Análisis Estático) | SonarQube | Analiza el código PHP en busca de patrones peligrosos como `eval()`. |
| **DAST** (Prueba Dinámica) | OWASP ZAP | Simula ataques desde el exterior mientras la app está en ejecución. Detecta XSS y otras vulnerabilidades del lado del cliente/servidor. |
| **IAST** (Prueba Interactiva) | Logs + Pruebas Manuales | Se revisa la trazabilidad de entradas maliciosas como inyecciones SQL y su efecto sobre el comportamiento del código. |
| **RASP** (Ataques en tiempo real) | Funcion Manual | Toma decisiones activas: bloquear, registrar, detener ejecución. |
| **GitHub Security** | Code Scanning + Dependabot | Analiza automáticamente el código y detecta dependencias y secretos inseguros desde el repositorio. |

---
## Resultados de Evaluación y Modificaciones Realizadas

---

### MODIFICACIONES APLICADAS PARA PRUEBAS DE SEGURIDAD (SAST)

 Archivo: `proyecto_jaime/add.php`

```php
if (isset($_POST['debug'])) {
    eval($_POST['debug']); //Vulnerabilidad simulada: ejecución remota de código
}
```
---

## MODIFICACIONES APLICADAS PARA PRUEBAS DE SEGURIDAD (IAST)

Archivo anterior : proyecto_airam/add.php
```
 $stmt = $conn->prepare("INSERT INTO contactos (nombre, telefono, email, direccion) VALUES (?, ?, ?, ?)");
 $stmt->execute([$nombre, $telefono, $email, $direccion]);

```
En vez de :
```
$sql = "INSERT INTO contactos (nombre, telefono, email, direccion) VALUES ('$_POST[nombre]', '$_POST[telefono]', '$_POST[email]', '$_POST[direccion]')";
mysqli_multi_query($conn, $sql);
```

Y por último -> Archivo: proyecto_airam/db.php

```
$conn = new mysqli($host, $user, $pass, $dbname);

```
---

## MODIFICACIONES APLICADAS PARA PRUEBAS DE SEGURIDAD (DAST - XSS Reflejado)

Archivo: proyecto_airam/index.php

1. Se añadió un formulario de búsqueda GET:
```
<form method="GET">
    <input type="text" name="busqueda" placeholder="Buscar contacto...">
    <button type="submit">Buscar</button>
</form>
```
2. Se introdujo una vulnerabilidad XSS reflejada intencionada:
```
<?php
if (isset($_GET['busqueda'])) {
    echo "<p>Resultados para: " . $_GET['busqueda'] . "</p>";
}
?>
```
---

## 	Credenciales hardcoded (GitHub Security)

Archivo: proyecto_airam/db.php
```
$user = "admin";
$pass = "1234";
```
---

### Análisis SAST – SonarQube

SonarQube identificó varias debilidades estructurales y recomendaciones de seguridad en el código fuente:

-  **Uso de `eval()` identificado como Security Hotspot:** Detectado en `proyecto_jaime/add.php`, marcado como crítico. Representa riesgo de ejecución remota de código (RCE).
-  **Uso de `require` en vez de `require_once`:** Detectado en múltiples archivos (`add.php`, `index.php`, etc.). Puede provocar múltiples inclusiones no deseadas.
-  **No uso de namespaces (`use`)**: Se recomienda migrar a imports por namespaces para mejorar mantenibilidad.
-  **Presencia de código comentado no utilizado:** Detectado en varios archivos. Debe eliminarse para mejorar la claridad del código.
-  **Etiqueta de cierre `?>` innecesaria en `db.php`:** Mala práctica en archivos puramente PHP.
-  **Ausencia de nueva línea al final de archivo:** Reportado como convención no cumplida.
-  **Uso de tabs en vez de espacios:** Incumple estándar PSR-2 / PSR-12.
-  **Espacios en blanco finales en líneas de código:** Se recomienda eliminar para mantener el código limpio.

### Análisis DAST – OWASP ZAP

OWASP ZAP identificó múltiples problemas de seguridad en la aplicación en ejecución:

-  **XSS Reflejado:** Confirmado en el parámetro `busqueda` (archivo `index.php`).
-  **XSS DOM:** Detectado por ausencia de filtrado en manipulaciones del DOM desde JavaScript.
-  **Falta de tokens CSRF:** Los formularios de `add.php` y `delete.php` no incluyen token de validación.
-  **Sin cabecera CSP:** No se aplica ninguna política de seguridad para evitar carga de scripts externos.
-  **Falta de X-Frame-Options:** Posibilidad de ataques de clickjacking.
-  **Archivos ocultos detectados:** Posible acceso a archivos no destinados al público (como backups).
-  **Parameter Tampering:** Algunos formularios permiten manipular valores sin validación posterior.

### Análisis IAST
Este análisis fue realizado de forma manual por el autor para demostrar una vulnerabilidad de tipo **inyección SQL** (`SQL Injection`) en el formulario de contacto.

-  **SQL Injection (Confirmado):** Ejecutado exitosamente a través del campo `dirección` del formulario `add.php`.  
  - Se utilizó el siguiente payload en el campo `dirección`:  
    ```
    test'); DROP TABLE contactos; --
    ```
  - Resultado: La tabla `contactos` fue eliminada completamente.  
  - Confirmación: El archivo `index.php` mostró el siguiente error al intentar cargar la tabla eliminada:
    ```
    Fatal error: Uncaught mysqli_sql_exception: Table 'lista.contactos' doesn't exist
    ```
-  **Ejecución lograda con `mysqli_multi_query()`:** El código fue modificado para aceptar múltiples sentencias SQL en una sola llamada, lo que permitió que el payload ejecutara `DROP TABLE`.
Este análisis fue realizado **manualmente por el desarrollador del proyecto** para simular un ataque real y evaluar la efectividad de técnicas IAST en entorno controlado.

### Análisis RASP 
Debido a la ausencia de soluciones RASP gratuitas para PHP, se implementó una lógica propia de detección en tiempo de ejecución:

- Se introdujo una función **`protegerEntrada()`** que analiza valores de entrada en busca de patrones maliciosos (`DROP`, `--`, `;`, etc.)
- Si se detecta una coincidencia, se bloquea la ejecución inmediatamente.
- Esta lógica simula el comportamiento básico de un motor RASP.
Función añadida en :

```php
function protegerEntrada($valor) {
    if (preg_match('/(DROP|UNION|SELECT|--|;)/i', $valor)) {
        error_log(" Intento de ataque detectado: $valor");
        die(" Acceso bloqueado por protección en tiempo de ejecución.");
    }
    return $valor;
}
```

### Análisis GitHub Security

GitGuardian detectó credenciales sensibles expuestas en el archivo `.env/mariadb.env` del repositorio `Waksford/Metodologias_3`.

-  **Tipo de secreto:** Contraseña genérica (`Generic Password`)
-  **Archivo afectado:** `.env/mariadb.env`
-  **Contenido expuesto:** `.MYSQL_PASSWORD=UniversidadEuropea`

> Estas vulnerabilidades fueron documentadas,y serán corregidas posteriormente y re-evaluadas para asegurar la mejora continua del proyecto.

## Autores 

**Jaime Alguacil Plaza**
  [Repositorio individual anterior](https://github.com/Waksford/Metodologias)

**Airam Socas**
