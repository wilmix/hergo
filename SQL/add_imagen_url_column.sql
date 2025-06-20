-- Script para agregar la columna ImagenUrl a la tabla articulos
-- Esta columna almacenará la ruta de la imagen en DigitalOcean Spaces

-- Verificar si la columna ya existe
SET @exist := (SELECT COUNT(*)
              FROM INFORMATION_SCHEMA.COLUMNS
              WHERE TABLE_NAME = 'articulos'
                AND COLUMN_NAME = 'ImagenUrl'
                AND TABLE_SCHEMA = DATABASE());

-- Si no existe, agregarla
SET @query = IF(@exist = 0, 
                'ALTER TABLE articulos ADD COLUMN ImagenUrl VARCHAR(255) NULL AFTER Imagen',
                'SELECT "Column ImagenUrl already exists."');
                
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Actualizar registros existentes
-- Esto establecerá ImagenUrl='hg/articulos/nombre_imagen' para todas las imágenes existentes
UPDATE articulos
SET ImagenUrl = CONCAT('hg/articulos/', Imagen)
WHERE Imagen IS NOT NULL AND Imagen != '' AND ImagenUrl IS NULL;