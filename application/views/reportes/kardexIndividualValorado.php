<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
            <div class="container">
                <div id="toolbar2" class="form-inline">
                    <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                        <?php foreach ($almacen->result_array() as $fila): ?>
                        <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                        <?php endforeach ?>
                        <option value="">TODOS</option>
                    </select>
                    <select class="form-control"  data-style="btn-primary" id="articulos_filtro" name="articulos_filtro">
                        <?php foreach ($articulos->result_array() as $fila): ?>
                        <option value=<?= $fila['idArticulos'] ?> ><?= $fila['CodigoArticulo'].' | '.$fila['Descripcion'] ?></option>
                        <?php endforeach ?>
                        <option value="436" selected="selected"></option>
                    </select>
                    <button  type="button" class="btn btn-primary btn-sm" id="kardex">
                        <span>
                        <i class="fa fa-share-square"></i>
                        </span>
                    </button>
                </div>
                <div class="text-center">
                    <h2>Kardex Individual Valorado -
                        <span id="tituloReporte"></span>
                    </h2>
                    <h4 id="nombreArticulo"></h4>
                </div>
                <table id="tablaKardex" data-toolbar="#toolbar2" data-toggle="table">
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
