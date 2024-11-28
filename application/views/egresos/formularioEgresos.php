<?php
    $cont=(isset($dcab))?true:false;//si existe datos cabecera true si existe => editar
    $idalmacen=0;
    $idtegreso=0;
    $idmoneda=0;
    $idcliente=0;
    $idvendedor=0;
    if($cont) //editar
    {
        $originalDate = $dcab->fechamov;      
        $newDate = date("Y-m-d", strtotime($originalDate));//revisar mes y año
        //$fechaPago = date("Y-m-d", strtotime($dcab->plazopago));//revisar mes y año        
        $idalmacen=$dcab->idalmacen;
        $idtegreso=$dcab->idtipomov;
        $idmoneda=$dcab->idmoneda;
        $idcliente=$dcab->idcliente;
        $idegresocompraslocales=$idtegreso;
        $idvendedor=$dcab->vendedor;
        $nmov = $dcab->n;
        $tipoMov = $dcab->tipomov;
        $fechaMovimiento = new DateTime($originalDate);
        $fechaPago = new DateTime($dcab->plazopago);
        $tiempoCredito = $fechaMovimiento->diff($fechaPago)->format('%a');
        $tipoMovimiento = $dcab->idtipomov;
        $dcab->tipoNota = $dcab->tipoNota ?? $dcab->tipoEgreso;
    }
    else
    {
      $idegresocompraslocales=$idtegreso;
    }
