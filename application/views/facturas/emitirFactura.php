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
            <option value="0">TODOS</option>
            <option value="6">VENTAS CAJA</option>
            <option value="7">NOTA DE ENTREGA</option>
          </select>
         </div>

         <table
            data-height="350"
            id="tfacturas"
            data-toolbar="#toolbar2"
            >              
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
                <div class = "input-group col-md-12 col-xs-12">
                  <span class="input-group-addon" id="basic-addon3">Cliente:</span>
                  <input type="text" class="form-control" id="nombreCliente" aria-describedby="sizing-addon2" disabled="">
                  <span class="input-group-addon" id="sizing-addon2">Mov:</span>
                  <input type="text" class="form-control" id="numeroMovT3" aria-describedby="sizing-addon2" disabled="">
                </div>
              </div>
            </div><!--row-->

            <div class="form-group row">
              <div class="col-md-6 col-xs-12"></div>
              <div class="col-md-6 col-xs-12">
                <div class = "input-group col-md-12 col-xs-12">
                  <span class="input-group-addon" id="">
                      <span class="fa fa-calendar"></span>
                  </span>
                    <input type="date" class="form-control" value="<?php echo $fecha ?>" id="fechaFactura" aria-describedby="sizing-addon2">
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
            </div>



           <input id="valuecliente"  class="hidden">
           <input id="valueidcliente"  class="hidden">
           <input id="idAlm"  class="hidden">
        
          <div class="table-responsive" class="table table-condensed">
            <div class="col-md-6 col-xs-12">             
              <table id="tabla2detalle"></table>
            </div>

            <div class="table-responsive">
              <div class = "col-md-12 col-xs-12">
                <table id="tabla3Factura"></table>
                <!--<table id="table"></table>-->
              </div>
            </div>
          </div><!--row-->


          <div class="form-group row">
            <div class="col-md-6 col-xs-12"></div>
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
                <input type="text" class="form-control" id="observacionesFactura" value="" />
              </div>
            </div>


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

<!-- Modal -->
<div id="facPrev" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <!--<h1 class="modal-title modal-ce">FACTURA</h1>-->
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4" class="text-center">
          <img align="center" src="<?php echo base_url("images/hergo.jpeg") ?>" alt="hergo" width="200" height="40">
          <div id="direction">
            <p><b>Casa Matriz - 0</b> <br>
            Av. Montes N° 611 * Zona Challapampa * Casilla 1024 <br>
            Telfs.:2285837 - 2285854 * Fax 2126283 <br>
            La Paz - Bolvia </p>
          </div>
          </div>
          <div class="col-md-4" class="text-center">
          <h1 class="text-center"><b>FACTURA</b></h1>
          </div>
          <div class="text-center" class="col-md-4">
            <p id="nitcss"><b>NIT: <span id="fNit">1000991026 </span></b></p>
            <p>FACTURA N°: <b id="fnumero"></b> <br>
            AUTORIZACION N°: <span id="fauto"></span></p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
          <p>Lugar y Fecha: <span id="fechaFacturaModal"><?= $fecha?></span><br>
          Señor(es): <span id="clienteFactura"></span>  <br>
          OC/Pedido: OL - <span id="clientePedido"></span></p>
          </div>
            <div class="col-md-4">
            <p class="text-center">NIT/CI:  <b><span id="clienteFacturaNit"></span></b></p>
            <p id="direction" class="text-center">Actividad economica: VENTA AL POR MAYOR DE MAQUINARIA, EQUIPO Y MATERIALES</p>
          </div>
        </div>
        <div>
           <table class="table">
            <thead>
              <tr>
                <th>Cantidad</th>
                <th>Unid.</th>
                <th>Codigo</th>
                <th>Articulo</th>
                 <th class="text-right">Precio Unit.</th>
                <th class="text-right">Total</th>
              </tr>
            </thead>
            <tbody id="cuerpoTablaFActura">
            
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-md-10">
            <p>SON: <b id="totalTexto"></b></p>
            <p>NOTA: <span id="notaFactura"></span></p>
            <br>
            <br>
            <p>CODIGO DE CONTROL: <span id="codigoControl">-------</span></p>
            <p>FECHA LIMITE DE EMISIÓN: <span id="fechaLimiteEmision"></span></p>
          </div>
          <div class="col-md-2">
            <input id="totalsinformatobs" class="hidden">
            <p>Total $US:   <span id="totalFacturaSusModal"></span></p>
            <p>Total Bs: <span id="totalFacturaBsModal"></span></p>
            <p>T/C <span id="tipoCambioFacturaModal"></span></p>
           
            <div id="qrcodeimg"></div>
           
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <p align="center">"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LA LEY"</p>
        <p align="center">Ley Nº 453: Está prohibido importar, distribuir  o comercializar productos expirados o prontos a expirar </p>
        <button type="button" class="btn btn-primary" id="guardarFactura">Grabar Factura</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
  
</div>

