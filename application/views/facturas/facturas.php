<div class="row">
  <div class="col-xs-12">
    <div class="box">
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
                  <option value="">TODOS</option>
              </select>
              <select class="btn btn-primary btn-sm" name="tipo_filtro" id="tipo_filtro">
                <option value="">TODOS</option>
                <option value="6">VENTAS CAJA</option>
                <option value="7">NOTA DE ENTREGA</option>
              </select>
             
           </div>

         <table class="table table-condensed" class="table table-striped"
            data-toggle="table"
            data-height="550"
            id="facturasConsulta"
            data-toolbar="#toolbar2"
            data-mobile-responsive="true"
            data-show-columns="true">
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