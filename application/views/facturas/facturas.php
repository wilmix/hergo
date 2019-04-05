<div class="row">
  <div class="col-xs-12">
    <div class="box">
    <button id="export" class="btn btn-success pull-right"><i class="far fa-file-excel"> </i> Excel</button>
    <hr>
      <div class="box-body">
          <div id="toolbar2" class="form-inline">
              <button  type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
                <span>
                  <i class="fa fa-calendar"></i> Fecha
                </span>
                  <i class="fa fa-caret-down"></i>
              </button>
              <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                <?php foreach ($almacen->result_array() as $fila): ?>
                  <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                <?php endforeach ?>
                  <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                  <option value="">TODOS</option>
              </select>
              <select class="btn btn-primary btn-sm" name="tipo_filtro" id="tipo_filtro">
                <option value="" selected="selected">TODOS</option>
                <option value="6">VENTAS CAJA</option>
                <option value="7">NOTA DE ENTREGA</option>
              </select>
              <button  type="button" class="btn btn-primary btn-sm" id="refresh">
                <span>
                  <i class="fa fa-refresh"></i>
                </span>
              </button>
             
           </div>

         <table class="table table-condensed" class="table table-striped"
            data-toggle="table"
            id="facturasConsulta"
            data-toolbar="#toolbar2">
          </table>

      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>

<style>
  #direction {
      font-size: 80%;
      text-align: center;
  }
  #nitcss {
      font-size: 140%;
      text-align: center;
  }
</style>
<script>
$(document).ready(function(){
  retornarTablaFacturacion();
})
</script>