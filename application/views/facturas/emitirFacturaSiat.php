<div class="row" id="app">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <div class="row">
          <div class="col-xs-12 col-md-6">
          <h4 class="text-center">PENDIENTES DE FACTURAR</h4>
          <p class="text-center">{{ tituloAlmacen }}</p>
            <table id="pendientesFacturar"></table>
          </div>
          <div class="col-xs-12 col-md-6">
            <h4 class="text-center" id="tituloEgreso">TITULO</h4>
              <h5 id="clienteEgreso"></h5>
            <table id="pendientesFacturarDetalle"></table>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-xs-12">
            <h3 class="text-center">{{ tituloFactura }} </h3>
            <div v-show="!siatOnline" class="alert alert-danger text-center" role="alert">
              El Sistema del SIAT no esta disponible en estos momentos.
            </div>
          </div>
          <div class="col-md-12">
            <form>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="">Cliente</label>
                  <input type="text" class="form-control" placeholder="Nombre Cliente" v-model="nombreClienteDocumento" disabled>
                  <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                </div>                
                <div class="form-group col-md-3">
                  <label for="">Moneda</label>
                  <select class="form-control" 
                            v-model="moneda" 
                            name="moneda">
                    <option v-for="option in monedas_siat" 
                            v-bind:value="option.id"
                            v-text="option.label">
                    </option>
                  </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="">OC/PedidoNº</label>
                    <input type="text" class="form-control" placeholder="Pedido" v-model="cabecera.clientePedido">
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-3">
                  <label for="">Metodo de Pago</label>
                  <v-select :options="metodos_pago_siat" v-model="metodo_pago_siat" :clearable="false"></v-select>
                </div>
                <div v-show="metodo_pago_siat.label.includes('TARJETA')" class="form-group col-md-3">
                  <label for="">Número de Tarjeta</label>
                  <input type="text" class="form-control" v-model="numeroTarjeta" placeholder="XXXX-XXXX" minlength="9" maxlength="9">
                  <small class="form-text text-muted">Solo si el metodo de pago es tarjeta primeros y ultimos cuatro numeros separados por guión.</small>
                </div>
                
                <div class="form-group col-md-3">
                  <label for="">Emision</label>
                  <select @change="cambioEmision" class="form-control"
                            v-model="emision" 
                            v-bind:disabled="selectEmisionDesabled">
                    <option value="1">Online</option>
                    <!-- <option value="2">Offline</option> -->
                    <option value="3">Contingencia</option>
                  </select>
                </div>

                <!-- <div v-show="cabecera.codigoTipoDocumentoIdentidad == 5" class="form-group col-md-3">
                  <label for="">Código Excepción</label>
                  <select class="form-control"
                            v-model="codigoExcepcion">
                    <option value="0">SIN EXCEPCION</option>
                    <option value="1">ENVIAR EXCEPCION</option>
                  </select>
                  <small class="form-text text-muted">Solo si se desea enviar excepción para el NIT.</small>
                </div> -->
              </div>
              <div class="row">
                <div v-show="emision == 3" class="form-group col-md-3">
                  <label for="">Número de Factura de Contingencia</label>
                  <input type="text" class="form-control" v-model="numeroFacturaContingencia">
                  <small class="form-text text-muted">Solo si es factura de contingencia (MANUAL).</small>
                </div>

                <div v-show="emision == 3" class="form-group col-md-3">
                  <label for="">Fecha Emisión Contingencia</label>
                  <vue-ctk-date-time-picker label="Fecha Emisión Contingencia" format="YYYY-MM-DDTHH:mm:ss.SSS" v-model="fechaEmision" input-class="form-control"></vue-ctk-date-time-picker>
                </div>
              </div>
              <div class="col-md-12 table-responsive">
                <table class="table table table-striped table-condensed table-responsive">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Código</th>
                      <th class="col-md-4">Descripción</th>
                      <th>Unidad</th>
                      <th class="bg-info text-right">Cantidad</th>
                      <th class="bg-info text-right">Precio</th>
                      <th class="bg-info text-right">Total</th>
                      <th class="bg-info text-center">
                        <button v-if="detalle.length>0" type="button" class="btn btn-default" aria-label="Right Align" @click="editRow()">
                          <span class="fa fa-pencil" aria-hidden="true"></span>
                        </button>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, key, index) in detalle" :key="item.detalle_egreso_id" :index="index">
                      <th>{{key + 1}} </th>
                      <td class="text-center"> {{ item.codigoProducto}} </td>
                      <td class="text-center">{{ item.descripcion }}</td>
                      <td class="text-center">{{ item.siatDescripcion }}</td>
                      <div>
                        <td v-if="edit" class="text-right">
                          <input type="number" class="form-control input-sm text-right" v-model="item.cantidad" v-on:keyup.enter="editRow">
                        </td>
                        <td v-else @dblclick="editRow()" class="text-right">{{ item.cantidad | moneda}}</td>
                      </div>
                      <div>
                        <td v-if="edit" class="text-right">
                          <input type="number" class="form-control input-sm text-right" v-model="item.precioUnitario" v-on:keyup.enter="editRow">
                        </td>
                        <td v-else @dblclick="editRow()" class="text-right">{{ item.precioUnitario | moneda}}</td>
                      </div>
                      <td class="text-right">{{ (item.subTotal) | moneda}}</td>
                      <td class="text-center">
                        <button type="button" class="btn btn-default"  @click="deleteRow(key)">
                          <span class="fa fa-times" aria-hidden="true"></span>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot v-if="detalle.length>0">
                      <tr >
                        <td class="text-right" colspan="6"><strong>Total Final:</strong> </td>
                        <td class="text-right bg-primary"><strong> {{ totalFactura | moneda }} </strong></td>
                      </tr>
                      <tr v-show="moneda == 2">
                        <td class="text-right" colspan="6"><strong>Tipo de Cambio:</strong> </td>
                        <td class="text-right bg-primary"><strong> {{ tipoCambio }} </strong></td>
                      </tr>
                      <tr v-show="moneda == 2">
                        <td class="text-right" colspan="6"><strong>Total $U$:</strong> </td>
                        <td class="text-right bg-primary"><strong> {{ montoTotalMoneda | moneda }} </strong></td>
                      </tr>
                  </tfoot>
                </table>
              </div>
              <div class="row">
              <div class="col-xs-12 col-md-12">
                <label for="observaciones_ne">Observaciones:</label>
                <input type="text" class="form-control"  name="glosa" value="" v-model="glosa"/>
                <hr>
              </div>
            </div>
              <!-- botones -->
              <div class="row">
                  <div class="col-xs-12 text-center">
                    
                    <button v-show="validar" type="button" class="btn btn-primary" @click="showModal">Factura</button>
                    <button type="button" class="btn btn-default" @click="cleanBill">Cancelar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- /.box-body -->
      </div> <!-- /.box-body -->
    </div> <!-- /.box  -->
  </div> <!-- /.col -->
  <div id="facturaPrevia" class="modal fade" role="dialog" >
        <div class="modal-dialog modal-lg" id="fac">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <!--<h1 class="modal-title modal-ce">FACTURA</h1>-->
                </div>
            </div>
        </div>    
    </div>
</div> <!-- /.class="row" EMITIR FACTURA-->