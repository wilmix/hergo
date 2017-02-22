<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div class="text-right">
              <a class="btn btn-default text-center btnnuevo" tyle="margin-bottom :10px" href="<?php echo base_url("Ingresos/Importaciones") ?>"></span>Ingreso Importaciones</a>
              <a class="btn btn-default text-center btnnuevo" tyle="margin-bottom :10px" href="#"></span>Compras Locales</a>
          </div> 
                             
          <table class="table-striped" 
              data-toggle="table"
              data-pagination="true"
              data-search="true"
              id="tingresos">           
          </table>
          
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>

<!-- Modal -->
<div id="modalIgresoDetalle" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detalle de ingresos</h4>
      </div>
      <div class="modal-body">
         <table class="table-striped" 
              data-toggle="table"
              data-pagination="true"
              data-search="true"
              id="tingresosdetalle">           
          </table>
          <h1 class="text-right">Total <span id="monedaingreso"></span> <span class="label label-default" id="totaldetalle"></span></h1>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
