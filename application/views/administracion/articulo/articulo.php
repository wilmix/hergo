<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div class="text-right">
           <!-- /.box-body 
              <a class="btn btn-default text-center btnnuevo" tyle="margin-bottom :10px" href="<?php echo base_url("index.php/reporte") ?>" target="_blank"><span class="glyphicon glyphicon-print"></span></a>
              <button class="btn btn-default text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal" data-target="#modalarticulo">Agregar nuevo Artículo</button>
          </div>
          <style type="text/css"> 
          .fht-cell .filterControl {
          display: none;
          }
th:hover .filterControl {
  display: block;
}
</style> -->

      <div id="toolbar" class="btn-group">
          <a class="btn btn-default text-center btnnuevo" tyle="margin-bottom :10px" href="<?php echo base_url("index.php/pdf/articulos_pdf") ?>" target="_blank"><span class="glyphicon glyphicon-print"></span></a>
          <button class="btn btn-default text-center btnnuevo" style="margin-bottom :10px" data-toggle="modal" data-target="#modalarticulo">Agregar nuevo Artículo</button>

      </div>

      <table id="tarticulo"
            data-toggle="table"
             data-toolbar="#toolbar">
           <thead>
    <tr>
        <th data-field="imagen" data-visible="true"></th>
        <th data-field="codigoarticulo"  data-filter-control="input" data-visible="true"></th>
        <th data-field="descripcion" data-filter-control="input" data-visible="true"></th>
        <th data-field="marcaarticulo" data-filter-control="input"  data-visible="true"></th>
        <th data-field="lineaarticulo" data-filter-control="input" data-visible="true"></th>
        <th data-field="partearticulo" data-filter-control="input" data-visible="true"></th>
        <th data-field="arancelariaarticulo" data-visible="false"></th>
        <th data-field="autorizaarticulo" data-visible="false"></th>
        <th data-field="productoarticulo" data-visible="false"></th>
        <th data-field="imagenes" data-visible="false"></th>
        <th data-field="uso" data-visible="true"></th>
    </tr>
    </thead>
       </table>

      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>

