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
        <input type="" name="id_lote" value="" id="id_lote" hidden>
        <!-- input oculto para el ID-->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title modalTitulo">Establecer Tipo de cambio</h3> <!--***********   class titulo *************-->
                </div>
                <!--MODAL BODY-->
                <div class="modal-body form form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tipo Cambio: </label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input name="tipoCambio" placeholder="Esttablecer nuevo tipo de cambio" class="form-control" id="tipoCambio" type="text">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default botoncerrarmodal" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="guardarTipoCambio">Guardar</button>
                </div>
                </div>
                <!-- /.<div class="modal-body form">-->
            </div>
        </div>
        <!-- /. modal -->
    </div>
</form>
