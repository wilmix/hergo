/**
 * Utilidades para gestionar archivos e imágenes en DO Spaces
 */
class FileUtils {
    /**
     * La URL base del CDN/Spaces para cargar archivos
     */
    static baseSpacesUrl = "https://images.hergo.app/";
    
    /**
     * Obtiene la URL completa para un archivo almacenado en Spaces
     * 
     * @param {string} relativePath - Ruta relativa del archivo (ej: "hg/articulos/imagen.jpg")
     * @returns {string} - URL completa del CDN
     */
    static getFullUrl(relativePath) {
        if (!relativePath) return null;
        
        // Si la ruta ya incluye la URL base, devolverla como está
        if (relativePath.startsWith('http')) {
            return relativePath;
        }
        
        return this.baseSpacesUrl + relativePath;
    }
    
    /**
     * Configura un componente fileinput con opciones estándar y capacidad para mostrar imagen previa
     * 
     * @param {string} selector - Selector del elemento de entrada de archivo
     * @param {Object} options - Opciones adicionales para fileinput
     * @param {string} existingImagePath - Ruta relativa a la imagen existente (opcional)
     */
    static setupFileInput(selector, options = {}, existingImagePath = null) {
        // Destruir instancia previa si existe
        $(selector).fileinput('destroy');
        
        // Opciones predeterminadas
        let defaultOptions = {
            language: "es",
            showUpload: false,
            previewFileType: "image",
            maxFileSize: 1024,
            showClose: false
        };
        
        // Si hay una imagen existente, configurar la vista previa
        if (existingImagePath) {
            const imageUrl = this.getFullUrl(existingImagePath);
            const caption = existingImagePath.split('/').pop();
            
            defaultOptions.initialPreview = [imageUrl];
            defaultOptions.initialPreviewAsData = true;
            defaultOptions.initialCaption = caption;
        }
        
        // Combinar opciones predeterminadas con las proporcionadas
        const mergedOptions = {...defaultOptions, ...options};
        
        // Inicializar fileinput con las opciones
        $(selector).fileinput(mergedOptions);
        
        // Reiniciar el campo oculto de eliminación
        $('#imagenEliminada').val(0);
    }
    
    /**
     * Maneja el evento de eliminación de un archivo
     * 
     * @param {string} selector - Selector del elemento de entrada de archivo
     * @param {string} hiddenFieldSelector - Selector del campo oculto para marcar eliminación
     */
    static handleFileClear(selector, hiddenFieldSelector) {
        $(document).off('fileclear', selector).on('fileclear', selector, function() {
            $(hiddenFieldSelector).val(1);
        });
    }
}