<!-- Modal -->
<form action=" " method="post"  id="form_articulo" enctype="multipart/form-data">
  <div class="modal fade" id="modalarticulo" role="dialog">
    <input type="" name="id_articulo" value="" id="id_articulo" hidden value="<?= "" ?>"> <!-- input oculto para el codigo de articulo-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title"></h3>
           </div>
                <!--MODAL BODY-->
          <div class="modal-body form form-horizontal">
              <fieldset>
                 <!-- Código--> 
              <div class="form-group">
                <label class="col-md-3 col-lg-3 control-label" for="codigoarticulo">Código</label>  
                <div class="col-md-9 col-lg-9  inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                    <input  autofocus name="codigo" placeholder="Código de Articulo" class="form-control" id="codigoarticulo"  type="text">
                  </div>
                </div>
              </div>
              <!-- Descripcion-->
              <div class="form-group">
                <label class="col-md-3 col-lg-3 control-label" for="descrpcionarticulo">Descripcion</label>  
                <div class="col-md-9 col-lg-9 inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-screenshot"></i></span>
                    <input  name="descripcion" placeholder="Descripción de artículo" class="form-control" id="descrpcionarticulo"  type="text">
                  </div>
                </div>
              </div>
              <!-- DescripcionFabrica-->
              <div class="form-group">
                <label class="col-md-3 col-lg-3 control-label" for="descripcionFabrica">Descripción Fábrica</label>  
                <div class="col-md-9 col-lg-9 inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-align-justify"></i></span>
                    <input  name="descripcionFabrica" placeholder="Descripción de Fábrica" class="form-control" id="descripcionFabrica"  type="text">
                  </div>
                </div>
              </div>
              <!-- Unidad  -->
              <div class="form-group"> 
                <label class="col-md-3 col-lg-3 control-label" for="unidadarticulo">Unidad</label>
                  <div class="col-md-9 col-lg-9 selectContainer">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
                      <select name="unidad" class="form-control selectpicker" id="unidadarticulo" >
                        <option value="" disabled>Seleccione</option>
                        <?php foreach ($unidad->result_array() as $fila): ?>
                          <option value="<?= $fila['idUnidad']  ?>"><?= $fila['Unidad'] ?></option>
                        <?php endforeach ?>
                      </select>
                  </div>
                </div>
              </div>
              <!-- Marca  -->
              <div class="form-group"> 
                <label class="col-md-3 col-lg-3 control-label" for="marcaarticulo">Marca</label>
                  <div class="col-md-9 col-lg-9 selectContainer">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
                      <select name="marca" class="form-control selectpicker" id="marcaarticulo" >
                        <option value="" disabled>Seleccione</option>
                        <?php foreach ($marca->result_array() as $fila): ?>
                          <option value="<?= $fila['idMarca']  ?>"><?= $fila['Marca'] ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                  </div>
              </div>
              <!-- Linea  -->
              <div class="form-group"> 
                <label class="col-md-3 col-lg-3 control-label" for="lineaarticulo">Linea</label>
                <div class="col-md-9 col-lg-9 selectContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                    <select name="linea" class="form-control selectpicker" id="lineaarticulo">
                      <option value="" disabled>Seleccione</option>
                      <?php foreach ($linea->result_array() as $fila): ?>
                          <option value="<?= $fila['idLinea']  ?>"><?= $fila['Linea'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
              </div>
              <!-- uso -->
              <div class="form-group"> 
                <label class="col-md-3 col-lg-3 control-label">En uso</label>
                <div class="col-md-9 col-lg-9 selectContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-object-align-top"></i></span>
                    <select name="uso" class="form-control selectpicker" id="uso">
                      <option value="" disabled>Elige si esta en uso</option>
                      <option selected value="1">Si</option>
                      <option value="0">No</option>
                    </select>
                  </div>
                </div>
              </div>
            </fieldset>     
            </div>
              <!-- Producto o servicio -->
              <div class="form-group"> 
                <label class="col-md-3 col-lg-3 control-label" for="productoarticulo">Producto o Servicio</label>
                <div class="col-md-9 col-lg-9 selectContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-resize-full"></i></span>
                    <select name="proser" class="form-control selectpicker" id="productoarticulo">
                      <option value="" disabled>Elige Producto o Servicio</option>
                      <option value="p">PRODUCTO</option>
                      <option value="s">SERVICIO</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-- Numero de parte ALFANUMERICO-->
              <div class="form-group">
                <label class="col-md-3 col-lg-3 control-label" for="partearticulo">Precio</label>  
                <div class="col-md-9 col-lg-9 inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                    <input  name="precio" class="form-control" id="precio" type="text">
                  </div>
                </div>
              </div>
              <!-- Numero de parte ALFANUMERICO-->
              <div class="form-group">
                <label class="col-md-3 col-lg-3 control-label" for="partearticulo">Número de Parte</label>  
                <div class="col-md-9 col-lg-9 inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
                    <input  name="parte"  class="form-control" id="partearticulo" type="text">
                  </div>
                </div>
              </div>
              <!-- Posicion arancelaria 10 numeros-->
              <div class="form-group">
                <label class="col-md-3 col-lg-3 control-label" for="arancelariaarticulo">Posicion Arancelaria</label>  
                <div class="col-md-9 col-lg-9 inputGroupContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-indent-left"></i></span>
                    <input  name="posicion"  class="form-control" id="arancelariaarticulo" type="text">
                  </div>
                </div>
              </div>
              <!-- Autorizacion-->
              <div class="form-group"> 
                <label class="col-md-3 col-lg-3 control-label" for="autorizaarticulo">Requisito</label>
                <div class="col-md-9 col-lg-9 selectContainer">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-font"></i></span>
                    <select name="autoriza" class="form-control selectpicker" id="autorizaarticulo">
                      <option value="" disabled>Si corresponde</option>
                      <?php foreach ($requisito->result_array() as $fila): ?>
                          <option value="<?= $fila['idRequisito']  ?>"><?= $fila['Requisito'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
              </div>

                <!-- Imagen -->                
              <div class="form-group upload_image">
                <label class="col-md-3 col-lg-3 control-label" for="imagenes">Imagen de artículo</label>
                <div class="col-md-9 col-lg-9">
                  <!--<input type="file" id="imagen" name="imagen" id="imagenarticulo">-->                  
                  <input id="imagenes" name="imagenes" type="file" class="file-loading" accept="image/*">
                  <p class="help-block">Seleccione imagen para el articulo menor a 1mb.</p>
                </div>
              </div>                
                

                        
          <div class="modal-footer">
                <button type="button" class="btn btn-default botoncerrarmodal" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="bguardar_articulo">Guardar</button>
            </div>
          </div> <!-- /.<div class="modal-body form">-->
        </div>
      </div> <!-- /. modal -->
  </div>
</form>

<script>
   
</script>

<!-- Modal imagen -->
<div class="modal fade" id="prev_imagen" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body text-center" id="imagen_max">
        <img class="maximizada" src="<?= base_url("/assets/img_articulos/ninguno.png") ?>">
      </div>
    </div>
  </div>
</div>