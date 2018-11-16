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
        $idalmacen=$dcab->idalmacen;
        $idtegreso=$dcab->idtipomov;
        $idmoneda=$dcab->idmoneda;
        $idcliente=$dcab->idcliente;
        $idegresocompraslocales=$idtegreso;
        $idvendedor=$dcab->vendedor;
        $nmov = $dcab->n;
        $tipoMov = $dcab->tipomov;
    }
    else
    {
      $idegresocompraslocales=$idtegreso;
    }
?>
<style>
  .totales{
      font-size: 1.3em;
      font-weight: bold;
    }
    input {
      height: 50px;
    }

    input:focus {
      background-color: rgba(60, 141, 188, 0.47);
      ;
      /*color: white;*/
      font-weight: 700;
    }

    select:focus {
      background-color: rgba(60, 141, 188, 0.47);
      /*color: white;*/
    }

    input[type=date]::-webkit-outer-spin-button,
    input[type=date]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
</style>
 <!-- Content Header (Page header) -->
 <section class="content-header">
      <h1>
        <?php echo isset($dcab) ?  'Modificar ':$menu.' - ' ?>
        <span><?php echo isset($dcab) ? $tipoMov.' # '.$nmov :$opcion ?></span>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active"><?php echo isset($opcion) ? $opcion :"" ?></li>
      </ol>
</section>

    <!-- Main content -->
<section class="content">

      <!-- Your Page Content Here -->
<?php $auxIdTipoIngreso=($cont)?$idtegreso:$idegreso ?>
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
            <?php endif ?>
            <div class="row">
              <!--PRIMERA FILA-->
              <div class=" col-xs-6 col-sm-6 col-md-3">
                <label>Almacen:</label>
                <select class="form-control form-control-sm" id="almacen_ne" name="almacen_ne" <?=($cont)?"disabled":""
                  ?>>
                  <option value=<?=$id_Almacen_actual ?>
                    selected="selected">
                    <?= $almacen_actual ?>
                  </option>
                  <?php foreach ($almacen->result_array() as $fila): ?>
                  <option value=<?=$fila['idalmacen'] ?>
                    <?= ($idalmacen==$fila['idalmacen'])?"selected":"" ?> >
                    <?= $fila['almacen'] ?>
                  </option>
                  <?php endforeach ?>
                </select>
              </div>
              <div class=" col-xs-6 col-sm-6 col-md-3">
                <input type="" name="tipomov_ne" id="_tipomov_ne" value="<?= ($auxIdTipoIngreso)?>" class="hidden">
                <label>Tipo de Ingreso:</label>
                <select class="form-control form-control-sm" id="tipomov_ne2" name="tipomov_ne2" disabled>
                  <?php foreach ($tegreso->result_array() as $fila): ?>
                  <?php if ($cont): ?>
                  <!--EDITAR-->
                  <?php if ($idtegreso==$fila['id']): ?>
                  <option value=<?=$fila['id'] ?> "selected">
                    <?= $fila['tipomov'] ?>
                  </option>
                  <?php endif ?>
                  <?php else: ?>
                  <!--NUEVO-->
                  <?php if ($idegreso==$fila['id']): ?>
                  <option value=<?=$fila['id'] ?>
                    <?= ($idegreso==$fila['id'])?"selected":"" ?>>
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
                <label>Vendedor:</label>
                <select class="form-control form-control-sm" id="idUsuarioVendedor" name="idUsuarioVendedor">
                  <?php foreach ($user->result_array() as $fila): ?>
                  <option value=<?=$fila['id'] ?>
                    <?=($fila['id']==$idvendedor)?"selected":""  ?> >
                    <?= $fila['nombre']?>
                  </option>
                  <?php endforeach ?>
                  <option value=<?=$user_id_actual ?> selected="selected">
                    <?= $nombre_actual ?>
                  </option>
                </select>
              </div>
            </div> <!-- div class="form-group-sm row" PRIMERA FILA -->

            <?php if ($auxIdTipoIngreso!=9): ?>
            <div class="row">
              <!--SEGUNDA FILA-->
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
              <div class="col-xs-4 col-sm-4 col-md-2">
                <label>Pedido Cliente:</label>
                <input id="pedido_ne" type="text" class="form-control form-control-sm" name="pedido_ne" value="<?= ($cont)?$dcab->clientePedido:''  ?>">
              </div>
              <div class="col-xs-4 col-sm-4 col-md-2">
                <label>Fecha de Pago: </label>
                <input id="fechapago_ne" name="fechapago_ne" type="text" class="form-control form-control-sm fecha_egreso"
                  value="<?= ($cont)?$dcab->plazopago: "" ?>">
              </div>
              <div class="col-xs-4 col-md-2">
                <label></label>
                <button tabindex="-1" type="button" data-toggle="modal" data-target="#modalcliente" class="form-control btn btn-success"
                  id="botonmodalcliente" style="margin-top: 4px;">
                  Añadir Cliente
                </button>
              </div>
            </div><!-- div class="form-group-sm row" SEGUNDA FILA-->
            <?php else : ?>
            <input type="text" readonly="true" name="idCliente" id="idCliente" class="hidden" value="<?= $auxIdCliente ?>">
            <?php endif ?>
            <hr>
            <div class="row filaarticulo">
            <div class="col-xs-12 col-md-2 has-feedback has-feedback-left">
                <label style="float: left;">Código:
                </label>
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
                  <input type="text" class="form-control form-control-sm" id="Descripcion_ne" name="Descripcion_ne"
                    disabled />
                </div>
                <div class="col-xs-4 col-md-2">
                  <label for="">Unidad:</label>
                  <input type="text" class="form-control form-control-sm" id="unidad_imp" disabled />
                </div>
                <div class="col-xs-4 col-md-2 ">
                  <label ><?= ($auxIdTipoIngreso==9) ? "Costo:" : "Precio:"?> </label>
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
            <div class="form-group row filaarticulo">
              <!--CUARTA FILA-->
              <div class="col-xs-12 col-md-4">
              </div>
              <div class="col-xs-4 col-md-2">
                <label>Cantidad:</label>
                <input type="text" style="text-align:right;" class="form-control form-control-sm" id="cantidad_ne" name="cantidad_ne" />
              </div>
              <div class="col-xs-4 col-md-2">
                <label class="costo_ne_label"><?= ($auxIdTipoIngreso==9) ? "Costo:" : "Precio:"?></label>
                <input type="text" style="text-align:right;" class="form-control form-control-sm" id="punitario_ne"
                  name="punitario_ne" />
              </div>
              <div class="col-xs-4 col-md-2">
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
            </div>
            <!--row CUARTA FILA -->
          </div> <!-- /.class="form" -->
          <hr>
          <!--Tabla para mostrar articulos ingresados-->
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
            <div class="col-xs-12 col-md-12">
              <label for="observaciones_ne">Observaciones:</label>
              <input type="text" class="form-control" id="obs_ne" name="obs_ne" value="<?= ($cont)?$dcab->obs:''  ?>" />
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
              <button type="button" class="btn btn-danger" id="cancelarMovimiento">Cancelar Movimiento</button>
              <?php endif ?>
            </div>
          </div>
        </form>
      </div> <!-- /.box-body    onclick="nuevoAlerta();" -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->
