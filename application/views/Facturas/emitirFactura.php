<style>
  body {
    /* el tamaño por defecto es 14px */
    font-size: 14px;
}
</style>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <div id="toolbar2" class="form-inline">
          <button type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
           <span>1 enero, 2017 - 31 diciembre, 2017</span>
            <i class="fa fa-caret-down"></i>
         </button>
          <select class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
            <option value="1">CENTRAL HERGO</option>
            <option value="2">DEPOSITO EL ALT</option>
            <option value="3">POTOSI</option>
            <option value="4">SANTA CRUZ</option>
            <option value="5">COCHABAMBA</option>
            <option value="6">TALLER</option>
            <option value="">TODOS</option>
          </select>
          <select class="btn btn-primary btn-sm" name="tipo_filtro" id="tipo_filtro">
            <option value="">TODOS</option>
            <option value="6">VENTAS CAJA</option>
            <option value="7">NOTA DE ENTREGA</option>
          </select>
         </div>

         <table class="table table-condensed" class="table table-striped"
            data-toggle="table"
            data-height="250"
            id=""
            data-toolbar="#toolbar2"
            data-mobile-responsive="true">
            <thead>
              <tr>
                <th class="col-sm-1">N</th>
                <th class="col-sm-1">Tipo</th>
                <th class="col-sm-1">Fecha</th>
                <th class="col-sm-1">N° Cliente</th>
                <th class="col-sm-6">Cliente</th>
                <th class="col-sm-1">Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <tr>
              <td>1276</td>
              <td>NE</td>
              <td>24/05/2017</td>
              <td>123456789</td>
              <td>SOBOCE S.A.</td>
              <td>1,816 </td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>1274</td>
              <td>NE</td>
              <td>23/05/2017</td>
              <td>123456789</td>
              <td>PAN AMERICAN SILVER BOLIVIA S.A.</td>
              <td>2,127</td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>1273</td>
              <td>NE</td>
              <td>23/05/2017</td>
              <td>123456789</td>
              <td>BOLIVIAN FOODS S.A.</td>
              <td>160</td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>1270</td>
              <td>NE</td>
              <td>23/05/2017  </td>
              <td>123456789</td>
              <td>GRANJA AVICOLA INTEGRAL SOFIA LTDA.</td>
              <td>195</td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>1269</td>
              <td>VC</td>
              <td>23/05/2017</td>
              <td>123456789</td>
              <td>OMNILIFE DE BOLIVIA S.A.</td>
              <td>90</td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>1265</td>
              <td>VC</td>
              <td>23/05/2017</td>
              <td>123456789</td>
              <td>OSCAR VALDA</td>
              <td>1,150.4</td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>1260</td>
              <td>NE</td>
              <td>23/05/2017</td>
              <td>123456789</td>
              <td>EMBOL S.A.</td>
              <td>858</td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>1251  </td>
              <td>VC</td>
              <td>22/05/2017</td>
              <td>123456789</td>
              <td>SOBOCE S.A.</td>
              <td>680</td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button></td>
            </tr>
            </tbody>    
          </table>
<br>
          
          <div>
            <form class="pull-right">
              Fecha Factura:
              <input  type="date" name="">
              <select class="btn btn-default btn-sm" name="tipo_filtro" id="tipo_filtro">
                <option class="success">QR</option>
                <option >MANUAL</option>
              </select>
              <select class="btn btn-default btn-sm" name="tipo_filtro" id="tipo_filtro">
                <option class="success">Bolivianos</option>
                <option >Dolares</option>
              </select>
              <a class="btn btn-default text-center btnnuevo"><span class="fa fa-print"></span> Factura</a>
              
            </form>
          </div>
