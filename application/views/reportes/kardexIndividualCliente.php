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
                    $this->load->view('reportHead/selectCoin');
                    $this->load->view('reportHead/buttonRefresh');
                ?>
                </div>
                <div class="text-center">
                    <h2>Kardex Individual de Cliente -
                        <span id="nombreCliente"></span>
                    </h2>
                    <h3 id="tituloReporte"></h3>
                    <h4 id="titleMoneda"></h4>
                    <h4 id="ragoFecha"></h4>
                </div>
                <hr>
                <div class="col-xs-12 col-lg-12 col-md-12">
                    <span style="display: none;" id="cargandocliente">
                    <i class="fa fa-spinner fa-pulse fa-fw"></i>
                    </span>
                    <input class="form-control form-control-sm" type="text" id="cliente_egreso" name="cliente_egreso" ">
                    <input type="text" readonly="true" name="idCliente" id="idCliente" class="hidden">
                    <input type="text" readonly="true" name="nameClient" id="nameClient" class="hidden" >
                    <div style="right: 22px;top:10px;position: absolute;" id="clientecorrecto">
                    <i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>
                    </div>
                </div>
                <hr>
                <table id="tablaKardex" data-toolbar="#toolbar2" data-toggle="table">
                </table>
            </div>
        </div>
    </div>
</div>


