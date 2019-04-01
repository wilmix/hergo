<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
            <button class="btn btn-success pull-right" id="export" data-toggle="tooltip" title="Excel"><i class="far fa-file-excel"> </i> Excel </button>
                <div id="toolbar2" class="form-inline">
                    <button type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
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
                    <select class="btn btn-primary btn-sm" name="tipo_filtro" id="tipo_filtro">
                        <?php foreach ($tipoingreso->result_array() as $fila): ?>
                        <option value="<?= $fila['id'] ?>" <?= $fila['id']==2?"selected":""  ?>><?= strtoupper($fila['tipomov']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="text-center">
                    <h2>DIARIO DE INGRESOS - <span id="tituloAlmacen"></span></h2>
                    <h3 id="tituloTipo" style="margin-top: 0px;margin-bottom: 0px;"></h3>
                    <h4 id="ragoFecha"></h4>
                </div>
                <table id="tablaDiarioIngresos"
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
