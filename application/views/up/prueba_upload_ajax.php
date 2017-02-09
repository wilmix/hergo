
<input id="archivos" name="imagenes[]" type="file" class="file-loading">
<hr>
<label class="control-label">Select File</label>
<input id="input-1" type="file" class="file" accept="image/*">
</div>

    <?php   
    $directory="imagenes/";
    
    $images = glob($directory . "*.*");
    ?>
    
    <script>
    $("#archivos").fileinput({
    uploadUrl: "http://localhost/hergo/up/upload.php", 
    uploadAsync: false,
    minFileCount: 1,
    maxFileCount: 1,
    showUpload: false, 
    showRemove: false,
    initialPreview: [
    <?php foreach($images as $image){?>
        "<img src='<?php echo "http://localhost/hergo/".$image; ?>' height='120px' class='file-preview-image'>",
    <?php } ?>],
    initialPreviewConfig: [<?php foreach($images as $image){ $infoImagenes=explode("/",$image);?>
    {caption: "<?php echo $infoImagenes[1];?>",  height: "120px", url: "http://localhost/hergo/up/borrar.php", key:"<?php echo $infoImagenes[1];?>"},
    <?php } ?>]
    }).on("filebatchselected", function(event, files) {
    
    $("#archivos").fileinput("upload");
    
    });
    </script>
<!--asdasdasd-->
    <script>
    $("#input-1").fileinput({
        showUpload: false,
        previewFileType: "image",
       
    });

</script>
