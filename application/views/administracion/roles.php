<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div id="toolbar2" class="form-inline">

            <select   class="form-control"  id="users_filtro" name="users_filtro">
                <?php foreach ($users->result_array() as $fila): ?>
                <option value=<?= $fila['id'] ?> ><?= $fila['nombre'] ?></option>
                <?php endforeach ?>
            </select>

          </div>
          <div class="container">
          <div class="text-center">
            <h2>ROLES</h2>
            <h2>Asignar permisos a <span id="nombreUser"></span></h2>
          </div>
          <table 
            id="tablaRoles" 
            data-toolbar="#toolbar2"
            data-toggle="table">
          </table>
          </div>
          
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
