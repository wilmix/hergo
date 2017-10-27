<?php

    $cont=(isset($dcab))?true:false;//si existe datos cabecera true si existe => editar
    $idalmacen=0;
    $idtegreso=0;
    $idmoneda=0;
    $idcliente=0;
    if($cont) //editar
    {
        $originalDate = $dcab->fechamov;      
        $newDate = date("Y-m-d", strtotime($originalDate));//revisar mes y año    
        $idalmacen=$dcab->idalmacen;
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
                <label>Almacen:</label>
                <select class="form-control form-control-sm" id="almacen_ne" name="almacen_ne" tabindex=1 <?= ($cont)?"disabled":"" ?>>
                    <?php foreach ($almacen->result_array() as $fila): ?>
                     <option value=<?= $fila['idalmacen'] ?> <?= ($idalmacen==$fila['idalmacen'])?"selected":"" ?> ><?= $fila['almacen'] ?></option>
                   <?php endforeach ?>
                 </select>
               </div>
               <div class=" col-xs-6 col-sm-6 col-md-3">                
                <input type="" name="tipomov_ne" value="<?= (isset($idingreso)?$idingreso:7)?>" class="hidden"><!--7 para nota de entrega-->
                <label for="tipomov_ne">Tipo de Ingreso:</label>
                <select class="form-control form-control-sm" id="tipomov_ne2" name="tipomov_ne2" tabindex=2  disabled>  
                    <?php foreach ($tegreso->result_array() as $fila): ?>
                      <?php if ($cont): ?> <!--EDITAR-->
                            <?php if ($idtegreso==$fila['id']): ?>
                              <option value=<?= $fila['id'] ?> "selected"><?= $fila['tipomov'] ?></option>
                            <?php endif ?>
                      <?php else: ?><!--NUEVO-->                
                            <?php if ($idegreso==$fila['id']): ?>
                                  <option value=<?= $fila['id'] ?> <?= ($idegreso==$fila['id'])?"selected":"" ?>><?= $fila['tipomov'] ?></option>
                            <?php endif ?>
                      <?php endif ?>        
                    <?php endforeach ?>
                </select>
               </div>
               <div class="col-xs-6 col-sm-6 col-md-2">

                  <label for="fechamov_ne" >Fecha:</label>
                  <input id="fechamov_ne" type="date" class="form-control form-control-sm" name="fechamov_ne" placeholder="Fecha" value="<?= ($cont)?$newDate:$fecha  ?>" tabindex=3 <?= ($cont)?"disabled":"" ?>>
               </div>
               <div class="col-xs-6 col-sm-6 col-md-2">
                  <label for="moneda_ne">Moneda:</label>
                  <select class="form-control form-control-sm" id="moneda_ne" name="moneda_ne" tabindex=4>
                    <option value="1" <?= ($idmoneda==1)?"selected":"" ?>>BOLIVIANOS</option>
                    <option value="2" <?= ($idmoneda==2)?"selected":"" ?>>DOLARES </option>
                  </select>
               </div>

               <div class="col-xs-4 col-sm-4 col-md-2">
                  <label>Pedido Cliente:</label>
                  <input id="pedido_ne" type="text" class="form-control form-control-sm" name="pedido_ne" placeholder=""  tabindex=6 value="<?= ($cont)?$dcab->clientePedido:''  ?>">
               </div>
           </div> <!-- div class="form-group-sm row" PRIMERA FILA -->

            <div class="row filacabecera"> <!--SEGUNDA FILA-->


                  
            </div><!-- div class="form-group-sm row" SEGUNDA FILA-->



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
                     <label for="costo_ne">Precio Bs:</label>
                     <input type="text" class="form-control form-control-sm text-right tiponumerico" name="costo_ne" id="costo_ne" disabled/>
                  </div>
                   <div class="col-xs-6 col-md-2">
                      <!--mostrar saldo en almacen de articulo segun codigo-->
                     <label for="saldo_ne">Saldo:</label>
                      <input type="text" class="form-control form-control-sm text-right tiponumerico" id="saldo_ne" name="saldo_ne" disabled/>
                  </div>

                 </div><!-- div class="form-group-sm row"  TERCERA FILA-->

                 
                 <div class="form-group row filaarticulo"> <!--CUARTA FILA-->

                  <div class="col-xs-12 col-md-4">
                      <!--insertar PRECIO de articulo a ingresar-->

                  </div>

                  <div class="col-xs-4 col-md-2">
                        <!--insertar cantidad de productos a ingresar-->
                      <label>Cantidad:</label>
                      <input type="text" class="form-control form-control-sm" id="cantidad_ne" name="cantidad_ne" tabindex=10/>
                  </div>
                  <div class="col-xs-4 col-md-2">
                      <!--insertar costo de articulo a ingresar-->
                      <label>Precio Bs:</label> <!--CAMBIO PARA COMPRAS LOCALES-->
                      <input type="text" class="form-control form-control-sm tiponumerico" id="punitario_ne" name="punitario_ne" tabindex=11/>
                  </div>
                  <div class="col-xs-4 col-md-2">
                        <!--insertar cantidad de productos a ingresar-->
                      <label>% Descuento:</label>
                      <input type="text" class="form-control form-control-sm tiponumerico" id="descuento_ne" name="descuento_ne" tabindex=12/>
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
                <table  class="table table-condensed table-bordered table-striped">
                  <thead>
                    <tr>
                      <th class="col-sm-1" >Código</th>
                      <th class="col-sm-7">Artículo</th>
                      <th class="col-sm-1" class="text-right">Cantidad</th>
                      <th class="col-sm-1" class="text-right">Precio</th> 
                      <th class="col-sm-1" class="text-right">Descuento</th>
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
                                <td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="<?= $fila['descuento'] ?>"></input></td><!--nuevo-->
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
                  <span class = "input-group-addon">$</span>
                  <!--mostrar el total de dolares-->
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalacostosus" tabindex=14>
                  <span class = "input-group-addon" >Bs</span>
                  <!--mostrar el total bolivivanos-->
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalacostobs" tabindex=15>
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
                    <button type="button" class="btn btn-primary" id="actualizarMovimiento">Actualizar Movimiento</button>
                    <button type="button" class="btn btn-warning" id="anularMovimientoEgreso">Anular Movimiento</button>
                    <button type="button" class="btn btn-danger" id="cancelarMovimiento">Cancelar Movimiento</button>

                <?php else: ?>
                    <button type="button" class="btn btn-primary" id="guardarMovimiento" tabindex=11>Guardar Movimiento</button>
                    <button type="button" class="btn btn-danger" id="cancelarMovimiento" tabindex=12>Cancelar Movimiento</button>
                <?php endif ?>
              </div>
            </div>
        </form>

      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->



<!-- Modal -->
<form action=" " method="post"  id="form_clientes" enctype="multipart/form-data">
  <div class="modal fade" id="modalcliente" role="dialog">
    <input type="" name="id_cliente" value="" id="id_cliente" hidden value="<?= "" ?>"> <!-- input oculto para el codigo de articulo-->
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
                        <select name="tipo_doc" id="tipo_doc" class="form-control selectpicker" >
                          <option value=" " >Selecciona</option>

                         
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
                        <input  name="carnet" id="carnet" placeholder="00000000" class="form-control"  type="text">
                      </div>
                    </div>
                  </div>
                  <!-- Nombre de Cliente-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Nombre de Cliente</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input  name="nombre_cliente" id="nombre_cliente" placeholder="Nombre o Razon Social" class="form-control"  type="text">
                      </div>
                    </div>
                  </div>
                   <!-- Tipo Cliente-->
                  <div class="form-group"> 
                    <label class="col-md-3 col-lg-3 control-label">Tipo de Cliente</label>
                    <div class="col-md-9 col-lg-9 selectContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
                        <select name="clientetipo" id="clientetipo" class="form-control selectpicker" >
                          <option value=" " >Selecciona</option>

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
                        <input name="email" id="email"  placeholder="Dirección Email" class="form-control"  type="text">
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
                <button type="submit" class="btn btn-primary bguardar">Guardar</button>
              </div>
          </div> <!-- /class="modal-body form form-horizontal"-->
        </div> <!-- /. class="modal-dialog" -->
      </div> <!-- /. class="modal-dialog" -->
  </div> <!-- /. class="modal fade" -->
</form>