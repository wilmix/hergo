<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="toolbar2" class="form-inline">
                </div>
                <div class="text-center">
                    <h2>DATOS FACTURA</h2>
                </div>
                <div class="text-right">
                    <button class="btn btn-default text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal" data-target="#modalDatosFactura">Agregar Dosificacion</button>
                </div>
                <table id="tablaDatosFactura"
                    data-toolbar="#toolbar2"
                    data-toggle="table">
                </table>
                <!--row-->
            </div>
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
                                    <input name="hasta" placeholder="Hasta" class="form-control" id="hasta" type="text">
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
                                    <textarea class="form-control" id="leyenda1" name="leyenda1" placeholder="Ingrese llave de dosificación"></textarea>
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
                                    <textarea class="form-control" id="leyenda2" name="leyenda2" placeholder="Ingrese llave de dosificación"></textarea>
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
                                    <textarea class="form-control" id="leyenda3" name="leyenda3" placeholder="Ingrese llave de dosificación"></textarea>
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
