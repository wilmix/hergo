<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div id="toolbar" class="btn-group">
            <button class="btn btn-default text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal" data-target="#modalprovedor">Agregar Proveedor</button>
          </div>

          <table 
             data-toggle="table"
             data-toolbar="#toolbar"
             id="tproveedores"
             >
              

          </table>     
        </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- div class="row" -->


<!-- Modal -->
<form action=" " method="post"  id="form_provedor" enctype="multipart/form-data">
  <div class="modal fade" id="modalprovedor" role="dialog">
    <input type="" name="id_proveedor" value="" id="id_proveedor" hidden value=""> <!-- input oculto para el codigo de articulo-->
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title">Agregar Provedor</h3>
              </div>
                  <!--MODAL BODY-->
              <div class="modal-body form form-horizontal">
                <fieldset>
                  <!-- Tipo documento *******AGREGAR SELECT********* -->
                  <div class="form-group"> 
                    <label class="col-md-3 col-lg-3 control-label">Tipo de Documento</label>
                    <div class="col-md-9 col-lg-9 selectContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
                        <select name="tipo_doc" id="tipo_doc" class="form-control " >
                         
                            <option value=" " >Selecciona</option>
                             <?php foreach ($tipodocumento->result_array() as $fila):  ?>
                              <option value="<?= $fila['idDocumentoTipo'] ?>"><?= $fila['documentotipo']?></option>
                            <?php endforeach ?>                        
                      
                        </select>
                      </div>
                    </div>
                  </div>
                  <!-- Documento-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">N° Documento</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                        <input  name="carnet"  id="carnet" placeholder="00000000" class="form-control">
                      </div>
                    </div>
                  </div>
                  <!-- Nombre de Proovedor-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Nombre de Proveedor</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-screenshot"></i></span>
                        <input  name="nombre"  id="nombre" placeholder="Nombre de Proveedor" class="form-control"  type="text">
                      </div>
                    </div>
                  </div>
                  <!-- Direccion-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Direccion</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                          <input name="direccion" id="direccion" placeholder="Dirección de Proovedor" class="form-control" type="text">
                      </div>
                    </div>
                  </div>
                  <!-- Responsable -->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Responsable</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
                        <input  name="nombre_res" id="nombre_res" placeholder="Nombre de Responsable de Proovedor" class="form-control"  type="text">
                      </div>
                    </div>
                  </div>
                 <!-- Telefono -->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Telefono</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                         <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                        <input name="phone" id="phone" placeholder="Telefono de Proovedor" class="form-control">
                      </div>
                    </div>
                  </div>
                 <!-- Telefono Fax-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Telefono Fax</label>  
                      <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                         <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                        <input name="fax" id="fax" placeholder="Telefono Fax" class="form-control">
                      </div>
                    </div>
                  </div>
                  <!-- Email-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">E-Mail</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                         <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                         <input name="email" id="email" placeholder="Dirección Email" class="form-control"  type="text">
                      </div>
                    </div>
                  </div>
                  <!-- web-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Sitio WEB</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                        <input name="website" id="website" placeholder="Sitio Web Proovedor" class="form-control" type="text">
                      </div>
                    </div>
                  </div>
                </fieldset>
              <div class="modal-footer">
                <button type="button" class="btn btn-default botoncerrarmodal" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="bguardar_articulo">Guardar</button>
              </div>
          </div> <!-- /class="modal-body form form-horizontal"-->
        </div> <!-- /. class="modal-dialog" -->
      </div> <!-- /. class="modal-dialog" -->
  </div> <!-- /. class="modal fade" -->
</form>


