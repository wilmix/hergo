<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <div class="col-md-2">
          <label class="control-label">Sucursal</label>
          <input type="text" class="form-control" v-model="sucursal">
        </div>
        <div class="col-md-2">
          <label class="control-label">Punto Venta</label>
          <input type="text" class="form-control" v-model="puntoVenta">
        </div>
        <div class="col-md-2">
          <label class="control-label">&nbsp</label>
          <button type="button" class="btn btn-primary" @click="getCuis">Generar Cuis</button>
        </div>
      </div>
      <div class="box-body">
        <table id="table" class="table table-hover table-striped table-sm" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div>

  <div id="modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h2 class="modal-title"> Añadir Punto Venta </h2>
                <h4></h4>
            </div>
            <div class="modal-body">
                <form method="post" id="">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Descripción</label>
                            <div class="col-sm-10">
                            <v-select :options="tiposPuntoVenta" label="descripcion" v-model="codigoTipoPuntoVenta"></v-select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Descripción</label>
                            <div class="col-sm-10">
                                <input name="descripcion" type="text" class="form-control" v-model="descripcion" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nombre Punto Venta</label>
                            <div class="col-sm-10">
                                <input name="nombrePuntoVenta" type="text" class="form-control" v-model="nombrePuntoVenta" >
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="registrarPuntoventa">Registrar</button>
            </div>
        </div>
    </div>
  </div><!-- /.modal -->
   <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->



