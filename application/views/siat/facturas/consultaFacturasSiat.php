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
      </div>
      <div class="box-body">
        <table id="table" class="table table-hover table-striped table-sm" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div>
  <!-- /.class="col-xs-12" -->

  <div id="anular" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h2 class="modal-title"> Anular Factura {{ facturaRow.numeroFactura }} </h2>
                <h4>{{ `Se anulará la factura Nº ${facturaRow.numeroFactura} de ${facturaRow.ClienteFactura} del ${facturaRow.fechaFac}`}}</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="anularFactura">
                    <div class="form-horizontal">
                        <div class="form-group">
                          <label for="n1" class="col-sm-2 control-label">Motivo</label>
                          <div class="col-sm-10">
                          <select class="form-control" 
                                      v-model="codigoMotivo">
                              <option v-for="option in motivosAnulacion" 
                                      v-bind:value="option.codigoClasificador"
                                      v-text="option.label">
                              </option>
                          </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="titulo" class="col-sm-2 control-label">Detalle</label>
                          <div class="col-sm-10">
                              <input name="titulo" type="text" class="form-control" v-model="detalleAnulacion" >
                          </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="siatAnular">Anular</button>
            </div>
        </div>
    </div>
  </div><!-- /.modal -->
</div> <!-- /.class="row" -->



