<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <div class="forPrint col-md-3">
          <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
          </div>
        </div>
      </div>
      <div class="box-body">
        <table id="table" class="table table-hover table-striped table-sm" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div>
   <!-- /.class="col-xs-12" -->

   <!-- Modal -->
  <div id="pedidoModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-95">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <div class="col-md-4">
              <img  src="<?php echo base_url("images/hergo.jpeg") ?>" alt="hergo" width="200" height="50">
            </div>
            <div class="col-md-4" class="text-center">
              <h2 class="modal-title text-center">
                <span>SOLICITUD DE IMPORTACION </span>
              </h2>
            </div>
            <div class="col-md-4">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4 text-left ">
              <p>
                <strong>PEDIDO POR: </strong>
                <span v-text="pedidoPor"></span>
              </p>
              <p>
                <strong>Nº COTIZACION: </strong>
                <span v-text="cotizacion"></span>
              </p>
              <p>
                <strong>PROVEEDOR: </strong>
                <span v-text="proveedor"></span>
              </p>
            </div>
            <div class="col-md-4 text-center">
              <div>
                <p class="lead"> <strong>PEDIDO Nº: <span v-text="numYear"></span></strong></p>
              </div>
            </div>
            <div class="col-md-4 text-right">
              <p>
                <strong>FECHA DE PEDIDO: </strong>
                <span v-text="fecha"></span>
              </p>
              <p>
                <strong>FECHA DE RECEPCIÓN: </strong>
                <span v-text="recepcion"></span>
              </p>
              <p>
                <strong>FORMA DE PAGO: </strong>
                <span v-text="formaPago"></span>
              </p>
            </div>
          </div>
          <table class="table table-striped table-condensed table-responsive">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Código</th>
                  <th>Número Parte</th>
                  <th>Descripción Fabrica</th>
                  <th>Descripción Hergo</th>
                  <th>Unidad</th>
                  <th>Existencia</th>
                  <th>Rotacíon</th>
                  <th>Precio</th>
                  <th class="bg-info text-center">Cantidad Solicitada</th>
                  <th class="bg-info text-center">Precio Fabrica</th>
                  <th class="bg-info text-center">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, key, index) in items" :key="item.idCodigo"> <!-- v-for="fila in datosFactura" -->
                  <th>{{key + 1}} </th>
                  <td v-text="item.codigo"></td>
                  <td v-text="item.numParte"></td>
                  <td v-text="item.descripFabrica"></td>
                  <td v-text="item.descripcion"></td>
                  <td v-text="item.unidad"></td>
                  <td class="text-right"> {{ item.saldo | moneda }} </td>
                  <td class="text-right"> {{ item.rotacion | moneda }}</td>
                  <td class="text-right"> {{ item.cantidad | moneda }}</td>
                  <td class="text-right"> {{ item.precio | moneda }}</td>
                  <td class="text-right"> {{ item.precioFabrica | moneda }}</td>
                  <td class="text-right"> {{ (item.cantidad * item.precioFabrica) | moneda }}</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td class="text-right" colspan="11"><strong>Total $u$</strong></td>
                  <td class="text-right bg-primary"><strong>{{ totalDoc | moneda }}</strong></td>
                </tr>

                <tr>
                  <td class="text-right" colspan="11"><strong>Total BOB</strong></td>
                  <td class="text-right bg-primary"><strong> {{ (totalDoc * tipoCambio) | moneda }} </strong></td>
                </tr>
              </tfoot>
          </table>
        </div>
        <div class="modal-footer">
          <div class="col-md-12">
            <blockquote class="text-left">
              <p><strong>JUSTIFICACIÓN: </strong> <span v-text="glosa"></span></p>
            </blockquote>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

</div> <!-- /.class="row" -->



