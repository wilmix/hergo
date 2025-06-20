# Actualización de Sistema de Artículos para DigitalOcean Spaces

Esta actualización mejora el sistema de artículos para almacenar las imágenes en DigitalOcean Spaces en lugar del servidor local, proporcionando una mejor escalabilidad, redundancia y accesibilidad a las imágenes.

## Cambios realizados

1. Modificación del controlador `Articulos.php` para utilizar DigitalOcean Spaces
2. Actualización del modelo `Articulo_model.php` para incluir el campo ImagenUrl
3. Modificación del JavaScript para mostrar imágenes desde Spaces
4. Script SQL para actualizar la estructura de la base de datos
5. **Actualización de la URL base en el frontend para mostrar imágenes desde el CDN/subdominio personalizado**

## Pasos para implementar

### 1. Ejecutar el script SQL para actualizar la base de datos

El script `add_imagen_url_column.sql` agrega una columna `ImagenUrl` a la tabla `articulos` y actualiza los registros existentes para completar la URL correspondiente para imágenes existentes.

```sql
mysql -u usuario -p nombre_base_de_datos < SQL/add_imagen_url_column.sql
```

O desde phpMyAdmin importar el archivo SQL directamente.

### 2. Configuración de DigitalOcean Spaces

Las credenciales para DigitalOcean Spaces ya están configuradas en el archivo `application/config/config.php`. 

```php
/*
|--------------------------------------------------------------------------
| Spaces
|--------------------------------------------------------------------------
|
| Personal access tokens function like a combined name and password for API Authentication. 
| Generate a token to access the DigitalOcean API
|
*/
$config['credentialsSpacesDO'] = [
    'version'     => 'latest',
    'region'      => 'nyc3',      // Tu región (puede variar)
    'endpoint'    => 'https://nyc3.digitaloceanspaces.com',
    'credentials' => [
        'key'     => 'TU_ACCESS_KEY',
        'secret'  => 'TU_SECRET_KEY',
    ],
];
```

**IMPORTANTE**: No modifiques estos valores directamente en el código versionado. En su lugar, actualiza los valores en tu archivo `config.php` local.

### 3. Estructura de carpetas en DigitalOcean Spaces

Las imágenes de artículos ahora se almacenarán en:
- `hg/articulos/` - Para imágenes de artículos (nuevo estándar)

Esta estructura permite una organización más clara del contenido y evita colisiones de nombres de archivo entre diferentes tipos de recursos.

### 4. Configuración de la URL base para mostrar imágenes (CDN/Subdominio)

En el archivo `assets/hergo/articulo.js` se define la variable global:

```js
// URL base para mostrar imágenes de artículos desde DigitalOcean Spaces usando el subdominio CDN personalizado.
// Si cambias la configuración de tu CDN o subdominio, actualiza esta variable.
let baseSpacesUrl = "https://images.hergo.app/";
```

Esto permite que todas las imágenes se muestren desde el CDN/subdominio configurado en DigitalOcean Spaces. Si cambias el subdominio o la configuración del CDN, solo debes actualizar esta variable.

### 5. Compatibilidad hacia atrás

El sistema sigue siendo compatible con las imágenes existentes almacenadas en el servidor local. Si una imagen no tiene una URL de Spaces, se buscará en la ubicación local tradicional.

## Buenas prácticas y ejemplo reutilizable para otros módulos

Si tienes otros módulos (clientes, usuarios, documentos, etc.) que también almacenan archivos o imágenes en el servidor local y quieres migrarlos a DigitalOcean Spaces/CDN, sigue este mismo patrón:

### 1. Estructura de carpetas en Spaces
- Usa un prefijo único por módulo, por ejemplo:
  - `hg/articulos/` para artículos
  - `hg/clientes/` para imágenes de clientes
  - `hg/documentos/` para archivos PDF, docs, etc.

### 2. Subida de archivos en el backend
- Utiliza el SDK de AWS S3 para subir archivos a Spaces, especificando el prefijo adecuado.
- Guarda en la base de datos solo el path relativo (ejemplo: `hg/articulos/archivo.jpg`).

### 3. URL base en el frontend
- Define una variable global en el JS del módulo:

```js
// URL base para mostrar archivos desde Spaces/CDN
let baseSpacesUrl = "https://images.hergo.app/";
```
- Para mostrar cualquier archivo:

```js
let urlCompleta = baseSpacesUrl + pathRelativoEnBD;
```

### 4. Compatibilidad y migración
- Si ya tienes archivos en el servidor local, crea un script para migrarlos a Spaces y actualizar la base de datos.
- Mantén compatibilidad mostrando archivos locales si el campo de Spaces está vacío.

### 5. Ventajas
- Centralizas la gestión de archivos en Spaces.
- Puedes cambiar de CDN o subdominio fácilmente.
- El código es reutilizable y escalable para cualquier módulo.

---

**Este README puede servir como plantilla para cualquier otro módulo que requiera migrar archivos a Spaces/CDN y mantener el frontend desacoplado de la infraestructura.**

## Beneficios

- Mayor escalabilidad para manejar gran cantidad de imágenes
- Mejor rendimiento en la carga de imágenes
- Respaldos automáticos proporcionados por DigitalOcean
- Reducción de la carga en el servidor principal
- URLs consistentes para las imágenes independientemente del servidor
- Facilidad para cambiar de CDN o subdominio sin modificar todo el código frontend