<hr>

        
        <div class="table-responsive" class="table table-condensed">
            <div class="col-md-6 col-xs-12">
              <table
                class="table"
                data-toggle="table"
                data-height="250"
                data-mobile-responsive="true">
                <thead>
                  <tr >
                    <th class="col-sm-1">Codigo</th>
                    <th class="col-sm-7">Articulo</th>
                    <th class="col-sm-1">Cantidad</th>
                    <th class="col-sm-1">P/U Bs</th>
                    <th class="col-sm-1">Total Bs</th>
                    <th class="col-sm-1"><button type="button" class="btn btn-default"><span class="fa fa-arrow-circle-right" aria-hidden="true"></span></button></th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="danger">
                    <td>AS1600</td>
                    <td>TRIANGULO REFLECTIVO DE SEGURIDAD VEHICULAR</td>
                    <td>8</td>
                    <td>87.00</td>
                    <td>696.00</td>
                    <td><button type="button" class="btn btn-default"><span class="fa fa-arrow-right" aria-hidden="true"></span></button></td>
                  </tr>
                   <tr class="danger">
                    <td>CL4940</td>
                    <td>BOTIQUINES CON ELEMENTOS ESENCIALES</td>
                    <td>8</td>
                    <td>77.00</td>
                    <td>616.00</td>
                    <td><button type="button" class="btn btn-default"><span class="fa fa-arrow-right" aria-hidden="true"></span></button></td>
                  </tr>
                   <tr>
                    <td>KY0012</td>
                    <td>EXTINTOR PQ ABC 1 KL C/CACHA DE PLASTICO</td>
                    <td>8</td>
                    <td>63.00</td>
                    <td>504.00</td>
                    <td><button type="button" class="btn btn-default"><span class="fa fa-arrow-right" aria-hidden="true"></span></button></td>
                  </tr>
                </tbody>    
              </table>
            </div>

            <div class="table-responsive">
              <div class = "col-md-12 col-xs-12">
                <table
                 class="table"
                data-toggle="table"
                data-height="250"
                data-mobile-responsive="true">
                  <thead>
                    <tr>
                      <th><button type="button" class="btn btn-default"><span class="fa fa-arrow-circle-left" aria-hidden="true"></span></button></th>
                      <th>Codigo</th>
                      <th>Articulo</th>
                      <th>Cantidad</th>
                      <th>P/U Bs</th>
                      <th>Total Bs</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><button type="button" class="btn btn-default"><span class="fa fa-arrow-left" aria-hidden="true"></span></button></td>
                      <td>AS1600</td>
                      <td>TRIANGULO REFLECTIVO DE SEGURIDAD VEHICULAR</td>
                      <td>8</td>
                      <td data-field="name" data-editable="true">87.00</td>
                      <td>696.00</td>
                    </tr>
                     <tr>
                      <td><button type="button" class="btn btn-default"><span class="fa fa-arrow-left" aria-hidden="true"></span></button></td>
                      <td>CL4940</td>
                      <td>BOTIQUINES CON ELEMENTOS ESENCIALES</td>
                      <td>8</td>
                      <td>77.00</td>
                      <td>616.00</td>
                    </tr>
                 </tbody> 
                </table>
              </div>
            </div>
          </div><!--row-->


          <div class="form-group row">
              <div class="col-md-6 col-xs-12">
                
              </div>
              <div class="col-md-6 col-xs-12">
                <div class = "input-group col-md-12 col-xs-12">
                  <span class = "input-group-addon">$</span>
                  <!--mostrar el total de dolares-->
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="" value="188.51">
                  <span class = "input-group-addon" >Bs</span>
                  <!--mostrar el total bolivivanos-->
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="" value="1,312.00">
                 </div>
              </div>
            </div><!--row-->
            
            <div class="row">
                <div class="col-xs-12 col-md-12">
                  <!--insertar costo de articulo a ingresar-->
                  <label for="observaciones_ne">Observaciones:</label>
                  <input type="text" class="form-control" id="obs_ne" name="obs_ne" value="" />
              </div>
            </div>
            <br>
            <div class="pull-right" class="row">
              <div class="col-xs-12">
                <button type="button" class="btn btn-primary" id="guardarMovimiento" tabindex=16 >GrabarFactura</button>
                <button type="button" class="btn btn-danger" id="cancelarMovimiento" tabindex=12>Cancelar</button>
                <!--<button type="button" class="btn btn-info" id="cancelarMovimiento" tabindex=12>Imprimir</button>-->
            </div>
            </div>

       <!-- /.box-body -->
      </div>
    <!-- /.box -->
    </div>
  <!-- /.col -->
  </div>
</div>
<!-- /.class="row" EMITIR FACTURA-->



