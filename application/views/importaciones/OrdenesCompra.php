<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div id="ordenForm" class="box">
      <div class="box-header with-border">
        <h3 class="box-title" ></h3>
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
      </div>
      <div class="box-body">
        <table id="tableOC" class="table table-hover display compact" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
  <!-- Modal -->
  <div id="asociarFacturaModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-95">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <div class="col-md-12" class="text-center">
              <h2 class="modal-title text-center">
                <span>Asociar Factura</span>
              </h2>
            </div>
            <div class="col-md-4">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        </div>
        <div class="modal-body">
          <div class="row">

          </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-success" @click="saveFactura">Guardar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

</div> <!-- /.class="row" -->