<div class="row">
  <div class="col-xs-12">
    <div class="box">         
      <div class="box-header with-border">
        <h3 class="box-title">Ingreso Importaciones</h3>
      </div>
      <div class="box-body">
        <form action=" " method="post"  id="form_ingresoImportaciones">
          <div class="form">
          <!-- formulario PRIMERA FILA-->
          
            <div class="row"> <!--PRIMERA FILA-->
               <div class=" col-xs-6 col-sm-6 col-md-3">
                <label>Almacen:</label>
                <select class="form-control form-control-sm" id="almacen_imp" name="almacen_imp">                 
                   <?php foreach ($almacen->result_array() as $fila): ?>
                     <option value=<?= $fila['idalmacen'] ?>><?= $fila['almacen'] ?></option>
                   <?php endforeach ?>
                </select>
               </div>  
               <div class=" col-xs-6 col-sm-6 col-md-3">
                <label for="moneda_imp">Tipo de Ingreso:</label>
                <select class="form-control form-control-sm" id="tipomov_imp" name="tipomov_imp">
                   <?php foreach ($tingreso->result_array() as $fila): ?>
                     <option value=<?= $fila['id'] ?>><?= $fila['tipomov'] ?></option>
                   <?php endforeach ?>
                </select>
               </div>  
               <div class="col-xs-6 col-sm-6 col-md-2">

                  <label for="fechamov_imp" >Fecha:</label>
                  <input id="fechamov_imp" type="date" class="form-control form-control-sm" name="fechamov_imp" placeholder="Fecha" value="<?= $fecha  ?>">
               </div>
               <div class="col-xs-6 col-sm-6 col-md-2">
                  <label for="moneda_imp">Moneda:</label>
                  <select class="form-control form-control-sm" id="moneda_imp" name="moneda_imp">
                    <option value="1" selected>BOLIVIANOS</option>
                    <option value="2">DOLARES </option>
                  </select>
               </div>
               <div class="col-xs-12 col-sm-6 col-md-2">
                  <label for="fechamov_imp" ># Movimiento:</label>
                  <input id="nmov_imp" type="number" class="form-control" name="nmov_imp" placeholder="# Movimiento" readonly/>
               </div>               
            </div> <!-- div class="form-group-sm row" PRIMERA FILA -->
            <div class="row"> <!--SEGUNDA FILA-->
                   <div class="col-xs-12 col-lg-6 col-md-6">
                     <label >Proveedor:</label>
                     <!--<select class="form-control" id="proveedor_imp" name="proveedor_imp">-->
                       <select class="form-control selectpicker" data-size="5" data-live-search="true" id="proveedor_imp" name="proveedor_imp">
                        <?php foreach ($proveedor->result_array() as $fila): ?>
                         <option value=<?= $fila['idproveedor'] ?>><?= $fila['nombreproveedor'] ?></option>
                       <?php endforeach ?>
                      </select>
                      <!-- Busqueda con select cambiar a autocomplete-->

                   </div>                 
                   <div class="col-xs-4 col-sm-4 col-md-2">
                         <label>Orden de Compra:</label>
                         <input id="ordcomp_imp" type="text" class="form-control form-control-sm" name="ordcomp_imp" placeholder="Orden de Compra" >
                   </div>
                   <div class="col-xs-4 col-sm-4 col-md-2">
                         <label>N° Factura:</label>
                         <input id="nfact_imp" name="nfact_imp" type="text" class="form-control form-control-sm"  placeholder="# Factura" >
                   </div>
                   <div class="col-xs-4 col-sm-4 col-md-2">
                         <label>N° Ingreso:</label>
                         <input id="ningalm_imp" type="text" class="form-control form-control-sm" name="ningalm_imp" placeholder="# Ingreso" >
                   </div>
                </div><!-- div class="form-group-sm row" SEGUNDA FILA-->

                <hr>
                <div class="row"> <!--TERCERA FILA-->
                  <div class="col-xs-12 col-md-2 has-feedback has-feedback-left">
                      <!--seleccionar codigo de articulo de la base de datos-->
                     <label for="articulo_imp" style="float: left;">Codigo:</label><span style="margin-left: 10px;display: none;" id="cargandocodigo" ><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
                     <!--<select  class="form-control selectpicker" data-size="5" data-live-search="true" id="articulo_imp" name="articulo_imp" >
                        <?php //foreach ($articulo->result_array() as $fila): ?>
                         <option id=<?php //$fila['idArticulos'] ?> descripcion="<?php //$fila['Descripcion'] ?>"><?php //$fila['CodigoArticulo'] ?></option>
                       <?php //endforeach ?>
                     </select> -->
                     <input class="form-control form-control-sm" type="text" id="articulo_imp" name="articulo_imp"/>  
                     <div style="right: 22px;top:32px;position: absolute;" id="codigocorrecto"><i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i></div>
                  </div>
                  <div class="col-xs-12 col-md-4">
                      <!--mostrar descripcion de articulo segun codigo-->
                     <label for="descripcion_imp">Descripcion:</label>
                     <input type="text" class="form-control form-control-sm" id="Descripcion_imp" name="Descripcion_imp" readonly/> 
                  </div>
                  <div class="col-xs-4 col-md-2">
                       <!--mostrar unidad de articulo segun codigo-->
                     <label for="">Unidad:</label>
                     <input type="text" class="form-control form-control-sm" id="unidad_imp" readonly/> 
                  </div>
                  <div class="col-xs-4 col-md-2">
                      <!--mostrar costo promedio ponderado de articulo segun codigo-->
                     <label for="costo_imp">Costo:</label>
                     <input type="text" class="form-control form-control-sm" id="costo_imp" readonly/> 
                  </div>
                   <div class="col-xs-4 col-md-2">
                      <!--mostrar saldo en almacen de articulo segun codigo-->
                     <label for="saldo_imp">Saldo:</label>
                      <input type="text" class="form-control form-control-sm" id="saldo_imp" readonly/> 
                  </div>

                 </div><!-- div class="form-group-sm row"  TERCERA FILA-->
                 <div class="form-group row"> <!--CUARTA FILA-->

                  <div class="col-xs-12 col-md-6">
                      <!--insertar costo de articulo a ingresar-->
                      <label for="observaciones_imp">Observaciones:</label>
                      <input type="text" class="form-control" id="obs_imp" name="obs_imp" /> 
                  </div>
                  
                  <div class="col-xs-6 col-md-2">
                        <!--insertar cantidad de productos a ingresar-->
                      <label>Cantidad:</label>
                      <input type="number" class="form-control form-control-sm" id="cantidad_imp" name="cantidad_imp" /> 
                  </div>
                  <div class="col-xs-6 col-md-2">
                      <!--insertar costo de articulo a ingresar-->
                      <label>Costo Unitario:</label>
                      <input type="number" class="form-control form-control-sm" id="punitario_imp" name="punitario_imp" /> 
                  </div>

                  <div class="col-xs-12 col-md-2">
                  <label></label>
                  <button type="button" class="form-control btn btn-success" id="agregar_articulo" name="agregar_articulo" style="margin-top: 4px;">Añadir</button>
                  </div> 
               </div><!--row CUARTA FILA -->
            
          </div> <!-- /.class="form" -->
          <hr>
        <!--Tabla para mostrar articulos ingresados-->
              <div class="table-responsive">
                <table  class="table table-condensed table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Código</th>
                      <th>Artículo</th>
                      <th>Cantidad</th>
                      <th>Costo</th>
                      <th>Total</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyarticulos">
                    
                  </tbody>
                </table>
              </div> <!--div class="table-responsive"-->

              <div class="form-group row">
              <div class="col-xs-12 col-md-6">
                <button type="button" class="btn btn-primary" id="guardarMovimiento">Grabar Movimiento</button>
                <button type="button" class="btn btn-danger">Cancelar</button>
                
              </div>
              <div class="col-md-6 col-xs-12">
                <div class = "input-group col-md-12 col-xs-12">
                  <span class = "input-group-addon">$</span>
                  <!--mostrar el total de dolares-->
                  <input type = "text" class="form-control form-control-sm" placeholder = "" id="totalacostosus">
                  <span class = "input-group-addon" >Bs</span>
                  <!--mostrar el total bolivivanos-->
                  <input type = "text" class="form-control form-control-sm" id="totalacostobs">
                 </div>
              </div>
            </div><!--row-->
        </form>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->


<!-- select2 
<script type="text/javascript">
  $('select').select2();
</script>-->

