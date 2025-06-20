# Guía de Despliegue y Configuración del Sistema de Archivos en Spaces

Esta guía detalla los pasos necesarios para desplegar y configurar correctamente el sistema avanzado de gestión de archivos en Spaces en un entorno de producción.

## Prerrequisitos

1. Cuenta activa en DigitalOcean con acceso a Spaces
2. Credenciales de acceso (Space Key y Space Secret)
3. Bucket de Spaces creado
4. Opcional: CDN configurado para el bucket

## Paso 1: Configuración de Credenciales

Las credenciales de acceso a Spaces NO deben almacenarse en el repositorio. En su lugar, sigue estas opciones:

### Opción A: Archivo de Configuración Privado (Recomendado)

1. Crea un archivo `storage_credentials.php` en `application/config/` con este formato:

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Credenciales de DigitalOcean Spaces
$config['credentials_spaces'] = [
    'version'     => 'latest',
    'region'      => 'nyc3', // O tu región
    'endpoint'    => 'https://nyc3.digitaloceanspaces.com',
    'credentials' => [
        'key'    => 'TU_SPACE_KEY', 
        'secret' => 'TU_SPACE_SECRET',
    ],
];
```

2. Agrega este archivo a `.gitignore` para evitar que se suba al repositorio:

```
application/config/storage_credentials.php
```

3. En `storage.php`, agrega el código para cargar estas credenciales:

```php
// Cargar credenciales privadas si existe el archivo
$credentials_file = APPPATH . 'config/storage_credentials.php';
if (file_exists($credentials_file)) {
    include($credentials_file);
} else {
    // Credenciales por defecto (vacías)
    $config['credentials_spaces'] = [
        'version'     => 'latest',
        'region'      => 'nyc3',
        'endpoint'    => 'https://nyc3.digitaloceanspaces.com',
        'credentials' => [
            'key'    => '', 
            'secret' => '',
        ],
    ];
}
```

### Opción B: Variables de Entorno

1. Configura variables de entorno en tu servidor:

```bash
# En Apache (.htaccess o httpd.conf)
SetEnv DO_SPACES_KEY tuspaceskey
SetEnv DO_SPACES_SECRET tuspacessecret

# En sistema (para CLI)
export DO_SPACES_KEY=tuspaceskey
export DO_SPACES_SECRET=tuspacessecret
```

2. Modifica `storage.php` para usar estas variables:

```php
$config['credentials_spaces'] = [
    'version'     => 'latest',
    'region'      => 'nyc3',
    'endpoint'    => 'https://nyc3.digitaloceanspaces.com',
    'credentials' => [
        'key'    => getenv('DO_SPACES_KEY') ?: '', 
        'secret' => getenv('DO_SPACES_SECRET') ?: '',
    ],
];
```

## Paso 2: Configuración del CDN

Para optimizar la carga de imágenes, es recomendable configurar un CDN:

1. En DigitalOcean, activa el CDN para tu Space
2. O configura un subdominio personalizado (ej: `images.tudominio.com`)
3. Actualiza la configuración en `storage.php`:

```php
// URL base para acceder a los archivos (CDN/subdominio)
$config['spaces_cdn_url'] = 'https://images.tudominio.com/';
```

## Paso 3: Instalación de Dependencias

El sistema requiere el SDK de AWS para PHP:

```bash
composer require aws/aws-sdk-php
```

## Paso 4: Migración de Imágenes Existentes

Para migrar las imágenes existentes, tienes varias opciones:

### Opción A: Migración Masiva por CLI

```bash
# Conectarse por SSH al servidor
cd /ruta/a/tu/proyecto/hergo
php scripts/migrate_images.php articulos 50
```

### Opción B: Migración Gradual

Las imágenes se migrarán automáticamente cuando se editen los artículos.

## Paso 5: Verificación

Para verificar que el sistema está funcionando correctamente:

1. Intenta crear un nuevo artículo con imagen
2. Comprueba que la imagen se sube correctamente a Spaces
3. Verifica que la imagen se muestra correctamente en la interfaz

## Solución de Problemas

### No se pueden subir archivos

- Verifica que las credenciales de Spaces sean correctas
- Comprueba los permisos del bucket (debe permitir escritura)
- Revisa los logs en `application/logs/`

### Las imágenes no se muestran

- Asegúrate de que la URL del CDN es correcta en `storage.php`
- Verifica que el bucket y los objetos tienen permisos públicos de lectura
- Comprueba las políticas CORS del bucket

### Error "Class 'Aws\S3\S3Client' not found"

- Asegúrate de ejecutar `composer install` para instalar todas las dependencias
- Verifica que `vendor/autoload.php` se está cargando correctamente

## Mantenimiento

- Realiza copias de seguridad periódicas de tus Spaces
- Monitoriza el uso de almacenamiento para evitar cargos excesivos
- Considera implementar políticas de ciclo de vida para archivos antiguos

---

Para asistencia adicional, contacta al equipo de soporte.
