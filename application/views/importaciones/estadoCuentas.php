<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div id="ordenForm" class="box">
      <div class="box-header with-border">
        <h3 class="box-title" ></h3>
            <select class="btn btn-primary btn-sm" id="estadoFiltro" name="almacen_filtro">
                        <option value="pagada">PAGADAS</option>
                        <option value="historico">HISTORICO</option>
                        <option value="pendiente" selected="selected">PENDIENTES</option>
            </select>
      </div>
      <div class="box-body">
        <table id="table" class="table table-hover display compact" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->