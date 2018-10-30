<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="container">
                    <div id="toolbar" class="btn-group" >
                        <button class="btn btn-primary text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal" data-target="#modalTipoCambio">Agregar Tipo Cambio</button>
                    </div>
                <div class="text-center">
                    <h2>TIPO DE CAMBIO</h2>
                </div>
                <table id="tablaTipoCambio"
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
<form action=" " method="post" id="form_tipoCambio">
    <div class="modal fade" id="modalTipoCambio" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title modalTitulo">Establecer Tipo de cambio para: </h3> 
                </div>
                <!--MODAL BODY-->
                <div class="modal-body form form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><span id="fechaTipoCambio"></span> </label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input name="tipocambio" placeholder="Establecer nuevo tipo de cambio" class="form-control" name="tipocambio" id="tipocambio" type="text">
                                    <input name="id"   id="id" type="text" hidden>
                                    <input name="fecha"   id="fecha" type="text" hidden>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default botoncerrarmodal" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary guardarTipoCambio" id="guardarTipoCambio">Modificar</button>
                </div>
                </div>
                <!-- /.<div class="modal-body form">-->
            </div>
        </div>
        <!-- /. modal -->
    </div>
</form>
