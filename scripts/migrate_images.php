<?php
/**
 * Script para migrar imágenes locales a DigitalOcean Spaces
 * 
 * Este script utiliza la arquitectura avanzada de gestión de archivos para
 * migrar todas las imágenes desde el almacenamiento local a DigitalOcean Spaces.
 * 
 * Uso desde la raíz del proyecto: 
 *   php scripts/migrate_images.php [módulo] [tamaño_lote]
 * 
 * Ejemplos:
 *   php scripts/migrate_images.php articulos 50
 *   php scripts/migrate_images.php clientes 20
 * 
 * Parámetros:
 *   [módulo]      - El módulo cuyos archivos deseas migrar (default: articulos)
 *   [tamaño_lote] - Número de imágenes a procesar por lote (default: 50)
 * 
 * Requisitos:
 *   - PHP con extensiones: mysqli, curl, fileinfo
 *   - Acceso a la base de datos configurada en database.php
 *   - Credenciales de DigitalOcean Spaces configuradas en storage.php o variables de entorno
 * 
 * Notas:
 *   - El script usa rutas absolutas, por lo que puede ejecutarse desde cualquier ubicación
 *   - Se recomienda hacer un respaldo de la base de datos antes de ejecutar
 *   - Para migrar muchas imágenes, ejecute el script varias veces hasta completar
 *   - La columna ImagenUrl debe existir en la tabla correspondiente
 */

// Cargar CodeIgniter
define('BASEPATH', true); // Hack para evitar el acceso directo

// Determinar la ruta base del proyecto independientemente de dónde se ejecute el script
$scriptDir = dirname(__FILE__);
$projectRoot = realpath($scriptDir . '/..');

// Usar rutas absolutas para los archivos requeridos
require_once $projectRoot . '/application/config/constants.php';
require_once $projectRoot . '/vendor/autoload.php';

// Configurar entrada por línea de comandos
$modulo = isset($argv[1]) ? $argv[1] : 'articulos';
$batchSize = isset($argv[2]) ? (int)$argv[2] : 50;

// Cargar configuraciones usando rutas absolutas
require_once $projectRoot . '/application/config/database.php';
require_once $projectRoot . '/application/config/storage.php';

echo "=== Script de migración de imágenes a DigitalOcean Spaces ===\n";
echo "Módulo: $modulo\n";
echo "Tamaño de batch: $batchSize\n";
echo "Ruta del proyecto: $projectRoot\n\n";

// Verificar extensiones requeridas
$requiredExtensions = ['mysqli', 'curl', 'fileinfo'];
$missingExtensions = [];
foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}

if (!empty($missingExtensions)) {
    echo "ADVERTENCIA: Las siguientes extensiones PHP requeridas no están instaladas: " . 
         implode(', ', $missingExtensions) . "\n";
    echo "El script podría fallar. Instale las extensiones e intente nuevamente.\n\n";
}

// Inicializar la conexión a la base de datos
$mysqli = new mysqli(
    $db['default']['hostname'],
    $db['default']['username'],
    $db['default']['password'], 
    $db['default']['database']
);

if ($mysqli->connect_error) {
    die("Error de conexión a la base de datos: " . $mysqli->connect_error . "\n");
}

// Configuración específica por módulo
switch ($modulo) {    case 'articulos':
        $tableName = 'articulos';
        $idColumn = 'idArticulos';
        $imageColumn = 'Imagen';
        $imageUrlColumn = 'ImagenUrl';
        $localDir = $projectRoot . '/assets/img_articulos/';
        $spacesDir = 'hg/articulos/';
        break;
    case 'clientes':
        $tableName = 'clientes';
        $idColumn = 'idCliente';
        $imageColumn = 'Logo';
        $imageUrlColumn = 'LogoUrl';
        $localDir = $projectRoot . '/assets/img_clientes/';
        $spacesDir = 'hg/clientes/';
        break;
    // Agregar más módulos aquí
    default:
        die("Error: Módulo '$modulo' no soportado.\n");
}

// Verificar existencia de directorios y tabla
if (!is_dir($localDir)) {
    die("Error: Directorio local '$localDir' no encontrado.\n");
}

