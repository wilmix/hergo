<!-- Modal TIPO CAMBIO footer-->
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