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
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <!--<h3 class="box-title">Ingreso Importaciones</h3>-->
      </div>
      <div class="box-body">
        <form action=" " method="post" id="form_egreso">
          <div class="form">
            <!-- formulario PRIMERA FILA-->
            <?php if ($cont): ?>
            <input id="idegreso" name="idegreso" type="text" class="hidden" hidden value="<?= $dcab->idEgresos ?>">
            
            <?php endif ?>

            <div class="row filacabecera">
              <!--PRIMERA FILA-->
              <div class=" col-xs-6 col-sm-6 col-md-3">
                <label>Almacen Origen:</label>
                <select class="form-control form-control-sm" id="almacen_ori" name="almacen_ori"<?=($cont)?"disabled":""
                  ?>>
                  <option value=<?=$id_Almacen_actual ?> selected="selected">
                    <?= $almacen_actual ?>
                  </option>
                  <?php foreach ($almacen->result_array() as $fila): ?>
                  <option value=<?=$fila['idalmacen'] ?>
                    <?= ($idalmacenOrigen==$fila['idalmacen'])?"selected":"" ?> >
                    <?= $fila['almacen'] ?>
                  </option>
                  <?php endforeach ?>
                </select>
              </div>

              <!--ALMACEN DESTINO-->
              <div class=" col-xs-6 col-sm-6 col-md-3">
                <label>Almacen Destino:</label>
                <select <?=($cont)?"":"autofocus" ?> class="form-control form-control-sm" id="almacen_des" name="almacen_des" <?=($cont)?"disabled":""
                  ?>>
                  <option value="">SELECCIONE ALMACEN</option>
                  <?php foreach ($almacen->result_array() as $fila): ?>
                  <option value=<?=$fila['idalmacen'] ?>
                    <?= ($idalmacenDestino==$fila['idalmacen'])?"selected":"" ?> >
                    <?= $fila['almacen'] ?>
                  </option>
                  <?php endforeach ?>
                </select>
              </div>
              <input name="idEgreso" value="<?= ($cont)?$dTransferencia->idEgreso:"" ?>" class="hidden">
              <input name="idIngreso" value="<?= ($cont)?$dTransferencia->idIngreso:"" ?>" class="hidden">

              <div class="col-xs-6 col-sm-6 col-md-2">
                <label>Fecha:</label>
                <input id="fechamov_ne" type="text" class="form-control form-control-sm fecha_traspaso" name="fechamov_ne"
                  placeholder="Fecha" value="<?= ($cont)?$newDate:''  ?>" <?=($cont)?"autofocus":"" ?>>
              </div>
              <div>
                <label class="hidden">Moneda:</label>
                <select id="moneda_ne" name="moneda_ne" class="hidden">
                  <option value="1" <?=($idmoneda==1)?"selected":"" ?>>BOLIVIANOS</option>
                  <option value="2" <?=($idmoneda==2)?"selected":"" ?>>DOLARES </option>
                </select>
              </div>
              <div class="col-xs-4 col-sm-4 col-md-2">
                <label>N° Pedido:</label>
                <input id="pedido_ne" name="pedido_ne" type="text" class="form-control form-control-sm"
                  value="<?= ($cont)?$dcab->clientePedido:''  ?>">
              </div>
            </div> <!-- div class="form-group-sm row" PRIMERA FILA -->
            <hr>
            <div class="row filaarticulo">
              <!--TERCERA FILA-->
              <div class="col-xs-12 col-md-2 has-feedback has-feedback-left">
                  <label for="articulo_ne" style="float: left;">Código:</label><span style="margin-left: 10px;display: none;" id="cargandocodigoTest" ><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
                  <input class="form-control form-control-sm" type="text" id="articulo_impTest" name="articulo_imp"/>
                  <div style="right: 22px;top:32px;position: absolute;" id="codigocorrectoTest"><i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i></div>
              </div>
              <div>
                <input type="text" id="idArticulo" class="form-control form-control-sm hidden">
              </div>
              <div class="col-xs-9 col-md-4">
                <!--mostrar descripcion de articulo segun codigo-->
                <label for="descripcion_ne">Descripcion:</label>
                <input type="text" class="form-control form-control-sm" id="descripcionArticulo" name="Descripcion_ne" disabled />
              </div>
              <div class="col-xs-3 col-md-2">
                <!--mostrar unidad de articulo segun codigo-->
                <label for="">Unidad:</label>
                <input type="text" class="form-control form-control-sm" id="unidad_ne" name="unidad_ne" disabled />
              </div>
              <div class="col-xs-6 col-md-2">
                <!--mostrar costo promedio ponderado de articulo segun codigo-->
                <label>Costo Bs:</label>
                <input type="text" class="form-control form-control-sm text-right tiponumerico" name="costo_ne" id="costo"
                  disabled />
              </div>
              <div class="col-xs-6 col-md-2">
                <!--mostrar saldo en almacen de articulo segun codigo-->
                <label>Saldo:</label>
                <input type="text" class="form-control form-control-sm text-right tiponumerico" id="saldo" name="saldo_ne"
                  disabled />
              </div>

            </div><!-- div class="form-group-sm row"  TERCERA FILA-->


            <div class="form-group row filaarticulo">
              <!--CUARTA FILA-->

              <div class="col-xs-12 col-md-8">
                <!--insertar PRECIO de articulo a ingresar-->

              </div>

              <div class="col-xs-4 col-md-2">
                <!--insertar cantidad de productos a ingresar-->
                <label>Cantidad:</label>
                <input type="text" class="form-control form-control-sm" id="cantidad_ne" name="cantidad_ne" />
              </div>
              <!--<div class="col-xs-4 col-md-2">
                 
                <label>Costo Bs:</label>
                <input type="text" class="form-control form-control-sm tiponumerico" id="punitario_ne" name="punitario_ne"
                  tabindex=8 />
              </div>-->


              <div class="col-xs-12 col-md-2">
                <label></label>
                <button type="button" class="form-control btn btn-success" id="agregar_articulo" name="agregar_articulo"
                  style="margin-top: 4px;">Añadir</button>
              </div>
            </div>
            <!--row CUARTA FILA -->

          </div> <!-- /.class="form" -->
          <hr>
          <!--Tabla para mostrar articulos ingresados-->
          <div class="table-responsive">
            <table class="table table-condensed table-bordered table-striped" data-show-columns="true">
              <thead>
                <tr>
                  <th class="col-sm-1"> id</th>
                  <th class="col-sm-1">Código</th>
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
                          ?>
                <tr>
                  <td><input type="text" class="estilofila" disabled value="<?= $fila['idArticulos'] ?>"></td>
                  <td><input type="text" class="estilofila" disabled value="<?= $fila['CodigoArticulo'] ?>"></td>
                  <td><input type="text" class="estilofila" disabled value="<?= $fila['Descripcion'] ?>"></td>
                  <td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="<?= $fila['cantidad'] ?>"></td>
                  <td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="<?= $fila['punitario']?>"></td>
                  <td class="text-right"><input type="text" class="totalCosto estilofila tiponumerico" disabled value="<?= $fila['total'] ?>"></td>
                  <td><button type="button" class="btn btn-default eliminarArticulo" aria-label="Left Align">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                      </button>
                  </td>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
              </tbody>
            </table>
          </div>

          <div class="form-group row">
            <div class="col-md-6 col-xs-12">
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="input-group col-md-12 col-xs-12">
                <span class="input-group-addon">Sus</span>
                <!--mostrar el total de dolares-->
                <input type="text" class="form-control form-control-sm text-right tiponumerico" readonly id="totalacostosus">
                <span class="input-group-addon">Bs</span>
                <!--mostrar el total bolivivanos-->
                <input type="text" class="form-control form-control-sm text-right tiponumerico" readonly id="totalacostobs">
              </div>
            </div>
          </div>
          <!--row-->
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
              <button type="button" class="btn btn-primary" id="actualizarMovimiento">Modificar Traspaso</button>
              <?php if ($dcab->anulado==0): ?>
              <button type="button" class="btn btn-warning" id="anularTraspaso">Anular Traspaso</button>
              <?php else: ?>
              <button type="button" class="btn btn-info" id="recuperarTraspaso">Recuperar Traspaso</button>
              <?php endif ?>
              <button type="button" class="btn btn-danger" id="cancelarMovimiento">Cancelar Traspaso</button>
              <?php else: ?>
              <button type="button" class="btn btn-primary" id="guardarMovimiento">Guardar Traspaso</button>
              <button type="button" class="btn btn-danger" id="cancelarMovimiento">Cancelar Traspaso</button>
              <?php endif ?>



            </div>
          </div>
        </form>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->