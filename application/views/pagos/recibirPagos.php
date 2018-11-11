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
 <!-- Content Header (Page header) -->
 <section class="content-header">
      <h1>
        <?= isset($idPago)?'Modificar Pago':'Recibir Pago'?>
        <span v-text="numPago"></span>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Recibir Pago</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
<input type="text" id="idPago" value="<?= isset($idPago)?$idPago:0?>" class="hidden"> 
<input type="text" id="fechaEditar" value="<?= isset($idPago)?$fechaEditar:''?>" class=""> 


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
        
          <form id="formPagos">
            <div class="form-row">
              <div class="form-row align-items-center col-md-3">
              <input type="text" value = "<?php echo $id_Almacen_actual ?>" id="idAlmacenActual" hidden> 
                <label>Almacen: </label>
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
              <div class="form-row align-items-center col-md-2">
                <label>Fecha: </label>
                <input  v-model="fechaPago" 
                        class="form-control fecha_pago" 
                        type="text" 
                        id="fechaPago" 
                        name="fechaPago">
              </div>
              <div class="form-row align-items-center col-md-5">
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
                          name="idCliente_Pago" 
                          id="idCliente_Pago" 
                          class="hidden"> 
                </div>
              <div class="form-row align-items-center col-md-2">
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
              <div v-if="tipoPago == 2">
                <div class="form-row align-items-center col-md-2">
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
                <div class="form-row align-items-center col-md-2">
                  <label>Vaucher: </label>
                  <input type="text" class="form-control" v-model="transferencia" id="vaucher" name="vaucher">
                </div>
                <div v-if="tipoPago == 3">
                  <div class="form-row align-items-center col-md-2">
                    <label >Cheque N°: </label>
                    <input type="text" class="form-control" v-model="cheque" id="cheque" name="cheque">
                  </div>
                </div>
              </div> 
            <div class="table">
              <table class="table table-hover table-striped table-bordered" id="paraPagar_table">
                <thead>
                  <tr>
                    <th style="width:10%">N. Factura</th>
                    <th>Cliente</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">SaldoNuevo</th>
                    <th style="width:20%;text-align: center">Pagar</th>
                    <th style="width:5%"></th>
                  </tr>
                </thead>  
                <tbody>
                  <tr is="app-row" v-for="(pagar,index) in porPagar" :index="index" :pagar="pagar" @removerfila="deleteRow" >
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="4" class="text-right"><b>Total</b> </td>          
                    <td class="text-right"> {{ retornarTotal() | moneda}}</td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="row">
              <div class="col-xs-12 col-md-12">                  
                <label for="observaciones_ne">Glosa:</label>
                <input type="text" class="form-control" id="glosa" name="glosa" value="" v-model="glosa">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-xs-12">
              <?php if (isset($idPago)): ?>
                  <button type="button" class="btn btn-primary" id="editarPago" @click="editarPago">Modificar Pago</button>  
                  <button type="button" class="btn btn-warning" id="anularPago" @click="anularPago">Anular Movimiento</button>
                  <button type="button" class="btn btn-danger" id="cancelarPago" @click="cancelarPago">Cancelar Pago</button>
                <?php else: ?>
                  <button type="button" class="btn btn-primary" id="guardarPago" @click="guardarPago">Guardar Pago</button> 
                  <button type="button" class="btn btn-danger" id="cancelarPago" @click="cancelarPago">Cancelar Pago</button>
                <?php endif ?>
              </div>
            </div>
          </form>
        
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
</main>
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
<script type="text/x-template" id="row-template">
  <tr>
      <td>{{pagar.nFactura}}</td>
      <td>{{pagar.nombreCliente}}</td>
      <td class="text-right">{{pagar.total | moneda}}</td>
      <td class="text-right">{{retornarSaldoNuevo() | moneda}}</td>          
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
                <a @click="discard">
                  <span class="fa fa-times" aria-hidden="true"></span>                                   
                </a>
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