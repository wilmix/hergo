/**
 * Script para migrar imágenes locales a DigitalOcean Spaces
 * 
 * Este script permite migrar todas las imágenes de un directorio local
 * a DigitalOcean Spaces y actualiza la base de datos con las nuevas rutas.
 * 
 * Uso: php migrate_images_to_spaces.php [modulo]
 * Ejemplo: php migrate_images_to_spaces.php articulos
 */

require_once 'vendor/autoload.php'; // Autoload para AWS SDK
require_once 'application/config/database.php'; // Configuración de BD
require_once 'application/config/storage.php'; // Configuración de Spaces

// Obtener el módulo de línea de comandos o usar el default
$modulo = isset($argv[1]) ? $argv[1] : 'articulos';

// Configuración
$localDir = "assets/img_{$modulo}/"; // Directorio local de imágenes
$spacesDir = "hg/{$modulo}/"; // Directorio en Spaces
$tableName = $modulo; // Nombre de la tabla (ajustar si es diferente)
$idField = "id{$modulo}"; // Campo ID (ajustar si es diferente)
$imageField = 'Imagen'; // Campo actual para la imagen
$imageUrlField = 'ImagenUrl'; // Nuevo campo para la URL

// Verificar que el directorio existe
if (!file_exists($localDir)) {
    echo "Error: El directorio {$localDir} no existe.\n";
    exit(1);
}

// Crear cliente S3 para Spaces
$s3Client = new Aws\S3\S3Client($config['credentialsSpacesDO']);
$bucket = $config['spaces_bucket'];

// Conectar a la base de datos
$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);
if ($mysqli->connect_error) {
    echo "Error: No se pudo conectar a la base de datos: " . $mysqli->connect_error . "\n";
    exit(1);
}

// Verificar que la tabla tiene el campo para la URL
$result = $mysqli->query("SHOW COLUMNS FROM `{$tableName}` LIKE '{$imageUrlField}'");
if ($result->num_rows === 0) {
    echo "Agregando columna {$imageUrlField} a la tabla {$tableName}...\n";
    $mysqli->query("ALTER TABLE `{$tableName}` ADD COLUMN `{$imageUrlField}` VARCHAR(255) NULL AFTER `{$imageField}`");
}

// Obtener todos los registros con imágenes
$query = "SELECT `{$idField}`, `{$imageField}` FROM `{$tableName}` WHERE `{$imageField}` IS NOT NULL AND `{$imageField}` != ''";
$result = $mysqli->query($query);

echo "Se encontraron " . $result->num_rows . " registros con imágenes para migrar.\n";
$migrated = 0;
$errors = 0;

while ($row = $result->fetch_assoc()) {
    $id = $row[$idField];
    $imageName = $row[$imageField];
    $localPath = "{$localDir}{$imageName}";
    
    // Verificar si el archivo existe localmente
    if (!file_exists($localPath)) {
        echo "Advertencia: El archivo {$localPath} no existe para el registro ID {$id}.\n";
        continue;
    }
    
    try {
        // Crear nombre único para el archivo
        $newName = time() . '-' . createSlug($imageName);
        $spacesPath = "{$spacesDir}{$newName}";
        
        echo "Migrando {$imageName} a {$spacesPath}...\n";
        
        // Subir archivo a Spaces
        $s3Client->putObject([
            'Bucket' => $bucket,
            'Key' => $spacesPath,
            'SourceFile' => $localPath,
            'ACL' => 'public-read'
        ]);
        
        // Actualizar la base de datos
        $stmt = $mysqli->prepare("UPDATE `{$tableName}` SET `{$imageUrlField}` = ? WHERE `{$idField}` = ?");
        $stmt->bind_param("si", $spacesPath, $id);
        $stmt->execute();
        $stmt->close();
        
        $migrated++;
    } catch (Exception $e) {
        echo "Error al migrar imagen {$imageName}: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== Resumen de la migración ===\n";
echo "Total de registros procesados: " . $result->num_rows . "\n";
echo "Imágenes migradas exitosamente: {$migrated}\n";
echo "Errores: {$errors}\n";

$mysqli->close();

/**
 * Crea un slug amigable para URLs a partir de un string
 * 
 * @param string $string String a convertir
 * @return string Slug generado
 */
function createSlug($string) {
    $string = mb_strtolower($string, 'UTF-8');
    
    // Caracteres inválidos y sus reemplazos
    $find = array('¥','µ','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','\'','"');
    $repl = array('-','-','a','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','ny','o','o','o','o','o','o','u','u','u','u','y','y','','' );
    $string = str_replace($find, $repl, $string);
    
    // Más caracteres inválidos
    $find = array(' ', '&','%','$','·','!','(',')','?','¿','¡',':','+','*','\n','\r\n', '\\', '´', '`', '¨', ']', '[');
    $string = str_replace($find, '-', $string);
    $string = str_replace('--', '', $string);
    
    return $string;
}
