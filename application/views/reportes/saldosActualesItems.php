<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="btn-group pull-right">
                    <button class="btn btn-success pull-right" id="excel" data-toggle="tooltip" title="Excel"><i class="far fa-file-excel"> </i> Excel </button>
                    <button id="pdf" class="btn btn-danger" ><i class="far fa-file-pdf"> </i> PDF</button>
                </div>
                <div id="toolbar2" class="form-inline">
                    <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                        <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                        <?php foreach ($almacen->result_array() as $fila): ?>
                        <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                        <?php endforeach ?>
                        <option value="" >TODOS</option>
                    </select>
                    
                    <select id="moneda" class="btn btn-primary btn-sm">
                        <option value="0">BOB</option>
                        <option value="1">$U$</option>
                    </select>

                    <button  type="button" class="btn btn-primary btn-sm" id="saldos">
                        <span>
                        <i class="fa fa-share-square"></i>
                        </span>
                    </button>

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
