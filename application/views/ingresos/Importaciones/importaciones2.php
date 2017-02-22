<div class="row">
  <div class="col-xs-12">
    <div class="box">         
        <div class="box-header with-border">
          <h3 class="box-title">Ingreso Importaciones</h3>
        
          <!-- /.box-tools -->
        </div>
          <div class="box-body">
             
              <!-- formulario -->
              


              <div class="form-group-sm row">
                  <div class="col-sm-6">
                   <label >Proveedor:</label>
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
                 <div class="col-xs-6 col-sm-3">
                   <div class="form-group">
                       <label for="fechamov_imp" >Fecha:</label>
                       <input id="fechamov_imp" type="date" class="form-control form-control-sm" name="fechamov_imp" placeholder="Fecha" >
                     </div>
                 </div>
                 <div class=" col-xs-6 col-sm-3">
                  <label for="moneda_imp">Moneda:</label>
                    <select class="form-control form-control-sm" id="moneda_imp" name="moneda_imp">
                     <option selected>BOLIVIANOS</option>
                     <option>DOLARES </option>
                    </select>
                 </div>  
               </div>

              <div class="form-group-sm row">
              <!--seleccionar proveedor de la base de datos-->                               
               <div class="col-xs-6 col-md-3">
                  <!--insertar num de factura-->
                 <label class="" for="usr">Factura:</label>
                 <input  type="number" class="form-control form-control-sm" id="nfactura_imp" name="nfactura_imp" placeholder="# Factura"> 
               </div>
               <div class="col-xs-6 col-sm-3 col-md-3">
                  <!--insertar num de orden de compra-->
                 <label class="" for="usr">OrdenCompra:</label>
                 <input  type="number" class="form-control form-control-sm" id="ordencompra_imp" name="ordencompra_imp" placeholder="# Orden">
                </div>
                <div class="col-xs-6 col-sm-3 col-md-3">
                    <!--insertar num de ingreso-->
                  <label class="" for="usr">N°Ingreso:</label>
                  <input  type="number" class="form-control form-control-sm" id="ningresoalm" name="ningresoalm" placeholder="# Ingreso">
                </div>
                <div class="col-xs-6 col-md-3">
                  
                </div>
              </div><!--row-->
              <hr>

              <div class="form-group-sm row">
                <div class="col-xs-6 col-md-2">
                    <!--seleccionar codigo de articulo de la base de datos-->
                   <label for="articulo_imp">Codigo:</label>
                   <select   class="form-control form-control-sm" id="articulo_imp" name="articulo_imp">
                      <option>CL01234</option> 
                      <option>AV1234</option>
                   </select> 
                </div>
                <div class="col-xs-6 col-md-4">
                    <!--mostrar descripcion de articulo segun codigo-->
                   <label for="descripcion_imp">Descripcion:</label>
                   <input type="text" class="form-control form-control-sm" id="descripcion_imp"/> 
                </div>
                <div class="col-xs-6 col-md-2">
                     <!--mostrar unidad de articulo segun codigo-->
                   <label for="">Unidad:</label>
                   <input type="text" class="form-control form-control-sm" id="unidad_imp" /> 
                </div>
                <div class="col-xs-3 col-md-2">
                    <!--mostrar costo promedio ponderado de articulo segun codigo-->
                   <label for="costo_imp">Costo:</label>
                   <input type="text" class="form-control form-control-sm" id="costo_imp" /> 
                </div>
                 <div class="col-xs-3 col-md-2">
                    <!--mostrar saldo en almacen de articulo segun codigo-->
                   <label for="saldo_imp">Saldo:</label>
                    <input type="text" class="form-control form-control-sm" id="saldo_imp" /> 
                </div>
              </div><!--row-->
              
              <br>
              <div class="form-group-sm row">
                
                <div class="col-xs-6 col-md-2">
                      <!--insertar cantidad de productos a ingresar-->
                    <label>Cantidad:</label>
                    <input type="number" class="form-control form-control-sm" id="cantidad_imp" name="cantidad_imp" /> 
                </div>
                <div class="col-xs-6 col-md-2">
                    <!--insertar costo de articulo a ingresar-->
                    <label>Costo:</label>
                    <input type="number" class="form-control form-control-sm" id="unitario_imp" name="unitario_imp" /> 
                </div>
                <div class="col-xs-6 col-md-6">
                    <!--insertar costo de articulo a ingresar-->
                    <label for="observaciones_imp">Observaciones:</label>
                    <input type="number" class="form-control form-control-sm" id="observaciones_imp" name="observaciones_imp" /> 
                </div>
                <div class="col-xs-6 col-md-2">
                <label for="agregar_imp">sd</label>
                <input type="button" class="btn btn-success" id="agregar_imp">
                </div> 

             </div><!--row-->

              <br />

               <!--Tabla para mostrar articulos ingresados-->
        </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="box">         
        <div class="box-header with-border">
          <h3 class="box-title">Ingreso Importaciones</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
          <!-- /.box-tools -->
        </div>
        <div class="box-body">
     


           
               <!--Tabla para mostrar articulos ingresados-->
        </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>


