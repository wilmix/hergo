# Sistema de Gestión de Archivos con DigitalOcean Spaces

Este documento unifica la información sobre la implementación del sistema centralizado para gestionar archivos en DigitalOcean Spaces, originalmente desarrollado para el módulo de artículos pero diseñado para ser fácilmente extendido a otros módulos de la aplicación.

## Índice

1. [Arquitectura del Sistema](#1-arquitectura-del-sistema)
2. [Archivos Clave](#2-archivos-clave)
3. [Cómo Implementar en Nuevos Módulos](#3-cómo-implementar-en-nuevos-módulos)
4. [Despliegue y Configuración](#4-despliegue-y-configuración)
5. [Migración de Datos Existentes](#5-migración-de-datos-existentes)
6. [Buenas Prácticas y Seguridad](#6-buenas-prácticas-y-seguridad)

## 1. Arquitectura del Sistema

El sistema sigue un patrón MVC con una clara separación entre almacenamiento, lógica de negocio y presentación:

### Backend (PHP)

- **Biblioteca FileStorage**: Proporciona métodos unificados para subir y eliminar archivos de Spaces
- **Configuración Central**: Centraliza las credenciales y configuraciones para todos los módulos
- **Modelo-Controlador**: Los controladores utilizan `FileStorage` para operaciones de archivos, y los modelos almacenan solo rutas relativas

### Frontend (JavaScript)

- **Biblioteca FileUtils**: Clase estática para gestionar URLs de archivos y componentes de carga
- **Manejo de URL**: Construcción dinámica de URLs basadas en rutas relativas y dominio CDN configurado

### Estructura de Carpetas en Spaces

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

## 2. Archivos Clave

### PHP (Backend)

| Archivo | Descripción | Responsabilidad |
|---------|-------------|-----------------|
| `application/libraries/FileStorage.php` | Biblioteca principal | Gestiona todas las operaciones de archivos con Spaces |
| `application/config/storage.php` | Configuración central | Define buckets, rutas y configuración general |
| `application/config/storage_credentials.php` | Credenciales (privado) | Almacena las claves de acceso a Spaces (no versionado) |

### JavaScript (Frontend)

| Archivo | Descripción | Responsabilidad |
|---------|-------------|-----------------|
| `assets/hergo/fileutils.js` | Utilidades de archivos | Maneja URLs y componentes de entrada de archivos |

### Integración en un Módulo (ejemplo: Artículos)

| Archivo | Descripción | Cambios Realizados |
|---------|-------------|-------------------|
| `application/controllers/Articulos.php` | Controlador | Uso de `FileStorage` para subir/eliminar imágenes |
| `application/models/Articulo_model.php` | Modelo | Campos y métodos para gestionar URLs de imágenes |
| `assets/hergo/articulo.js` | Frontend del módulo | Usa `FileUtils` para gestionar imágenes |

### Script de Migración

| Archivo | Descripción | Uso |
|---------|-------------|-----|
| `scripts/migrate_images.php` | Script CLI | Migración de imágenes existentes de local a Spaces |

## 3. Cómo Implementar en Nuevos Módulos

Sigue estos pasos para implementar el sistema en un nuevo módulo:

### 1. Base de Datos

Agrega un campo para almacenar la ruta relativa del archivo:

```sql
ALTER TABLE tu_tabla ADD COLUMN ArchivoUrl VARCHAR(255) NULL;
```

### 2. Controlador

```php
// En el constructor
public function __construct() {
    parent::__construct();
    $this->load->library("FileStorage");
    $this->load->config('storage', TRUE);
}

// Método para manejar la subida/eliminación de archivos
private function _handleFiles(&$item, $id) {
    // 1. Subida de nuevo archivo
    if (!empty($_FILES['archivo']['name'])) {
        $result = $this->filestorage->uploadToSpaces(
            'nombre_modulo',    // Carpeta en Spaces (ej: 'clientes')
            $_FILES,            // Array $_FILES
            'archivo'           // Nombre del campo en el formulario
        );
        
        if ($result['success']) {
            // Guardar la ruta relativa en el modelo
            $item->ArchivoUrl = $result['path'];
            // Opcionalmente guardar solo el nombre para compatibilidad
            $item->Archivo = pathinfo($result['path'], PATHINFO_BASENAME);
        }
    }
    // 2. Eliminación del archivo existente
    else if ($this->input->post('archivoEliminado') == '1') {
        // Si estamos actualizando, obtener el registro actual
        if ($id > 0) {
            $registroActual = $this->tu_modelo->getById($id);
            // Si hay una URL almacenada, eliminar el archivo
            if (!empty($registroActual->ArchivoUrl)) {
                $this->filestorage->deleteFromSpaces($registroActual->ArchivoUrl);
            }
        }
        // Limpiar URL en el modelo
        $item->ArchivoUrl = "";
        $item->Archivo = ""; // Si usas campo de compatibilidad
    }
}

// En el método de guardar
public function guardar() {
    // ... código existente ...
    
    $item = new stdClass();
    $item->Nombre = $this->input->post('nombre');
    // ... otros campos ...
    
    // Manejar archivo
    $this->_handleFiles($item, $id);
    
    // Guardar en la base de datos
    if ($id > 0) {
        $this->tu_modelo->actualizar($id, $item);
    } else {
        $this->tu_modelo->insertar($item);
    }
    
    // ... resto del código ...
}
```

### 3. Vista (HTML)

```html
<!-- Incluir el script de utilidades -->
<script src="<?= base_url('assets/hergo/fileutils.js') ?>"></script>

<!-- Campo de archivo con botón de eliminación -->
<div class="form-group">
    <label for="archivo">Archivo</label>
    <input type="file" id="archivo" name="archivo" class="file" accept="image/*">
    <input type="hidden" id="archivoEliminado" name="archivoEliminado" value="0">
</div>
```

### 4. JavaScript (Frontend)

```javascript
$(document).ready(function() {
    // Configurar componente de archivo
    FileUtils.setupFileInput("#archivo", {
        allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf'],
        maxFileSize: 2000 // En KB
    });
    
    // Manejar evento de eliminación
    FileUtils.handleFileClear("#archivo", "#archivoEliminado");
});

// Para cargar un archivo existente
function cargarDatos(datos) {
    // ... código para cargar otros campos ...
    
    // Si hay una URL de archivo, configurar componente
    if (datos.ArchivoUrl) {
        FileUtils.setupFileInput("#archivo", {}, datos.ArchivoUrl);
    }
}

// Para mostrar un archivo en la interfaz
function mostrarArchivo(archivoUrl) {
    const urlCompleta = FileUtils.getFullUrl(archivoUrl);
    // Usar urlCompleta para mostrar imagen o enlace
    $("#vistaPrevia").attr("src", urlCompleta);
}
```

## 4. Despliegue y Configuración

### Requisitos Previos

1. Cuenta activa en DigitalOcean con acceso a Spaces
2. Bucket creado y configurado con permisos adecuados
3. Credenciales de acceso (Space Key y Space Secret)
4. Opcional: CDN configurado para el bucket

### Configuración de Credenciales

#### Opción A: Archivo de Configuración Privado (Recomendado)

1. Crea un archivo `application/config/storage_credentials.php`:

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Credenciales de DigitalOcean Spaces
$config['credentials_spaces'] = [
    'version'     => 'latest',
    'region'      => 'nyc3',
    'endpoint'    => 'https://nyc3.digitaloceanspaces.com',
    'credentials' => [
        'key'    => 'TU_SPACE_KEY',
        'secret' => 'TU_SPACE_SECRET',
    ],
];
```

2. Agrega este archivo a `.gitignore`

#### Opción B: Variables de Entorno

Configura las variables en el servidor y léelas en la configuración.

### Instalación de Dependencias

El sistema requiere el SDK de AWS para PHP:

```bash
composer require aws/aws-sdk-php
```

## 5. Migración de Datos Existentes

### Uso del Script de Migración

```bash
# Formato básico (desde la raíz del proyecto)
php scripts/migrate_images.php [módulo] [tamaño_lote] [grupo_bd] [opciones]

# Ejemplos:
# Migrar 50 imágenes de artículos usando conexión por defecto
php scripts/migrate_images.php articulos 50

# Migrar con grupo de conexión "production"
php scripts/migrate_images.php articulos 20 production

# Activar modo debug para ver detalles
php scripts/migrate_images.php articulos 50 default debug

# Modo test (sin cambios reales) + forzar migración
php scripts/migrate_images.php articulos 10 default test force

# Limpiar URLs existentes y empezar de nuevo
php scripts/migrate_images.php articulos 50 default clean
```

### Opciones del Script

- `debug`: Mostrar información detallada del proceso
- `test`: Ejecutar en modo prueba (sin cambios reales)
- `force`: Volver a migrar incluso si ya existe URL
- `clean`: Limpiar URLs existentes para empezar de nuevo

### Migración Gradual

Si prefieres un enfoque más conservador, el sistema está diseñado para migrar automáticamente los archivos cuando se editan los registros correspondientes.

## 6. Buenas Prácticas y Seguridad

### Almacenamiento

1. **Estructura de carpetas**: Usa carpetas separadas por módulo (`hg/modulo/`)
2. **Rutas relativas**: Almacena solo rutas relativas en la base de datos, nunca URLs completas
3. **Nombres de archivos**: Genera nombres únicos basados en timestamp para evitar colisiones

### Seguridad

1. **Credenciales**: Nunca incluyas credenciales en archivos versionados
2. **Validación de archivos**: Valida tipos y tamaños antes de subir
3. **CORS**: Configura correctamente las políticas CORS en tu bucket

### Frontend

1. **Desacoplamiento**: Usa `FileUtils` para abstraer los detalles de implementación del almacenamiento
2. **URLs**: Construye URLs completas solo en el frontend, usando la base configurada
3. **Compatibilidad**: Mantén compatibilidad con archivos locales durante la transición

### Beneficios del Sistema

1. **Centralización**: Una única biblioteca gestiona todas las operaciones con archivos
2. **Desacoplamiento**: El frontend no necesita conocer detalles de implementación del almacenamiento
3. **Mantenibilidad**: Cambiar el proveedor o la configuración solo requiere modificar la biblioteca central
4. **Consistencia**: Nomenclatura y organización uniforme de archivos
5. **Escalabilidad**: Mayor capacidad de almacenamiento y mejor rendimiento con CDN

---

## Contacto y Soporte

Para consultas o problemas relacionados con esta implementación, contactar al equipo de desarrollo.
