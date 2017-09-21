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
          <!--<table 
            cellspacing="0" 
            id="tfacturas"
            data-toolbar="#toolbar2">
          </table>-->
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>


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
          <img align="center" src="http://localhost:8080/hergo/images/hergo.jpeg" alt="hergo" width="200" height="40">
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
          <p>Lugar y Fecha: <span id="fechaFacturaModal"></span><br>
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
       
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
  
</div>
<script>
$(document).ready(function(){
  retornarTablaFacturacion();
})
</script>