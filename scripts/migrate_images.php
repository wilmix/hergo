<?php
/**
 * Script para migrar imágenes locales a DigitalOcean Spaces
 * 
 * Este script utiliza la arquitectura avanzada de gestión de archivos para
 * migrar todas las imágenes desde el almacenamiento local a DigitalOcean Spaces.
 * 
 * Uso desde la raíz del proyecto: 
 *   php scripts/migrate_images.php [módulo] [tamaño_lote] [grupo_bd] [opciones]
 * 
 * Ejemplos:
 *   php scripts/migrate_images.php articulos 50
 *   php scripts/migrate_images.php articulos 20 production
 *   php scripts/migrate_images.php clientes 30 hergo debug
 *   php scripts/migrate_images.php articulos 50 default force
 *   php scripts/migrate_images.php articulos 10 default debug force test
 * 
 * Parámetros:
 *   [módulo]      - El módulo cuyos archivos deseas migrar (default: articulos)
 *   [tamaño_lote] - Número de imágenes a procesar por lote (default: 50)
 *   [grupo_bd]    - Grupo de conexión a usar en database.php (default: local)
 * 
 * Opciones:
 *   debug  - Activar modo depuración con información detallada
 *   force  - Forzar la migración incluso si ya hay una URL (sobrescribe)
 *   test   - Modo prueba (no realiza cambios reales)
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

// Definir constantes que normalmente proporciona CodeIgniter
define('APPPATH', $projectRoot . '/application/');
define('FCPATH', $projectRoot . '/');

// Usar rutas absolutas para los archivos requeridos
require_once $projectRoot . '/application/config/constants.php';
require_once $projectRoot . '/vendor/autoload.php';

// Configurar entrada por línea de comandos
$modulo = isset($argv[1]) ? $argv[1] : 'articulos';
$batchSize = isset($argv[2]) ? (int)$argv[2] : 50;
$dbGroup = isset($argv[3]) ? $argv[3] : 'local';

// Opciones
$debug = in_array('debug', $argv);
$force = in_array('force', $argv);
$test = in_array('test', $argv);

// Cargar configuraciones usando rutas absolutas
require_once $projectRoot . '/application/config/database.php';
require_once $projectRoot . '/application/config/storage.php';

echo "=== Script de migración de imágenes a DigitalOcean Spaces ===\n";
echo "Módulo: $modulo\n";
echo "Tamaño de batch: $batchSize\n";
echo "Grupo de base de datos: $dbGroup\n";
echo "Ruta del proyecto: $projectRoot\n";
echo "Modo debug: " . ($debug ? "Activado" : "Desactivado") . "\n\n";

if ($debug) {
    echo "=== Información de depuración ===\n";
    echo "PHP version: " . phpversion() . "\n";
    echo "Extensiones cargadas: " . implode(", ", get_loaded_extensions()) . "\n";
    echo "Variables definidas:\n";
    echo "- BASEPATH: " . (defined('BASEPATH') ? BASEPATH : 'no definido') . "\n";
    echo "- APPPATH: " . (defined('APPPATH') ? APPPATH : 'no definido') . "\n";
    echo "- FCPATH: " . (defined('FCPATH') ? FCPATH : 'no definido') . "\n\n";
}

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

// Verificar que el grupo de base de datos existe
if (!isset($db[$dbGroup])) {
    echo "ERROR: El grupo de base de datos '$dbGroup' no existe en database.php.\n";
    echo "Grupos disponibles: " . implode(', ', array_keys($db)) . "\n";
    exit(1);
}

// Usar el grupo de conexión especificado
echo "Intentando conectar a base de datos usando grupo '$dbGroup'...\n";
echo "Host: " . $db[$dbGroup]['hostname'] . "\n";
echo "Base de datos: " . $db[$dbGroup]['database'] . "\n\n";

// Aumentar el timeout de conexión
ini_set('default_socket_timeout', 30);
set_time_limit(300); // 5 minutos para todo el script

// Conectar usando la configuración estándar de CodeIgniter
try {
    // Incluir el puerto en la conexión si está especificado
    $port = isset($db[$dbGroup]['port']) ? (int)$db[$dbGroup]['port'] : 3306;
    
    $mysqli = @new mysqli(
        $db[$dbGroup]['hostname'],
        $db[$dbGroup]['username'],
        $db[$dbGroup]['password'], 
        $db[$dbGroup]['database'],
        $port
    );    if ($mysqli->connect_error) {
        echo "Error con mysqli: " . $mysqli->connect_error . "\n";
        echo "Intentando conexión alternativa con PDO...\n";        
        // Alternativa: usar PDO para la conexión
        $port = isset($db[$dbGroup]['port']) ? ";port={$db[$dbGroup]['port']}" : "";
        $dsn = "mysql:host={$db[$dbGroup]['hostname']}{$port};dbname={$db[$dbGroup]['database']};charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 30,
        ];
        try {
            $pdo = new PDO($dsn, $db[$dbGroup]['username'], $db[$dbGroup]['password'], $options);
            echo "Conexión establecida con PDO.\n\n";
            
            // Definir una clase wrapper para mantener compatibilidad con el resto del código
            class MySQLiWrapper {
                private $pdo;
                
                public function __construct($pdo) {
                    $this->pdo = $pdo;
                }
                
                public function query($sql) {
                    $stmt = $this->pdo->query($sql);
                    return new PDOResultWrapper($stmt);
                }
                
                public function prepare($sql) {
                    return new PDOStmtWrapper($this->pdo->prepare($sql));
                }
                
                public function close() {
                    $this->pdo = null;
                }
            }
            
            class PDOResultWrapper {
                private $stmt;
                public $num_rows;
                
                public function __construct($stmt) {
                    $this->stmt = $stmt;
                    $this->stmt->execute();
                    $this->num_rows = $this->stmt->rowCount();
                }
                
                public function fetch_assoc() {
                    return $this->stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
            
            class PDOStmtWrapper {
                private $stmt;
                
                public function __construct($stmt) {
                    $this->stmt = $stmt;
                }
                
                public function bind_param($types, ...$params) {
                    $i = 0;
                    foreach ($params as $param) {
                        $this->stmt->bindValue(++$i, $param);
                    }
                }
                
                public function execute() {
                    return $this->stmt->execute();
                }
                
                public function close() {
                    $this->stmt = null;
                }
            }
            
            $mysqli = new MySQLiWrapper($pdo);
        } catch (PDOException $e) {
            die("Error de conexión con PDO: " . $e->getMessage() . "\n");
        }
    } else {
        echo "Conexión establecida correctamente con mysqli.\n\n";
    }
} catch (Exception $e) {
    die("Excepción al conectar a la base de datos: " . $e->getMessage() . "\n");
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
} else if ($debug) {
    echo "Directorio local encontrado: $localDir\n";
    // Lista algunos archivos del directorio para verificar
    $files = @scandir($localDir);
    if ($files) {
        $imageCount = 0;
        $sampleFiles = [];
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && is_file($localDir . $file)) {
                $imageCount++;
                if (count($sampleFiles) < 5) {
                    $sampleFiles[] = $file;
                }
            }
        }
        echo "Encontrados $imageCount archivos en el directorio local.\n";
        if (!empty($sampleFiles)) {
            echo "Ejemplos de archivos: " . implode(', ', $sampleFiles) . "\n";
        }
    } else {
        echo "No se pudo leer el contenido del directorio. Verificar permisos.\n";
    }
    echo "\n";
}

// Verificar si la columna URL existe, si no, crearla
$result = $mysqli->query("SHOW COLUMNS FROM `$tableName` LIKE '$imageUrlColumn'");
if ($result->num_rows === 0) {
    echo "La columna '$imageUrlColumn' no existe. Creándola...\n";
    $mysqli->query("ALTER TABLE `$tableName` ADD COLUMN `$imageUrlColumn` VARCHAR(255) NULL AFTER `$imageColumn`");
    echo "Columna creada.\n";
} else if ($debug) {
    echo "La columna '$imageUrlColumn' existe en la tabla.\n\n";
    
    // Mostrar algunos ejemplos de registros que deberían ser migrados
    $sampleSql = "SELECT `$idColumn`, `$imageColumn` FROM `$tableName` 
                  WHERE `$imageColumn` IS NOT NULL AND `$imageColumn` != '' 
                  AND (`$imageUrlColumn` IS NULL OR `$imageUrlColumn` = '')
                  LIMIT 5";
    $sampleResult = $mysqli->query($sampleSql);
    
    if ($sampleResult->num_rows > 0) {
        echo "Ejemplos de registros que serán migrados:\n";
        echo "ID | Nombre de Archivo | ¿Existe?\n";
        echo "------------------------------\n";
        while ($row = $sampleResult->fetch_assoc()) {
            $fileExists = file_exists($localDir . $row[$imageColumn]) ? "Sí" : "No";
            echo "{$row[$idColumn]} | {$row[$imageColumn]} | $fileExists\n";
        }
        echo "\n";
    }
}

// Inicializar cliente S3
try {
    // Comprobar qué configuración existe
    if (isset($config['credentials_spaces'])) {
        $credentials = $config['credentials_spaces'];
    } else if (isset($config['credentialsSpacesDO'])) {
        $credentials = $config['credentialsSpacesDO'];
    } else {
        throw new Exception("No se encontraron credenciales para DigitalOcean Spaces");
    }
    
    // Verificar que la configuración tenga todos los campos necesarios
    $requiredFields = ['version', 'region', 'endpoint'];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (!isset($credentials[$field])) {
            $missingFields[] = $field;
        }
    }
    
    // Verificar credenciales
    if (!isset($credentials['credentials']['key']) || !isset($credentials['credentials']['secret'])) {
        $missingFields[] = 'credentials.key y/o credentials.secret';
    }
    
    if (!empty($missingFields)) {
        throw new Exception("Faltan campos requeridos en la configuración: " . implode(', ', $missingFields));
    }
    
    echo "Inicializando cliente S3...\n";
    
    if ($debug) {
        echo "Configuración S3:\n";
        echo "- Region: " . $credentials['region'] . "\n";
        echo "- Endpoint: " . $credentials['endpoint'] . "\n";
        echo "- Version: " . $credentials['version'] . "\n";
        echo "- Key: " . substr($credentials['credentials']['key'], 0, 4) . "..." . "\n";
    }
    
    $s3Client = new Aws\S3\S3Client($credentials);
    $bucket = $config['spaces_bucket'] ?? 'hergo-space';
    
    // Verificar que el bucket existe y es accesible
    if ($debug) {
        try {
            $s3Client->headBucket(['Bucket' => $bucket]);
            echo "Bucket '$bucket' verificado - OK\n";
        } catch (Exception $e) {
            echo "ADVERTENCIA: No se pudo verificar el bucket '$bucket'. El error fue: " . $e->getMessage() . "\n";
        }
    }
    
    echo "Cliente S3 inicializado correctamente. Bucket: $bucket\n\n";
} catch (Exception $e) {
    die("Error al inicializar cliente S3: " . $e->getMessage() . "\n" .
        "Verifica que las credenciales estén correctamente configuradas en storage.php o en las variables de entorno.\n");
}

// Consultar registros con imágenes para migrar
if ($force) {
    // Con force, migra todas las imágenes aunque ya tengan URL
    $sql = "SELECT `$idColumn`, `$imageColumn`, `$imageUrlColumn` FROM `$tableName` 
            WHERE `$imageColumn` IS NOT NULL AND `$imageColumn` != '' 
            LIMIT $batchSize";
    echo "MODO FORCE ACTIVADO: Se migrarán todas las imágenes, incluso las que ya tengan URL.\n\n";
} else {
    // Sin force, solo migra imágenes sin URL
    $sql = "SELECT `$idColumn`, `$imageColumn`, `$imageUrlColumn` FROM `$tableName` 
            WHERE `$imageColumn` IS NOT NULL AND `$imageColumn` != '' 
            AND (`$imageUrlColumn` IS NULL OR `$imageUrlColumn` = '')
            LIMIT $batchSize";
}

if ($test) {
    echo "MODO TEST ACTIVADO: No se realizarán cambios reales en la base de datos ni en Spaces.\n\n";
}

if ($debug) {
    echo "SQL de consulta: $sql\n\n";
    
    // Contar total de imágenes en la tabla
    $countTotalSql = "SELECT COUNT(*) as total FROM `$tableName` WHERE `$imageColumn` IS NOT NULL AND `$imageColumn` != ''";
    $countTotalResult = $mysqli->query($countTotalSql);
    $totalImages = $countTotalResult->fetch_assoc()['total'];
    
    // Contar imágenes ya migradas
    $countMigratedSql = "SELECT COUNT(*) as migrated FROM `$tableName` 
                         WHERE `$imageColumn` IS NOT NULL AND `$imageColumn` != '' 
                         AND `$imageUrlColumn` IS NOT NULL AND `$imageUrlColumn` != ''";
    $countMigratedResult = $mysqli->query($countMigratedSql);
    $migratedImages = $countMigratedResult->fetch_assoc()['migrated'];
    
    echo "Estadísticas de imágenes:\n";
    echo "- Total de registros con imagen: $totalImages\n";
    echo "- Registros ya migrados: $migratedImages\n";
    echo "- Registros pendientes de migrar: " . ($totalImages - $migratedImages) . "\n\n";
}

$result = $mysqli->query($sql);
$totalRegistros = $result->num_rows;

if ($totalRegistros === 0) {
    echo "No hay imágenes por migrar en este lote.\n";
    
    // Verificar si la tabla tiene imágenes en absoluto
    $checkImagesSql = "SELECT COUNT(*) as has_images FROM `$tableName` WHERE `$imageColumn` IS NOT NULL AND `$imageColumn` != ''";
    $checkImagesResult = $mysqli->query($checkImagesSql);
    $hasImages = $checkImagesResult->fetch_assoc()['has_images'];
    
    if ($hasImages === '0') {
        echo "La tabla $tableName no contiene registros con imágenes.\n";
    } else {
        // Verificar si la columna URL existe y tiene valores
        $checkUrlColumnSql = "SELECT COUNT(*) as has_url FROM `$tableName` 
                             WHERE `$imageColumn` IS NOT NULL AND `$imageColumn` != '' 
                             AND `$imageUrlColumn` IS NOT NULL AND `$imageUrlColumn` != ''";
        $checkUrlColumnResult = $mysqli->query($checkUrlColumnSql);
        $hasUrlValues = $checkUrlColumnResult->fetch_assoc()['has_url'];
        
        if ($hasUrlValues > 0) {
            echo "Parece que las imágenes ya han sido migradas. $hasUrlValues registros tienen valores en la columna $imageUrlColumn.\n";
        } else {
            echo "La tabla tiene imágenes pero ninguna cumple con los criterios para migración.\n";
            echo "Verifique que la columna $imageUrlColumn existe y está configurada correctamente.\n";
        }
    }
    
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
$skipped = 0;

while ($row = $result->fetch_assoc()) {
    $id = $row[$idColumn];
    $filename = $row[$imageColumn];
    $existingUrl = isset($row[$imageUrlColumn]) ? $row[$imageUrlColumn] : null;
    $localPath = $localDir . $filename;
    
    if ($existingUrl && !$force) {
        if ($debug) {
            echo "Saltando ID $id, ya tiene URL: $existingUrl\n";
        }
        $skipped++;
        continue;
    }
    
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
        
        if ($test) {
            // En modo test, simular pero no ejecutar
            echo "TEST: Simularía subir '$localPath' a '$spacesPath' con tipo '$contentType'... OK\n";
            $migrados++;
        } else {
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
        }
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        $errores++;
    }
}

// Resumen
echo "\n=== Resumen de migración ===\n";
echo "Total encontrados: $totalRegistros\n";
echo "Éxitos: $migrados\n";
echo "Errores: $errores\n";
echo "Saltados: $skipped\n";

if ($test) {
    echo "\nESTE FUE UN MODO DE PRUEBA - No se realizaron cambios reales.\n";
} else if ($migrados > 0) {
    echo "\nLas imágenes se han migrado con éxito a DigitalOcean Spaces.\n";
    echo "Para ver las imágenes migradas: " . (isset($config['spaces_cdn_url']) ? $config['spaces_cdn_url'] . $spacesDir : "https://$bucket.{$credentials['region']}.digitaloceanspaces.com/$spacesDir") . "\n";
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
