<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="btn-group pull-right">
                    <button id="pdfGeneral" class="btn btn-danger" ><i class="far fa-file-pdf"> </i> PDF</button>
                    <button id="pdfGeneralSN" class="btn btn-danger"><i class="far fa-file-pdf"> </i> PDF SN</button>
                    <button onclick="window.print();" class="btn btn-primary pull-right" ><i class="fa fa-print"> </i> Imprimir</button>
                    <button class="btn btn-success pull-right" id="export" data-toggle="tooltip" title="Excel"><i class="far fa-file-excel"> </i> Excel </button>
                </div>
                <hr>
                <div id="" class="form-inline">
                    <?php
                        $this->load->view('reportHead/selectAlm');
                    ?>
                    <select class="form-control"  data-style="btn-primary" id="articulos_filtro" name="articulos_filtro" multiple="multiple" style="width: 75%">
                        <?php foreach ($articulos->result_array() as $fila): ?>
                            <option value=<?= $fila['CodigoArticulo'] ?> ><?= $fila['CodigoArticulo'].' | '.$fila['Descripcion'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <?php
                        $this->load->view('reportHead/buttonRefresh');
                    ?>
                </div>
                <div class="text-center">
                    <h2>Kardex Individual Valorado -
                        <span id="tituloReporte"></span>
                    </h2>
                    <h3 id="nombreArticulo"></h3>
                </div>
                <div id="tablas">
                </div>
            </div>
        </div>
    </div>
</div>
