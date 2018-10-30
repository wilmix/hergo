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
                <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                <option value="">TODOS</option>
            </select>
            <select class="btn btn-primary btn-sm" name="tipo_filtro" id="tipo_filtro">
              <option value="0">TODOS</option>
              <option value="6">VENTAS CAJA</option>
              <option value="7">NOTA DE ENTREGA</option>
            </select>
            <button  type="button" class="btn btn-primary btn-sm" id="refresh">
              <span>
                <i class="fa fa-refresh"></i>
              </span>
          </button>
        </div>
        <table
          data-height="350"
          id="tfacturas"
          data-toolbar="#toolbar2">              
        </table>
        <div class="form-group row">
          <div class="col-md-6 col-xs-12">
            <div class = "input-group col-md-12 col-xs-12">
              <span class="input-group-addon">Cliente:</span>
              <input type="text" class="form-control"  id="nombreClienteTabla1" aria-describedby="sizing-addon2" disabled="">
              <span class="input-group-addon">Mov:</span>
              <input type="text" class="form-control" id="tipoNumEgreso" aria-describedby="sizing-addon2" disabled="">
            </div>
          </div>
          <div class="col-md-6 col-xs-12">
            <div class="input-group col-md-12 col-xs-12">
              <span class="input-group-addon">Cliente: <span class="badge label-success hidden" id="errorCliente"><i class="fa fa-check"></i></span> </span><span style="margin-left: 10px;display: none;" id="cargandocliente" ><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
              <input class="form-control form-control-sm" type="text" id="cliente_factura" name="cliente_factura" value="">
              <input type="text" readonly="true" name="idCliente_factura" id="idCliente_factura" class="hidden" value=""> 
              <span class="input-group-addon hidden facturaManual">N° Factura:</span>
              <input type="number" class="form-control hidden facturaManual" id="numFacManual" aria-describedby="sizing-addon2">
              <input type="text" readonly="true" name="nit_factura" id="nit_factura" class="hidden"  value=""> 
            </div>
          </div>
        </div><!--row-->
        <div class="form-group row">
          <div class="col-md-6 col-xs-12">
            <div class = "input-group col-md-12 col-xs-12">
              <span class="input-group-addon" id="basic-addon3">Pedido:</span>
              <input type="text" class="form-control" id="pedidoClienteT2" aria-describedby="sizing-addon2" disabled="">
              <span class="input-group-addon" id="sizing-addon2">Moneda:</span>
              <input type="text" class="form-control" id="monedaT2" aria-describedby="sizing-addon2" disabled="">
            </div>
        </div>
          <div class="col-md-6 col-xs-12">
            <div class = "input-group col-md-12 col-xs-12">
              <span class="input-group-addon" id="">
                  <span class="fa fa-calendar"></span>
              </span>
                <input type="text" class="form-control" value="" id="fechaFactura" aria-describedby="sizing-addon2">
              <span class="input-group-addon">
                  <span class="fa fa-qrcode"></span>
              </span>
                <select class="form-control" id="tipoFacturacion">
                  <option class="success" value="0">QR</option>
                  <option value="1">MANUAL</option>
                </select>
              <span class="input-group-addon">
                <span class="fa fa-money"></span>
              </span>
                <select class="form-control"  id="moneda">
                  <option class="success" value="1">Bs.</option>
                  <option value="2">$u$</option>
                </select>
              <span class="input-group-btn">
                <button class="btn btn-default" type="button" id="crearFactura"><span class="fa fa-print"></span> Factura</button>
              </span>
            </div>
          </div>
        </div><!--row-->
        <!--hidden-->
        <input id="valuecliente" class="hidden">
        <input id="valueidcliente" class="hidden">
        <input id="idAlm" class="hidden">
        <input id="nitCliente" class="hidden">
        <input id="idclienteHidden" class="hidden">
        
        <div class="table-responsive" class="table table-condensed">
          <div class="col-md-6 col-xs-12">             
            <table id="tabla2detalle"></table>
          </div>
          <div class="table-responsive">
            <div class = "col-md-12 col-xs-12">
              <table id="tabla3Factura"></table>
            </div>
          </div>
        </div>
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
            <label for="observaciones_ne">Observaciones:</label>
            <input type="text" class="form-control" id="observacionesFactura" name="observacionesFactura" value="" />
          </div>
        </div>
       <!-- /.box-body -->
      </div>    <!-- /.box-body -->
    </div>  <!-- /.box  -->
  </div> <!-- /.col -->
</div> <!-- /.class="row" EMITIR FACTURA-->
