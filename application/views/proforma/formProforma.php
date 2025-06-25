<!-- Your Page Content Here -->
<div class="row">
  <div class="col-xs-12">
    <div id="app" class="box">
      <div class="box-header with-border">
        <div v-if="errorMessage" class="alert alert-danger alert-dismissible">
          <button type="button" class="close" @click="errorMessage = ''" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-ban"></i> Error!</h4>
          {{ errorMessage }}
        </div>
      </div>
      <div class="box-body">
        <form action="" method="post" id="form_pedidos" @submit.prevent="store" novalidate>
          <!-- 0 -->
          <input type="text" id="idProforma" value="<?php echo isset($id) ? $id : '' ?>" hidden>
          <!-- 1 -->
          <div class="row">
            <!-- fecha -->
            <div class="form-group col-sm-6 col-md-2" :class="{'has-error': errors.fecha}">
              <label for="fecha">Fecha: <span class="text-danger">*</span></label>
              <vuejs-datepicker v-model="fecha" :language="es" :format="customFormatter" name="fecha" input-class="form-control">
              </vuejs-datepicker>
              <span class="help-block" v-if="errors.fecha">{{ errors.fecha }}</span>
            </div>
            <!-- almacen -->
            <div class="form-group col-sm-6 col-md-2" :class="{'has-error': errors.almacen}">
              <label for="almacen">Almacen: <span class="text-danger">*</span></label>
              <select class="form-control" 
                      :disabled="disabled"
                      v-model="almacen" 
                      id="almacen" 
                      name="almacen">
                  <option value="">Seleccione almacén</option>
                  <option v-for="option in almacenes" 
                          v-bind:value="option.value"
                          v-text="option.alm">
                  </option>
              </select>
              <span class="help-block" v-if="errors.almacen">{{ errors.almacen }}</span>
            </div>
            <!-- MONEDA -->
            <div class="form-group col-sm-6 col-md-2" :class="{'has-error': errors.moneda}">
              <label for="moneda">Moneda: <span class="text-danger">*</span></label>
              <select class="form-control" 
                      v-model="moneda" 
                      name="moneda">
                  <option value="">Seleccione moneda</option>
                  <option v-for="option in monedas" 
                          v-bind:value="option.value"
                          v-text="option.moneda">
                  </option>
              </select>
              <span class="help-block" v-if="errors.moneda">{{ errors.moneda }}</span>
            </div>
            <!-- cliente -->
            <div class="form-group col-sm-12 col-md-2" :class="{'has-error': errors.clienteDato}">
              <label for="cliente">Cliente: <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="clienteDato" name="clienteDato" v-model="clienteDato" maxlength="150">
              <span class="help-block" v-if="errors.clienteDato">{{ errors.clienteDato }}</span>
            </div>
            <!-- complemento -->
            <div class="form-group col-sm-12 col-md-2">
              <label for="cliente">Complemento:</label>
              <textarea class="form-control" id="complemento" rows="1"name="complemento" v-model="complemento" maxlength="200"></textarea>
            </div>
            <!-- <div class="form-group col-sm-12 col-md-4">
              <label for="cliente">Cliente:</label>
              <v-select label="label" :filterable="false" :options="clienteList"
                  @search="onSearchCliente" v-model="cliente" :select-on-key-codes="[9, 13]" :required="!cliente">
                  <template slot="no-options">
                    Busca un cliente..
                  </template>
              </v-select>
            </div> -->
            
            <!-- condicionPago -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="condicionPago">Condicion de Pago:</label>
              <input type="text" class="form-control" name="condicionPago" v-model="condicionPago" maxlength="150">
            </div>
            
            <!-- validez -->
            <div class="form-group col-sm-6 col-md-2" >
              <label for="validez">Validez de Oferta: </label>
              <input type="text" class="form-control" id="validez" name="validez" @change="total" v-model="validez" maxlength="150">
            </div>

            <!-- lugarEntrega -->
            <div class="form-group col-sm-6 col-md-2" >
              <label for="lugarEntrega">Lugar de entrega: </label>
              <input type="text" class="form-control" id="lugarEntrega" name="lugarEntrega" @change="total" v-model="lugarEntrega" maxlength="150">
            </div>

            <!-- descuento -->
            <div class="form-group col-sm-6 col-md-2" >
              <label for="porcentajeDescuento">Descuento % (opcional): </label>
              <input type="number" class="form-control" id="porcentajeDescuento" name="porcentajeDescuento" @change="total" v-model="porcentajeDescuento">
            </div>
            
            <!-- tipo -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="tipo">Tipo:</label>
                <select class="form-control" 
                            v-model="tipo" 
                            id="tipo" 
                            name="tipo">
                    <option v-for="option in tipos" 
                            v-bind:value="option.value"
                            v-text="option.tipo">
                    </option>
                </select>
            </div>

            <!-- garantia -->
            <div class="form-group col-sm-6 col-md-2" >
              <label for="garantia">Garantia: </label>
              <input type="text" class="form-control" id="garantia" name="garantia" v-model="garantia" maxlength="100">
            </div>

            <!-- tiempoEntregaC -->
            <div class="form-group col-sm-6 col-md-2" >
              <label for="tiempoEntregaC">Tiempo de Entrega (opcional): </label>
              <input type="text" class="form-control" id="tiempoEntregaC" name="tiempoEntregaC" v-model="tiempoEntregaC" maxlength="50">
            </div>

           

          </div>

          <!-- 2 -->
          <div class="row">
            <div class="form-group col-sm-12 col-md-4">
              <label for="articulosArray">Artículo:</label>
              <v-select class="style-chooser"
                        :options="articulosArray" 
                        id="codigo" 
                        label="label" 
                        id="value" 
                        :select-on-key-codes="[9, 13]"
                        v-model="articulosArraySelected">
              <template v-slot:option="option">
                <div class="optionsVSelect">
                  <span class="codigoVSelect">{{option.codigo}}</span>
                  <span class="descpVSelect" >{{ option.descp }}</span>
                </div>
              </template>
            </v-select>
            </div>
            <div class="form-group col-sm-12 col-md-8">
              <card-product :selectedart="selectedart" ></card-product>
            </div>
          </div>
          <!-- 3 -->
          <div class="row">
            <!-- vacio -->
            <!-- <div class="form-group col-sm-0 col-md-2">
            </div> -->
            <!-- industria -->
            <div class="form-group col-sm-0 col-md-2">
            <label for="industria">Industria:</label>
              <input type="text" class="form-control" v-model="industria" maxlength="50">
            </div>
            <!-- marca -->
            <div class="form-group col-sm-0 col-md-2">
              <label for="industria">Marca:</label>
              <input type="text" class="form-control" v-model="marca" maxlength="50">
            </div>
            <!-- TiempoEntrega -->
            <div class="form-group col-sm-0 col-md-2">
            <label for="tiempoEntrega">TiempoEntrega:</label>
              <input type="text" class="form-control" v-model="tiempoEntrega" maxlength="50">
            </div>
            <!-- cantidad -->
            <div class="form-group col-sm-4 col-md-2">
              <label for="cantidad">Cantidad:</label>
              <input type="number" style="text-align:right;" class="form-control" v-model.number="cantidad">
            </div>
            <!-- precio -->
            <div class="form-group col-sm-4 col-md-2">
              <label for="precioLista">Precio de Lista:</label>
              <input type="number" style="text-align:right;" class="form-control" v-model.number="precioLista" @keydown.enter.prevent="addDetalle">
            </div>
            <!-- addButton -->
            <div class="col-sm-4 col-xs-12 col-md-2">
              <label></label>
              <button type="button" class="form-control btn btn-success table-layout: fixed" @click="addDetalle">
                Añadir
              </button>
            </div>
          </div>
          <!-- tabla -->
          <div class="row">
          <div class="form-group col-sm-12 col-md-12">
            <table class="table table table-striped table-condensed table-responsive">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Código</th>
                  <th>Imagen</th>
                  <th class="col-md-4">Descripción</th>
                  <th class="text-left">Marca</th>
                  <th class="">Industria</th>
                  <th class="">Tiempo de Entrega</th>
                  <th>Unidad</th>
                  <th class="bg-info text-right">Cantidad</th>
                  <th class="bg-info text-right">Precio</th>
                  <th class="bg-info text-right">Total</th>
                  <th class="bg-info text-center">
                    <button v-if="items.length>0" type="button" class="btn btn-default" aria-label="Right Align" @click="editRow()">
                      <span class="fa fa-pencil" aria-hidden="true"></span>
                    </button>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, key, index) in items" :key="item.idCodigo" :index="index">
                  <th>{{key + 1}} </th>
                  <td class="text-center"> {{ item.codigo}} </td>
                  <td class="text-center">
                    <img :src="item.url_img" class="card-img img-responsive center-block" width="50" height="50" style="background: #CEE6F5;border-radius: 10px;" >
                  </td>
                  <div>
                    <td v-if="edit" class="text-left col-md-4"> 
                      <input type="text" class="form-control input-sm" v-model="item.descrip" v-on:keyup.enter="editRow" maxlength="1000"></input>
                    </td>
                    <td v-else @dblclick="editRow()" class="text-left col-md-4">{{ item.descrip }}</td>
                  </div>
                  <div>
                    <td v-if="edit" class="text-right">
                      <input type="text" class="form-control input-sm text-right" v-model="item.marcaSigla" v-on:keyup.enter="editRow">
                    </td>
                    <td v-else @dblclick="editRow()" class="text-left">{{ item.marcaSigla}}</td>
                  </div>
                  <div>
                    <td v-if="edit" class="text-right">
                      <input type="text" class="form-control input-sm text-right" v-model="item.industria" v-on:keyup.enter="editRow" maxlength="6">
                    </td>
                    <td v-else @dblclick="editRow()" class="text-left">{{ item.industria}}</td>
                  </div>
                  <div>
                    <td v-if="edit" class="text-right">
                      <input type="text" class="form-control input-sm text-right" v-model="item.tiempoEntrega" v-on:keyup.enter="editRow" maxlength="9">
                    </td>
                    <td v-else @dblclick="editRow()" class="text-left">{{ item.tiempoEntrega}}</td>
                  </div>
                  <td class="text-center">{{ item.uni }}</td>
                  <div>
                    <td v-if="edit" class="text-right">
                      <input type="number" class="form-control input-sm text-right" v-model="item.cantidad" v-on:keyup.enter="editRow">
                    </td>
                    <td v-else @dblclick="editRow()" class="text-right">{{ item.cantidad | moneda}}</td>
                  </div>
                  <div>
                    <td v-if="edit" class="text-right">
                      <input type="number" class="form-control input-sm text-right" v-model="item.precioLista" v-on:keyup.enter="editRow">
                    </td>
                    <td v-else @dblclick="editRow()" class="text-right">{{ item.precioLista | moneda}}</td>
                  </div>
                  <td class="text-right">{{ (item.cantidad * item.precioLista) | moneda}}</td>
                  <td class="text-center">
                    <button type="button" class="btn btn-default"  @click="deleteRow(key)">
                      <span class="fa fa-times" aria-hidden="true"></span>
                    </button>
                  </td>
                </tr>
              </tbody>
              <tfoot v-if="items.length>0">
                  <tr>
                    <td class="text-right" colspan="10" ><strong> Total: </strong></td>
                    <td class="text-right bg-primary"><strong> {{ (totalDoc) | moneda }} </strong></td>
                  </tr>
                  <tr v-if="porcentajeDescuento>0">
                    <td class="text-right" colspan="10" ><strong> Descuento {{ porcentajeDescuento }}% :</strong></td>
                    <td class="text-right bg-primary"><strong> {{ (descuento) | moneda }} </strong></td>
                  </tr>
                <tr  v-if="porcentajeDescuento>0">
                  <td class="text-right" colspan="10"><strong>Total Final:</strong> </td>
                  <td class="text-right bg-primary"><strong> {{ (totalFin) | moneda }} </strong></td>
                </tr>
              </tfoot>
            </table>
          </div>
          </div>
          <!-- pie -->
          <div class="row">
            <div class="form-group col-sm-12 col-md-12">
              <!-- cotizacion -->
              <div class="form-group col-sm-12 col-md-12">
                <label for="glosa">Observaciones:</label>
                <textarea class="form-control" id="glosa" rows="3"name="glosa" v-model="glosa" maxlength="1000"></textarea>
              </div>
            </div>
          </div>

          <!-- botones -->
          <div class="row">
              <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                  <i class="fa fa-spinner fa-spin" v-if="isSubmitting"></i>
                  {{ btnGuardar }}
                </button>
                <button type="button" class="btn btn-default" @click="cleanForm" :disabled="isSubmitting">Cancelar</button>
            </div>
          </div>
        </form>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->