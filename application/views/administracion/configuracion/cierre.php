<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <h1>Cierre de Gestión</h1>
                <div id="steps">
                    <h3>Verificar Pendientes</h3>
                    <section>
                        <p>Verificar Ingresos pendientes de aprobación y Ventas Caja sin Facturar de la gestión actual.</p>
                        <div class="box-body">
                            <div id="toolbar2" class="form-inline">
                                <button  type="button" class="btn btn-primary" id="btnVerificarPendientes">
                                    <span>
                                        <i class="fa fa-check"> Verificar </i>
                                    </span>
                                </button>
                            </div>
                            <table 
                                id="verificarPendientes" 
                                data-toolbar="#toolbar2"
                                data-height="450">
                            </table>
                        </div>
                    </section>

                    <h3>Verificar Negativos</h3>
                    <section>
                        <p>Verificar Artículos con saldo negativo de la gestión actual.</p>
                        <div class="box-body">
                            <div id="toolbar3" class="form-inline">
                                <button  type="button" class="btn btn-primary" id="btnVerificarNegativos">
                                    <span>
                                        <i class="fa fa-check"> Verificar </i>
                                    </span>
                                </button>
                            </div>
                            <table 
                                id="verificarNegativos" 
                                data-toolbar="#toolbar3"
                                 data-height="450">
                            </table>
                        </div>
                    </section>

                    <h3>Datos Inventario Inicial</h3>
                    <section>
                        <div class="text-center">
                            <h4>Agregar tipo de Cambio para Inventario Inicial</h4>
                            <br>
                            <button class="btn btn-primary text-center btnnuevo"  data-toggle="modal" data-target="#modalTipoCambio">Agregar Tipo Cambio</button>
                            <br>
                            <hr>
                            <br>
                            <h4>Crear y descargar backup de la Base de Datos</h4>
                            <br>
                            <button class="btn btn-primary text-center btnnuevo" id="backupDB">Backup</button>
                        </div>
                    </section>

                    <h3>Cierre</h3>
                    <section>
                        <p>Cerrar Gestión e Iniciar siguiente gestión</p>
                    </section>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>

    <!-- Modal -->
<form action=" " method="post" id="form_tipoCambio">
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
                                    <input placeholder="Establecer nuevo tipo de cambio" class="form-control" name="tipocambio" id="tipocambio" type="text" autofocus>
                                    <input name="id"   id="id" type="text" hidden>
                                </div>
                            </div>
                        </div>
                        <div class="form-group fecha-cambio">
                            <label class="col-md-3 control-label"><span ></span> Fecha</label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input class="form-control" name="fechaCambio" id="fechaCambio" type="text" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default botoncerrarmodal" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary guardarTipoCambio" id="guardarTipoCambio">Guardar</button>
                </div>
                </div>
                <!-- /.<div class="modal-body form">-->
            </div>
        </div>
        <!-- /. modal -->
    </div>
</form>