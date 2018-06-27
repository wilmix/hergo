<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <div class="text-right" class="btn-group" id="toolbar">

          <a class="btn btn-primary btn-sm" href="<?php echo base_url("Traspasos/traspasoEgreso") ?>" target="_blank"><i class="fa fa-plus-circle fa-lg"></i>  Traspasos</a>

        </div>

            <div  id="toolbar2" class="form-inline">

              <button  type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
               <span>
                 <i class="fa fa-calendar"></i> Fecha
               </span>
                <i class="fa fa-caret-down"></i>
             </button>


             <!-- <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                <?php foreach ($almacen->result_array() as $fila): ?>
                <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                <?php endforeach ?>
                <option value="">TODOS</option>

              </select>
              
              <select class="btn btn-primary btn-sm" name="tipo_filtro" id="tipo_filtro">
                <?php foreach ($tipoingreso->result_array() as $fila): ?>
                  <option value="<?= $fila['id'] ?>" <?= $fila['id']==7?"selected":""  ?>><?= strtoupper($fila['tipomov']) ?></option>
                <?php endforeach ?>
                <option value="">TODOS</option>
                 
              </select>-->

           </div>

          <table 
            id="tTraspasos"
            data-toolbar="#toolbar2"
            data-height="550">
          </table>

      </div>

      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>




<!-- Modal -->
<div id="modalEgresoDetalle" class="modal fade" role="dialog">
  <div class="modal-dialog modal-95">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <div class="modal-title" ><h4>
            <h2><span class="label label-primary" id="nombreModal"></span>
            <span class="label label-primary" id="numeromovimiento"></span>
            <span id="estadoTraspaso"></span>
            </h2>
          <!--</h4>-->          
              
        </div>
        
      </div>
      <div class="modal-body">
         <!-- formulario PRIMERA FILA-->
          <div class="row"> <!--PRIMERA FILA-->
             <div class=" col-xs-6 col-sm-6 col-md-3">
              <label>Almacen Origen:</label>
              <input id="almacen_ori" type="text" class="form-control" name="almacen_ori" readonly="">
             </div>
             <div class=" col-xs-6 col-sm-6 col-md-3">
              <label>Almacen Destino:</label>
              <input id="almacen_des" type="text" class="form-control" name="almacen_des" readonly="">
             </div>
             <div class="col-xs-6 col-sm-6 col-md-2">
                <label for="fechamov_egr" >Fecha:</label>
                <input id="fechamov_egr" type="text" class="form-control" name="fechamov_egr" readonly="">
             </div>
             <div class="col-xs-6 col-sm-6 col-md-2">
                <!--<label for="moneda_egr">Moneda:</label>
                <input id="moneda_egr" type="text" class="form-control" name="moneda_egr" readonly="">-->

             </div>
             <div class="col-xs-4 col-sm-4 col-md-2">
                  <!--<label>N° Pedido:</label>
                  <input id="pedido_egr" type="text" class="form-control" name="pedido_egr" readonly="">-->
             </div>

          </div> 

          <hr>

         <table class="table-striped"
              data-toggle="table"
              id="tTraspasodetalle">
          </table>
          <!--TOTALES SISTEMA-->
          

          <div class="col-md-12 col-xs-12" style="padding: 0px">
            <div class = "input-group col-md-3 col-xs-6 pull-right">
              <!--<span class = "input-group-addon">$</span>
              <!--<input type = "text" class="form-control form-control-sm text-right tiponumerico" id="" disabled="">-->
              <span class = "input-group-addon">Bs</span>
              <!--mostrar el total bolivivanos sistema-->
              <input type = "text" class="form-control form-control-sm text-right tiponumerico pull-right" id="totalbsdetalle" disabled="">
             </div>
          </div>
          <div class="clearfix"></div>
          <hr>



          <div class="row">
                <div class="col-xs-12 col-md-12">
                  <!--insertar costo de articulo a ingresar-->
                  <label for="observaciones_egr">Observaciones:</label>
                  <input type="text" class="form-control" id="obs_egr" name="obs_egr" readonly="" /> 
              </div>
              
                
          </div>
          <div class="clearfix"></div>

      </div>
      <div class="modal-footer">
        <span id="pendienteaprobado"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
