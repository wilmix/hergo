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
                        <h3 class="box-title">Articulos Web</h3>
                    </div>
                    <div class="box-body">
                    <table id="web" class="table table-hover table-striped table-sm" style="width:100%">
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
                        <h4 class="modal-title"> Editar {{codigo}} - {{ desc_sis }} </h4>
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
                                    <label for="n1" class="col-sm-2 control-label">Nivel 1</label>
                                    <div class="col-sm-10">
                                        <v-select :options="data_n1" v-model="n1" @input="getLevel(n1.id, 'web_nivel2', 'id_nivel1')"></v-select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="isActive" class="col-sm-2 control-label">Nivel 2</label>
                                    <div class="col-sm-10">
                                        <v-select :options="data_n2" v-model="n2"  @input="getLevel(n2.id, 'web_nivel3', 'id_nivel2')"></v-select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="isActive" class="col-sm-2 control-label">Nivel 3</label>
                                    <div class="col-sm-10">
                                        <v-select :options="data_n3" v-model="n3"></v-select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="imagen" class="col-sm-2 control-label">Imagen Web</label>
                                    <div class="col-sm-10">
                                        <input name="imagen" type="file" class="file-loading" id="imagen" accept=".png, .jpg, .jpeg">
                                        <p class="help-block">Imagen del nivel.</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pdf" class="col-sm-2 control-label">Ficha Técnica</label>
                                    <div class="col-sm-10">
                                        <input name="pdf" type="file" class="file-loading" id="pdf" accept=".pdf">
                                        <p class="help-block">Ficha tecnica PDF</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="video" class="col-sm-2 control-label">Video</label>
                                    <div class="col-sm-10">
                                        <input name="video" type="file" class="file-loading" id="video" accept=".mp4">
                                        <p class="help-block">Video Opcional</p>
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