<?php

    $cont=(isset($dcab))?true:false;//si existe datos cabecera true si existe => editar
    $idalmacen=0;
    $idtingreso=0;
    $idmoneda=0;
    $idproveedor=0;
    $tipoDoc=1;
    if($cont)
    {
        $originalDate = $dcab->fechamov;
        //$originalDate = str_replace("/", "-", $originalDate);
        $newDate = date("Y-m-d", strtotime($originalDate));//revisar mes y año
       /* echo $originalDate."\n";
        echo $newDate;
        die();*/
        $idalmacen=$dcab->idalmacen;
        $idtingreso=$dcab->idtipomov;
        $idmoneda=$dcab->idmoneda;
        $idproveedor=$dcab->idproveedor;
        $idingresocompraslocales=$idtingreso;
        $tipoDoc = $dcab->tipoDoc;
        $tipoMov = $dcab->tipomov;
    }
    else
    {
      $idingresocompraslocales=$idingreso;
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
<section class="content-header">
  <h1>
    <?php echo isset($dcab) ?  'Modificar ':$menu.' - ' ?>
    <span><?php echo isset($dcab) ? $tipoMov.' # '.$dcab->n :$opcion ?></span>
  </h1>
</section>

<!-- Main content -->
<section class="content">
  
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <form action="" method="post"  id="form_ingresoImportaciones">
          <div class="form">
          <!-- OCULTO ID-->
            <?php if ($cont): ?>
                <input  id="idingresoimportacion" 
                        name="idingresoimportacion" 
                        type="text" 
                        class="hidden"  
                        value="<?= $dcab->idIngresos ?>">
            <?php endif ?>
            <div>
              <input  id="nmov" 
                      type="number" 
                      class="hidden" 
                      name="nmov" 
                      value="<?= ($cont)?$dcab->n:""  ?>"/>
              <input  id="img_name" 
                      type="text" 
                      class="hidden"  
                      name="img_name" 
                      value="<?= ($cont)?$dcab->img_route:""  ?>"/>
            </div>
            <!--PRIMERA FILA-->
            <div class="row"> 
              <!-- almacen -->                
              <div class=" col-xs-6 col-sm-6 col-md-3">
                <label>Almacen:</label>
                <select class="form-control form-control-sm" 
                        id="almacen_imp" 
                        name="almacen_imp" 
                        <?= ($cont)?"disabled":"" ?>>
                  <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                  <?php foreach ($almacen->result_array() as $fila): ?>
                      <option value=<?= $fila['idalmacen'] ?> 
                      <?= ($idalmacen==$fila['idalmacen'])?"selected":"" ?>>
                      <?= $fila['almacen'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>
              <!-- tipo ingreso -->
              <div class=" col-xs-6 col-sm-6 col-md-3">
                <input type="" name="tipomov_imp" value="<?= (isset($idingreso)?$idingreso:0)?>" class="hidden">
                <label for="tipomov_imp">Tipo de Ingreso:</label>
                <select class="form-control form-control-sm" 
                        id="tipomov_imp2" 
                        name="tipomov_imp2"  
                        <?= ($cont)?"disabled":"" ?>>
                  <?php foreach ($tingreso->result_array() as $fila): ?>
                    <?php if ($cont): ?>
                      <?php if ($idtingreso==$fila['id']): ?>
                        <option value=<?= $fila['id'] ?> "selected"><?= $fila['tipomov'] ?></option>
                      <?php endif ?>
                    <?php else: ?>
                      <?php if ($idingreso==$fila['id']): ?>
                                  <option value=<?= $fila['id'] ?> <?= ($idingreso==$fila['id'])?"selected":"" ?>><?= $fila['tipomov'] ?></option>
                      <?php endif ?>
                    <?php endif ?>
                  <?php endforeach ?>
                </select>
              </div>
              <!-- fecha -->
              <div class="col-xs-6 col-sm-6 col-md-2">
                  <label>Fecha:</label>
                  <input  id="fechamov_imp" 
                          type="text" 
                          class="form-control form-control-sm fecha_ingreso" 
                          name="fechamov_imp" 
                          placeholder="Fecha" 
                          value="<?= ($cont)?$newDate:''  ?>"  <?= ($cont)?"":"autofocus" ?> />
              </div>
              <!-- moneda -->
              <div class="col-xs-6 col-sm-6 col-md-2">
                  <label for="moneda_imp">Moneda:</label>
                  <select class="form-control form-control-sm" id="moneda_imp" name="moneda_imp">
                    <option value="1" <?= ($idmoneda==1)?"selected":"" ?> >BOLIVIANOS</option>
                    <option value="2" <?= ($idmoneda==2)?"selected":"" ?>>DOLARES </option>
                  </select>
              </div>
              <!-- orden compra -->
              <div class="col-xs-4 col-sm-4 col-md-2">
                <label>Orden de Compra:</label>
                <input id="ordcomp_imp" 
                      type="text" 
                      class="form-control form-control-sm" 
                      name="ordcomp_imp" 
                      placeholder="Orden de Compra" 
                      value="<?= ($cont)?$dcab->ordcomp:"" ?>" >
              </div>
            </div> <!-- div class="form-group-sm row" PRIMERA FILA -->
            <!--SEGUNDA FILA-->
            <div class="row"> 
              <!-- provedor -->
              <div class="col-xs-12 col-lg-6 col-md-6">
                <label >Proveedor:</label>
                  <select  class="form-control " 
                          data-size="5" data-live-search="true" 
                          id="proveedor_imp" name="proveedor_imp">
                  <?php foreach ($proveedor->result_array() as $fila): ?>
                    <option  value=<?= $fila['idproveedor'] ?> 
                              <?= ($idproveedor==$fila['idproveedor'])?"selected":"" ?>>
                              <?= $fila['nombreproveedor'] ?>
                    </option>
                  <?php endforeach ?>
                </select>
              </div>
              <!-- tipo nFactura-->
              <div id="tipoNumFactura" >
                <div class="col-xs-4 col-sm-4 col-md-2">
                  <label >Tipo:</label>
                  <select class="form-control form-control-sm" id="tipoDoc" name="tipoDoc">
                      <option value="1" <?= ($tipoDoc==1)?"selected":"" ?> >CON FACTURA</option>
                      <option value="2" <?= ($tipoDoc==2)?"selected":"" ?>>SIN FACTURA </option>
                      <option value="3" <?= ($tipoDoc==3)?"selected":"" ?>>EN TRANSITO </option>
                  </select>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-2">
                  <label class="tipoDocumento hidden">N° Factura:</label>
                  <input id="nfact_imp" 
                        name="nfact_imp" 
                        type="text" 
                        class="form-control form-control-sm  tipoDocumento hidden"  
                        placeholder="# Factura" 
                        value="<?= ($cont)?$dcab->nfact:""?>">
                </div>
              </div>
               <!-- flete -->
               <div  class="col-xs-4 col-md-2">
                  <label for="flete">Flete:</label>
                  <input  type="number" 
                          style="text-align:right;" 
                          class="form-control form-control-sm" 
                          id="flete" 
                          name="flete"
                          value="<?= ($cont)?$dcab->flete:"" ?>" />
              </div>
            </div><!-- div class="form-group-sm row" SEGUNDA FILA-->
            <hr>
            <!--TERCERA FILA-->
              <div class="row">
              <!-- codigo -->
              <div class="col-xs-12 col-md-2 has-feedback has-feedback-left">
                  <label style="float: left;">Código:
                    </label>
                    <span style="margin-left: 10px;display: none;" 
                          id="cargandocodigoTest" >
                          <i class="fa fa-spinner fa-pulse fa-fw"></i>
                    </span>
                  <input  class="form-control form-control-sm" 
                          type="text" 
                          id="articulo_impTest" 
                          name="articulo_imp">
                  <div  style="right: 22px;top:32px;position: absolute;" 
                        id="codigocorrectoTest">
                        <i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>
                  </div>
              </div>
              <!-- descripcion -->
              <div class="col-xs-12 col-md-4">
                  <label for="descripcion_imp">Descripción:</label>
                  <input type="text" 
                        class="form-control form-control-sm" 
                        id="Descripcion_imp" 
                        name="Descripcion_imp" 
                        disabled/>
              </div>
              <!-- unidad -->
              <div class="col-xs-4 col-md-2">
                  <label for="">Unidad:</label>
                  <input type="text" 
                        class="form-control form-control-sm" 
                        id="unidad_imp" 
                        disabled/>
              </div>
              <!-- cpp -->
              <div class="col-xs-4 col-md-2 ">
                  <label id="labelCPP">CPP Bs.:
                    <span style="margin-left: 10px; display: none;" 
                          class="cargandoCostoSaldo">
                    <i class="fa fa-spinner fa-pulse fa-fw"></i>
                    </span>
                  </label>
                  <input type="text" 
                        class="form-control form-control-sm text-right tiponumerico" 
                        id="costo_imp" 
                        disabled/>
              </div>
              <!-- saldo -->
              <div class="col-xs-4 col-md-2">
                <label for="saldo_imp">Saldo:
                    <span style="margin-left: 10px; display: none;" 
                          class="cargandoCostoSaldo">
                          <i class="fa fa-spinner fa-pulse fa-fw"></i>
                    </span>
                  </label>
                  <input  type="text" 
                          class="form-control form-control-sm text-right tiponumerico" 
                          id="saldo_imp" 
                          disabled/>
              </div>
            </div><!-- div class="form-group-sm row"  TERCERA FILA-->
            <!--CUARTA FILA-->
            <div class="form-group row">
              <!-- vacio -->
              <div class="col-xs-12 col-md-4">
              </div>
              <!-- cantidad -->
              <div class="col-xs-4 col-md-2">
                  <label>Cantidad:</label>
                  <input  type="text" 
                          style="text-align:right;" 
                          class="form-control form-control-sm" 
                          id="cantidad_imp" 
                          name="cantidad_imp" />
              </div>
              <!-- total -->
              <div class="col-xs-4 col-md-2">
                  <label id="labelTotal"><?= $idingresocompraslocales==2? "Total Bs:":"Costo Unitario Bs:" ?></label>
                  <input  type="text" 
                          style="text-align:right;" 
                          class="form-control form-control-sm" 
                          id="punitario_imp" 
                          name="punitario_imp" />
              </div>
              <!-- costoUnitario -->
              <div class="col-xs-4 col-md-2">
                    <!--insertar cantidad de productos a ingresar-->
                  <label>Costo Unitario:</label>
                  <input type="text" class="form-control form-control-sm tiponumerico" id="constounitario" name="" disabled/>
              </div>
              <!-- idArticuloHidden -->
              <div>
                <input type="text" id="idArticulo" class="hidden">
              </div>
              <!-- botonAñadir -->      
              <div class="col-xs-12 col-md-2">
                <label></label>
                <button type="button" class="form-control btn btn-success" id="agregar_articulo" name="agregar_articulo" style="margin-top: 4px;" >Añadir</button>
              </div>
            </div><!--row CUARTA FILA -->
          </div> <!-- /.class="form" -->
          <hr>
          <!--Tabla para mostrar articulos ingresados-->
          <div class="table-responsive">
            <table  class="table table-condensed table-bordered table-striped" >
              <thead>
                <tr>
                  <th></th>
                  <th class="col-sm-1" >Código</th>
                  <th class="col-sm-6">Artículo</th>
                  <th class="col-sm-1" class="text-right">Cantidad</th>
                  <th class="col-sm-1" class="text-right">P/U</th>
                  <th class="col-sm-1" class="text-right">Total</th>
                  <th class="text-right">C/U Sistema</th>
                  <th class="text-right">Total Sistema</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody id="tbodyarticulos" name="tabla">
                <?php if ($cont): ?>
                    <?php foreach ($detalle as $fila): ?>
                      <?php 
                        $punitariofac= $fila['cantidad']==""?0:$fila['cantidad'];
                        $punitariofac=$fila['totaldoc'] / $punitariofac;
                      ?>
                        <tr>
                            <td><input type="text" class="estilofila hidden" disabled value="<?= $fila['idArticulo'] ?>"></td>
                            <td><input type="text" class="estilofila" disabled value="<?= $fila['CodigoArticulo'] ?>"></td>
                            <td><input type="text" class="estilofila" disabled value="<?= $fila['Descripcion'] ?>"></td>
                            <td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="<?= $fila['cantidad'] ?>"></td>
                            <td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="<?= $punitariofac ?>"></td><!--nuevo-->
                            <td class="text-right"><input type="text" class="totalDoc estilofila tiponumerico" disabled value="<?= $fila['totaldoc'] ?>"></td><!--nuevo-->
                            <td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="<?= $fila['punitario'] ?>"></td>
                            <td class="text-right"><input type="text" class="totalCosto estilofila tiponumerico" disabled value="<?= $fila['total'] ?>"></td>
                            <td><button type="button" class="btn btn-default eliminarArticulo" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>

              </tbody>
            </table>
          </div>
          <!-- totales -->            
          <div class="form-group row">
            <div class="col-md-6 col-xs-12">
            </div>
            <div class="col-md-6 col-xs-12">
              <div class = "input-group col-md-12 col-xs-12">
                <span class = "input-group-addon totales" id="nombretotaldoc"></span>
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico totales" disabled id="totalacostodoc">
                <span class = "input-group-addon totales" id="nombretotalsis" ></span>
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico totales" disabled id="totalacostobs">
              </div>
            </div>
          </div>
          <hr>
          <!-- observaciones -->
          <div class="row">
            <div class="col-md-8">
              <!--insertar costo de articulo a ingresar-->
              <label for="observaciones_imp">Observaciones:</label>
              <input type="text" class="form-control" id="obs_imp" name="obs_imp" value="<?= ($cont)?$dcab->obs:""  ?>" />
            </div>
            <!-- Imagen -->
            <div class="col-md-4">
               <div class="upload_image">
                <div class="form-group">
                  <label for="img_route">Imagen de comprobante:</label>
                  <input id="img_route" name="img_route" type="file" accept="image/*">
                </div>
              </div> 
            </div>
          </div>
          <hr>
          <hr>
          <!-- botonesGuardar -->
          <div class="row">
              <div class="col-xs-12">
              <?php if ($cont): ?>
                  <button type="button" class="btn btn-primary" id="actualizarMovimiento">Modificar Movimiento</button>
                    <?php if ($dcab->anulado==0): ?>
                      <button type="button" class="btn btn-warning" id="anularMovimiento">Anular Movimiento</button>  
                    <?php else: ?>
                      <button type="button" class="btn btn-info" id="recuperarMovimiento">Recuperar Movimiento</button>  
                    <?php endif ?>
                    
                  <button type="button" class="btn btn-danger" id="cancelarMovimientoActualizar">Cancelar</button>
              <?php else: ?>
                  <button type="button" class="btn btn-primary" id="guardarMovimiento">Guardar Movimiento</button>
                  <button type="button" class="btn btn-danger" id="cancelarMovimiento">Cancelar Movimiento</button>
              <?php endif ?>
            </div>
          </div>
        </form>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->

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