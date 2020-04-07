<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div> <!-- pull-left -->
            <img src="<?php echo base_url("images/hergo.jpeg") ?>" alt="logoHergo" width="150" height="45" style="position: absolute;">
          </div>
          <div class="btn-group pull-right">
            <button class="btn btn-success pull-right" id="export" data-toggle="tooltip" title="Excel"><i class="far fa-file-excel"> </i> Excel </button>
            <!-- <button id="pdf" class="btn btn-danger" ><i class="far fa-file-pdf"> </i> PDF</button> -->
            <button onclick="window.print();" class="btn btn-primary pull-right" ><i class="fa fa-print"> </i> Imprimir</button>
          </div>
          <hr>
          <div id="toolbar2" class="form-inline">
            <?php
                $this->load->view('reportHead/buttonDate');
                $this->load->view('reportHead/selectAlm');
                $this->load->view('reportHead/buttonRefresh');
            ?>
          </div>
          <div class="text-center">
            <h2>Prueba Kardex</h2>
            <h3 id="tituloReporte"></h3>
            <h4 id="ragoFecha"></h4>
          </div>
          <div>
          <h4>La prueba del kardex se realiza mediante la siguiente formula: </h4>
          <h3>Inventario Inicial + Compras Netas - Inventario Final = Costo de mercaderia Vendida</h3>
          <h4>El Costo de Mercaderia Vendida deberá ser igual al del Estado de Ventas y Costos. </h4>
          </div>
          <table 
            id="pruebaKardex" 
            data-toolbar="#toolbar2"
            data-toggle="table" >
          </table>
          
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
