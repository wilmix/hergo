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
        <div class="form-group col-sm-6 col-md-2" id="almacen" @change="onChangeFilter()">
          <select class="form-control" :disabled="disabled" v-model="almacen" id="almacen" name="almacen">
            <option v-for="option in almacenes" v-bind:value="option.value" v-text="option.alm">
            </option>
          </select>
        </div>
        <div class="form-group col-sm-6 col-md-2" @change="onChangeFilter()">
          <select class="form-control" v-model="tipoEgreso" name="tipoEgreso">
            <option v-for="option in tiposEgreso" v-bind:value="option.value" v-text="option.tipo">
            </option>
          </select>
        </div>
      </div>
      <div class="box-body">
        <table id="table" class="table table-hover table-striped table-sm" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div>
  <!-- /.class="col-xs-12" -->

  <!-- Modal -->
  <div id="addNotaModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <div class="col-md-12">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="col-md-12" class="text-center">
            <h3 class="modal-title text-center">
              <span>SEGUIMIENTO DE LA FACTURA <br> Nº {{selectedRow.nFactura}} DE {{selectedRow.cliente}} DEL {{selectedRow.fechaFac}}</span>
            </h3>
          </div>
        </div>
        <div class="modal-body">
          <form method="post" id="notasForm">
            <div class="row">
              <div class="form-group col-sm-12 col-md-12">
                <label>Nota:</label>
                <textarea class="form-control" style="min-width: 100%" v-model="nota"></textarea>
                <p class="text-right"><u>Autor: {{ nombreUsuario }}</u></p>
                <button type="button" class="btn btn-primary" @click="save">Guardar</button>
              </div>
            </div>
          </form>
          <div class="row">
            <div class="form-group col-sm-12 col-md-12">
              <label>Seguimiento:</label>
              <blockquote v-for="nota in notasFactura.registros">
                <p>{{ nota.nota }}</p>
                <footer>Creado por {{ nota.autor }} <cite title="Source Title">{{ nota.fecha_hora }}</cite></footer>
              </blockquote>

            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" @click="save">Guardar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

</div> <!-- /.class="row" -->