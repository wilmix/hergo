<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div class="text-right">
              <button class="btn btn-default text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal" data-target="#modalunidad">Agregar nueva Unidad</button>
          </div>                    
          <table 
              data-toggle="table"
             data-height="550"
             data-search="true"
             data-show-toggle="true"
             data-show-columns="true"
             data-locale="es-MX"
             data-show-refresh="false"
             data-page-size="10"
             data-pagination="true"
             data-search="true">
              <thead>
                  <tr>
                      <th>Unidad</th>
                      <th>Sigla</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>                   
                 <?php foreach ($unidad->result_array() as $fila): ?>
                   <tr id="<?= $fila['idUnidad'] ?>">
                     <td><?= $fila['Unidad'] ?></td>
                     <td><?= $fila['Sigla'] ?></td>
                     <td>
                       <div class="text-right">
                        <button class="btn btn-default botoneditar"><i class="fa fa-pencil" ></i></button>
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
<form action=" " method="post"  id="form_unidad">
  <div class="modal fade" id="modalunidad" role="dialog">
      <input type="" name="cod" value="" id="cod_unidad" hidden> <!-- input oculto para el codigo de unidad-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title modalunidadtitulo"></h3>
           </div>
                <!--MODAL BODY-->
          <div class="modal-body form form-horizontal">
            <fieldset>
            <!-- Nombre de Marca-->
              <div class="form-group">
                <label class="col-md-3 control-label" for="modalnombreunidad">Unidad</label>  
                <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                      <input  autofocus name="unidad" placeholder="Nombre de la Unidad" id="modalnombreunidad" class="form-control"  type="text" style="text-transform:uppercase; " onkeyup="javascript:this.value=this.value.toUpperCase();">
                    </div>
                </div>
                
              </div>
                <!-- sigla de marca-->
              <div class="form-group">
                <label class="col-md-3 control-label" for="modalsiglaunidad">Sigla</label>  
                <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-screenshot"></i></span>
                <input  name="sigla" placeholder="Sigla de la Unidad" class="form-control" id="modalsiglaunidad" type="text"  style="text-transform:uppercase; " onkeyup="javascript:this.value=this.value.toUpperCase();">
                  </div>
                </div>
                
              </div>
                <!-- Ciudad  
              <div class="form-group"> 
                <label class="col-md-3 control-label">Ciudad</label>
                  <div class="col-md-9 selectContainer">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-pushpin"></i></span>
                      <select name="ciudad" class="form-control selectpicker" id="modalciudadalmacen" >
                        <option value=" " >Selecciona</option>
                        <option>La Paz</option>
                        <option>Potosi</option>
                        <option >Santa Cruz</option>
                        <option >Tarija</option>
                      </select>
                   </div>
                </div>
                
              </div>-->
              <!-- Uso -->
              <div class="form-group">
                <label class="col-md-3 control-label">En Uso</label>
                <div class="col-md-9">
                    <div class="radio">
                        <label><input type="radio" name="enuso" value="1" checked/> Si </label>
                        <label><input type="radio" name="enuso" value="0" /> No </label>
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
