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
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#verPagos">Ver pago</button>
           </div>

           <table class="table table-condensed" class="table table-striped"
              data-toggle="table"
              data-height="550"
              id="facturasPagos"
              data-toolbar="#toolbar2"
              data-mobile-responsive="true"
              data-show-columns="true">
            </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>


<!-- Modal -->
<div id="verPagos" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">PAGO N°321(numero del pago)</h4>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>Fecha Fac</th>
              <th>N° Factura</th>
              <th>Cliente</th>
              <th>Monto</th>
              <th>Lote</th>

            </tr>
          </thead>
          <tbody>
            <tr>
              <td>19/01/2017</td>
              <td>25</td>
              <td>SALAS</td>
              <td>1.563.50</td>
              <td>62</td>
            </tr>
            <tr>
              <td>25/07/2017</td>
              <td>564</td>
              <td>SALAS</td>
              <td>986.30</td>
              <td>62</td>
            </tr>
            <tr>
              <td>17/09/2017</td>
              <td>97</td>
              <td>SALAS</td>
              <td>100.50</td>
              <td>62</td>
          </tbody>
        </table>
      </div>

              <div class="col-md-12 col-xs-12">
                <div class = "input-group col-md-12 col-xs-12">
                  <span class = "input-group-addon">$</span>
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalFacturaSus" value="">
                  <span class = "input-group-addon" >Bs</span>
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalFacturaBs" value="">
                 </div>
              </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



<script>
$(document).ready(function(){
  retornarTablaFacturacion();
})
</script>