<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <button id="export" class="btn btn-success pull-right"><i class="far fa-file-excel"> </i> Excel</button>
          <div id="toolbar2" class="form-inline">
            <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
            <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
              <?php foreach ($almacen->result_array() as $fila): ?>
              <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
              <?php endforeach ?>
              <option value="">TODOS</option>
            </select>
          </div>
          <div class="text-center">
            <h2>LIBRO DE VENTAS - <span id="tituloAlmacen"></span></h2>
            <h4 id="ragoFecha"></h4>
          </div>
          <table 
            id="allKardexTable" 
            data-toolbar="#toolbar2"
            data-toggle="table">
          </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>

