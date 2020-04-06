<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <button id="export" class="btn btn-success pull-right"><i class="far fa-file-excel"> </i> Excel</button>
        <button onclick="window.print();" class="btn btn-primary pull-right" ><i class="fa fa-print"> </i> Imprimir</button>
        <hr>
        <div id="toolbar2" class="form-inline">
          <button  type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
            <span>
              <i class="fa fa-calendar"></i> Fecha
            </span>
              <i class="fa fa-caret-down"></i>
          </button>
          <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
              <?php if ($grupsOfUser == 'Nacional') : ?>
                  <?php foreach ($almacen->result_array() as $fila): ?>
                    <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                  <?php endforeach ?>
                    <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                    <option value="">TODOS</option>
              <?php else : ?>
                  <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
              <?php endif; ?>
          </select>
        </div>
        <div class="text-center">
          <h2>NOTAS DE ENTREGA POR FACTURAR - <span id="tituloReporte"></span></h2>
          <h4 id="ragoFecha"></h4>
        </div>
        <table 
          id="tablaNotasEntregaFacturar" 
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

