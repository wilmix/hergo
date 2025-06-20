# Implementación Avanzada del Sistema de Gestión de Archivos en DigitalOcean Spaces

Este documento describe la implementación avanzada del sistema de gestión de archivos en DigitalOcean Spaces, que proporciona una arquitectura centralizada, reutilizable y robusta para manejar archivos en toda la aplicación.

## Componentes Principales

### 1. Biblioteca FileStorage (PHP)

La biblioteca `FileStorage` proporciona una abstracción para todas las operaciones relacionadas con archivos, encapsulando la lógica de interacción con DigitalOcean Spaces.

**Ubicación:** `application/libraries/FileStorage.php`

**Características principales:**
- Subida de archivos a DigitalOcean Spaces con manejo de errores
- Eliminación de archivos de Spaces
- Generación de nombres de archivo seguros y únicos
- Detección automática de tipos de contenido
- Logs detallados para monitoreo y depuración

**Ejemplo de uso:**

```php
// Cargar la biblioteca
$this->load->library('FileStorage');

// Subir un archivo
$result = $this->filestorage->uploadToSpaces('articulos', $_FILES, 'imagen');
if ($result['success']) {
    // Guardar ruta en la base de datos
    $item->ImagenUrl = $result['path'];
}

// Eliminar un archivo
$this->filestorage->deleteFromSpaces('hg/articulos/imagen.jpg');
```

### 2. Configuración Centralizada

La configuración del sistema de almacenamiento está centralizada en un único archivo, facilitando cambios y mantenimiento.

**Ubicación:** `application/config/storage.php`

**Contenido:**
- Credenciales de acceso a DigitalOcean Spaces
- URL base del CDN
- Estructura de carpetas por módulo
- Configuración de tipos de archivo permitidos y tamaños máximos
- Opciones de almacenamiento alternativo (local, fallback)

### 3. Utilidades Frontend (JavaScript)

La clase `FileUtils` proporciona métodos para gestionar URLs y componentes de carga de archivos en el frontend.

**Ubicación:** `assets/hergo/fileutils.js`

**Características principales:**
- Generación de URLs completas a partir de rutas relativas
- Configuración estándar para componentes de entrada de archivos
- Manejo de eventos de eliminación de imágenes

**Ejemplo de uso:**

```javascript
// Configurar un campo de entrada de archivo
FileUtils.setupFileInput("#imagenes", {
    maxFileSize: 1024,
    allowedFileExtensions: ['jpg', 'png', 'gif']
}, existingImagePath);

// Obtener URL completa para mostrar imagen
const imageUrl = FileUtils.getFullUrl(producto.imagenUrl);
```

## Implementación en el Módulo de Artículos

El módulo de Artículos ha sido completamente refactorizado para utilizar esta nueva arquitectura:

### 1. Base de Datos

Se utiliza el campo `ImagenUrl` para almacenar rutas relativas a las imágenes en Spaces:

```
hg/articulos/1624552477-nombre-imagen.jpg
```

El campo `Imagen` se mantiene para compatibilidad con código existente.

### 2. Controlador (Articulos.php)

El controlador ha sido actualizado para:
- Usar la biblioteca FileStorage para todas las operaciones con imágenes
- Cargar configuración centralizada
- Manejar la eliminación de imágenes existentes
- Proporcionar URLs completas al frontend

### 3. Vista y JavaScript (articulo.js)

El código JavaScript ahora:
- Usa FileUtils para configurar componentes de entrada de archivos
- Maneja correctamente las URLs de imágenes (tanto locales como en Spaces)
- Proporciona una experiencia de usuario mejorada con previsualizaciones

## Migración de Imágenes Existentes

Se han creado dos herramientas para migrar imágenes existentes:

1. **Script CLI de migración masiva:**  
   `scripts/migrate_images.php`
   
   Permite migrar en lotes todas las imágenes existentes para un módulo específico.
   
   ```bash
   # Migrar imágenes de artículos (50 por lote)
   php scripts/migrate_images.php articulos 50
   ```

2. **Migración automática en la edición:**  
   Al editar un artículo, si tiene una imagen local pero no en Spaces,
   se subirá automáticamente a Spaces al guardar.

## Cómo Implementar en Nuevos Módulos

Para integrar este sistema en un nuevo módulo, sigue estos pasos:

### 1. Base de Datos

Agregar un campo para almacenar la ruta del archivo:

```sql
ALTER TABLE tu_tabla ADD COLUMN ArchivoUrl VARCHAR(255) NULL;
```

### 2. Controlador

```php
// En el constructor
$this->load->library("FileStorage");
$this->load->config('storage', TRUE);

// Para subir un archivo
private function _handleFile(&$item, $id)
{
    if (!empty($_FILES['archivo']['name'])) {
        $result = $this->filestorage->uploadToSpaces(
            'nombre_modulo',
            $_FILES,
            'archivo'
        );
        
        if ($result['success']) {
            $item->ArchivoUrl = $result['path'];
        }
    }
    // Manejar eliminación si es necesario
    else if ($this->input->post('archivoEliminado') == '1') {
        $item->ArchivoUrl = "";
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
    // Configurar componente de archivo
    FileUtils.setupFileInput("#archivo", {
        allowedFileExtensions: ['jpg', 'png', 'pdf']
    });
    
    // Manejar evento de eliminación
    FileUtils.handleFileClear("#archivo", "#archivoEliminado");
});

// Para mostrar un archivo existente
function cargarArchivo(archivoUrl) {
    FileUtils.setupFileInput("#archivo", {}, archivoUrl);
}
```

## Buenas Prácticas

1. **Almacenar solo rutas relativas** en la base de datos, nunca URLs completas
2. **Usar la biblioteca FileStorage** para todas las operaciones con archivos
3. **Separar módulos** en carpetas distintas dentro de Spaces
4. **Validar tipos de archivos** permitidos antes de subirlos
5. **Manejar errores** adecuadamente en todas las operaciones
6. **Usar FileUtils** para gestionar URLs en el frontend
7. **Mantener compatibilidad** con imágenes existentes durante la transición

## Notas de Seguridad

- Las imágenes subidas pasan por un proceso de limpieza de nombres de archivo
- Se utilizan nombres únicos basados en timestamp para evitar colisiones
- No se permite la subida de tipos de archivo potencialmente peligrosos
- Se implementa logging detallado para auditoría de operaciones

## Próximos Pasos

1. Implementar sistema de permisos para archivos privados
2. Agregar soporte para versiones de archivos
3. Implementar sistema de caché para optimizar el rendimiento
4. Desarrollar interfaz de administración central para archivos

---

**Contacto para soporte:** [tu@email.com](mailto:tu@email.com)
