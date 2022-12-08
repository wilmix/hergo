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
        <div class="col-md-2">
                <button type="button" class="btn btn-primary btn-block" @click="showModalEvento">Registrar Evento</button>
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
  <!-- MODAL EMPAQUETAR ENVIAR -->
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

  <!-- MODAL EVENTO SIGNIFICATIVO -->
  <div id="modalEvento" class="modal fade" role="dialog">
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
                            <!-- <div class="form-group">
                                <label for="descripcion" class="col-sm-2 control-label">Fecha</label>
                                <div class="col-sm-10"> -->
                                    <!-- <vuejs-datepicker  v-model="fechaRegistro" :language="es" :format="customFormatter" input-class="form-control" @selected="onChangeFecha()">
                                    </vuejs-datepicker> -->
                                    <!-- <vuejs-datepicker v-model="registroFecha" :language="es" :format="customFormatter"  input-class="form-control"></vuejs-datepicker>
                                </div>
                            </div> -->
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
                                <vue-ctk-date-time-picker label="Fecha Inicio Contingencia" format="YYYY-MM-DDTHH:mm:ss.SSS" v-model="fechaHoraInicioEvento" input-class="form-control"></vue-ctk-date-time-picker>
                                    <!-- <input name="inicio" placeholder="AAAA-MM-DD HH:MM:SS" type="text" class="form-control" v-model="fechaHoraInicioEvento" > -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fin" class="col-sm-2 control-label">Fin Evento</label>
                                <div class="col-sm-10">
                                <vue-ctk-date-time-picker label="Fecha Fin Contingencia" format="YYYY-MM-DDTHH:mm:ss.SSS" v-model="fechaHoraFinEvento" input-class="form-control"></vue-ctk-date-time-picker>
                                    <!-- <input name="fin" placeholder="AAAA-MM-DD HH:MM:SS" type="text" class="form-control" v-model="fechaHoraFinEvento" > -->
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
                                    <small class="form-text text-muted">VIGENCIA: {{ cufdEventoInicio }} AL {{ cufdEventoFin }}</small>
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



