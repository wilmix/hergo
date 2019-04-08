<div class="row">
  <div class="col-xs-12">
    <div class="box">
    <hr>
      <div class="box-body">
          <div id="toolbar2" class="form-inline">
              <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                <?php foreach ($almacen->result_array() as $fila): ?>
                  <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                <?php endforeach ?>
                  <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                  <option value="">TODOS</option>
              </select>

              <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="lote_filtro" name="lote_filtro">
                <?php foreach ($lotes->result_array() as $fila): ?>
                  <option value=<?= $fila['idLote'] ?> ><?= $fila['idLote'] ?></option>
                <?php endforeach ?>
              </select>

              <button  type="button" class="btn btn-primary btn-sm" id="refresh">
                <span>
                  <i class="fa fa-refresh"></i>
                </span>
              </button>
             
           </div>

         <table class="table table-condensed" class="table table-striped"
            data-toggle="table"
            id="facturasManuales"
            data-toolbar="#toolbar2">
          </table>

      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
  <!-- modal editar factura manual -->
<form method="post" id="form_FacturaManual">
    <div class="modal fade" id="modalFacturaManual" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title modalTitulo">Modificar Factura<span id="tituloModal"></span></h3> 
                </div>
                <!--MODAL BODY-->
                <div class="modal-body form form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <div class="col-md-12 inputGroupContainer">
                                <h4 id="msjeBeforeUpdate"></h4>
                            </div>
                            <label class="col-md-3 control-label"> Número Factura</label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input placeholder="" class="form-control" name="newNumFac" id="newNumFac" type="text" autofocus autocomplete="off">
                                    <input name="id"  id="id" type="text" hidden>
                                    <input name="alm"  id="alm" type="text" hidden>
                                    <input name="fechaLimite"  id="fechaLimite" type="text" hidden>
                                    <input name="desde"  id="desde" type="text" hidden>
                                    <input name="hasta"  id="hasta" type="text" hidden>
                                </div>
                            </div>
                            <label class="col-md-3 control-label"> Fecha Factura</label>
                            <div class="col-md-9 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-screenshot"></i>
                                    </span>
                                    <input placeholder="" class="form-control" name="newFechaFac" id="newFechaFac" type="date" autofocus autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default botoncerrarmodal" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary guardarModFactura" id="guardarModFactura">Guardar</button>
                </div>
                </div>
                <!-- /.<div class="modal-body form">-->
            </div>
        </div>
        <!-- /. modal -->
    </div>
</form>