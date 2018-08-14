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
                        <!--<option value="">TODOS</option>-->
                    </select>

                    <select class="form-control"  data-style="btn-primary" id="clientes_filtro" name="clientes_filtro">
                        <?php foreach ($clientes->result_array() as $fila): ?>
                        <option value=<?= $fila['cliente'] ?> ><?= $fila['nombreCliente'].' | '.$fila['documento'] ?></option>
                        <?php endforeach ?>
                    </select>

                    <button  type="button" class="btn btn-primary btn-sm" id="kardex">
                        <span>
                        <i class="fa fa-share-square"></i>
                        </span>
                    </button>
                </div>
                <div class="text-center">
                    <h2>Kardex Individual de Cliente -
                        <span id="tituloReporte"></span>
                    </h2>
                    <h4 id="nombreCliente"></h4>
                </div>
                <table id="tablaKardex" data-toolbar="#toolbar2" data-toggle="table">
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
