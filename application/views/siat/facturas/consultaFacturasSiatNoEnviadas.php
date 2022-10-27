<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <div class="forPrint col-md-3">
          <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
          </div>
        </div>
        <div class="form-group col-sm-6 col-md-2" id="almacen" @change="onChangeAlm()">
            <select class="form-control" 
                        :disabled="disabled"
                        v-model="almacen" 
                        id="almacen" 
                        name="almacen">
                <option v-for="option in almacenes" 
                        v-bind:value="option.value"
                        v-text="option.alm">
                </option>
            </select>
        </div>
        <div class="form-group col-sm-6 col-md-2">
            <button class="form-control btn btn-primary" id="buttonSelected" @click="modalEmpaquetar">Empaquetar y enviar</button>
        </div>
      </div>
      </div>
      <div class="box-body">
        <table id="table" class="table table-hover table-striped table-sm" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  <!-- /.class="col-xs-12" -->
  <div id="modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h2 class="modal-title"> Empaquetar y enviar </h2>
                <h4>Las facturas seleccionadas se empaquetaran y enviaran al SIAT para su validacion</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="anularFactura">
                    <div class="form-horizontal">
                        <div class="form-group">
                          <label for="codigoEvento" class="col-sm-3 control-label">Codigo Evento</label>
                          <div class="col-sm-9">
                              <input name="codigoEvento" type="text" class="form-control" v-model="codigoEvento" >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="cantidadFacturas" class="col-sm-3 control-label">Cantidad Facturas</label>
                          <div class="col-sm-9">
                              <input name="cantidadFacturas" type="text" class="form-control" v-model="cantidadFacturas" >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="cafc" class="col-sm-3 control-label">Código CAFC</label>
                          <div class="col-sm-9">
                              <input name="cafc" type="text" class="form-control" v-model="cafc" >
                          </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="enviarPaquete">Enviar</button>
            </div>
        </div>
    </div>
  </div><!-- /.modal -->
</div> <!-- /.class="row" -->



