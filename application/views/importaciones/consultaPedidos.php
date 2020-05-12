<style>
  .modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

</style>
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
              <span>Danny Gonzales</span>
            </p>
            <p>
              <strong>Nº COTIZACION: </strong>
              <span>COT/546556</span>
            </p>
            <p>
              <strong>PROVEEDOR:: </strong>
              <span>3H INDUSTRIALES SRL</span>
            </p>
          </div>
          <div class="col-md-4 text-center">
            <div>
              <p class="lead"> <strong>PEDIDO Nº: <span>003/20</span></strong></p>
            <!-- <h3><b>PEDIDO Nº: </b> <span > 003/20</span></h3> -->
            </div>
          </div>
          <div class="col-md-4 text-right">
            <p>
              <strong>FECHA DE PEDIDO: </strong>
              <span>10/01/2020</span>
            </p>
            <p>
              <strong>FECHA DE RECEPCIÓN: </strong>
              <span>10/12/2020</span>
            </p>
            <p>
              <strong>FORMA DE PAGO: </strong>
              <span>CRÉDITO</span>
            </p>
          </div>
        </div>
        <table class="table">
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
            <tbody id="cuerpoTablaFActura">
              <tr v-for="fila in datosFactura">
                <td>
                  1
                </td>
                <td>
                  TM1100
                </td>
                <td>
                  70070892446
                </td>
                <td class="text-uppercase">
                  Lorem ipsum dolor, sit amet consectetur adipisicing elit. 
                </td>
                <td>
                  RESPIRADOR 8210 N95 CONTRA POLVOS Y PARTICULAS
                </td>
                <td>
                  PZA
                </td>
                <td class="text-right">
                  1,544.00
                </td>
                <td  class="text-right">
                  48,800.00
                </td>
                <td  class="text-right">
                  9.45
                </td>
                <td  class="text-right bg-info">
                  10.00
                </td>
                <td  class="text-right bg-info">
                  10.00
                </td>
                <td  class="text-right bg-info">
                  100.00
                </td>
              </tr>
            </tbody>
            <tfoot v-if="items.length>0">
              <tr>
                <td class="text-right" colspan="11" style="padding-top: 0;padding-left: 0;"><strong>Total $u$</strong></td>
                <td class="text-right bg-primary" style="padding-top: 0;padding-left: 0;"><strong>100.00</strong></td>
              </tr>

              <tr>
                <td class="text-right" colspan="11" style="padding-top: 0;padding-left: 0;"><strong>Total BOB</strong></td>
                <td class="text-right bg-primary" style="padding-top: 0;padding-left: 0;"><strong> 696.00</strong></td>
              </tr>
            </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <div class="col-md-12">
          <blockquote class="text-left">
            <p><strong>JUSTIFICACIÓN: </strong> Amet deserunt iste ab voluptates beatae ut excepturi voluptatum veniam, quod obcaecati temporibus quo impedit laboriosam, voluptatibus fugit accusantium architecto. Totam, dolores.</p>
          </blockquote>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</div>

</div> <!-- /.class="row" -->



