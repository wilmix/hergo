<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div id="toolbar2" class="form-inline">
            <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
              <?php foreach ($almacen->result_array() as $fila): ?>
              <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
              <?php endforeach ?>
              <option value="">TODOS</option>
            </select>

            <select class="form-control"  data-style="btn-primary" id="clientes_filtro" name="clientes_filtro">
                <?php foreach ($clientes->result_array() as $fila): ?>
                <option value=<?= $fila['idCliente'] ?> ><?= $fila['nombreCliente'].' | '.$fila['documento'] ?></option>
                <?php endforeach ?>
                <option value="">TODOS</option>
            </select>

            <button  type="button" class="btn btn-primary btn-sm" id="pendientes">
                <span>
                <i class="fa fa-share-square"></i>
                </span>
            </button>

          </div>
          <div class="container">
          <div class="text-center">
            <h2>FACTURAS PENDIENTES DE PAGO - <span id="nombreCliente"></span></h2>
            <h3 id="tituloReporte"></h3>
          </div>
          <table 
            id="tablaFacturasPendientes" 
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

