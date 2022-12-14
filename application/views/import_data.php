<!DOCTYPE html>
<html>

<body>
    <form enctype="multipart/form-data" method="post" role="form">
        <div class="form-group">
            <label for="exampleInputFile">File Upload</label>
            <input type="file" name="file" id="file" size="150" accept=".csv">
            <p class="help-block">Only Excel/CSV File Import.</p>
        </div>
        <button type="submit" class="btn btn-default" name="submit" value="submit">Upload</button>
        <button type="submit" class="btn btn-default" name="save" value="save">Save</button>
    </form>
</body>

</html>