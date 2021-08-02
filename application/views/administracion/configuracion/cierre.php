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

                    <h3>Cierre</h3>
                    <section>
                        <div class="text-center">
                            <br>
                            <h4>Crear y descargar backup de la Base de Datos</h4>
                            <br>
                            <button class="btn btn-primary text-center btnnuevo" id="backupDB">Backup</button>
                            <br>
                            <hr>
                            <h4>Fecha de Inventario Inicial</h4>
                            <div class="fecha-cambio">
                            <label class="col-md-4 control-label"><span ></span> </label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input class="form-control" name="fechaCambio" id="fechaCambio" type="text">
                                </div>
                            </div>
                            </div>
                            <br>
                            <br>
                            <h4>Generar cierre para todas las sucursales</h4>
                            <br>
                            <button class="btn btn-primary text-center btnnuevo" id="generarCierre">Generar</button>
                        </div>
                    </section>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>