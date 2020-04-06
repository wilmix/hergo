<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <button class="btn btn-success pull-right" id="export" data-toggle="tooltip" title="Excel"><i class="far fa-file-excel"> </i> Excel </button>
                <button id="pdf" class="btn btn-danger pull-right" ><i class="far fa-file-pdf"> </i> PDF</button>
                <div id="toolbar2" class="form-inline">
                    <button type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
                        <span>
                            <i class="fa fa-calendar"></i> Fecha
                        </span>
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                        <?php if ($grupsOfUser == 'Nacional') : ?>
                            <?php foreach ($almacen->result_array() as $fila): ?>
                            <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                            <?php endforeach ?>
                            <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                            <option value="">TODOS</option>
                        <?php else : ?>
                            <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                        <?php endif; ?>
                    </select>
                    <select class="btn btn-primary btn-sm" name="tipo_filtro" id="tipo_filtro">
                        <option value="">TODOS</option>
                        <?php foreach ($tipoingreso->result_array() as $fila): ?>
                        <option value="<?= $fila['id'] ?>" <?= $fila['id']==2?"selected":""  ?>><?= strtoupper($fila['tipomov']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="text-center">
                    <h2>REPORTE INGRESOS - <span id="tituloAlmacen"></span></h2>
                    <h3 id="tituloTipo" style="margin-top: 0px;margin-bottom: 0px;"></h3>
                    <h4 id="ragoFecha"></h4>
                </div>
                <table id="tablaReporteIngresos"
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
