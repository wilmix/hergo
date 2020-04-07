<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="btn-group pull-right">
                    <button class="btn btn-success pull-right" id="excel" data-toggle="tooltip" title="Excel"><i class="far fa-file-excel"> </i> Excel </button>
                    <button id="pdf" class="btn btn-danger" ><i class="far fa-file-pdf"> </i> PDF</button>
                    <button onclick="window.print();" class="btn btn-primary pull-right" ><i class="fa fa-print"> </i> Imprimir</button>
                </div>
                <hr>
                <div id="toolbar2" class="form-inline">
                <?php
                    $this->load->view('reportHead/selectAlm');
                    $this->load->view('reportHead/selectCoin');
                    $this->load->view('reportHead/buttonRefresh');
                ?>
                </div>
                <div class="text-center">
                    <h2>Saldos Actuales de Items  -
                        <span id="tituloReporte"></span>
                    </h2>
                    <h3 id="nombreArticulo"></h3>
                </div>
                <table id="tablaSaldos" data-toolbar="#toolbar2" data-toggle="table" >
                </table>
            </div>
        </div>
    </div>
</div>
