<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
            <div class="btn-group pull-right">
                <button id="pdfGeneral" class="btn btn-danger" ><i class="far fa-file-pdf"> </i> PDF</button>
                <button id="pdfGeneralSN" class="btn btn-danger"><i class="far fa-file-pdf"> </i> PDF SN</button>
                <button onclick="window.print();" class="btn btn-primary pull-right" ><i class="fa fa-print"> </i> Imprimir</button>
            </div>
            <hr>
                <div id="" class="form-inline">
                    <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                    <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                        <?php foreach ($almacen->result_array() as $fila): ?>
                        <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                        <?php endforeach ?>
                        <option value="">TODOS</option>
                    </select>
                    <select class="form-control"  data-style="btn-primary" id="articulos_filtro" name="articulos_filtro" multiple="multiple" style="width: 75%">
                        <?php foreach ($articulos->result_array() as $fila): ?>
                        <option value=<?= $fila['CodigoArticulo'] ?> ><?= $fila['CodigoArticulo'].' | '.$fila['Descripcion'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <button  type="button" class="btn btn-primary btn-sm" id="refresh">
                        <span>
                        <i class="fa fa-share-square"></i>
                        </span>
                    </button>
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
