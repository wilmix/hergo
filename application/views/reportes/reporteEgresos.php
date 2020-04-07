<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
            <button class="btn btn-success pull-right" id="export" data-toggle="tooltip" title="Excel"><i class="far fa-file-excel"> </i> Excel </button>
            <button id="pdf" class="btn btn-danger pull-right" ><i class="far fa-file-pdf"> </i> PDF</button>
                <div id="toolbar2" class="form-inline">
                    <?php
                        $this->load->view('reportHead/buttonDate');
                        $this->load->view('reportHead/selectAlm');
                        $this->load->view('reportHead/selectTipo');
                        $this->load->view('reportHead/buttonRefresh');
                    ?>
                </div>
                <div class="text-center">
                    <h2>REPORTE EGRESOS - <span id="tituloAlmacen"></span></h2>
                    <h3 id="tituloTipo" style="margin-top: 0px;margin-bottom: 0px;"></h3>
                    <h4 id="ragoFecha"></h4>
                </div>
                <table id="tablaReporteEgresos"
                    data-toolbar="#toolbar2"
                    data-toggle="table">
                </table>
                <!--row-->
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
