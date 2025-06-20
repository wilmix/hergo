<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Configuración del sistema de almacenamiento de archivos
 */

// Credenciales de DigitalOcean Spaces (S3 compatible storage)
$config['credentialsSpacesDO'] = [
    'version'     => 's3',
    'region'      => 'sfo3', // Ajusta esto según tu región
    'endpoint'    => 'https://sfo3.digitaloceanspaces.com', // Ajusta esto según tu endpoint
    'credentials' => [
        'key'    => 'TU_ACCESS_KEY', // Reemplazar con tu access key real
        'secret' => 'TU_SECRET_KEY', // Reemplazar con tu secret key real
    ],
];

// Nombre del bucket de DigitalOcean Spaces
$config['spaces_bucket'] = 'hergo-space';

// URL base para acceder a los archivos (CDN/subdominio)
$config['spaces_cdn_url'] = 'https://images.hergo.app/';

// Estructura de carpetas para los diferentes módulos
$config['spaces_folders'] = [
    'articulos' => 'hg/articulos',
    'clientes' => 'hg/clientes',
    'usuarios' => 'hg/usuarios',
    'documentos' => 'hg/documentos',
    'facturas' => 'hg/facturas',
    // Añadir más módulos aquí
];

// Tamaños máximos de archivos por tipo (en bytes)
$config['max_file_sizes'] = [
    'image' => 1048576, // 1MB
    'document' => 5242880, // 5MB
    'default' => 2097152, // 2MB
];
