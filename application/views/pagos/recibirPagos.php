<?php

    $edit=(isset($cab))?true:false;//si existe datos cabecera true si existe => editar
    if($edit)
    {
      $fechaEditar=$cab->fechaPago;
    }

?>

<style>
 .montopagar
  {
    float:left;
    width:80%;
    text-align: right;
  }
</style>
<main id="app">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-body">
            <div id="toolbar2" class="form-inline">
            <button  type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
              <span>
                <i class="fa fa-calendar"></i>
              </span>
              <i class="fa fa-caret-down"></i>
            </button>
              <select class="btn btn-primary btn-sm" 
                      data-style="btn-primary" 
                      id="almacen_filtro" 
                      name="almacen_filtro">
                <?php foreach ($almacen->result_array() as $fila): ?>
                  <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                <?php endforeach ?>
                  <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                  <option value="">TODOS</option>
              </select>
              <button  type="button" class="btn btn-primary btn-sm" id="refresh">
                <span>
                  <i class="fa fa-refresh"></i>
                </span>
              </button>
            </div>
            <table 
              id="tPendientes" 
              data-toolbar="#toolbar2"
              data-toggle="table"
              data-height="350">
            </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-body">
            <form id="formPagos" enctype="multipart/form-data" role="form" method="POST">
              <input type="text" id="idPago" name="idPago" value="<?= isset($idPago)?$idPago:0?>" class="hidden"> 
              <input type="text" id="fechaEditar" value="<?= isset($idPago)?$fechaEditar:''?>" class="hidden"> 
              <div class="row">
                <!-- almacen -->
                <div class="form-group col-md-2">
                  <input type="text" value = "<?php echo $id_Almacen_actual ?>" id="idAlmacenActual" hidden> 
                  <label for="almacen">Almacen: </label>
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
                <!-- fecha -->
                <div class="form-group col-md-2">
                  <label>Fecha: </label>
                  <input 
                          class="form-control fecha_pago" 
                          type="text" 
                          id="fechaPago" 
                          name="fechaPago">
                </div>
                <!-- cliente -->
                <div class="form-group col-md-6">
                  <label>Cliente: 
                    <span class="badge label-success hidden" 
                          id="errorCliente">
                      <i class="fa fa-check"></i>
                    </span>
                    <span style="margin-left: 10px;display: none;" 
                          id="cargandocliente" >
                      <i class="fa fa-times" style="color:#bf0707"></i>
                    </span>
                  </label>
                  <input  class="form-control form-control-sm" 
                          type="text" id="cliente_factura" 
                          name="cliente_factura" 
                          v-model="nombreCliente" 
                          value="">
                  <input  type="text" 
                          v-model="cliente"  
                          name="cliente" 
                          id="idCliente_Pago" 
                          class="hidden"> 
                </div>
                <!-- tipo -->
                <div class="form-group col-md-2">
                    <label>Tipo: </label>
                    <select class="form-control" 
                            v-model="tipoPago" 
                            id="tipoPago" 
                            name="tipoPago">
                      <option v-for="option in options" 
                              v-bind:value="option.value"
                              v-text="option.tipo">
                      </option>
                    </select>
                </div>
              </div>
              <div class="row">
                <div v-if="tipoPago != 4">
                  <!-- banco -->
                  <div class="form-group  col-md-2">
                    <label >Banco:</label>
                      <select class="form-control" 
                          v-model="banco" 
                          id="banco" 
                          name="banco">
                        <?php foreach ($bancos->result_array() as $fila): ?>
                          <option value=<?= $fila['id'] ?>> <?= $fila['sigla'] ?> </option>
                        <?php endforeach ?>
                      </select>
                  </div>
                  <!-- vaucher -->
                  <div class="form-group  col-md-2">
                    <label>Vaucher: </label>
                    <input type="text" class="form-control" v-model="transferencia" id="vaucher" name="transferencia">
                  </div>
                </div>
                <!-- Imagen -->
                <div class="upload_image">
                  <div class="form-group col-md-6">
                    <label for="img_route">Imagen de Pago:</label>
                    <input id="img_route" @change="getImagen" name="img_route" type="file" class="file-loading" accept="image/*">
                  </div>
                </div>  
                <!-- Cheque -->
                <div v-if="tipoPago == 3">
                  <div class="form-group col-md-2">
                    <label >Cheque N°: </label>
                    <input type="text" class="form-control" v-model="cheque" id="cheque" name="cheque">
                  </div>
                </div> 
              </div>
              <!-- Tabla -->
              <div class="table-responsive form-group">
                  <table id="paraPagar_table" class="table table-hover table-striped table-bordered table-responsive px-4" >
                    <thead>
                      <tr>
                        <th style="width:10%">N. Factura</th>
                        <th>Cliente</th>
                        <th class="text-right">Total</th>
                        <!-- <th class="text-right">SaldoNuevo</th> -->
                        <th style="width:20%;text-align: right">Pagar</th>
                        <th style="width:5%"></th>
                      </tr>
                    </thead>  
                    <tbody>
                      <tr is="app-row" v-for="(pagar,index) in porPagar" :index="index" :pagar="pagar" @removerfila="deleteRow" >
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="3" class="text-right"><b>Total</b> </td>          
                        <td class="text-right"> {{ retornarTotal() | moneda}}</td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
              </div>
              <!-- Glosa -->
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="glosa">Glosa:</label>
                  <input class="form-control" type="text" id="glosa" name="glosa" value="" v-model="glosa">
                </div>
              </div>
              <!-- Botones -->
              <div class="row">
                <div class="form-group col-md-12">
                  <div class="btn-group pull-right">
                    <?php if (isset($idPago)): ?>
                      <button type="button" class="btn btn-primary" id="editarPago" @click="editarPago">Modificar Pago</button>  
                      <button type="button" class="btn btn-warning" id="anularPago" @click="anularPago">Anular Movimiento</button>
                      <button type="button" class="btn btn-danger" id="cancelarPago" @click="cancelarPago">Cancelar Pago</button>
                    <?php else: ?>
                      <button type="button" class="btn btn-primary" id="guardarPago" @click="guardarPago">Guardar Pago</button> 
                      <button type="button" class="btn btn-danger" id="cancelarPago" @click.once="cancelarPago">Cancelar Pago</button>
                    <?php endif ?>
                  </div>
                </div>
              </div>
            </form>
          </div>
      </div>
    </div>
  </div>
</main>

<script type="text/x-template" id="row-template">
  <tr>
      <td>{{pagar.nFactura}}</td>
      <td>{{pagar.nombreCliente}}</td>
      <td class="text-right">{{pagar.total | moneda}}</td>
      <!-- <td class="text-right">{{retornarSaldoNuevo() | moneda}}</td>     -->      
      <td>
        <template v-if="!editing">
          <a @click="edit" style="cursor:pointer" class="montopagar"><span  class="description">{{pagar.pagar | moneda}}</span></a>
           
        </template>
        <template v-else>            
            <input type="text" class="inputnumeric montopagar" v-model="montopagar" @keyup.enter="update">
            <div id="botonesinput">                
                <a @click="update">
                  <span class="fa fa-check" aria-hidden="true"></span>                                
                </a>
                <!-- <a @click="discard">
                  <span class="fa fa-times" aria-hidden="true"></span>                                   
                </a> -->
                <div class="clearfix"></div>
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