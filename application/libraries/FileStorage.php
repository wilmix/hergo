<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * FileStorage Library
 * 
 * Una biblioteca centralizada para gestionar la subida de archivos a DigitalOcean Spaces
 * y otras operaciones de almacenamiento de archivos.
 * 
 * @author CopilotGPT
 * @version 1.0
 */
class FileStorage {
    private $CI;
    private $client;
    private $bucket;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('storage', TRUE);
        
        // Inicializar cliente S3 para DigitalOcean Spaces
        $this->client = new Aws\S3\S3Client($this->CI->config->item('credentialsSpacesDO'));
        $this->bucket = $this->CI->config->item('spaces_bucket') ?: 'hergo-space';
    }
    
    /**
     * Sube un archivo a DigitalOcean Spaces
     * 
     * @param string $module Nombre del módulo (para organizar en carpetas)
     * @param array $file Datos del archivo desde $_FILES
     * @param string $fileKey Clave del archivo en $_FILES (default: 'file')
     * @param string $prefix Prefijo opcional adicional para la ruta de almacenamiento
     * @return array Información sobre la subida (éxito, nombre, url, mensaje)
     */
    public function uploadToSpaces($module, $file, $fileKey = 'file', $prefix = '') {
        // Comprobación de seguridad
        if(empty($file[$fileKey]['name'])) {
            return [
                'success' => false,
                'filename' => '',
                'path' => '',
                'message' => 'No se seleccionó ningún archivo'
            ];
        }
        
        try {
            // Crear un nombre único para el archivo
            $originalName = $file[$fileKey]['name'];
            $filename = $this->createUniqueFilename($originalName);
            
            // Definir ruta completa en Spaces
            $path = "hg/" . $module . "/" . ($prefix ? $prefix . "/" : "") . $filename;
            
            // Determinar el Content-Type
            $contentType = $this->getContentType($originalName);
            
            // Subir a DigitalOcean Spaces
            $uploadObject = $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $path,
                'SourceFile' => $file[$fileKey]['tmp_name'],
                'ACL' => 'public-read',
                'ContentType' => $contentType
            ]);
            
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $path,
                'message' => 'Archivo subido correctamente'
            ];
            
        } catch (Exception $e) {
            log_message('error', 'Error al subir archivo a Spaces: ' . $e->getMessage());
            return [
                'success' => false,
                'filename' => '',
                'path' => '',
                'message' => 'Error al subir el archivo: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Elimina un archivo de DigitalOcean Spaces
     * 
     * @param string $path Ruta completa del archivo en Spaces
     * @return bool Si se eliminó correctamente
     */
    public function deleteFromSpaces($path) {
        if (empty($path)) {
            return false;
        }
        
        try {
            $this->client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $path
            ]);
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error al eliminar archivo de Spaces: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crea un nombre de archivo único basado en el timestamp y un slug del nombre original
     * 
     * @param string $filename Nombre original del archivo
     * @return string Nombre de archivo único
     */
    private function createUniqueFilename($filename) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $baseName = pathinfo($filename, PATHINFO_FILENAME);
        $slug = $this->createSlug($baseName);
        
        return time() . '-' . $slug . '.' . $extension;
    }
    
    /**
     * Crea un slug amigable para URLs a partir de un string
     * 
     * @param string $string String a convertir
     * @return string Slug generado
     */
    private function createSlug($string) {
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
    
    /**
     * Determina el Content-Type basado en la extensión del archivo
     * 
     * @param string $filename Nombre del archivo
     * @return string Content-Type correspondiente
     */
    private function getContentType($filename) {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        $contentTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            // Añadir más tipos según sea necesario
        ];
        
        return isset($contentTypes[$extension]) ? $contentTypes[$extension] : 'application/octet-stream';
    }
}
