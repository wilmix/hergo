<!-- Your Page Content Here -->
<div class="row">
  <div class="col-xs-12">
    <div id="app" class="box">
      <div class="box-header with-border">
        <!-- <h3 class="box-title" v-text="title"></h3> -->
      </div>
        <div class="box-body">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Articulos Web - Promociones</h3>
                    </div>
                    <div class="box-body">
                    <table id="promos" class="table table-hover table-striped table-sm" style="width:100%">
                    </table>
                    </div>
                    <div class="box-footer clearfix">
                    </div>
                </div>
            </div>
        </div> <!-- /.box-body -->
        <div id="itemWeb" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">  Promociones </h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="formItemWeb">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="titulo" class="col-sm-2 control-label">Nombre</label>
                                    <div class="col-sm-10">
                                        <input name="titulo" type="text" class="form-control" v-model="titulo">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="descripcion" class="col-sm-2 control-label">Descripción</label>
                                    <div class="col-sm-10">
                                        <textarea name="descripcion" class="form-control" id="glosa" rows="5"name="glosa" v-model="descripcion"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="isActive" class="col-sm-2 control-label">Activo</label>
                                    <div class="col-sm-10">
                                        <!-- <v-select :options="['EFECTIVO','CRÉDITO']" v-model="isActive"></v-select> -->
                                        <select name="isActive" class="form-control" v-model="isActive">
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="imagen" class="col-sm-2 control-label">Imagen 370x248</label>
                                    <div class="col-sm-10">
                                        <input name="imagen" type="file" class="file-loading" id="imagen" accept=".png, .jpg, .jpeg">
                                        <p class="help-block">Imagen de la promo</p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="add">Guardar</button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->