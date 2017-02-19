<div class="row">
  <div class="col-xs-12">
    <div class="box">
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url() ?>Ingresos">Ingresos</a></li>
            <li class="active">Ingreso Importaciones</li>
          </ol>
        <div class="box-header with-border">
          <h3 class="box-title">Ingreso Importaciones</h3>


          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
          <!-- /.box-tools -->
        </div>
          <div class="box-body">
              <!-- botones -->
              <div class="text-right">
                <a class="btn btn-default text-center btnnuevo" tyle="margin-bottom :10px" href="<?php echo base_url("Ingresos/Importaciones") ?>">Ingreso Importaciones</a>
                <a class="btn btn-default text-center btnnuevo" tyle="margin-bottom :10px" href="#">Compras Locales</a>
              </div> 
              <!-- formulario -->
              <div class="form-group-sm row">
                <div class="col-md-4">
                 <h1></h1>
                </div>
                <!--ingresar fecha de movimiento //por defecto fecha actual-->
                <div class="col-md-3">
                  <label class="col-sm-4 col-form-label col-form-label-sm" >Fecha:</label>
                  <input id="fechamov_imp" type="date" class="form-control form-control-sm" name="fechamov_imp" placeholder="Fecha" >
                 </div>
                 <!--seleccionar tipo moneda-->
                <div class="col-md-3">
                 <label class="col-sm-2 col-form-label col-form-label-sm">Moneda:</label>
                   <select class="form-control form-control-sm" id="moneda_imp" name="moneda_imp">
                    <option selected>BOLIVIANOS</option>
                    <option>DOLARES </option>
                   </select>
                </div>
                <!-- fecha de movimiento ****PENDIENTE
                <div class="col-md-2">
                  <label class="col-sm-2 col-form-label col-form-label-sm">Movimiento:</label>
                  <input id="" type="number" class="form-control form-control-sm" placeholder="# Movimiento"> 
                </div>row-->
              </div>
              
             
              <div class="form-group-sm row">
              <!--seleccionar proveedor de la base de datos-->
                <div class="col-xs-6 col-xl-6 col-md-6">
                   <label  class="col-sm-2 col-form-label col-form-label-sm">Proveedor:</label>
                   <select class="form-control form-control-sm" id="proveedor_imp" name="proveedor_imp">
                     <option>3 M</option>
                     <option>AIRGAS SOUTH INC</option>
                     <option>CIENSA LTDA.</option>
                     <option>COLMENA</option>
                     <option>COMERCIAL DE HERRAMIENTAS PALESTINA</option>
                     <option>COMERCIAL GABRIEL</option>
                     <option>COMERCIAL OERLIKON</option>
                     <option>CONFECCIONES MURILLO</option>
                     <option>COSIM</option>
                     <option>DINMEC</option>
                     <option>DISTRIBUIDORA INDUSTRIAL</option>
                     <option>DREAMS</option>
                     <option>EBENEZER</option>
                    </select> 
                </div>
                
               <div class="col-xs-6 col-xl-2 col-md-2">
                  <!--insertar num de factura-->
                 <label class="col-sm-2 col-form-label col-form-label-sm" for="usr">Factura:</label>
                 <input  type="number" class="form-control form-control-sm" id="nfactura_imp" name="nfactura_imp" placeholder="# Factura"> 
               </div>
               <div class="col-xs-6 col-xl-2 col-md-2">
                  <!--insertar num de orden de compra-->
                 <label class="col-sm-2 col-form-label col-form-label-sm" for="usr">OrdenCompra:</label>
                 <input  type="number" class="form-control form-control-sm" id="ordencompra_imp" name="ordencompra_imp" placeholder="# Orden">
                </div>
                <div class="col-xs-6 col-xl-2 col-md-2">
                    <!--insertar num de ingreso-->
                  <label class="col-sm-2 col-form-label col-form-label-sm" for="usr">N°Ingreso:</label>
                  <input  type="number" class="form-control form-control-sm" id="ningresoalm" name="ningresoalm" placeholder="# Ingreso">
                </div>
              </div><!--row-->
              

              <div class="form-group-sm row">
                <div class="col-xs-6 col-xl-2 col-md-2">
                    <!--seleccionar codigo de articulo de la base de datos-->
                   <label class="col-sm-2 col-form-label col-form-label-sm">Codigo:</label>
                   <select   class="form-control form-control-sm" id="articulo_imp" name="articulo_imp">
                      <option>CL01234</option> 
                      <option>AV1234</option>
                   </select> 
                </div>
                <div class="col-xs-6 col-xl-5 col-md-5">
                    <!--mostrar descripcion de articulo segun codigo-->
                   <label class="col-sm-2 col-form-label col-form-label-sm">Descripcion:</label>
                   <input type="text" class="form-control form-control-sm" /> 
                </div>
                <div class="col-xs-6 col-xl-1 col-md-1">
                     <!--mostrar unidad de articulo segun codigo-->
                   <label class="col-sm-2 col-form-label col-form-label-sm">Unidad:</label>
                   <input type="text" class="form-control form-control-sm" /> 
                </div>
                <div class="col-xs-6 col-xl-2 col-md-2">
                    <!--mostrar costo promedio ponderado de articulo segun codigo-->
                   <label class="col-sm-2 col-form-label col-form-label-sm">Costo:</label>
                   <input type="text" class="form-control form-control-sm" /> 
                </div>
                 <div class="col-xs-6 col-xl-2 col-md-2">
                    <!--mostrar saldo en almacen de articulo segun codigo-->
                   <label class="col-sm-2 col-form-label col-form-label-sm">Saldo:</label>
                    <input type="text" class="form-control form-control-sm" /> 
                </div>
              </div><!--row-->
              

              <div class="form-group-sm row">
                <div class="col-xs-6 col-xl-5 col-md-5"></div>
                <div class="col-xs-6 col-xl-2 col-md-2">
                      <!--insertar cantidad de productos a ingresar-->
                    <label class="col-sm-2 col-form-label col-form-label-sm">Cantidad</label>
                    <input type="number" class="form-control form-control-sm" id="cantidad_imp" name="cantidad_imp" /> 
                </div>
                <div class="col-xs-6 col-xl-2 col-md-2">
                    <!--insertar costo de articulo a ingresar-->
                    <label class="col-sm-2 col-form-label col-form-label-sm">Costo</label>
                    <input type="number" class="form-control form-control-sm" id="unitario_imp" name="unitario_imp" /> 
                </div>
                <div class="col-xs-6 col-xl-1 col-md-1">
                  <label class="col-sm-2 col-form-label col-form-label-sm" for=""></label>
                  <a class="btn btn-default text-center btn btn-success" tyle="margin-bottom :10px" href="#">
                  <span class="glyphicon glyphicon-plus"></span></a>
                </div> 

             </div><!--row-->

              <br />

               <!--Tabla para mostrar articulos ingresados-->
              <div class="table-responsive">
                <table  class="table table-condensed">
                  <thead>
                    <tr>
                      <th>Código</th>
                      <th>Artículo</th>
                      <th>Cantidad</th>
                      <th>Costo</th>
                      <th>Total</th>
                      <th>Borrar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>TM2152</td>
                      <td>CARTUCHO 6005 PARA FORMALDEYDO Y VAPORES ORGANICOS</td>
                      <td>30</td>
                      <td>3</td>
                      <td>90</td>
                      <td><button type="button" class="btn btn-default" aria-label="Left Align">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                  </td>
                    </tr>
                    <tr>
                      <td>CS1007</td>
                      <td>CARTEL SEÑALIZACION EN ACRILICO Y VINIL DE 15 X 15 CM</td>
                      <td>100</td>
                      <td>50</td>
                      <td>5000</td>
                      <td><button type="button" class="btn btn-default" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>
                    </tr>
                    <tr>
                      <td>TM2110</td>
                      <td>CARTUCHO 6001 PARA VAPORES ORGANICOS</td>
                      <td>10</td>
                      <td>5</td>
                      <td>50</td>
                      <td><button type="button" class="btn btn-default" aria-label="Left Align"><span class="glyphiconglyphicon-trash" aria-hidden="true"></span> </button></td>
                    </tr>
                  </tbody>
                </table>
              </div> <!--div class="table-responsive"-->


              
            <div class="form-group-sm row">
              <div class="col-md-6">
                <button type="button" class="btn btn-primary">Grabar Movimiento</button>
                <button type="button" class="btn btn-danger">Cancelar</button>
              </div>
              <div class="col-md-6">
              <div class = "input-group col-md-12">
                <span class = "input-group-addon">$</span>
                <!--mostrar el total de dolares-->
                <input type = "text" class="form-control form-control-sm" placeholder = "">
                <span class = "input-group-addon">Bs</span>
                <!--mostrar el total bolivivanos-->
                <input type = "text" class="form-control form-control-sm">
               </div>
              </div>
            </div><!--row-->

            <script>
              $('select').select2();
            </script>

        </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>