?>
<!-- Your Page Content Here -->
<?php $auxIdTipoIngreso=($cont)?$idtegreso:$tipoMovimiento ?>
<?php $auxIdAlmacen=($cont)?$idalmacen:$idalmacen ?>

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
              <input id="idegreso" name="idegreso" type="text" class="hidden" value="<?= $dcab->idEgresos ?>">
              <input id="nmov" name="nmov" type="text" class="hidden" value="<?= $nmov ?>">
              <input id="idMoneda" name="idMoneda" type="text" class="hidden" value="<?= $idmoneda ?>">
            <?php endif ?>
            <!--PRIMERA FILA-->
            <div class="row">
              <div class=" col-xs-6 col-sm-6 col-md-3">
                <input type="text" name="almacen" value="<?= ($auxIdAlmacen)?>" class="hidden">
                <label>Almacen: </label>
                <select class="form-control form-control-sm" id="almacen_ne" name="almacen_ne">
                    <?php if ($grupsOfUser == 'Nacional') : ?>
                        <?php $almacenSeleccionado = false; ?>
                        <?php foreach ($almacenes->result_array() as $fila): ?>
                            <?php if ($fila['idalmacen'] == $id_Almacen_actual && !$almacenSeleccionado): ?>
                                <option value=<?= $fila['idalmacen'] ?> selected="selected"><?= $fila['almacen'] ?></option>
                                <?php $almacenSeleccionado = true; ?>
                            <?php else: ?>
                                <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                            <?php endif; ?>
                        <?php endforeach ?>
                    <?php else : ?>
                        <option value=""></option>
                        <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                    <?php endif; ?>
                </select>
              </div>
              <div class=" col-xs-6 col-sm-6 col-md-3">
                <input type="" name="tipomov_ne" id="_tipomov_ne" value="<?= ($auxIdTipoIngreso)?>" class="hidden">
                <label>Tipo de Egreso:</label>
                <select class="form-control form-control-sm" id="tipomov_ne2" name="tipomov_ne2" >
                  <?php foreach ($tiposEgresos->result_array() as $fila): ?>
                  <?php if ($cont): ?>
                  <!--EDITAR-->
                  <?php if ($idtegreso==$fila['id']): ?>
                  <option value=<?=$fila['id'] ?> "selected">
                    <?= $fila['tipomov'] ?>
                  </option>
                  <?php endif ?>
                  <?php else: ?>
                  <!--NUEVO-->
                  <?php if ($tipoMovimiento==$fila['id']): ?>
                  <option value=<?=$fila['id'] ?>
                    <?= ($tipoMovimiento==$fila['id'])?"selected":"" ?>>
                    <?= $fila['tipomov'] ?>
                  </option>
                  <?php endif ?>
                  <?php endif ?>
                  <?php endforeach ?>
                </select>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-2">
                <label>Fecha:</label>
                <input id="fechamov_ne" type="text" class="form-control form-control-sm fecha_egreso" name="fechamov_ne"
                  placeholder="Fecha" value="<?= ($cont)?$newDate:""  ?>" <?=($cont)?"":"autofocus" ?> />
              </div>
              <div class="col-xs-6 col-sm-6 col-md-2">
                <label for="moneda_ne">Moneda:</label>
                <select class="form-control form-control-sm" id="moneda_ne" name="moneda_ne">
                  <option value="1" <?=($idmoneda==1)?"selected":"" ?>>BOLIVIANOS</option>
                  <option value="2" <?=($idmoneda==2)?"selected":"" ?>>DOLARES </option>
                </select>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-2">
                <label><?php echo( $auxIdTipoIngreso == 7 ? 'E.V.Responsable' : 'Responsable') ?> </label>
                <select class="form-control form-control-sm" id="idUsuarioVendedor" name="idUsuarioVendedor">
                  <?php if ($auxIdTipoIngreso == 7): ?>
                      <option disabled selected value="0">Seleccione vendedor</option>
                      <?php foreach ($users->result_array() as $fila): ?>
                      <option value=<?=$fila['id'] ?>
                        <?=($fila['id']==$idvendedor)?"selected":""  ?> >
                        <?= $fila['nombre']?>
                      </option>
                    <?php endforeach ?>
                  <?php else: ?>
                      <option value=<?= isset($dcab) ? $dcab->vendedor : $user_id_actual ?> selected="selected">
                          <?= isset($dcab) ? $dcab->nVendedor : $nombre_actual ?>
                      </option>
                  <?php endif ?>

                    
                </select>                
              </div>
            </div> <!-- div class="form-group-sm row" PRIMERA FILA -->

            <!--SEGUNDA FILA-->
            <div class="row">
              <div class="col-xs-12 col-lg-6 col-md-6">
                <label>Cliente:</label>
                <span style="margin-left: 10px;display: none;" id="cargandocliente">
                  <i class="fa fa-spinner fa-pulse fa-fw"></i>
                </span>
                <input class="form-control form-control-sm" type="text" id="cliente_egreso" name="cliente_egreso" value="<?= ($cont)?$dcab->nombreCliente:''  ?>">
                <input type="text" readonly="true" name="idCliente" id="idCliente" class="hidden" value="<?= ($cont)?$dcab->idcliente:'0'  ?>">
                <div style="right: 22px;top:32px;position: absolute;" id="clientecorrecto">
                  <i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>
                </div>
              </div>
              <div class="hiddenBaja col-xs-4 col-sm-4 col-md-2">
                <label>Pedido Cliente:</label>
                <input id="pedido_ne" type="text" class="form-control form-control-sm" name="pedido_ne" value="<?= ($cont)?$dcab->clientePedido:''  ?>">
              </div>
              <div class="hiddenBaja hiddenVC col-xs-4 col-sm-4 col-md-2">
                <label>Días de Crédito: </label>
                <input id="tiempoCredito" name="tiempoCredito" type="number" class="form-control form-control-sm"
                  value="<?= ($cont)?$tiempoCredito: '0' ?>">
              </div>
              <div class="hiddenBaja col-xs-4 col-md-2">
                <label></label>
                <button tabindex="-1" type="button" data-toggle="modal" data-target="#modalcliente" class="form-control btn btn-success"
                  id="botonmodalcliente" style="margin-top: 4px;">
                  Añadir Cliente
                </button>
              </div>
              <div class="hiddenBaja hidden col-xs-4 col-sm-4 col-md-2">
                <label>Fecha de Pago:</label>
                <input id="fechapago_ne" name="fechapago_ne" type="text" class="form-control form-control-sm"
                  value="<?= ($cont)?$dcab->plazopago: '' ?>">
              </div>
            </div><!-- div class="form-group-sm row" SEGUNDA FILA-->
            <hr>
            <!--TERCERA FILA-->
            <div class="row filaarticulo">
              <div class="col-xs-12 col-md-2 has-feedback has-feedback-left">
                <label style="float: left;">Código:</label>
                  <span style="margin-left: 10px;display: none;" id="cargandocodigoTest">
                    <i class="fa fa-spinner fa-pulse fa-fw"></i>
                  </span>
                  <input class="form-control form-control-sm" type="text" id="articulo_impTest" name="articulo_imp">
                  <div style="right: 22px;top:32px;position: absolute;" id="codigocorrectoTest">
                    <i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>
                  </div>
              </div>
              <div class="col-xs-12 col-md-4">
                <label>Descripción:</label>
                <input type="text" class="form-control form-control-sm" id="Descripcion_ne" name="Descripcion_ne" disabled />
              </div>
              <div class="col-xs-4 col-md-2">
                <label for="">Unidad:</label>
                <input type="text" class="form-control form-control-sm" id="unidad_imp" disabled />
              </div>
              <div class="col-xs-4 col-md-2 ">
                <label class="costo_ne_label" ><?= ($auxIdTipoIngreso==9) ? "Costo Bs:" : "Precio Bs:"?> </label>
                <input type="text" class="form-control form-control-sm text-right tiponumerico" id="precio"
                  disabled />
              </div>
              <div class="col-xs-4 col-md-2">
                <label for="saldo_imp">Saldo:
                  <span style="margin-left: 10px; display: none;" class="cargandoCostoSaldo">
                    <i class="fa fa-spinner fa-pulse fa-fw"></i>
                  </span>
                </label>
                <input type="text" class="form-control form-control-sm text-right tiponumerico" id="saldo_ne"
                  disabled />
              </div>
              <div>
                <input type="text" id="idArticulo" class="hidden">
              </div>
            </div><!-- div class="form-group-sm row"  TERCERA FILA-->
            <!--CUARTA FILA-->
            <div class="form-group row filaarticulo">
              <div class="col-xs-12  <?= ($auxIdTipoIngreso==9)?'col-md-6':'col-md-4' ?>">
              </div>
              <div class="col-xs-4 col-md-2">
                <label>Cantidad:</label>
                <input type="text" style="text-align:right;" class="form-control form-control-sm" id="cantidad_ne" name="cantidad_ne" />
              </div>
              <div class="col-xs-4 col-md-2">
                <label class="costo_ne_label"><?= ($auxIdTipoIngreso==9) ? "Costo Bs:" : "Precio Bs:"?></label>
                <input type="text" style="text-align:right;" class="form-control form-control-sm" id="punitario_ne"
                  name="punitario_ne" <?= ($auxIdTipoIngreso==9)?'tabindex="-1"':'' ?>/>
              </div>
              <div class="col-xs-4 col-md-2 <?= ($auxIdTipoIngreso==9)?'hidden':'' ?>">
                <label>% Descuento:</label>
                <input type="text" style="text-align:right;" class="form-control form-control-sm" id="descuento_ne"
                  name="descuento_ne" />
              </div>
              <div class="col-xs-12 col-md-2">
                <label></label>
                <button type="button" class="form-control btn btn-success" id="agregar_articulo" name="agregar_articulo"
                  style="margin-top: 4px;">
                  Añadir
                </button>
              </div>
            </div><!--row CUARTA FILA -->
          </div> <!-- /.class="form" -->
          <hr>
          <!--Tabla-->
          <div class="table-responsive">
            <table id="tablaEditarEgreso">
            </table>
          </div>
          <div class="form-group row">
            <div class="col-md-6 col-xs-12">
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="input-group col-md-12 col-xs-12">
                <span class="input-group-addon totales">$</span>
                <!--mostrar el total de dolares-->
                <input type="text" class="form-control form-control-sm text-right tiponumerico totales" disabled id="totalDolaresMod">
                <span class="input-group-addon totales">Bs</span>
                <!--mostrar el total bolivivanos-->
                <input type="text" class="form-control form-control-sm text-right tiponumerico totales" disabled id="totalBolivianosMod">
              </div>
            </div>
          </div>
           <hr>
          <div class="row">
            <div class="col-xs-12 col-md-10">
              <label for="observaciones_ne">Observaciones:</label>
              <input type="text" class="form-control" id="obs_ne" name="obs_ne" value="<?= ($cont)?$dcab->obs:''  ?>" />
            </div>
            <div class="col-xs-12 col-md-2 hiddenVC">
              <label for="tipoNota">Tipo: <?php echo '' ?></label>
              <select class="form-control form-control-sm" name="tipoNota" id="tipoNota"  >
              <option value="">Seleccionar</option>
                <?php
                  // Definir opciones de tipoNota
                  $opcionesVentaCaja = [
                    1 => 'Venta'
                  ];
                  $opcionesTipoNota = [
                    1 => 'Venta',
                    2 => 'Prestamo',
                    3 => 'Muestra'
                  ];
                  $opcionesBaja = [
                    11 => 'Baja Reingreso',
                    12 => 'Baja Definitiva'
                  ];
                  // Elegir las opciones basadas en idegreso
                  $opciones = $tipoMovimiento == 6 ? $opcionesVentaCaja : ($tipoMovimiento == 7 ? $opcionesTipoNota : ($tipoMovimiento == 9 ? $opcionesBaja : []));
                  // Obtener tipoNota seleccionado
                  $tipoNotaSeleccionado = isset($dcab) ? $dcab->tipoNota : ($tipoMovimiento == 6 ? 1 : ($tipoMovimiento == 9 ? '' : ($tipoMovimiento == 7 ? '' : 1)));
                  

                  // Mostrar opciones de tipoNota
                  foreach ($opciones as $valor => $texto) {
                      $seleccionado = $valor == $tipoNotaSeleccionado ? 'selected="selected"' : '';
                      echo "<option value=\"$valor\" $seleccionado>$texto</option>";
                  }
                ?>
              </select>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xs-12">
              <?php if ($cont): ?>
                <button type="button" class="btn btn-primary" id="actualizarMovimiento">Modificar Movimiento</button>
              <?php if ($dcab->anulado==0): ?>
                <button type="button" class="btn btn-warning" id="anularMovimientoEgreso">Anular Movimiento</button>
              <?php else: ?>
                <button type="button" class="btn btn-info" id="recuperarMovimientoEgreso">Recuperar Movimiento</button>
              <?php endif ?>
                <button type="button" class="btn btn-danger" id="cancelarMovimiento">Cancelar Movimiento</button>
              <?php else: ?>
                <button type="button" class="btn btn-primary" id="guardarMovimiento">Guardar Movimiento</button>
                <button button type="button" class="btn btn-danger" id="cancelarMovimiento">Cancelar Movimiento</button>
              <?php endif ?>
            </div>
          </div>
        </form>
      </div> <!-- /.box-body    onclick="nuevoAlerta();" -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->