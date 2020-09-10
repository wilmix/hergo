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
      <hr>
        <form method="post"  id="modalPago">
          <div class="row">
            <div class="form-group col-sm-3 col-md-3">
              <strong>Fecha Pago: </strong>
              <vuejs-datepicker  v-model="fechaPago" :language="es" :format="customFormatter" input-class="form-control">
              </vuejs-datepicker>
            </div>
            <div class="form-group col-sm-3 col-md-3">
              <strong>N° Pago: </strong>
              <input type="text" name="nPago" class="form-control" v-model="nPago">
            </div>
            <div class="form-group col-sm-2 col-md-2">
              <strong>Total Pago: </strong>
              <input type="number" name="montoPago" class="form-control" v-model="montoPago">
            </div>
          </div>
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
            <div class="table-responsive form-group">
                <table id="pagos" class="table table-hover table-striped table-bordered table-responsive px-4" >
                  <thead>
                    <tr>
                      <th>Pedido</th>
                      <th>Proveedor</th>
                      <th>Fecha Emisión</th>
                      <th>N° Factura</th>
                      <th style="width:20%;text-align: right">Total</th>
                      <th style="width:20%;text-align: right">Pagar</th>
                      <th style="width:5%"></th>
                    </tr>
                  </thead>  
                  <tbody>
                    <tr is="app-row" v-for="(pagar, index) in pagoslist" :key="pagar.id" :index="index" :pagar="pagar" @removerfila="deleteRow">
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="5" class="text-right"><b>Total</b> </td>          
                      <td class="text-right"> {{ getTotalPago() | moneda}}</td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
            </div>
        </form>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->

<script type="text/x-template" id="row-template">
  <tr>
      <td>{{pagar.orden}}</td>
      <td>{{pagar.proveedor}}</td>
      <td>{{pagar.fecha}}</td>
      <td>{{pagar.facN}}</td>
      <td style="width:20%;text-align: right">{{pagar.monto | moneda}}</td>
      <td style="width:20%;text-align: right">
        <template v-if="!editing">
          <a @click="edit" style="cursor:pointer" class="montopagar">
            <span >{{montopagar | moneda}}
            </span>
          </a>
        </template>
        <template v-else>
            <input type="text" class="inputnumeric montopagar" v-model="montopagar" @keyup.enter="update">
            <div id="botonesinput">                
                <a @click="update">
                  <span class="fa fa-check" aria-hidden="true"></span>                                
                </a>
            </div>
            <!-- <label v-if="error != ''"  class="label label-danger">{{error}}</label> -->
        </template>
      </td>
      <td>
        <button type="button" class="btn btn-default" aria-label="Right Align" @click="remove">
          <span class="fa fa-times" aria-hidden="true"></span>
        </button>
      </td>
  </tr>

</script>
<style>
 .montopagar
  {
    float:left;
    width:80%;
    text-align: right;
  }
</style>