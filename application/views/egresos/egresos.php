<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <div class="text-right" class="btn-group" id="toolbar">
          <a class="btn btn-primary btn-sm hidden-xs" href="<?php echo base_url("egresos/VentasCaja") ?>" target="_blank"><i class="fa fa-plus-circle fa-lg"></i>  VentaCaja</a>

          <a class="btn btn-primary btn-sm hidden-xs" href="<?php echo base_url("egresos/Notaentrega") ?>" target="_blank"><i class="fa fa-plus-circle fa-lg"></i>  NotaEntrega</a>

          <a class="btn btn-primary btn-sm hidden-xs" href="<?php echo base_url("egresos/BajaProducto") ?>" target="_blank"><i class="fa fa-plus-circle fa-lg"></i>  BajaProducto</a>

        </div>

        <div  id="toolbar2" class="form-inline">
          <button  type="button" class="btn btn-primary btn-sm col-sm-5 col-xs-12" id="fechapersonalizada">
            <span>
              <i class="fa fa-calendar"></i> Fecha
            </span>
            <i class="fa fa-caret-down"></i>
          </button>

          <select   class="btn btn-primary btn-sm col-sm-3 col-xs-12" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
            <?php foreach ($almacen->result_array() as $fila): ?>
            <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
            <?php endforeach ?>
            <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
            <option value="">TODOS</option>
          </select>
              
          <select class="btn btn-primary btn-sm col-sm-3 col-xs-10" name="tipo_filtro" id="tipo_filtro">
            <?php foreach ($tipoingreso->result_array() as $fila): ?>
              <option value="<?= $fila['id'] ?>" <?= $fila['id']==7?"selected":""  ?>><?= strtoupper($fila['tipomov']) ?></option>
            <?php endforeach ?>
            <option value="">TODOS</option>
          </select>

          <button  type="button" class="btn btn-primary btn-sm btn-sm col-sm-1 col-xs-2" id="refresh">
            <span>
              <i class="fa fa-refresh"></i>
            </span>
          </button>
        </div>

        <table 
          id="tegresos"
          data-toolbar="#toolbar2">
        </table>

      </div>

      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>




<!-- Modal -->
<div id="modalEgresoDetalle" class="modal fade" role="dialog">
  <div class="modal-dialog modal-95">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="modal-title">
          <h4>
            <h2>
              <span class="label label-primary" id="nombreModal"></span>
              <span class="label label-primary" id="numeromovimiento"></span>
              <span id="facturadonofacturado"></span>
            </h2>
        </div>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class=" col-xs-6 col-sm-6 col-md-3">
            <label>Almacen:</label>
            <input id="almacen_egr" type="text" class="form-control" name="almacen_egr" readonly="">
          </div>
          <div class=" col-xs-6 col-sm-6 col-md-3">
            <label for="moneda_egr">Tipo de Egreso:</label>
            <input id="tipomov_egr" type="text" class="form-control" name="tipomov_egr" readonly="">
          </div>
          <div class="col-xs-6 col-sm-6 col-md-2">
            <label for="fechamov_egr">Fecha:</label>
            <input id="fechamov_egr" type="text" class="form-control" name="fechamov_egr" readonly="">
          </div>
          <div class="col-xs-6 col-sm-6 col-md-2">
            <label for="moneda_egr">Moneda:</label>
            <input id="moneda_egr" type="text" class="form-control" name="moneda_egr" readonly="">
          </div>
          <div style="position: fixed;right: 0;z-index:9999" class="col-xs-4 col-sm-4 col-md-2">
            <label>N° Factura:</label>
            <select class="form-control" multiple style="height: 95px;" id="facturasnum">
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-lg-6 col-md-6">
            <label>Cliente:</label>
            <input id="cliente_egr" type="text" class="form-control" name="cliente_egr" readonly="">
          </div>
          <div class="col-xs-4 col-sm-4 col-md-2">
            <label>Pedido Cliente:</label>
            <input id="pedido_egr" type="text" class="form-control" name="pedido_egr" readonly="">
          </div>
          <div class="col-xs-4 col-sm-4 col-md-2">
            <label>Fecha de Pago:</label>
            <input id="fechaPago" type="text" class="form-control" name="fechaPago" readonly="">
          </div>
        </div>
        <hr>
        <table class="table-striped" data-toggle="table" id="tegresosdetalle">
        </table>
        <div class="row">
          <div class="col-xs-12 col-md-12">
            <label for="observaciones_egr">Observaciones:</label>
            <input type="text" class="form-control" id="obs_egr" name="obs_egr" readonly="" />
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="modal-footer">
        <span id="pendienteaprobado"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
