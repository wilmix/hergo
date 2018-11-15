<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <div class="text-right" class="btn-group" id="toolbar">
          <a class="btn btn-primary btn-sm" href="<?php echo base_url(" Traspasos/traspasoEgreso") ?>" target="_blank"><i
              class="fa fa-plus-circle fa-lg"></i> Traspasos</a>
        </div>
        <div id="toolbar2" class="form-inline">
          <button type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
            <span>
              <i class="fa fa-calendar"></i> Fecha
            </span>
            <i class="fa fa-caret-down"></i>
          </button>
          <button type="button" class="btn btn-primary btn-sm " id="refresh">
            <span>
              <i class="fa fa-refresh"></i>
            </span>
          </button>
        </div>
        <table id="tTraspasos">
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
            <h2><span class="label label-primary" id="nombreModal">Traspaso de Almacenes</span>
            </h2>
        </div>
      </div>
      <div class="modal-body">
        <!-- formulario PRIMERA FILA-->
        <div class="row">
          <!--PRIMERA FILA-->
          <div class=" col-xs-6 col-sm-6 col-md-3">
            <label>Almacen Origen:</label>
            <input id="almacen_ori" type="text" class="form-control" name="almacen_ori" readonly="">
          </div>
          <div class=" col-xs-6 col-sm-6 col-md-3">
            <label>Almacen Destino:</label>
            <input id="almacen_des" type="text" class="form-control" name="almacen_des" readonly="">
          </div>
          <div class="col-xs-6 col-sm-6 col-md-2">
            <label>Fecha:</label>
            <input id="fechamov_egr" type="text" class="form-control" name="fechamov_egr" readonly="">
          </div>
          <div class="col-xs-6 col-sm-6 col-md-2">
            <label>Pedido:</label>
            <input id="pedido" type="text" class="form-control" readonly="">
          </div>
          <div class="col-xs-4 col-sm-4 col-md-2">
          </div>
        </div>
        <hr>

        <table class="table-striped" data-toggle="table" id="tTraspasodetalle">
        </table>

        <div class="row">
          <div class="col-xs-12 col-md-12">
            <!--insertar costo de articulo a ingresar-->
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