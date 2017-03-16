
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
         
          <div class="text-right">
            <div id="toolbar" class="btn-group">
              
                  <button type="button" class="btn btn-default" id="fechapersonalizada">
                    <span>
                      <i class="fa fa-calendar"></i> Fecha
                    </span>
                    <i class="fa fa-caret-down"></i>
                  </button>
                
               <a class="btn btn-default text-center btnnuevo" tyle="margin-bottom :10px" href="<?php echo base_url("index.php/ingresoreporte") ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Mostrar reporte</a>

              <a class="btn btn-default text-center btnnuevo" tyle="margin-bottom :10px" href="<?php echo base_url("Ingresos/Importaciones") ?>">Ingreso Importaciones</a>

              <!--<a class="btn btn-default text-center btnnuevo" tyle="margin-bottom :10px" href="#"></span>Compras Locales</a>-->
            </div> 
                             
          <table class="table-striped" 
              data-toggle="table"
              data-pagination="true"
              data-search="true"
              data-page-size="100"
              data-pagination="true"
              data-height="550"
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
  <div class="modal-dialog modal-95">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detalle de ingresos</h4>
      </div>
      <div class="modal-body">
         <!-- formulario PRIMERA FILA-->
          <div class="row"> <!--PRIMERA FILA-->
             <div class=" col-xs-6 col-sm-6 col-md-3">
              <label>Almacen:</label>
              <input id="almacen_imp" type="text" class="form-control" name="almacen_imp" readonly="">              
             </div>  
             <div class=" col-xs-6 col-sm-6 col-md-3">
              <label for="moneda_imp">Tipo de Ingreso:</label>
              <input id="tipomov_imp" type="text" class="form-control" name="tipomov_imp" readonly="">                           
             </div>  
             <div class="col-xs-6 col-sm-6 col-md-2">
                <label for="fechamov_imp" >Fecha:</label>
                <input id="fechamov_imp" type="text" class="form-control" name="fechamov_imp" readonly="">                 
             </div>
             <div class="col-xs-6 col-sm-6 col-md-2">
                <label for="moneda_imp">Moneda:</label>
                <input id="moneda_imp" type="text" class="form-control" name="moneda_imp" readonly="">
                
             </div>
             <div class="col-xs-12 col-sm-6 col-md-2">
                <label for="fechamov_imp" ># Movimiento:</label>
                <input id="nmov_imp" type="text" class="form-control" name="nmov_imp" readonly="">                
             </div>               
          </div> <!-- div class="form-group-sm row" PRIMERA FILA -->
          <div class="row"> <!--SEGUNDA FILA-->
                 <div class="col-xs-12 col-lg-6 col-md-6">
                   <label >Proveedor:</label>
                   <input id="proveedor_imp" type="text" class="form-control" name="proveedor_imp" readonly="">                   
                 </div>
                 <div class="col-xs-4 col-sm-4 col-md-2">
                       <label>Orden de Compra:</label>
                       <input id="ordcomp_imp" type="text" class="form-control" name="ordcomp_imp" readonly="">                       
                 </div>
                 <div class="col-xs-4 col-sm-4 col-md-2">
                       <label>N° Factura:</label>
                       <input id="nfact_imp" type="text" class="form-control" name="nfact_imp" readonly="">                       
                 </div>
                 <div class="col-xs-4 col-sm-4 col-md-2">
                       <label>N° Ingreso:</label>
                       <input id="ningalm_imp" type="text" class="form-control" name="ningalm_imp" readonly="">
                 </div>
              </div><!-- div class="form-group-sm row" SEGUNDA FILA-->
              <hr>
         <table class="table-striped" 
              data-toggle="table"
              data-pagination="true"
              data-search="true"
              id="tingresosdetalle">           
          </table>
         
          <div class="col-md-6 col-xs-12 pull-right" style="padding: 0px">
            <div class = "input-group col-md-12 col-xs-12">
              <span class = "input-group-addon">$</span>
              <!--mostrar el total de dolares-->
              <input type = "text" class="form-control form-control-sm text-right" id="totalsusdetalle" disabled="">
              <span class = "input-group-addon">Bs</span>
              <!--mostrar el total bolivivanos-->
              <input type = "text" class="form-control form-control-sm text-right" id="totalbsdetalle" disabled="">
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
