<!-- Your Page Content Here -->
<div class="row">
  <div class="col-xs-12">
    <div id="app" class="box">
      <div class="box-header with-border">
        <!-- <h3 class="box-title" v-text="title"></h3> -->
      </div>
        <div class="box-body">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nivel 1</h3>
                    </div>
                    <div class="box-body">
                    <table id="web_nivel1" class="table table-hover table-striped table-sm" style="width:100%">
                    </table>
                    </div>
                    <div class="box-footer clearfix">
                    </div>

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Nivel 3</h3>
                        </div>
                        <div class="box-body">
                            <table id="web_nivel3" class="table table-hover table-striped table-sm" style="width:100%">
                            </table>
                        </div>
                        <div class="box-footer clearfix">
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nivel 2</h3>
                    </div>
                    <div class="box-body">
                        <table id="web_nivel2" class="table table-hover table-striped table-sm" style="width:100%">
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                    </div>
                </div>
            </div>
        </div> <!-- /.box-body -->
        <div id="levelModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear">
                        <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">{{ modalTitle }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Nombre</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" v-model="name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Activo</label>
                                <div class="col-sm-10">
                                    <!-- <v-select :options="['EFECTIVO','CRÉDITO']" v-model="isActive"></v-select> -->
                                    <select name="isActive" class="form-control" v-model="isActive">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal" @click="clear">Close</button>
                        <button type="button" class="btn btn-primary" @click="add">Guardar</button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->