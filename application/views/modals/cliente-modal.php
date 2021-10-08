<!-- Modal Cliente-->
<form action=" " method="post"  id="form_clientes" enctype="multipart/form-data">
  <div class="modal fade" id="modalcliente" role="dialog">
    <input type="" name="id_cliente" value="" id="id_cliente" hidden value="<?= "" ?>"> <!-- input oculto para el codigo de articulo-->
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title"></h3>
              </div>
                  <!--MODAL BODY-->
              <div class="modal-body form form-horizontal">
                <fieldset>
                  <!-- Tipo Documento-->
                  <div class="form-group"> 
                    <label class="col-md-3 col-lg-3 control-label">Tipo de Documento</label>
                    <div class="col-md-9 col-lg-9 selectContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
                        <select name="tipo_doc" id="tipo_doc" class="form-control" >
                        <option value="2" selected="selected">NIT</option>
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
                        <input  name="carnet" id="carnet" placeholder="00000000" class="form-control"  type="text">
                      </div>
                    </div>
                  </div>
                  <!-- Nombre de Cliente-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Nombre de Cliente</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input  name="nombre_cliente" id="nombre_cliente" placeholder="Nombre o Razon Social" class="form-control"  type="text">
                      </div>
                    </div>
                  </div>
                   <!-- Tipo Cliente-->
                  <div class="form-group"> 
                    <label class="col-md-3 col-lg-3 control-label">Tipo de Cliente:</label>
                    <div class="col-md-9 col-lg-9 selectContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
                        <select name="clientetipo" id="clientetipo" class="form-control " >
                          <option value="1" selected>Cliente</option>
                          <?php foreach ($tipocliente->result_array() as $fila):  ?>
                            <option value="<?= $fila['idClientetipo'] ?>"><?= $fila['clientetipo']?></option>
                          <?php endforeach ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <!-- diasCredito -->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Tiempo Crédito</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fas fa-clock"></i></span>
                        <input name="diasCredito" id="diasCredito" placeholder="Tiempo de crédito del cliente" class="form-control" type="number" />
                      </div>
                    </div>
                  </div>
                  <!-- Direccion-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Direccion</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                        <input name="direccion" id="direccion" placeholder="Dirección de Cliente" class="form-control" type="text">
                      </div>
                    </div>
                  </div>
                  <!-- Telefono -->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Telefono</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                        <input name="phone" id="phone" placeholder="Telefono de Cliente" class="form-control" type="number">
                      </div>
                    </div>
                  </div>
                  <!-- Email-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">E-Mail</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input name="email" id="email"  placeholder="Dirección Email" class="form-control"  type="text">
                      </div>
                    </div>
                  </div>
                  <!-- web-->
                  <div class="form-group">
                    <label class="col-md-3 col-lg-3 control-label">Sitio WEB</label>  
                    <div class="col-md-9 col-lg-9 inputGroupContainer">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                        <input name="website" id="website" placeholder="Sitio Web Cliente" class="form-control" type="text">
                      </div>
                    </div>
                  </div>
                </fieldset>
              <div class="modal-footer">
                <button type="button" class="btn btn-default botoncerrarmodal" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary bguardar">Guardar</button>
              </div>
          </div> <!-- /class="modal-body form form-horizontal"-->
        </div> <!-- /. class="modal-dialog" -->
      </div> <!-- /. class="modal-dialog" -->
  </div> <!-- /. class="modal fade" -->
</form>