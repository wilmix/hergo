# Guía para Subir Archivos a DigitalOcean Spaces

Este documento explica cómo utilizar la funcionalidad de subida de archivos a DigitalOcean Spaces desde cualquier controlador de la aplicación.

## Configuración

Las credenciales y configuración para acceder a DigitalOcean Spaces ya están definidas en el archivo `application/config/config.php`:

```php
$config['credentialsSpacesDO'] = [
    'version'     => 'latest',
    'region'      => 'nyc3',      // Tu región 
    'endpoint'    => 'https://nyc3.digitaloceanspaces.com',
    'credentials' => [
        'key'     => 'TU_ACCESS_KEY',
        'secret'  => 'TU_SECRET_KEY',
    ],
];
```

## Uso Básico

Para subir un archivo a Spaces desde cualquier controlador, puedes usar el siguiente método:

```php
/**
 * Sube un archivo a DigitalOcean Spaces
 * 
 * @param array $file Array de archivo (normalmente $_FILES)
 * @param string $folder Carpeta destino dentro del bucket (debe terminar con /)
 * @param string $field Nombre del campo de formulario para el archivo
 * @param string $type Tipo MIME del archivo
 * @return string Nombre del archivo subido (ya con slug aplicado)
 */
public function uploadSpaces($file, $folder, $field, $type)
{
    // Crear cliente S3 usando las credenciales configuradas
    $client = new Aws\S3\S3Client($this->config->item('credentialsSpacesDO'));
    
    // Subir el archivo al bucket
    $uploadObject = $client->putObject([
        'Bucket' => 'hergo-space',          // Nombre del bucket
        'Key' => $folder.$this->get_slug($file[$field]['name']),  // Ruta y nombre del archivo
        'SourceFile' => $file[$field]['tmp_name'],  // Archivo temporal
        'ACL' => 'public-read',    // Permisos públicos para lectura
        'ContentType' => $type     // Tipo de contenido (ej: 'image/jpeg')
    ]);	
    
    // Devolver el nombre del archivo (ya con slug aplicado)
    return $this->get_slug($file[$field]['name']);
}
```

## Ejemplo de Uso

Aquí hay un ejemplo de cómo usar este método en un controlador:

```php
// En un método de controlador que maneja un formulario con subida de archivos
public function guardarDocumento()
{
    // Verificar si se subió un archivo
    if(isset($_FILES['documento']) && $_FILES['documento']['name'] !== '') {
        // Carpeta destino dentro del bucket
        $folder = 'hg/documentos/';
        
        // Subir el archivo
        $nombreArchivo = $this->uploadSpaces(
            $_FILES,              // Array de archivos
            $folder,              // Carpeta destino
            'documento',          // Nombre del campo del formulario
            'application/pdf'     // Tipo MIME
        );
        
        // Guardar referencia en la base de datos
        $data = [
            'nombre' => $this->input->post('nombre'),
            'archivo_url' => $folder . $nombreArchivo  // Guardar ruta relativa
        ];
        
        $this->documentos_model->guardar($data);
    }
    
    // Redireccionar o responder según sea necesario
    redirect('documentos');
}
```

## Estructura de Carpetas Recomendada

Para mantener una estructura organizada, se recomienda seguir esta convención para las carpetas:

- `hg/articulos/` - Para imágenes de artículos
- `hg/documentos/` - Para documentos en general (PDFs, etc.)
- `hg/usuarios/` - Para imágenes de perfil de usuarios
- `hg/clientes/` - Para archivos relacionados con clientes
- `hg/facturas/` - Para archivos de facturas

## Manipulación de URLs en el Frontend

Para mostrar los archivos almacenados en Spaces, use la variable global `baseSpacesUrl` definida en el frontend:

```javascript
// En tu archivo JavaScript
let baseSpacesUrl = "https://images.hergo.app/";

function mostrarArchivo(rutaRelativa) {
    // Combinar la URL base con la ruta relativa almacenada en BD
    const urlCompleta = baseSpacesUrl + rutaRelativa;
    
    // Usar la URL completa para mostrar o enlazar al archivo
    $("#miImagen").attr("src", urlCompleta);
    // O para enlaces
    $("#miEnlace").attr("href", urlCompleta);
}
```

## Función de Slug

Esta función es útil para crear nombres de archivo seguros para la URL:

```php
/**
 * Crea un slug amigable para URLs a partir de un string
 * 
 * @param string $string String a convertir
 * @return string Slug generado
 */
private function get_slug($string_in) {
    $string_output = mb_strtolower($string_in, 'UTF-8');
    
    // Caracteres inválidos y sus reemplazos
    $find = array('¥','µ','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','\'','"');
    $repl = array('-','-','a','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','ny','o','o','o','o','o','o','u','u','u','u','y','y','','' );
    $string_output = str_replace($find, $repl, $string_output);
    
    // Más caracteres inválidos
    $find = array(' ', '&','%','$','·','!','(',')','?','¿','¡',':','+','*','\n','\r\n', '\\', '´', '`', '¨', ']', '[');
    $string_output = str_replace($find, '-', $string_output);
    $string_output = str_replace('--', '', $string_output);
    
    return $string_output;
}
```
