<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div class="text-right">
              <button class="btn btn-default text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal" data-target="#modalmarca">Marca</button>
          </div>                    
          <table 
              id="talmacen"
              data-toggle="table"
              data-pagination="true"
              data-search="true"
              data-classes="table table-hover table-condensed"
              data-striped="true"
              data-sort-name="stargazers_count"
              data-sort-order="desc">
              <thead>
                  <tr>
                      <th>Marca</th>
                      <th>Sigla</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>                   
                 <?php foreach ($marca->result_array() as $fila): ?>
                   <tr id="<?= $fila['idMarca'] ?>">
                     <td><?= $fila['Marca'] ?></td>
                     <td><?= $fila['Sigla'] ?></td>
                     <td>
                       <div class="text-right">
                        <button class="btn btn-default botoneditar"><i class="fa fa-pencil" ></i></button>
                        <button class="btn btn-default"><i class=" fa fa-trash-o"></i></button>
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
<form action=" " method="post"  id="form_marcaArticulos">
  <div class="modal fade" id="modalmarca" role="dialog">
      <input type="" name="cod" value="" id="cod_marca" hidden> <!-- input oculto para el codigo de almacen-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title modalmarcatitulo"></h3>
           </div>
                <!--MODAL BODY-->
          <div class="modal-body form form-horizontal">
            <fieldset>
            <!-- Nombre de Marca-->
              <div class="form-group">
                <label class="col-md-3 control-label" for="modalnombremarca">Marca</label>  
                <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                      <input  autofocus name="marca" placeholder="Nombre de la Marca" id="modalnombremarca" class="form-control"  type="text" style="text-transform:uppercase; " onkeyup="javascript:this.value=this.value.toUpperCase();">
                    </div>
                </div>
                
              </div>
                <!-- sigla de marca-->
              <div class="form-group">
                <label class="col-md-3 control-label" for="modalsiglamarca">Sigla</label>  
                <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-screenshot"></i></span>
                <input  name="sigla" placeholder="Sigla de la Marca" class="form-control" id="modalsiglamarca" type="text"  style="text-transform:uppercase; " onkeyup="javascript:this.value=this.value.toUpperCase();">
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
                <button type="submit" class="btn btn-primary" id="bguardar_marca">Guardar</button>
            </div>
          </div> <!-- /.<div class="modal-body form">-->
        </div>
      </div> <!-- /. modal -->
  </div>
</form>
