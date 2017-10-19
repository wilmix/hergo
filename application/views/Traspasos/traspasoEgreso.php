<?php

    $cont=(isset($dcab))?true:false;//si existe datos cabecera true si existe => editar
    $idalmacen=0;
    $idtegreso=0;
    $idmoneda=0;
    $idcliente=0;
    $idalmacenDestino=0;
    $idalmacenOrigen=0;
    if($cont) //editar
    {

        $originalDate = $dcab->fechamov;      
        $newDate = date("Y-m-d", strtotime($originalDate));//revisar mes y año    
        $idalmacenDestino=$dTransferencia->iddestino;
        $idalmacenOrigen=$dTransferencia->idorigen;
        $idtegreso=$dcab->idtipomov;
        $idmoneda=$dcab->idmoneda;
        $idcliente=$dcab->idcliente;
        $idegresocompraslocales=$idtegreso;
   
    }
    else
    {
      $idegresocompraslocales=$idtegreso;
    }
?>
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
                <form action=" " method="post"  id="form_egreso">
          <div class="form">
          <!-- formulario PRIMERA FILA-->
          <?php if ($cont): ?>
                <input id="idegreso" name="idegreso" type="text" class="hidden"  hidden value="<?= $dcab->idEgresos ?>">
            <?php endif ?>

            <div class="row filacabecera"> <!--PRIMERA FILA-->
               <div class=" col-xs-6 col-sm-6 col-md-3">
                <label>Almacen Origen:</label>
                <select class="form-control form-control-sm" id="almacen_ori" name="almacen_ori" tabindex=1 <?= ($cont)?"disabled":"" ?>>
                    <?php foreach ($almacen->result_array() as $fila): ?>
                     <option value=<?= $fila['idalmacen'] ?> <?= ($idalmacenOrigen==$fila['idalmacen'])?"selected":"" ?> ><?= $fila['almacen'] ?></option>
                   <?php endforeach ?>
                 </select>
               </div>

               <!--ALMACEN DESTINO-->
               <div class=" col-xs-6 col-sm-6 col-md-3">
                <label>Almacen Destino:</label>
                <select class="form-control form-control-sm" id="almacen_des" name="almacen_des" tabindex=1 <?= ($cont)?"disabled":"" ?>>
                    <?php foreach ($almacen->result_array() as $fila): ?>
                     <option value=<?= $fila['idalmacen'] ?> <?= ($idalmacenDestino==$fila['idalmacen'])?"selected":"" ?> ><?= $fila['almacen'] ?></option>
                   <?php endforeach ?>
                 </select>
               </div>
              <input  name="idEgreso" value="<?= ($cont)?$dTransferencia->idEgreso:"" ?>" class="hidden">
              <input  name="idIngreso" value="<?= ($cont)?$dTransferencia->idIngreso:"" ?>" class="hidden">

               <div class="col-xs-6 col-sm-6 col-md-2">
                  <label>Fecha:</label>
                  <input id="fechamov_ne" type="date" class="form-control form-control-sm" name="fechamov_ne" placeholder="Fecha" value="<?= ($cont)?$newDate:$fecha  ?>" tabindex=3 <?= ($cont)?"disabled":"" ?>>
               </div>
               <div class="col-xs-6 col-sm-6 col-md-2">
                  <label>Moneda:</label>
                  <select class="form-control form-control-sm" id="moneda_ne" name="moneda_ne" tabindex=4>
                    <option value="1" <?= ($idmoneda==1)?"selected":"" ?>>BOLIVIANOS</option>
                    <option value="2" <?= ($idmoneda==2)?"selected":"" ?>>DOLARES </option>
                  </select>
               </div>
               <div class="col-xs-4 col-sm-4 col-md-2">
                      <label>N° Pedido:</label>
                      <input id="pedido_ne" name="pedido_ne" type="text" class="form-control form-control-sm" tabindex=6 value="<?= ($cont)?$dcab->clientePedido:''  ?>">
                </div>
            </div> <!-- div class="form-group-sm row" PRIMERA FILA -->
            

                <hr>
                <div class="row filaarticulo"> <!--TERCERA FILA-->
                  <div class="col-xs-12 col-md-2 has-feedback has-feedback-left">
                      <!--seleccionar codigo de articulo de la base de datos-->
                     <label for="articulo_ne" style="float: left;">Codigo:</label><span style="margin-left: 10px;display: none;" id="cargandocodigo" ><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
                     <input class="form-control form-control-sm" type="text" id="articulo_imp" name="articulo_imp"/ tabindex=9>
                     <div style="right: 22px;top:32px;position: absolute;" id="codigocorrecto"><i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i></div>
                  </div>
                  <div class="col-xs-9 col-md-4">
                      <!--mostrar descripcion de articulo segun codigo-->
                     <label for="descripcion_ne">Descripcion:</label>
                     <input type="text" class="form-control form-control-sm" id="Descripcion_ne" name="Descripcion_ne" readonly/>
                  </div>
                  <div class="col-xs-3 col-md-2">
                       <!--mostrar unidad de articulo segun codigo-->
                     <label for="">Unidad:</label>
                     <input type="text" class="form-control form-control-sm" id="unidad_ne" name="unidad_ne" disabled/>
                  </div>
                  <div class="col-xs-6 col-md-2">
                      <!--mostrar costo promedio ponderado de articulo segun codigo-->
                     <label>Costo Bs:</label>
                     <input type="text" class="form-control form-control-sm text-right tiponumerico" name="costo_ne" id="costo_ne" disabled/>
                  </div>
                   <div class="col-xs-6 col-md-2">
                      <!--mostrar saldo en almacen de articulo segun codigo-->
                     <label>Saldo:</label>
                      <input type="text" class="form-control form-control-sm text-right tiponumerico" id="saldo_ne" name="saldo_ne" disabled/>
                  </div>

                 </div><!-- div class="form-group-sm row"  TERCERA FILA-->

                 
                 <div class="form-group row filaarticulo"> <!--CUARTA FILA-->

                  <div class="col-xs-12 col-md-4">
                      <!--insertar PRECIO de articulo a ingresar-->

                  </div>
                  <!--Descuento oculto-->
                  <div class="col-xs-4 col-md-2">
                      <input type="text" class="hidden" class="form-control form-control-sm tiponumerico" id="descuento_ne" name="descuento_ne" tabindex=12/>
                  </div>

                  <div class="col-xs-4 col-md-2">
                        <!--insertar cantidad de productos a ingresar-->
                      <label>Cantidad:</label>
                      <input type="text" class="form-control form-control-sm" id="cantidad_ne" name="cantidad_ne" tabindex=10/>
                  </div>
                  <div class="col-xs-4 col-md-2">
                      <!--insertar costo de articulo a ingresar-->
                      <label>Costo Bs:</label> 
                      <input type="text" class="form-control form-control-sm tiponumerico" id="punitario_ne" name="punitario_ne" tabindex=11/>
                  </div>
                  

                  <div class="col-xs-12 col-md-2">
                  <label></label>
                  <button type="button" class="form-control btn btn-success" id="agregar_articulo" name="agregar_articulo" style="margin-top: 4px;" tabindex=13>Añadir</button>
                  </div>
               </div><!--row CUARTA FILA -->

          </div> <!-- /.class="form" -->
          <hr>
        <!--Tabla para mostrar articulos ingresados-->
              <div class="table-responsive">
                <table  class="table table-condensed table-bordered table-striped" 
                data-show-columns="true">
                  <thead>
                    <tr>
                      <th class="col-sm-1" >Código</th>
                      <th class="col-sm-7">Artículo</th>
                      <th class="col-sm-1" class="text-right">Cantidad</th>
                      <th class="col-sm-1" class="text-right">Costo</th> 
                      <th class="col-sm-1" class="text-right">Total</th> 
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                   <tbody id="tbodyarticulos">
                    <?php if ($cont): ?>
                        <?php foreach ($detalle as $fila): ?>
                          <?php 
                            $punitariofac= $fila['cantidad']==""?0:$fila['cantidad'];
                            //$punitariofac=$fila['totaldoc'] / $punitariofac;
                          ?>
                            <tr>
                                <td><input type="text" class="estilofila" disabled value="<?= $fila['CodigoArticulo'] ?>"></input></td>
                                <td><input type="text" class="estilofila" disabled value="<?= $fila['Descripcion'] ?>"></input</td>
                                <td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="<?= $fila['cantidad'] ?>"></input></td>
                                <td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="<?= $fila['punitario']?>"></input></td><!--nuevo-->
                                
                                <td class="text-right"><input type="text" class="totalCosto estilofila tiponumerico" disabled value="<?= $fila['total'] ?>"></input></td>
                                <!--<td class="text-right"><input type="text" class="totalCosto estilofila tiponumerico" disabled value=""></input></td>--><!--nuevo-->
                                <td><button type="button" class="btn btn-default eliminarArticulo" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>

                  </tbody>
                </table>
              </div> <!--div class="table-responsive"-->

            <div class="form-group row">
              <div class="col-md-6 col-xs-12">
                
              </div>
              <div class="col-md-6 col-xs-12">
                <div class = "input-group col-md-12 col-xs-12">
                  <span class = "input-group-addon">Sus</span>
                  <!--mostrar el total de dolares-->
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" readonly id="totalacostosus" tabindex=14>
                  <span class = "input-group-addon" >Bs</span>
                  <!--mostrar el total bolivivanos-->
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" readonly id="totalacostobs" tabindex=15>
                 </div>
              </div>
            </div><!--row-->
            <hr>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                  <!--insertar costo de articulo a ingresar-->
                  <label for="observaciones_ne">Observaciones:</label>
                  <input type="text" class="form-control" id="obs_ne" name="obs_ne" value="<?= ($cont)?$dcab->obs:''  ?>" />
              </div>
                <hr>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-12">
                <?php if ($cont): ?>
                    <button type="button" class="btn btn-primary" id="actualizarMovimiento">Actualizar Traspaso</button>   
                    <?php if ($dcab->anulado==0): ?>
                        <button type="button" class="btn btn-warning" id="anularTraspaso">Anular Traspaso</button>  
                      <?php else: ?>
                        <button type="button" class="btn btn-info" id="recuperarTraspaso">Recuperar Traspaso</button>  
                      <?php endif ?>                                            
                    <button type="button" class="btn btn-danger" id="cancelarMovimiento">Cancelar Traspaso</button>
                <?php else: ?>
                    <button type="button" class="btn btn-primary" id="guardarMovimiento" tabindex=11>Guardar Traspaso</button>
                    <button type="button" class="btn btn-danger" id="cancelarMovimiento" tabindex=12>Cancelar Traspaso</button>
                <?php endif ?>

                

              </div>
            </div>
        </form>

      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->