// Verificar si la columna URL existe, si no, crearla
$result = $mysqli->query("SHOW COLUMNS FROM `$tableName` LIKE '$imageUrlColumn'");
if ($result->num_rows === 0) {
    echo "La columna '$imageUrlColumn' no existe. Creándola...\n";
    $mysqli->query("ALTER TABLE `$tableName` ADD COLUMN `$imageUrlColumn` VARCHAR(255) NULL AFTER `$imageColumn`");
    echo "Columna creada.\n";
}

// Inicializar cliente S3
try {
    $s3Client = new Aws\S3\S3Client($config['credentials_spaces']);
    $bucket = $config['spaces_bucket'];
} catch (Exception $e) {
    die("Error al inicializar cliente S3: " . $e->getMessage() . "\n" .
        "Verifica que las credenciales estén correctamente configuradas en storage.php o en las variables de entorno.\n");
}

// Consultar registros con imágenes sin URL
$sql = "SELECT `$idColumn`, `$imageColumn` FROM `$tableName` 
        WHERE `$imageColumn` IS NOT NULL AND `$imageColumn` != '' 
        AND (`$imageUrlColumn` IS NULL OR `$imageUrlColumn` = '')
        LIMIT $batchSize";

$result = $mysqli->query($sql);
$totalRegistros = $result->num_rows;

if ($totalRegistros === 0) {
    echo "No hay imágenes por migrar.\n";
    exit(0);
}

echo "Se encontraron $totalRegistros imágenes para migrar.\n\n";

// Función para crear un slug desde un nombre de archivo
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

// Procesar registros
$migrados = 0;
$errores = 0;

while ($row = $result->fetch_assoc()) {
    $id = $row[$idColumn];
    $filename = $row[$imageColumn];
    $localPath = $localDir . $filename;
    
    echo "Procesando ID $id, archivo: $filename... ";
    
    // Verificar existencia del archivo local
    if (!file_exists($localPath)) {
        echo "ERROR: Archivo no encontrado.\n";
        $errores++;
        continue;
    }
    
    try {
        // Crear nombre de archivo único
        $newFilename = time() . '-' . createSlug(pathinfo($filename, PATHINFO_FILENAME)) . '.' . pathinfo($filename, PATHINFO_EXTENSION);
        $spacesPath = $spacesDir . $newFilename;
        
        // Detectar tipo MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $contentType = finfo_file($finfo, $localPath);
        finfo_close($finfo);
        
        // Subir a Spaces
        $s3Client->putObject([
            'Bucket' => $bucket,
            'Key' => $spacesPath,
            'SourceFile' => $localPath,
            'ACL' => 'public-read',
            'ContentType' => $contentType
        ]);
        
        // Actualizar base de datos
        $stmt = $mysqli->prepare("UPDATE `$tableName` SET `$imageUrlColumn` = ? WHERE `$idColumn` = ?");
        $stmt->bind_param("si", $spacesPath, $id);
        $stmt->execute();
        $stmt->close();
        
        echo "OK\n";
        $migrados++;
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        $errores++;
    }
}

// Resumen
echo "\n=== Resumen de migración ===\n";
echo "Total procesados: $totalRegistros\n";
echo "Éxitos: $migrados\n";
echo "Errores: $errores\n";

if ($migrados > 0) {
    echo "\nLas imágenes se han migrado con éxito a DigitalOcean Spaces.\n";
    echo "Para ver las imágenes migradas: " . $config['spaces_cdn_url'] . $spacesDir . "\n";
}

// Si hay más registros por procesar
$countSql = "SELECT COUNT(*) as restantes FROM `$tableName` 
             WHERE `$imageColumn` IS NOT NULL AND `$imageColumn` != '' 
             AND (`$imageUrlColumn` IS NULL OR `$imageUrlColumn` = '')";
$countResult = $mysqli->query($countSql);
$restantes = $countResult->fetch_assoc()['restantes'];

if ($restantes > 0) {
    echo "\nQuedan $restantes imágenes por migrar. Ejecute el script nuevamente.\n";
} else {
    echo "\n¡Migración completa! Todas las imágenes del módulo $modulo han sido migradas.\n";
}

$mysqli->close();
