<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div id="ordenForm" class="box">
    <div class="box-header with-border">
      <div class="forPrint col-md-3">
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
          <i class="fa fa-calendar"></i>&nbsp;
          <span></span> <i class="fa fa-caret-down"></i>
        </div>
      </div>
      <select class="btn btn-primary btn-sm" id="estadoFiltro" name="almacen_filtro">
        <option value="pedidos">PEDIDOS</option>
        <option value="servicios">SERVICIOS</option>
      </select>
      <button  id="show-modal" @click="showModal = true" class="btn btn-default">FacturaServicios</button>
    </div>
      <div class="box-body">
        <table id="tableFP" class="table table-hover display compact" style="width:100%">
        </table>
      <hr>
        <form method="post"  id="formPago">
          <div class="row">
            <div class="form-group col-sm-3 col-md-3">
              <strong>Fecha Pago: </strong>
              <vuejs-datepicker  v-model="fechaPago" :language="es" :format="customFormatter" input-class="form-control">
              </vuejs-datepicker>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-3 col-md-3">
              <div class="upload_image">
                <div class="form-group">
                  <label for="img_route">Comprobante:</label>
                  <input id="url_pago" name="url_pago" type="file" accept="application/pdf">
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
            <div class="row">
              <div class="col-xs-12 text-center">
                <button type="button" class="btn btn-primary" @click="savePago">Guardar</button>
                <button type="button" class="btn btn-default" @click="cancel">Cancelar</button>
            </div>
          </div>
        </form>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
  <!-- use the modal component, pass in the prop -->
  <modal v-if="showModal" @close="showModal = false">
  </modal>
</div> <!-- /.class="row" -->

<script type="text/x-template" id="row-template">
  <tr>
      <td>{{pagar.orden}}</td>
      <td>{{pagar.proveedor}}</td>
      <td>{{pagar.fecha}}</td>
      <td>{{pagar.facN}}</td>
      <td style="width:20%;text-align: right;">{{pagar.montoFactPro | moneda}}</td>
      <td style="width:20%;text-align: right;">
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
            <label v-if="error != ''"  class="label label-danger">{{error}}</label>
        </template>
      </td>
      <td>
        <button type="button" class="btn btn-default" aria-label="Right Align" @click="remove">
          <span class="fa fa-times" aria-hidden="true"></span>
        </button>
      </td>
  </tr>

</script>
<!-- template for the modal component -->
<script type="text/x-template" id="modal-template">
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <slot name="header">
              <h3>Factura Servicios</h3>
            </slot>
          </div>

          <div class="modal-body">
            <slot name="body">
              <form method="post"  id="formFactServ">
                <div class="row">
                  <div class="form-group col-sm-3 col-md-3">
                    <label>Fecha Factura: </label>
                    <vuejs-datepicker  v-model="fecha" :language="es" :format="customFormatter" input-class="form-control">
                    </vuejs-datepicker>
                  </div>
                  <div class="form-group col-sm-3 col-md-3">
                    <label>N° Factura: </label>
                    <input type="text" name="n" class="form-control" v-model="n">
                  </div>
                  <div class="form-group col-sm-2 col-md-2">
                    <label>Tiempo Credito: </label>
                    <input type="number" name="tiempo_credito" class="form-control" v-model="credito">
                  </div>
                  
                  <div class="form-group col-sm-2 col-md-2">
                    <label>Total Factura: </label>
                    <input type="number" name="monto" class="form-control" v-model="monto">
                  </div>

                  <div class="col-xs-4 col-sm-4 col-md-2">
                    <label >Transporte:</label>
                    <select class="form-control form-control-sm"  v-model="transporte" name="transporte">
                        <option selected>MARÍTIMO</option>
                        <option>AÉREO</option>
                        <option>COURRIER</option>
                        <option >TERRESTRE</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-12 col-md-12">
                    <label>Detalle: </label>
                    <input type="text" class="form-control"  v-model="glosa" name="glosa">
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
              </form>
                <div class="row">
                  <div class="alert alert-danger text-center" role="alert" v-if="errors">
                  {{ errors }}
                  </div>
                </div>
            </slot>
          </div>

          <div class="modal-footer text-center">
            <slot name="footer"  class="col-xs-12 ">
            <button type="button" class="btn btn-success" @click="saveFactServ">Guardar</button>
            <button type="button" class="btn btn-default modal-default-button" @click="$emit('close')">Cerrar</button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</script>