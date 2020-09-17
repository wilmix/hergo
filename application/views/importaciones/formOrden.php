<!-- Your Page Content Here -->
<div class="row">
  <div class="col-xs-12">
    <div id="ordenForm" class="box">
      <div class="box-header with-border">
        <!-- <h3 class="box-title" v-text="title"></h3> -->
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" v-if="!idOrden">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
      </div>
      <div class="box-body">
        <table id="table" class="table table-hover display compact" style="width:100%" v-if="!idOrden">
        </table>
        <hr v-if="!idOrden">
        <form method="post" >
            <input type="text" id="idOrden" value="<?php echo isset($id) ? $id : '' ?>" hidden>
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 text-center">
                    <h3 v-text="title"></h1>
                    <vuejs-datepicker  v-model="fecha" :language="es" :format="customFormatter" name="fecha" input-class="form-control">
                    </vuejs-datepicker>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-8">
                    <p>
                        <strong>Señor(es): </strong>
                        <span>{{ proveedor }}</span>
                    </p>
                    <p>
                        <strong>Dirección: </strong>
                        <span> {{ direccion }}</span>
                    </p>
                    <!-- <p>
                        <strong>Termino de pago: </strong>
                        <span> Crédito</span>
                    </p> -->
                    <p>
                        <strong>Atención: </strong>
                        <input type="text" class="form-control" v-model="atencion">
                    </p>
                    <p>
                        <strong>Referencia: </strong>
                        <input type="text" class="form-control" v-model="referencia">
                    </p>
                </div>
                <div class="form-group col-sm-12 col-md-4">
                    <p>
                        <strong>Telf.: </strong>
                        <span>{{ fono }}</span>
                    </p>
                    <p>
                        <strong>Fax: </strong>
                        <span>{{fax}}</span>
                    </p>
                    <p>
                        <strong>Condiciones de compra: </strong>
                        <v-select :options="['EXW','FOB','CIF','FCA','CPT','CIP']" v-model="condicionCompra"></v-select>
                    </p>
                    <p>
                        <strong>Forma  de Envio: </strong>
                        <v-select :options="['AEREO','MARITIMO','TERRESTRE']" v-model="formaEnvio"></v-select>
                    </p>
                    <p v-if="formaPago=='CRÉDITO'">
                        <strong>Días de crédito: </strong>
                        <input type="number" class="form-control" v-model="diasCredito">
                    </p>
                </div>
            </div>
            <table class="table table-striped table-condensed table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Descripción</th><!-- de fabrica -->
                        <th>Unidad</th>
                        <th>Número Parte</th>
                        <th class="bg-info text-center">Cantidad</th>
                        <th class="bg-info text-center">Valor Unitario</th>
                        <th class="bg-info text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, key, index) in items" :key="item.idCodigo">
                        <th>{{key + 1}} </th>
                        <td v-text="item.codigo"></td>
                        <td v-text="item.descripFabrica"></td>
                        <td v-text="item.unidad"></td>
                        <td v-text="item.numParte"></td>
                        <td class="text-right"> {{ item.cantidad | moneda }}</td>
                        <td class="text-right"> {{ item.precioFabrica | moneda }}</td>
                        <td class="text-right"> {{ (item.cantidad * item.precioFabrica) | moneda }}</td>
                    </tr>
                </tbody>
                <tfoot>
                <tr>
                  <td class="text-right" colspan="7" ><strong> Flete </strong></td>
                  <td class="text-right bg-primary"><strong>{{ flete | moneda }}</strong></td>
                  <!-- <td class="text-right bg-primary"><strong> {{ 0 | moneda }} </strong></td> -->
                </tr>
                    <tr>
                        <td class="text-right" colspan="7"><strong>Total $u$</strong></td>
                        <td class="text-right bg-primary"><strong>{{ totalDoc | moneda }}</strong></td>
                    </tr>
                </tfoot>
            </table>

            <div class="row">
                <div class="form-group col-sm-12 col-md-12">
                    <label for="glosa">Glosa:</label>
                    <input type="text" class="form-control" id="glosa" name="glosa" v-model="glosa">
                </div>
            </div>

            <!-- botones -->
            <div class="row">
                <div class="col-xs-12 text-center">
                    <button type="button" class="btn btn-primary" @click="store" v-text="btnGuardar">Guardar</button>
                    <button type="button" class="btn btn-default" @click="cancel">Cancelar</button>
                </div>
            </div>


        </form>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->