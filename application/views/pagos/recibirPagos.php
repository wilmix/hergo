<style>
 .montopagar
  {
    float:left;
    width:80%;
    text-align: right;
  }

 
</style>
<input type="text" id="numpago" value="<?= isset($numPago)?$numPago:0?>" class="hidden">
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div id="toolbar2" class="form-inline">
          <button  type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
             <span>
               <i class="fa fa-calendar"></i> Fecha
             </span>
              <i class="fa fa-caret-down"></i>
           </button>
            <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
              <?php foreach ($almacen->result_array() as $fila): ?>
              <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
              <?php endforeach ?>
              <option value="">TODOS</option>
            </select>
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

<div class="row" id="app">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <table class="table table-hover table-striped table-bordered" id="paraPagar_table">
            <thead>
              <tr>
                <th style="width:10%">N. Factura</th>
                <th>Cliente</th>
                <th class="text-right">Total</th>
                <th class="text-right">Saldo</th>
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
          <hr>
          <div class="row">
                <div class="col-xs-12 col-md-12">                  
                  <label for="observaciones_ne">Glosa:</label>
                  <input type="text" class="form-control" id="glosa" name="glosa" value="" v-model="glosa">
              </div>
              <hr>
          </div>
          <hr>
          <div class="row">
              <div class="col-xs-12">
                <button type="button" class="btn btn-primary" id="guardarPago" @click="guardarPago">Guardar Pago</button>
                <button type="button" class="btn btn-danger" id="cancelarPago">Cancelar Pago</button>
              </div>
          </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
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