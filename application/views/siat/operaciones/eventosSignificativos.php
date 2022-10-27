<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
            <div class="col-md-2">
                <vuejs-datepicker  v-model="fecha" :language="es" :format="customFormatter" input-class="form-control">
                </vuejs-datepicker>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary btn-block" @click="get_eventos">Ver Eventos</button>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary btn-block" @click="showModal">Registrar Evento</button>
            </div>
      </div>
      <!-- <div class="box-header with-border">
            <div class="col-md-2">
                <input  v-model="codigoEvento" input-class="form-control">
                </input>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary btn-block" @click="enviarPaquete">enviar Paquete</button>
            </div>
      </div> -->
      <!-- <div class="box-header with-border">
            <div class="col-md-2">
                <input  v-model="codigoRecepcion" input-class="form-control">
                </input>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary btn-block" @click="validacionRecepcionPaquete">validar</button>
            </div>
      </div> -->
      <div class="box-body">
        <table id="table" class="table table-hover table-striped table-sm" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div><!-- /.class="col-xs-12" -->
    <div id="modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h2 class="modal-title"> Registrar Evento Significativo </h2>
                    <h4></h4>
                </div>
                <div class="modal-body">
                    <form method="post" id="anularFactura">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="descripcion" class="col-sm-2 control-label">Fecha</label>
                                <div class="col-sm-10">
                                    <!-- <vuejs-datepicker  v-model="fechaRegistro" :language="es" :format="customFormatter" input-class="form-control" @selected="onChangeFecha()">
                                    </vuejs-datepicker> -->
                                    <vuejs-datepicker v-model="registroFecha" :language="es" :format="customFormatter"  input-class="form-control"></vuejs-datepicker>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="n1" class="col-sm-2 control-label">Motivo</label>
                                <div class="col-sm-10">
                                <select class="form-control" 
                                            v-model="motivo">
                                    <option v-for="option in motivos" 
                                            v-bind:value="option.codigo"
                                            v-text="option.label">
                                    </option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inicio" class="col-sm-2 control-label">Inicio Evento</label>
                                <div class="col-sm-10">
                                    <input name="inicio" placeholder="AAAA-MM-DD HH:MM:SS" type="text" class="form-control" v-model="fechaHoraInicioEvento" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fin" class="col-sm-2 control-label">Inicio Evento</label>
                                <div class="col-sm-10">
                                    <input name="fin" placeholder="AAAA-MM-DD HH:MM:SS" type="text" class="form-control" v-model="fechaHoraFinEvento" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="descripcion" class="col-sm-2 control-label">Descripción</label>
                                <div class="col-sm-10">
                                    <input name="descripcion" type="text" class="form-control" v-model="descripcion" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cufdEvento" class="col-sm-2 control-label">CUFD Evento</label>
                                <div class="col-sm-10">
                                    <input name="cufdEvento" type="text" class="form-control" v-model="cufdEvento" >
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="registrarEvento">Registrar</button>
                </div>
            </div>
        </div>
    </div><!-- /.modal -->
</div> <!-- /.class="row" -->



