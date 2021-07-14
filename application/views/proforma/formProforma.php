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
          <input type="text" id="idProforma" value="<?php echo isset($id) ? $id : '' ?>" hidden>
          <!-- 1 -->
          <div class="row">
            <!-- fecha -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="fecha">Fecha:</label>
                <vuejs-datepicker  v-model="fecha" :language="es" :format="customFormatter" name="fecha" input-class="form-control">
                </vuejs-datepicker>
            </div>
            <!-- almacen -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="recepcion">Almacen:</label>
                <select class="form-control" 
                            v-model="almacen" 
                            id="almacen" 
                            name="almacen">
                    <option v-for="option in almacenes" 
                            v-bind:value="option.value"
                            v-text="option.alm">
                    </option>
                </select>
            </div>
            <!-- cliente -->
            <div class="form-group col-sm-12 col-md-4">
              <label for="cliente">Cliente:</label>
              <v-select label="label" :filterable="false" :options="clienteList"
                  @search="onSearchCliente" v-model="cliente" :select-on-key-codes="[9, 13]" :required="!cliente">
                  <template slot="no-options">
                    Busca un cliente..
                  </template>
              </v-select>
            </div>
            <!-- MONEDA -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="recepcion">Moneda:</label>
                <select class="form-control" 
                            v-model="moneda" 
                            name="moneda">
                    <option v-for="option in monedas" 
                            v-bind:value="option.value"
                            v-text="option.moneda">
                    </option>
                </select>
            </div>
            <!-- condicionPago -->
            <div class="form-group col-sm-6 col-md-2">
              <label for="condicionPago">Condicion de Pago:</label>
              <input type="text" class="form-control" name="condicionPago" v-model="condicionPago">
            </div>
            
            <!-- validez -->
            <div class="form-group col-sm-6 col-md-2" >
              <label for="validez">Validez de Oferta: </label>
              <input type="text" class="form-control" id="validez" name="validez" @change="total" v-model="validez">
            </div>

            <!-- lugarEntrega -->
            <div class="form-group col-sm-6 col-md-2" >
              <label for="lugarEntrega">Lugar de entrega: </label>
              <input type="text" class="form-control" id="lugarEntrega" name="lugarEntrega" @change="total" v-model="lugarEntrega">
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
              <input type="text" class="form-control" id="garantia" name="garantia" v-model="garantia">
            </div>

            <!-- tiempoEntregaC -->
            <div class="form-group col-sm-6 col-md-2" >
              <label for="tiempoEntregaC">Tiempo de Entrega (opcional): </label>
              <input type="text" class="form-control" id="tiempoEntregaC" name="tiempoEntregaC" v-model="tiempoEntregaC">
            </div>

           

          </div>

          <!-- 2 -->
          <div class="row">
            <div class="form-group col-sm-12 col-md-4">
              <!-- <label for="codigo">Articulo:</label>
              <v-select label="label" :filterable="false" :options="articulosList"
                  @search="onSearch" v-model="selectedart" id="codigoArt" :select-on-key-codes="[9, 13]">
                  <template slot="no-options">
                    Busca un artículo..
                  </template>
              </v-select> -->
              <label for="articulosArray">Artículo:</label>
              <v-select :options="articulosArray" id="codigo" label="label" id="value" :select-on-key-codes="[9, 13]" v-model="articulosArraySelected" />
            </div>
            <div class="form-group col-sm-12 col-md-8">
              <card-product :selectedart="selectedart" ></card-product>
            </div>
          </div>
          <!-- 3 -->
          <div class="row">
            <!-- vacio -->
            <div class="form-group col-sm-0 col-md-2">
            </div>
            <!-- industria -->
            <div class="form-group col-sm-0 col-md-2">
            <label for="industria">Industria:</label>
              <input type="text" class="form-control" v-model="industria">
            </div>
            <!-- TiempoEntrega -->
            <div class="form-group col-sm-0 col-md-2">
            <label for="tiempoEntrega">TiempoEntrega:</label>
              <input type="text" class="form-control" v-model="tiempoEntrega">
            </div>
            <!-- cantidad -->
            <div class="form-group col-sm-4 col-md-2">
              <label for="cantidad">Cantidad:</label>
              <input type="number" style="text-align:right;" class="form-control" v-model.number="cantidad">
            </div>
            <!-- precio -->
            <div class="form-group col-sm-4 col-md-2">
              <label for="precioLista">Precio de Lista:</label>
              <input type="number" style="text-align:right;" class="form-control" v-model.number="precioLista">
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
                  <th>Imagen</th>
                  <th>Descripción</th>
                  <th class="text-left">Marca</th>
                  <th>Industria</th>
                  <th>Tiempo de Entrega</th>
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
                    <td v-if="edit" class="text-left col-md-6"> 
                      <input type="text" class="form-control input-sm" v-model="item.descrip" v-on:keyup.enter="editRow"></input>
                    </td>
                    <td v-else @dblclick="editRow()" class="text-left">{{ item.descrip }}</td>
                  </div>
                  <div>
                    <td v-if="edit" class="text-right">
                      <input type="text" class="form-control input-sm text-right col-md-2" v-model="item.marcaSigla" v-on:keyup.enter="editRow">
                    </td>
                    <td v-else @dblclick="editRow()" class="text-left">{{ item.marcaSigla}}</td>
                  </div>
                  <div>
                    <td v-if="edit" class="text-right">
                      <input type="text" class="form-control input-sm text-right col-md-2" v-model="item.industria" v-on:keyup.enter="editRow">
                    </td>
                    <td v-else @dblclick="editRow()" class="text-left">{{ item.industria}}</td>
                  </div>
                  <div>
                    <td v-if="edit" class="text-right">
                      <input type="text" class="form-control input-sm text-right col-md-2" v-model="item.tiempoEntrega" v-on:keyup.enter="editRow">
                    </td>
                    <td v-else @dblclick="editRow()" class="text-left">{{ item.tiempoEntrega}}</td>
                  </div>
                  <td class="text-center">{{ item.uni }}</td>
                  <div>
                    <td v-if="edit" class="text-right">
                      <input type="number" class="form-control input-sm text-right col-md-2" v-model="item.cantidad" v-on:keyup.enter="editRow">
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
                <textarea class="form-control" id="glosa" rows="3"name="glosa" v-model="glosa"></textarea>
              </div>
            </div>
          </div>

          <!-- botones -->
          <div class="row">
              <div class="col-xs-12 text-center">
                <button type="button" class="btn btn-primary" @click="store" v-text="btnGuardar"></button>
                <button type="button" class="btn btn-default" @click="cleanForm">Cancelar</button>
            </div>
          </div>
        </form>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->



