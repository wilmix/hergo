# Sistema de Gestión de Archivos en DigitalOcean Spaces

Este documento describe la arquitectura y uso del sistema centralizado para gestionar archivos en DigitalOcean Spaces, implementado inicialmente en el módulo de artículos y diseñado para ser fácilmente extendido a otros módulos.

## Arquitectura

El sistema sigue un patrón MVC con una clara separación entre almacenamiento, lógica de negocio y presentación:

### 1. Backend (PHP)

- **Biblioteca FileStorage**: `application/libraries/FileStorage.php`
  - Proporciona métodos unificados para subir y eliminar archivos de Spaces
  - Gestiona la creación de nombres de archivo seguros y únicos
  - Maneja errores y logging

- **Configuración**: `application/config/storage.php`
  - Centraliza las credenciales de acceso
  - Define los buckets y carpetas para cada módulo
  - Establece URLs base para el CDN

- **Modelo-Controlador**:
  - Los controladores utilizan la biblioteca FileStorage para operaciones de archivos
  - Los modelos almacenan únicamente rutas relativas a los archivos (no URLs completas)

### 2. Frontend (JavaScript)

- **Biblioteca FileUtils**: `assets/hergo/fileutils.js`
  - Clase estática para gestionar URLs de archivos
  - Métodos para configurar componentes de carga de archivos
  - Manejo de eventos para eliminación de imágenes

## Estructura de Carpetas en Spaces

```
hergo-space (bucket)
└── hg
    ├── articulos
    │   └── [imágenes de artículos]
    ├── clientes
    │   └── [imágenes de clientes]
    ├── usuarios
    │   └── [imágenes de perfil]
    ├── documentos
    │   └── [archivos PDF, etc.]
    └── [otros módulos]
```

## Implementación en el Módulo de Artículos

El módulo de artículos ha sido completamente refactorizado para utilizar este sistema:

1. **Base de datos**:
   - Campo `ImagenUrl` almacena rutas relativas (ej: `hg/articulos/imagen.jpg`)
   - Campo `Imagen` se mantiene para retrocompatibilidad

2. **Controlador** (`Articulos.php`):
   - Separación de responsabilidades en métodos privados
   - Uso de `FileStorage` para subir imágenes

3. **Frontend** (`articulo.js`):
   - Uso de `FileUtils` para gestionar URLs y componentes de carga

## Cómo Implementar en Nuevos Módulos

Sigue estos pasos para implementar el sistema en un nuevo módulo:

### 1. Base de datos

Agrega un campo para almacenar la ruta del archivo:

```sql
ALTER TABLE tu_tabla ADD COLUMN ArchivoUrl VARCHAR(255) NULL;
```

### 2. Controlador

```php
// Cargar la biblioteca
$this->load->library("FileStorage");

// Subir un archivo
public function subirArchivo() {
    $resultado = $this->filestorage->uploadToSpaces(
        'nombre_modulo',    // carpeta en Spaces
        $_FILES,            // archivos
        'campo_archivo'     // nombre del campo en el formulario
    );
    
    if ($resultado['success']) {
        // Guardar en la base de datos
        $data = new stdClass();
        $data->ArchivoUrl = $resultado['path'];
        $this->modelo->guardar($data);
    }
}
```

### 3. Vista (JavaScript)

```html
<!-- En tu HTML -->
<script src="<?= base_url('assets/hergo/fileutils.js') ?>"></script>

<input type="file" id="archivo" name="archivo">
<input type="hidden" id="archivoEliminado" name="archivoEliminado" value="0">
```

```javascript
// En tu JavaScript
$(document).ready(function() {
    // Configurar el componente de archivo
    FileUtils.setupFileInput("#archivo");
    
    // Manejar evento de eliminación
    FileUtils.handleFileClear("#archivo", "#archivoEliminado");
});

// Para mostrar un archivo existente
function mostrarArchivo(url) {
    const rutaCompleta = FileUtils.getFullUrl(url);
    // Hacer algo con la URL completa
}
```

## Ventajas

1. **Centralización**: Una única biblioteca gestiona todas las operaciones con archivos
2. **Desacoplamiento**: El frontend no necesita conocer detalles de implementación del almacenamiento
3. **Mantenibilidad**: Cambiar el proveedor de almacenamiento solo requiere modificar la biblioteca central
4. **Consistencia**: Nomenclatura y organización uniforme de archivos
5. **Facilidad de migración**: Extensible a módulos existentes sin grandes refactorizaciones

## Notas de Seguridad

- Todas las imágenes subidas pasan por un proceso de limpieza de nombres de archivo
- El sistema solo permite tipos de archivo seguros según la configuración
- Se implementa logging para operaciones fallidas

## Despliegue y Configuración

1. **Configuración de DigitalOcean Spaces**:
   - Crear un bucket y credenciales según la documentación de DO
   - Configurar el CDN o subdominio personalizado

2. **Configuración del sistema**:
   - Actualizar credenciales en `application/config/storage.php`
   - Añadir nuevos módulos a la estructura de carpetas

3. **Migración de archivos existentes**:
   - Utilizar script de migración para mover archivos existentes a Spaces
   - Actualizar registros en la base de datos con nuevas rutas
