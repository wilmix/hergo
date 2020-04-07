<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div id="toolbar2" class="form-inline">
            <?php
              $this->load->view('reportHead/buttonDate');
              $this->load->view('reportHead/selectAlm');
              $this->load->view('reportHead/buttonRefresh');
            ?>
          </div>
        <table 
          id="tpagos"
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
<style>
  .anulado {
      color:red;
  }
</style>
<!-- Modal -->
<div class="modal fade" id="modalPagos" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document" id="app">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="" v-bind:class="{ anulado:anulado==1?true:false }">Recibo Nº {{numPago}} {{anulado==1?'Anulado':''}} <span class="badge badge-light"> {{ tipoPago }}</span></h2><!-- Numero de pago -->
      </div>
      <div class="modal-body" >
      <div class="row">
        <div class="col-md-3">
          <p>Almacen: {{almacen}}</p>
        </div>  
        <div class="col-md-3">
          <p>Fecha: {{fecha | fechaCorta}}</p>
        </div>  
        <div v-if="tipoPago == 'TRANSFERENCIA'">
            <div class="col-md-3">
              <p>Banco: {{ banco }}</p>
            </div>  
            <div class="col-md-3">
              <p>Vaucher: {{ transferencia }}</p>
            </div>
        </div>
        <div v-if="tipoPago == 'CHEQUE'">
        <div class="col-md-3">
              <p>Cheque: {{ cheque }}</p>
            </div> 
        </div>
        
      </div>
      <div class="row">
      <div class="col-md-12">
          <p>Cliente: {{cliente}}</p><!-- Nombre de cliente que pago  -->
        </div>
      </div>
        
        <div class="col-md-6">
          
        </div>
        <div class="col-md-12">
          <p>Por concepto de:</p>
            <table class="table" >
                <thead>
                  <tr>
                    <th>Lote</th>
                    <th>Suc.</th>
                    <th>N°Factura</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th class="text-right">Monto</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(fila,index) in tabla">
                    <td>{{fila.lote}}</td>
                    <td>{{fila.siglAlm}}</td>
                    <td>{{fila.nFactura}}</td>
                    <td>{{fila.nombreCliente}}</td>
                    <td>{{fila.pagada | estado}}</td>
                    <td class="text-right">{{fila.monto | moneda}}</td>
                    
                  </tr>                 
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="5"> <b> {{retornarTotal() | literal}} </b></td>
                    <td class="text-right"> <b> {{retornarTotal() | moneda}} </b> </td>
                  </tr>
                </tfoot>
            </table>
            <p>Glosa: {{glosa}}</p>   
        </div>
     </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