<!-- Modal CLIENTES-->
<form action=" " method="post" id="form_clientes" enctype="multipart/form-data">
  <div class="modal fade" id="modalcliente" role="dialog">
    <input type="" name="id_cliente" value="" id="id_cliente" hidden value="<?= "" ?>">
    <!-- input oculto para el codigo de articulo-->
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
                  <select name="tipo_doc" id="tipo_doc" class="form-control selectpicker">
                    <option value=" ">Selecciona</option>
                    <?php foreach ($tipodocumento->result_array() as $fila):  ?>
                    <option value="<?= $fila['idDocumentoTipo'] ?>">
                      <?= $fila['documentotipo']?>
                    </option>
                    <?php endforeach ?>

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
                  <input name="carnet" id="carnet" placeholder="00000000" class="form-control" type="text">
                </div>
              </div>
            </div>
            <!-- Nombre de Cliente-->
            <div class="form-group">
              <label class="col-md-3 col-lg-3 control-label">Nombre de Cliente</label>
              <div class="col-md-9 col-lg-9 inputGroupContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input name="nombre_cliente" id="nombre_cliente" placeholder="Nombre o Razon Social" class="form-control"
                    type="text">
                </div>
              </div>
            </div>
            <!-- Tipo Cliente-->
            <div class="form-group">
              <label class="col-md-3 col-lg-3 control-label">Tipo de Cliente</label>
              <div class="col-md-9 col-lg-9 selectContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
                  <select name="clientetipo" id="clientetipo" class="form-control selectpicker">
                    <option value=" ">Selecciona</option>
                    <?php foreach ($tipocliente->result_array() as $fila):  ?>
                    <option value="<?= $fila['idClientetipo'] ?>">
                      <?= $fila['clientetipo']?>
                    </option>
                    <?php endforeach ?>
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
                  <input name="email" id="email" placeholder="Dirección Email" class="form-control" type="text">
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