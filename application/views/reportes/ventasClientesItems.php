<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="toolbar2" class="form-inline">
                    <button  type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
                        <span>
                        <i class="fa fa-calendar"></i> Fecha
                        </span>
                        <i class="fa fa-caret-down"></i>
                    </button>
           
                    <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                        <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                        <?php foreach ($almacen->result_array() as $fila): ?>
                        <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                        <?php endforeach ?>
                        <option value="">TODOS</option>
                    </select>

                    <select class="form-control"  data-style="btn-primary" id="articulos_filtro" name="articulos_filtro">
                        <option value="" selected>TODOS</option>
                        <?php foreach ($articulos->result_array() as $fila): ?>
                        <option value=<?= $fila['idArticulos'] ?> ><?= $fila['CodigoArticulo'].' | '.$fila['Descripcion'] ?></option>
                        <?php endforeach ?>
                        
                    </select>

                    <button  type="button" class="btn btn-primary btn-sm" id="refresh">
                        <span>
                        <i class="fa fa-share-square"></i>
                        </span>
                    </button>
                </div>

                <div class="text-center">
                    <h2>Movimiento Clientes Items
                        <span id="item"></span>
                    </h2>
                    <h2 id="tituloReporte"></h2>
                </div>
                <table id="tablaVentasClientesItems" data-toolbar="#toolbar2" data-toggle="table">
                </table>
            </div>
        </div>
    </div>
</div>