<!-- Modal CLIENTES-->
<form action=" " method="post" id="form_clientes" enctype="multipart/form-data">
  <div class="modal fade" id="modalcliente" role="dialog">
    <input type="" name="id_cliente" value="" id="id_cliente" hidden value="<?= "" ?>">
    <!-- input oculto para el codigo de articulo-->
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Agregar Cliente</h3>
        </div>
        <!--MODAL BODY-->
        <div class="modal-body form form-horizontal">
          <fieldset>
            <!-- Tipo Documento-->
            <div class="form-group">
              <label class="col-md-3 col-lg-3 control-label">Tipo de Documento</label>
              <div class="col-md-9 col-lg-9 selectContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
                  <select name="tipo_doc" id="tipo_doc" class="form-control selectpicker">
                    <option value="2" selected="selected">NIT</option>
                    <?php foreach ($tipodocumento->result_array() as $fila):  ?>
                    <option value="<?= $fila['idDocumentoTipo'] ?>">
                      <?= $fila['documentotipo']?>
                    </option>
                    <?php endforeach ?>

                  </select>
                </div>
              </div>
            </div>
            <!-- Documento-->
            <div class="form-group">
              <label class="col-md-3 col-lg-3 control-label">N° Documento</label>
              <div class="col-md-9 col-lg-9 inputGroupContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                  <input name="carnet" id="carnet" placeholder="00000000" class="form-control" type="text">
                </div>
              </div>
            </div>
            <!-- Nombre de Cliente-->
            <div class="form-group">
              <label class="col-md-3 col-lg-3 control-label">Nombre de Cliente</label>
              <div class="col-md-9 col-lg-9 inputGroupContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input name="nombre_cliente" id="nombre_cliente" placeholder="Nombre o Razon Social" class="form-control"
                    type="text">
                </div>
              </div>
            </div>
            <!-- Tipo Cliente-->
            <div class="form-group">
              <label class="col-md-3 col-lg-3 control-label">Tipo de Cliente</label>
              <div class="col-md-9 col-lg-9 selectContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
                  <select name="clientetipo" id="clientetipo" class="form-control selectpicker">
                  <option value="1" selected>Cliente</option>
                    <?php foreach ($tipocliente->result_array() as $fila):  ?>
                    <option value="<?= $fila['idClientetipo'] ?>">
                      <?= $fila['clientetipo']?>
                    </option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
            </div>
            <!-- Direccion-->
            <div class="form-group">
              <label class="col-md-3 col-lg-3 control-label">Direccion</label>
              <div class="col-md-9 col-lg-9 inputGroupContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                  <input name="direccion" id="direccion" placeholder="Dirección de Cliente" class="form-control" type="text">
                </div>
              </div>
            </div>
            <!-- Telefono -->
            <div class="form-group">
              <label class="col-md-3 col-lg-3 control-label">Telefono</label>
              <div class="col-md-9 col-lg-9 inputGroupContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                  <input name="phone" id="phone" placeholder="Telefono de Cliente" class="form-control" type="number">
                </div>
              </div>
            </div>
            <!-- Fax -->
            <div class="form-group">
              <label class="col-md-3 col-lg-3 control-label">Fax</label>
              <div class="col-md-9 col-lg-9 inputGroupContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                  <input name="fax" id="fax" placeholder="Fax" class="form-control" type="number">
                </div>
              </div>
            </div>
            <!-- Email-->
            <div class="form-group">
              <label class="col-md-3 col-lg-3 control-label">E-Mail</label>
              <div class="col-md-9 col-lg-9 inputGroupContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                  <input name="email" id="email" placeholder="Dirección Email" class="form-control" type="text">
                </div>
              </div>
            </div>
            <!-- web-->
            <div class="form-group">
              <label class="col-md-3 col-lg-3 control-label">Sitio WEB</label>
              <div class="col-md-9 col-lg-9 inputGroupContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                  <input name="website" id="website" placeholder="Sitio Web Cliente" class="form-control" type="text">
                </div>
              </div>
            </div>
          </fieldset>
          <div class="modal-footer">
            <button type="button" class="btn btn-default botoncerrarmodal" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary bguardar" v-text="btnGuardar"></button>
          </div>
        </div> <!-- /class="modal-body form form-horizontal"-->
      </div> <!-- /. class="modal-dialog" -->
    </div> <!-- /. class="modal-dialog" -->
  </div> <!-- /. class="modal fade" -->
</form>
<!-- Modal TIPO CAMBIO-->
<form method="post" id="form_tipoCambio">
    <div class="modal fade" id="modalTipoCambio" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title modalTitulo">Establecer Tipo de cambio: <span id="fechaTitulo"></span></h3> 
                </div>
                <!--MODAL BODY-->
                <div class="modal-body form form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> Tipo Cambio</label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input placeholder="Establecer nuevo tipo de cambio" class="form-control" name="tipocambio" id="tipocambio" type="text" autofocus autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default botoncerrarmodal" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="setTipoCambio">Guardar</button>
                </div>
                </div>
                <!-- /.<div class="modal-body form">-->
            </div>
        </div>
        <!-- /. modal -->
    </div>
</form>