<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Configuración del Sistema de Almacenamiento de Archivos
 * 
 * Este archivo centraliza toda la configuración relacionada con el almacenamiento 
 * de archivos en la nube (DigitalOcean Spaces, AWS S3, etc.)
 */

// Proveedor de almacenamiento activo ('spaces' o 's3')
$config['storage_provider'] = 'spaces';

// Cargar credenciales privadas si existe el archivo
$credentials_file = APPPATH . 'config/storage_credentials.php';
if (file_exists($credentials_file)) {
    include($credentials_file);
} else {
    // Credenciales por defecto (vacías)
    $config['credentials_spaces'] = [
        'version'     => 'latest',
        'region'      => 'nyc3', // Ajusta según la región de tu Spaces
        'endpoint'    => 'https://nyc3.digitaloceanspaces.com', // Ajusta según tu endpoint
        'credentials' => [
            'key'    => getenv('DO_SPACES_KEY') ?: '', 
            'secret' => getenv('DO_SPACES_SECRET') ?: '',
        ],
    ];
}

// Nombre del bucket de DigitalOcean Spaces
$config['spaces_bucket'] = 'hergo-space';

// URL base para acceder a los archivos (CDN/subdominio)
$config['spaces_cdn_url'] = 'https://images.hergo.app/';

// Estructura de carpetas para los diferentes módulos
$config['storage_folders'] = [
    'articulos' => 'hg/articulos',
    'clientes' => 'hg/clientes',
    'usuarios' => 'hg/usuarios', 
    'documentos' => 'hg/documentos',
    'facturas' => 'hg/facturas',
    'reportes' => 'hg/reportes',
    'productos' => 'hg/productos',
    'firma_digital' => 'hg/firma_digital',
    // Añadir más módulos según sea necesario
];

// Tamaños máximos de archivos por tipo (en bytes)
$config['storage_max_file_sizes'] = [
    'image' => 1048576,     // 1MB para imágenes
    'document' => 5242880,  // 5MB para documentos
    'pdf' => 10485760,      // 10MB para PDFs
    'excel' => 15728640,    // 15MB para archivos Excel
    'default' => 2097152,   // 2MB por defecto
];

// Tipos MIME permitidos
$config['storage_allowed_mime_types'] = [
    'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
    'document' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
    'excel' => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
    'csv' => ['text/csv', 'text/plain'],
    'default' => ['application/octet-stream'],
];

// Configuración de almacenamiento local (fallback)
$config['local_storage_path'] = FCPATH . 'uploads/';
