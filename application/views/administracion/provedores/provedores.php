<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div id="toolbar" class="btn-group">
            <button class="btn btn-default text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal" data-target="#modalprovedor">Agregar Proveedor</button>
          </div>

          <table 
             data-toggle="table"
             data-height="550"
             data-search="true"
             data-show-columns="true"
             data-show-columns="true"
             data-page-size="200"
             data-pagination="true"
             data-search="true"
             data-striped="false"
             data-toolbar-align="right"
             data-toolbar="#toolbar">
              <thead>
                <tr>
                  <th data-field="idproveedor" data-visible="false">Id</th>
                  <th data-field="idDocumentoTipo" >Tipo Documento</th>
                  <th data-field="documento" data-switchable="false">Número de Documento</th>
                  <th data-field="nombreproveedor" data-switchable="false">Provedor</th>
                  <th data-field="responsable" data-visible="false">Responsable</th>
                  <th data-field="telefono" data-visible="false">Telefono</th>
                  <th data-field="fax" data-visible="false">Fax</th>
                  <th data-field="email" data-visible="false">Email</th>
                  <th data-field="web" data-visible="false">Web</th>
                  <th data-field="autor" data-visible="false">Autor</th>
                  <th data-field="fecha" data-visible="false">Fecha</th>
                  <th data-field="logo" data-visible="false">Logo</th>
                  <th>Editar</th>
                </tr>
              </thead>

              <tbody>

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
    <input type="" name="id_articulo" value="" id="id_articulo" hidden value="<?= "" ?>"> <!-- input oculto para el codigo de articulo-->
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
                        <select name="tipo_doc" class="form-control selectpicker" >
                          <option value=" " >Selecciona</option>
                          <option>NIT</option>
                          <option>CARNET DE IDENTIDAD</option>
                          <option ></option>
                          <option ></option>
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
                        <input  name="carnet" placeholder="00000000" class="form-control"  type="number">
                      </div>
                    </div>
                  </div>
                  <!-- Nombre de Proovedor-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Nombre de Proveedor</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-screenshot"></i></span>
                        <input  name="nombre" placeholder="Nombre de Proveedor" class="form-control"  type="text">
                      </div>
                    </div>
                  </div>
                  <!-- Direccion-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Direccion</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                          <input name="direccion" placeholder="Dirección de Proovedor" class="form-control" type="text">
                      </div>
                    </div>
                  </div>
                  <!-- Responsable -->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Responsable</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
                        <input  name="nombre_res" placeholder="Nombre de Responsable de Proovedor" class="form-control"  type="text">
                      </div>
                    </div>
                  </div>
                 <!-- Telefono -->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Telefono</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                         <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                        <input name="phone" placeholder="Telefono de Proovedor" class="form-control" type="number">
                      </div>
                    </div>
                  </div>
                 <!-- Telefono Fax-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Telefono Fax</label>  
                      <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                         <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                        <input name="phone" placeholder="Telefono Fax" class="form-control" type="number">
                      </div>
                    </div>
                  </div>
                  <!-- Email-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">E-Mail</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                         <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                         <input name="email" placeholder="Dirección Email" class="form-control"  type="text">
                      </div>
                    </div>
                  </div>
                  <!-- web-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Sitio WEB</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                        <input name="website" placeholder="Sitio Web Proovedor" class="form-control" type="text">
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


