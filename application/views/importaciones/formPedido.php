<!-- Your Page Content Here -->
<div class="row">
  <div class="col-xs-12">
    <div id="app" class="box">
      <div class="box-header with-border">
        <h3 class="box-title" v-text="title"></h3>
      </div>
      <div class="box-body">
        <form action="" method="post" id="form_pedidos">
          <!-- 0 -->
          <input type="text" id="idPedido" value="<?php echo isset($id) ? $id : '' ?>" hidden>
          <!-- 1 -->
          <div class="row">
            <!-- fecha -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="fecha">Fecha:</label>
                <vuejs-datepicker  v-model="fecha" :language="es" :format="customFormatter" name="fecha" input-class="form-control">
                </vuejs-datepicker>
            </div>
            <!-- recepcion -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="recepcion">Recepción:</label>
              <vuejs-datepicker v-model="recepcion" :language="es" name="recepcion" 
                                :format="customFormatter" input-class="form-control">
              </vuejs-datepicker>
            </div>
            <!-- proveedor -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="proveedor">Proveedor:</label>
              <v-select label="label" :filterable="false" :options="proveList"
                  @search="onSearchProveedor" v-model="selectedProv">
                  <template slot="no-options">
                    Busca un proveedor..
                  </template>
              </v-select>
            </div>
            <!-- pedidoPor -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="pedidoPor">Pedido por:</label>
              <input type="text" class="form-control" id="pedidoPor" name="pedidoPor" v-model="pedidoPor">
            </div>
            <!-- cotizacion -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="cotizacion">Nº de Cotización:</label>
              <input type="text" class="form-control" id="cotizacion" name="cotizacion" v-model="cotizacion">
            </div>
            <!-- formaPago -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="formaPago">Forma de Pago:</label>
              <v-select :options="formaPagoList" v-model="formaPago"></v-select>
            </div>
          </div>
          <!-- 2 -->
          <div class="row">
            <div class="form-group col-sm-12 col-md-4">
              <label for="codigo">Articulo:</label>
              <v-select label="label" :filterable="false" :options="articulosList"
                  @search="onSearch" v-model="selectedArticulo" id="codigoArt">
                  <template slot="no-options">
                    Busca un artículo..
                  </template>
              </v-select>
            </div>
            <div class="form-group col-sm-12 col-md-8">
              <div class="card mb-12" style="background: #CEE6F5;border: #B1D6ED 2px solid;border-radius: 10px;">
                <div class="row no-gutters">
                  <div class="col-md-2 col-sm-3 col-lg-2">
                    <img :src="url_img" class="card-img img-responsive center-block" width="200" height="200" style="background: #CEE6F5;border-radius: 10px;" >
                  </div>
                  <div class="col-md-6 col-sm-5 col-lg-7">
                    <div class="card-body" v-show="selectedArticulo">
                      <blockquote>
                      <h4 class="font-weight-bold" v-text="codigo"></h4>
                        <p class="card-text" v-html="descripcion + ' - ' + unidad + '<br>' + 
                                                      'Fábrica: ' + descripFabrica + '<br>' +
                                                      'Nº Parte: ' + numParte" > 
                        </p>
                      </blockquote>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-4 col-lg-3">
                    <div class="card-body" v-show="selectedArticulo">
                      <blockquote>
                        <p class="card-text text-center"> 
                          <span class="font-weight-bold" v-text="">Saldo: </span> {{saldo | moneda }} <br>
                          <span class="font-weight-bold">Precio $u$:</span>  {{(precio / tipoCambio) | moneda }} <br>
                          <span class="font-weight-bold">CPP $u$:</span>  {{ (cpp / tipoCambio) | moneda }} <br>
                          <span class="font-weight-bold">Rotaciòn:</span>  {{rotacion| moneda }}
                      </p>
                      </blockquote>
                    </div>
                  </div>
                  </div>
              </div>
            </div>
          </div>
          <!-- 3 -->
          <div class="row">
            <!-- vacio -->
            <div class="form-group col-sm-0 col-md-6">
            </div>
            <!-- cantidad -->
            <div class="form-group col-sm-4 col-md-2">
              <label for="cantidad">Cantidad:</label>
              <input type="number" style="text-align:right;" class="form-control" v-model.number="cantidad">
            </div>
            <!-- precio -->
            <div class="form-group col-sm-4 col-md-2">
              <label for="precio">Precio de Fabrica $u$:</label>
              <input type="number" style="text-align:right;" class="form-control" v-model.number="precioFabrica">
            </div>
            <!-- addButton -->
            <div class="col-sm-4 col-xs-12 col-md-2">
              <label></label>
              <button type="button" class="form-control btn btn-success" @click="addDetalle">
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
                  <th class="bg-info text-center" colspan="2">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, key, index) in items" :key="item.idCodigo" :index="index">
                  <th>{{key + 1}} </th>
                  <td class="text-center"> {{ item.codigo}} </td>
                  <td> {{item.numParte}} </td>
                  <td> {{ item.descripFabrica}} </td>
                  <td> {{ item.descripcion }} </td>
                  <td class="text-center">{{ item.unidad }}</td>
                  <td class="text-right">{{ item.saldo | moneda }}</td>
                  <td class="text-right">{{ item.rotacion | moneda }}</td>
                  <td class="text-right">{{ item.precio | moneda }}</td>
                  <td class="text-right">{{ item.cantidad | moneda}}</td>
                  <td class="text-right">{{ item.precioFabrica | moneda}}</td>
                  <td class="text-right">{{ (item.cantidad * item.precioFabrica) | moneda}}</td>
                  <td>
                    <button type="button" class="btn btn-default" aria-label="Right Align" @click="deleteRow(key)">
                      <span class="fa fa-times" aria-hidden="true"></span>
                    </button>
                  </td>
                </tr>
              </tbody>
              <tfoot v-if="items.length>0">
                <tr>
                  <td class="text-right" colspan="11" ><strong> Total $u$ </strong></td>
                  <td class="text-right bg-primary"><strong> {{ totalDoc | moneda }} </strong></td>
                </tr>
                <!-- <tr>
                  <td class="text-right" colspan="11">T/C</td>
                  <td class="text-right"> {{ tipoCambio | moneda }} </td>
                </tr> -->
                <tr>
                  <td class="text-right" colspan="11"><strong>Total BOB</strong> </td>
                  <td class="text-right bg-primary"><strong> {{ (totalDoc * tipoCambio) | moneda }} </strong></td>
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
                <label for="glosa">Justificación:</label>
                <input type="text" class="form-control" id="glosa" name="glosa" v-model="glosa">
              </div>
            </div>
          </div>
          <!-- botones -->
          <div class="row">
              <div class="col-xs-12 text-center">
                <button type="button" class="btn btn-primary" @click="store" v-text="btnGuardar"></button>
                <button type="button" class="btn btn-default" @click="cancel">Cancelar</button>
            </div>
          </div>
        </form>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->