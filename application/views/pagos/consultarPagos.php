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
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPagos">
              ModalPagos
            </button>
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

<!-- Modal -->
<div class="modal fade" id="modalPagos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="exampleModalLabel">Recibo Nº 21</h2><!-- Numero de pago -->
      </div>
      <div class="modal-body">
        <div class="col-md-6">
          <p>Almacen: Central HergoA</p>
          <p>Cliente: Banco Solidario S.A</p>
        </div>
        <div class="col-md-6">
          <p>Fecha: 13 de diciembre de 2017</p>
        </div>
        <div class="col-md-12">
          <p>Por concepto de:</p>
            <table class="table">
                <thead>
                  <tr>
                    <th>Lote</th>
                    <th>Factura</th>
                    <th>Monto Bs</th>
                    <th>Monto $</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>69</td>
                    <td>3570</td>
                    <td>700</td>
                    <td>100</td>
                  </tr>
                  <tr>
                    <td>69</td>
                    <td>847</td>
                    <td>350</td>
                    <td>50</td>
                  </tr>
                  <tr>
                    <td>69</td>
                    <td>996</td>
                    <td>50</td>
                    <td>7</td>
                  </tr>
                </tbody>
              </table>
        </div>
        <p>Total</p>
      </div>
      <div class="modal-footer">
        <!-- /.box <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
      </div>
    </div>
  </div>
</div>