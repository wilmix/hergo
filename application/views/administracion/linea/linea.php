<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div class="text-right">
              <button class="btn btn-default text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal" data-target="#modallinea">Agregar nueva Linea</button>
          </div>
                              
          <table 
            data-toggle="table"
             data-height="550"
             data-search="true"
             data-show-toggle="true"
             data-show-columns="true"
             data-locale="es-MX"
             data-show-refresh="false"
             data-page-size="100"
             data-pagination="true"
             data-search="true"
             data-show-export="true"
             data-export-types="['excel', 'pdf']"
             data-striped="true"
             >
              <thead>
                  <tr>
                      <th>Linea</th>
                      <th>Sigla</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>                   
                 <?php foreach ($linea->result_array() as $fila): ?>
                   <tr id="<?= $fila['idLinea'] ?>">
                     <td><?= $fila['Linea'] ?></td>
                     <td><?= $fila['Sigla'] ?></td>
                     <td>
                       <div class="text-right">
                        <button class="btn btn-default botoneditar"><i class="fa fa-pencil" ></i></button>
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
<form action=" " method="post"  id="form_linea">
  <div class="modal fade" id="modallinea" role="dialog">
      <input type="" name="cod" value="" id="cod_linea" hidden> <!-- input oculto para el codigo de almacen-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title modallineatitulo"></h3>
           </div>
                <!--MODAL BODY-->
          <div class="modal-body form form-horizontal">
            <fieldset>
            <!-- Nombre de Marca-->
              <div class="form-group">
                <label class="col-md-3 control-label" for="modalnombrelinea">Linea</label>  
                <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                      <input  autofocus name="linea" placeholder="Nombre de la Linea" id="modalnombrelinea" class="form-control"  type="text" style="text-transform:uppercase; " onkeyup="javascript:this.value=this.value.toUpperCase();">
                    </div>
                </div>
                
              </div>
                <!-- sigla de marca-->
              <div class="form-group">
                <label class="col-md-3 control-label" for="modalsiglalinea">Sigla</label>  
                <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-screenshot"></i></span>
                <input  name="sigla" placeholder="Sigla de la Linea" class="form-control" id="modalsiglalinea" type="text"  style="text-transform:uppercase; " onkeyup="javascript:this.value=this.value.toUpperCase();">
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
                <button type="submit" class="btn btn-primary" id="bguardar_linea">Guardar</button>
            </div>
          </div> <!-- /.<div class="modal-body form">-->
        </div>
      </div> <!-- /. modal -->
  </div>
</form>
