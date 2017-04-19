<style>
    input{
        height: 50px;
    }
  input:focus{

  background-color: rgba(60, 141, 188, 0.47);;
  /*color: white;*/
      font-weight: 700;
}
select:focus{

  background-color:rgba(60, 141, 188, 0.47);
  /*color: white;*/

}

input[type=date]::-webkit-outer-spin-button,
input[type=date]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
</style>

<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <!--<h3 class="box-title">Ingreso Importaciones</h3>-->
      </div>
      <div class="box-body">
                <form action=" " method="post"  id="form_ingresoImportaciones">
          <div class="form">
          <!-- formulario PRIMERA FILA-->
                        <div class="row"> <!--PRIMERA FILA-->
               <div class=" col-xs-6 col-sm-6 col-md-3">
                <label>Almacen:</label>
                <select class="form-control form-control-sm" id="" name="almacen_imp" tabindex=1 >
                                        <option value=1  >CENTRAL HERGO</option>
                                        <option value=2  >DEPOSITO EL ALTO</option>
                                        <option value=3  >POTOSI</option>
                                        <option value=4  >SANTA CRUZ</option>
                                        <option value=5  >COCHABAMBA</option>
                                        <option value=6  >TALLER</option>
                                   </select>
               </div>
               <div class=" col-xs-6 col-sm-6 col-md-3">
                <input type="" name="tipomov_imp" value="2" class="hidden">
                <label for="tipomov_imp">Tipo de Ingreso:</label>
                <select class="form-control form-control-sm" id="tipomov_imp2" name="tipomov_imp2" tabindex=2  disabled>  
                <option value=2 selected>Nota de Entrega</option>                         
                </select>
               </div>
               <div class="col-xs-6 col-sm-6 col-md-2">

                  <label for="fechamov_imp" >Fecha:</label>
                  <input id="fechamov_imp" type="date" class="form-control form-control-sm" name="fechamov_imp" placeholder="Fecha" tabindex=3 >
               </div>
               <div class="col-xs-6 col-sm-6 col-md-2">
                  <label for="moneda_imp">Moneda:</label>
                  <select class="form-control form-control-sm" id="moneda_imp" name="moneda_imp" tabindex=4>
                    <option value="1"  >BOLIVIANOS</option>
                    <option value="2" >DOLARES </option>
                  </select>
               </div>
               <div class="col-xs-12 col-sm-6 col-md-2">
                  <label for="fechamov_imp" ># Movimiento:</label>
                  <input id="nmov_imp" type="number" class="form-control" name="nmov_imp" placeholder="# Movimiento" disabled value=""/>
               </div>
            </div> <!-- div class="form-group-sm row" PRIMERA FILA -->
            <div class="row"> <!--SEGUNDA FILA-->
                   <div class="col-xs-12 col-lg-6 col-md-6">
                     <label >Cliente:</label>
                       <select class="form-control selectpicker" data-size="5" data-live-search="true" id="" name="" tabindex=5>
                                                <option value=1 >NOMBRE CLIENTE</option>
                                                <option value=2 >INC</option>
                                                <option value=3 >ALCARDUPLEX</option>
                                                <option value=4 >CIENSA LTDA.</option>
                                                <option value=5 >COLMENA</option>
                                                <option value=6 >COMERCIAL</option>
                                                <option value=7 >GABRIEL</option>
                                                <option value=8 >OERLIKON</option>
                                                <option value=9 >MURILLO</option>
                                                <option value=10 >COSIM</option>
                                             </select>

                      <!-- Busqueda con select cambiar a autocomplete-->

                   </div>
                   <div class="col-xs-4 col-sm-4 col-md-2">
                         <label>Pedido Cliente:</label>
                         <input id="" type="text" class="form-control form-control-sm" name="" placeholder="Orden de Compra" value="" tabindex=6>
                   </div>
                   <div class="col-xs-4 col-sm-4 col-md-2">
                         <label>Fecha de Pago: </label>
                         <input id="" name="" type="date" class="form-control form-control-sm"  placeholder="Fecha Pago" value="" tabindex=7>
                   </div>
                  <div class="col-xs-12 col-md-2">
                  <label></label>
                  <button type="button" class="form-control btn btn-success" id="agregar_articulo" name="agregar_articulo" style="margin-top: 4px;" tabindex=11>Añadir Cliente</button>
                  </div>
                </div><!-- div class="form-group-sm row" SEGUNDA FILA-->


                <hr>
                <div class="row"> <!--TERCERA FILA-->
                  <div class="col-xs-12 col-md-2 has-feedback has-feedback-left">
                      <!--seleccionar codigo de articulo de la base de datos-->
                     <label for="articulo_imp" style="float: left;">Codigo:</label><span style="margin-left: 10px;display: none;" id="cargandocodigo" ><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
                     <input class="form-control form-control-sm" type="text" id="articulo_imp" name="articulo_imp"/ tabindex=9>
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
                     <input type="text" class="form-control form-control-sm" id="unidad_imp" disabled/>
                  </div>
                  <div class="col-xs-4 col-md-2">
                      <!--mostrar costo promedio ponderado de articulo segun codigo-->
                     <label for="costo_imp">Precio Bs:</label>
                     <input type="text" class="form-control form-control-sm text-right tiponumerico" id="costo_imp" disabled/>
                  </div>
                   <div class="col-xs-4 col-md-2">
                      <!--mostrar saldo en almacen de articulo segun codigo-->
                     <label for="saldo_imp">Saldo:</label>
                      <input type="text" class="form-control form-control-sm text-right tiponumerico" id="saldo_imp" disabled/>
                  </div>

                 </div><!-- div class="form-group-sm row"  TERCERA FILA-->
                 <div class="form-group row"> <!--CUARTA FILA-->

                  <div class="col-xs-12 col-md-4">
                      <!--insertar costo de articulo a ingresar-->

                  </div>

                  <div class="col-xs-4 col-md-2">
                        <!--insertar cantidad de productos a ingresar-->
                      <label>Cantidad:</label>
                      <input type="text" class="form-control form-control-sm" id="cantidad_imp" name="cantidad_imp" tabindex=10/>
                  </div>
                  <div class="col-xs-4 col-md-2">
                      <!--insertar costo de articulo a ingresar-->
                      <label>Precio Bs:</label> <!--CAMBIO PARA COMPRAS LOCALES-->
                      <input type="text" class="form-control form-control-sm tiponumerico" id="punitario_imp" name="punitario_imp" tabindex=11/>
                  </div>
                  <div class="col-xs-4 col-md-2">
                        <!--insertar cantidad de productos a ingresar-->
                      <label>% Descuento:</label>
                      <input type="text" class="form-control form-control-sm tiponumerico" id="constounitario" name="" />
                  </div>

                  <div class="col-xs-12 col-md-2">
                  <label></label>
                  <button type="button" class="form-control btn btn-success" id="agregar_articulo" name="agregar_articulo" style="margin-top: 4px;" tabindex=11>Añadir</button>
                  </div>
               </div><!--row CUARTA FILA -->

          </div> <!-- /.class="form" -->
          <hr>
        <!--Tabla para mostrar articulos ingresados-->
              <div class="table-responsive">
                <table  class="table table-condensed table-bordered table-striped">
                  <thead>
                    <tr>
                      <th class="col-sm-1" >Código</th>
                      <th class="col-sm-6">Artículo</th>
                      <th class="col-sm-1" class="text-right">Cantidad</th>
                      <th class="col-sm-1" class="text-right">P/U Bs</th> <!--nuevo-->
                      <th class="col-sm-1" class="text-right">% Dscnt</th> <!--nuevo-->
                      <th class="text-right">Total</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody id="">
                    
                  </tbody>
                </table>
              </div> <!--div class="table-responsive"-->

            <div class="form-group row">
              <div class="col-md-6 col-xs-12">
                
              </div>
              <div class="col-md-6 col-xs-12">
                <div class = "input-group col-md-12 col-xs-12">
                  <span class = "input-group-addon">$</span>
                  <!--mostrar el total de dolares-->
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalacostosus" tabindex=13>
                  <span class = "input-group-addon" >Bs</span>
                  <!--mostrar el total bolivivanos-->
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalacostobs" tabindex=14>
                 </div>
              </div>
            </div><!--row-->
            <hr>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                  <!--insertar costo de articulo a ingresar-->
                  <label for="observaciones_imp">Observaciones:</label>
                  <input type="text" class="form-control" id="obs_imp" name="obs_imp" value="" />
              </div>
                <hr>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-12">
                                    <button type="button" class="btn btn-primary" id="guardarMovimiento" tabindex=11>Guardar Movimiento</button>
                    <button type="button" class="btn btn-danger" id="cancelarMovimiento" tabindex=12>Cancelar Movimiento</button>
                

              </div>
            </div>
        </form>

      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->