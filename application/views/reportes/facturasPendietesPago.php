<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
      <div class="form-inline">
          <button class="btn btn-success pull-right" id="export" data-toggle="tooltip" title="Excel"><i class="far fa-file-excel"> </i> Excel </button>
          <button id="pdf" class="btn btn-danger pull-right" ><i class="far fa-file-pdf"> </i> PDF</button>
          <button onclick="window.print();" class="btn btn-primary pull-right" ><i class="fa fa-print"> </i> Imprimir</button>
      </div>
      <hr>
          <div id="toolbar2" class="form-inline">
          <?php
              $this->load->view('reportHead/buttonDate');
              $this->load->view('reportHead/selectAlm');
              $this->load->view('reportHead/selectCoin');
              $this->load->view('reportHead/buttonRefresh');
          ?>
          </div>
          <div class="text-center">
            <h2>FACTURAS PENDIENTES DE PAGO</h2>
            <h3 id="tituloReporte"></h3>
            <h4 id="ragoFecha"></h4>
          </div>
          <table 
            id="tablaFacturasPendientes" 
            data-toolbar="#toolbar2"
            data-toggle="table">
          </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>

