<style>
  body {
    /* el tamaño por defecto es 14px */
    font-size: 14px;
}
div.collapse {
    overflow:visible;
    /*or*/
    position:static;
}
</style>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <div id="toolbar2" class="form-inline">
          <button type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
           <span>1 enero, 2017 - 31 diciembre, 2017</span>
            <i class="fa fa-caret-down"></i>
         </button>
          <select class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
            <option value="1">CENTRAL HERGO</option>
            <option value="2">DEPOSITO EL ALT</option>
            <option value="3">POTOSI</option>
            <option value="4">SANTA CRUZ</option>
            <option value="5">COCHABAMBA</option>
            <option value="6">TALLER</option>
            <option value="0">TODOS</option>
          </select>
          <select class="btn btn-primary btn-sm" name="tipo_filtro" id="tipo_filtro">
            <option value="0">TODOS</option>
            <option value="6">VENTAS CAJA</option>
            <option value="7">NOTA DE ENTREGA</option>
          </select>
         </div>
        <!--tabla para facturas validas-->
         <table
            data-height="350"
            id="tfacturas"
            data-toolbar="#toolbar2">              
          </table>
<hr>

        
        <div class="table-responsive" class="table table-condensed">
            <div class="col-md-12 col-xs-12">             
              <table id="tabla2detalle"></table>
            </div>
            
            <!--<div class="table-responsive">
              <div class = "col-md-12 col-xs-12">
                <table id="tabla3Factura"></table>
              </div>
            </div>-->
        </div><!--row-->


            <div class="form-group row">
              <div class="col-md-6 col-xs-12">
                
              </div>
              <div class="col-md-6 col-xs-12">
                <div class = "input-group col-md-12 col-xs-12">
                  <span class = "input-group-addon">$</span>
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalFacturaSus" value="">
                  <span class = "input-group-addon" >Bs</span>
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalFacturaBs" value="">
                 </div>
              </div>
            </div><!--row-->
            
            <div class="row">
                <div class="col-xs-12 col-md-12">
                  <!--insertar costo de articulo a ingresar-->
                  <label for="observaciones_ne">Observaciones:</label>
                  <input type="text" class="form-control" id="observacionesFactura" value="" />
              </div>
            </div>
            <br>
       <!-- /.box-body -->
      </div>
    <!-- /.box -->
    </div>
  <!-- /.col -->
  </div>
</div>
<!-- /.class="row" EMITIR FACTURA-->
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

