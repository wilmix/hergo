<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div>
                    <div id="toolbar" class="btn-group" >
                        <button class="btn btn-primary text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal" data-target="#modalDatosFactura">Agregar Dosificacion</button>
                    </div>
                
                <div class="text-center">
                    <h2>DATOS FACTURA</h2>
                </div>
                <table id="tablaDatosFactura"
                    data-toolbar="#toolbar">
                </table>
                </div>
                <!--row-->
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>

<!-- Modal -->
<form action=" " method="post" id="form_datosFactura">
    <div class="modal fade" id="modalDatosFactura" role="dialog">
        <input type="" name="id_lote" value="" id="id_lote" hidden>
        <!-- input oculto para el ID-->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title modalTitulo"></h3> <!--***********   class titulo *************-->
                </div>
                <!--MODAL BODY-->
                <div class="modal-body form form-horizontal">
                    <fieldset>
                        <!-- almacen-->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Almacen: </label>
                            <div class="col-md-9 selectContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-pushpin"></i>
                                    </span>
                                    <select name="almacen" class="form-control selectpicker" id="almacen">
                                        <option value="" >Seleccione</option>
                                        <?php foreach ($almacenes->result_array() as $fila): ?>
                                            <option value="<?= $fila['idalmacen']  ?>"><?= $fila['almacen'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- autorizacion-->
                        <div class="form-group">
                            <label class="col-md-3 control-label">N° Autorización: </label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input name="autorizacion" placeholder="N° de Autorización" class="form-control" id="autorizacion" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- desde -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Desde: </label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input name="desde" placeholder="Desde" class="form-control" id="desde" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- hasta -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Hasta: </label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input name="hasta" placeholder="Hasta" value="9999" class="form-control" id="hasta" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- fecha limite -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Fecha Limite de Emisión: </label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input name="fechaLimite" placeholder="AAAA-MM-DD" class="form-control" id="fechaLimite" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- tipo -->
                        <div class="form-group"> 
                            <label class="col-md-3 col-lg-3 control-label">Tipo: </label>
                            <div class="col-md-9 col-lg-9 selectContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-resize-full"></i></span>
                                <select name="tipo" class="form-control selectpicker" id="tipo">
                                    <option>Elige</option>
                                    <option value="1">Manual</option>
                                    <option value="0">Computarizada</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        <!-- llave -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Llave de Dosificación: </label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input name="llave" placeholder="Ingrese llave de dosificación" class="form-control" id="llave" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- leyenda 1111111111 -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Leyenda 1: </label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <textarea class="form-control" id="leyenda1" name="leyenda1">"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LA LEY"</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Leyenda 2: </label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <textarea class="form-control" id="leyenda2" name="leyenda2">Ley Nº 453: Está prohibido importar, distribuir  o comercializar productos prohibidos o retirados en el país de origen por atentar a la integridad física y a la salud</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Leyenda 3: </label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <textarea class="form-control" id="leyenda3" name="leyenda3">VENTA AL POR MAYOR DE MAQUINARIA, EQUIPO Y MATERIALES</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Uso -->
                        <div class="form-group"> 
                            <label class="col-md-3 col-lg-3 control-label">En uso</label>
                            <div class="col-md-9 col-lg-9 selectContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-object-align-top"></i></span>
                                <select name="uso" class="form-control selectpicker" id="uso">
                                <option>Elige</option>
                                <option value="1">Si</option>
                                <option value="0">No</option>
                                </select>
                            </div>
                            </div>
                        </div>
                    </div>
                    </fieldset>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default botoncerrarmodal" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="guardarDatosFactura">Guardar</button>
                </div>
                </div>
                <!-- /.<div class="modal-body form">-->
            </div>
        </div>
        <!-- /. modal -->
    </div>
</form>
