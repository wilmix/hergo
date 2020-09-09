<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div id="ordenForm" class="box">
      <div class="box-header with-border">
        <h3 class="box-title" ></h3>
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
      </div>
      <div class="box-body">
        <table id="tableFP" class="table table-hover display compact" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->


    <!-- Modal -->
    <div id="pagoModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-95">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <div class="col-md-12" class="text-center">
              <h2 class="modal-title text-center">
                <span>Asociar Pago a Factura {{ nFacProv }}</span>
              </h2>
            </div>
            <div class="col-md-4">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        </div>
        <div class="modal-body"> 
          <form method="post"  id="modalAsociarFactura">
            <div class="row">
              <div class="form-group col-sm-3 col-md-6">
                <strong>Proveedor: {{ proveedor }}</strong>
              </div>
              <div class="form-group col-sm-2 col-md-2">
                <strong>Fecha de Emisión:  {{ fechaEmision }}</strong>
              </div>
              <div class="form-group col-sm-2 col-md-2">
                <strong>Crédito: {{ tiempo_credito }} </strong>
              </div>
              <div class="form-group col-sm-2 col-md-2">
                <strong>Total Factura: {{ montoFactPro | moneda }}</strong>
              </div>
            </div>

            <hr>

           <!--  <div class="row">
              <div class="form-group col-sm-3 col-md-3">
                <strong>Fecha Factura: </strong>
                <vuejs-datepicker  v-model="fecha" :language="es" :format="customFormatter" input-class="form-control">
                </vuejs-datepicker>
              </div>

              <div class="form-group col-sm-3 col-md-3">
                <strong>N° Factura: </strong>
                <input type="text" name="n" class="form-control" v-model="">
              </div>
              
              <div class="form-group col-sm-2 col-md-2">
                <strong>Tiempo Credito: </strong>
                <input type="number" name="tiempo_credito" class="form-control" v-model="tiempo_credito">
              </div>
              
              <div class="form-group col-sm-2 col-md-2">
                <strong>Total Factura: </strong>
                <input type="number" name="monto" class="form-control" v-model="totalFacturaC">
              </div>

              <div class="form-group col-sm-2 col-md-2">
                <strong>Saldo: <br>{{montoOrden - totalFacturaC | moneda }} </strong>
              </div>
            </div> -->

            <div class="row">
              <div class="form-group col-sm-3 col-md-3">
                <div class="upload_image">
                  <div class="form-group">
                    <label for="img_route">Comprobante:</label>
                    <input id="url" name="url" type="file" accept="application/pdf">
                  </div>
                </div> 
              </div>
            </div>
          </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-success" @click="saveFactura">Guardar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" >Cerrar</button>
        </div>
      </div>
    </div>
  </div>



</div> <!-- /.class="row" -->