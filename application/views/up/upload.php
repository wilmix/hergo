<form action="<?php echo base_url('index.php/principal/subir_imagen') ?>" method="post" enctype="multipart/form-data">
<input id="input-1" name="imagenes[]" type="file" class="file-loading" accept="image/*">
    <input type="submit" value="Submit">
<form>
</div>

   
    <script>
    $("#input-1").fileinput({
        
        showUpload: false,
        previewFileType: "image",
       
    });

</script>