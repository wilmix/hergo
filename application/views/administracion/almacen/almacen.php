<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div class="text-right">
              <button class="btn btn-default text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal" data-target="#modalalmacen">Agregar nuevo almacen</button>
          </div>                    
          <table 
              data-toggle="table"
              data-pagination="true"
              data-search="true"
              data-classes="table table-hover table-condensed"
              data-striped="true"
              data-sort-name="stargazers_count"
              data-sort-order="desc"
              class="table-striped"
              >
              <thead>
                  <tr>
                      <th>Nombre de almacen</th>
                      <th>Direccion</th>
                      <th>Ciudad</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>                   
                 <?php foreach ($almacen->result_array() as $fila): ?>
                   <tr id="<?= $fila['idalmacen'] ?>">
                     <td><?= $fila['almacen'] ?></td>
                     <td><?= $fila['direccion'] ?></td>
                     <td><?= $fila['ciudad'] ?></td>
                     <td>
                       <div class="text-right">
                        <button class="btn btn-default botoneditar"><i class=" fa fa-pencil" ></i></button>
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
<form action=" " method="post"  id="form_almacen">
  <div class="modal fade" id="modalalmacen" role="dialog">
    <input type="" name="cod" value="" id="cod_almacen" hidden> <!-- input oculto para el codigo de almacen-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title modalalmacentitulo"></h3>
           </div>
                <!--MODAL BODY-->
          <div class="modal-body form form-horizontal">
            <fieldset>
            <!-- Nombre de almacen-->
              <div class="form-group">
                <label class="col-md-3 control-label" for="modalnombrealmacen">Nombre de Almacen</label>  
                <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                      <input  autofocus name="almacen" placeholder="Nombre de Almacen" id="modalnombrealmacen" class="form-control"  type="text" style="text-transform:uppercase; " onkeyup="javascript:this.value=this.value.toUpperCase();">
                    </div>
                </div>
                
              </div>
                <!-- Direccion de almacen-->
              <div class="form-group">
                <label class="col-md-3 control-label" for="modaldireccionalmacen">Dirección de Almacen</label>  
                <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-screenshot"></i></span>
                <input  name="direccion" placeholder="Dirección de Almacen" class="form-control" id="modaldireccionalmacen" type="text"  style="text-transform:uppercase; " onkeyup="javascript:this.value=this.value.toUpperCase();">
                  </div>
                </div>
                
              </div>
                <!-- Ciudad  -->
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
                
              </div>
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
                <button type="submit" class="btn btn-primary" id="bguardar_almacen">Guardar</button>
            </div>
          </div> <!-- /.<div class="modal-body form">-->
        </div>
      </div> <!-- /. modal -->
  </div>
</form>
