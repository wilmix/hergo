  <!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div id="ordenForm" class="box">
      <div class="box-header with-border">
            <select class="btn btn-primary" id="pedServFilter" name="ped_serv_filtro">
              <option value="pedidos">PEDIDOS</option>
              <option value="servicios">SERVICIOS</option>
            </select>
            <select class="btn btn-info" id="estadoFiltro" name="estado_filtro">
                        <option value="pagada">PAGADAS</option>
                        <option value="historico">HISTORICO</option>
                        <option value="pendiente" selected="selected">PENDIENTES</option>
            </select>
      </div>
      <div class="box-body">
      <h3 class="box-title text-center" id="titulo"> </h3>
        <table id="table" class="table table-hover display compact" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->