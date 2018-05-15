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
          </div>
          <table 
            id="tpagos" 
            data-toolbar="#toolbar2"
            data-toggle="table"
            data-height="550">
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
        <h2 class="modal-title" id="" v-bind:class="{ anulado:anulado==1?true:false }">Recibo Nº {{numPago}} {{anulado==1?'Anulado':''}}</h2><!-- Numero de pago -->
      </div>
      <div class="modal-body" >
        <div class="col-md-6">
          <p>Almacen: {{almacen}}</p>
          <p>Cliente: {{cliente}}</p><!-- Nombre de cliente que pago  -->
        </div>
        <div class="col-md-6">
          <p>Fecha: {{fecha | fechaCorta}}</p>
        </div>
        <div class="col-md-12">
          <p>Por concepto de:</p>
            <table class="table" >
                <thead>
                  <tr>
                    <th>Lote</th>
                    <th>Cliente</th>
                    <th>N°Factura</th>                    
                    <th class="text-right">Total Factura</th>
                    <th class="text-right">Saldo</th>
                    <th class="text-right">Pagado Bs</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(fila,index) in tabla">
                    <td>{{fila.lote}}</td>
                    <td>{{fila.nombreCliente}}</td>
                    <td>{{fila.nFactura}}</td>
                    <td class="text-right">{{fila.totalFactura | moneda}}</td>
                    <td class="text-right">{{fila.saldo | moneda}}</td>
                    <td class="text-right">{{fila.montoPago | moneda}}</td>
                    
                  </tr>                 
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="5">{{retornarTotal() | literal}}</td>
                    <td class="text-right">{{retornarTotal() | moneda}}</td>
                  </tr>
                </tfoot>
              </table>
        </div>
        <br>
        
        <div class="clearfix"></div>
      </div>
       
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

