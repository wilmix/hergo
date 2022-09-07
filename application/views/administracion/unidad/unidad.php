<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <div class="text-right">
          <button class="btn btn-default text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal"
            data-target="#modalunidad">Agregar nueva Unidad</button>
        </div>
        <table data-toggle="table" data-height="550" data-search="true" data-show-toggle="true" data-show-columns="true"
          data-locale="es-MX" data-show-refresh="false" data-search="true" data-striped="true">
          <thead>
            <tr>
              <th>Unidad</th>
              <th>Sigla</th>
              <th>siat_codigo</th>
              <th>siat_unidadMedida</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($unidad as $fila): ?>
            <tr id="<?= $fila['idUnidad'] ?>">
              <td><?= $fila['Unidad'] ?></td>
              <td><?= $fila['Sigla'] ?></td>
              <td><?= $fila['siat_codigo'] ?></td>
              <td><?= $fila['siat_unidadMedida'] ?></td>
              <td>
                <div class="text-right">
                  <button class="btn btn-default botoneditar"><i class="fa fa-pencil"></i></button>
                </div>
      </div>
      </td>
      </tr>
      <?php endforeach ?>
      </tbody>
      </table>

    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
</div>
<!-- /.col -->
</div>

<!-- Modal -->
<form action=" " method="post" id="form_unidad">
  <div class="modal fade" id="modalunidad" role="dialog">
    <input type="" name="cod" value="" id="cod_unidad" hidden> <!-- input oculto para el codigo de unidad-->
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h3 class="modal-title modalunidadtitulo"></h3>
        </div>
        <!--MODAL BODY-->
        <div class="modal-body form form-horizontal">
          <fieldset>
            <!-- unidad-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="modalnombreunidad">Unidad</label>
              <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                  <input autofocus name="unidad" placeholder="Nombre de la Unidad" id="modalnombreunidad"
                    class="form-control" type="text">
                </div>
              </div>
            </div>
            <!-- sigla unidad-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="modalsiglaunidad">Sigla</label>
              <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-screenshot"></i></span>
                  <input name="sigla" placeholder="Sigla de la Unidad" class="form-control" id="modalsiglaunidad"
                    type="text">
                </div>
              </div>
            </div>
             <!-- Unidad Siat  -->
             <div class="form-group"> 
                <label class="col-md-3 col-lg-3 control-label" for="unidadSiat">Unidad Siat</label>
                  <div class="col-md-9 col-lg-9 inputGroupContainer">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
                      <select name="unidadSiat" class="form-control" id="siat_codigo" >
                        <option value="" disabled>Seleccione</option>
                        <?php foreach ($unidadMedidaSiat as $fila): ?>
                          <option value="<?= $fila['codigoClasificador']  ?>"><?= $fila['descripcion'] ?></option>
                        <?php endforeach ?>
                      </select>
                  </div>
                </div>
              </div>
        </div>
        </fieldset>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="bguardar_unidad">Guardar</button>
        </div>
      </div> <!-- /.<div class="modal-body form">-->
    </div>
  </div> <!-- /. modal -->
  </div>
</form>