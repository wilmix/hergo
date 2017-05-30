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
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalFacturaDetalle">Open Modal</button>
           </div>

         <table class="table table-condensed" class="table table-striped"
            data-toggle="table"
            data-height="550"
            id=""
            data-toolbar="#toolbar2"
            data-mobile-responsive="true"
            data-show-columns="true">
            <thead>
              <tr>
                <th data-visible="false">Lote</th>
                <th>N</th>
                <th class="col-sm-1">Movimiento</th>
                <th class="col-sm-1">Fecha</th>
                <th data-visible="false" class="col-sm-1">N° Cliente</th>
                <th class="col-sm-4">Cliente</th>
                <th class="col-sm-1">Total</th>
                <th data-align="center">Estado</th>
                <th ></th>
              </tr>
            </thead>
            <tbody>
            <tr>
              <td>38</td>
              <td>1276</td>
              <td>NE-554</td>
              <td>24/05/2017</td>
              <td>123456789</td>
              <td>SOBOCE S.A.</td>
              <td>1,816 </td>
              <td><span class="label label-success">Facturado</span></td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
              <button type="button" class="btn btn-default"><span class="fa fa-times"  aria-hidden="true"></span></button>


              </td>
            </tr>
            <tr>
              <td>38</td>
              <td>1274</td>
              <td>NE-874</td>
              <td>23/05/2017</td>
              <td>123456789</td>
              <td>PAN AMERICAN SILVER BOLIVIA S.A.</td>
              <td>2,127</td>
              <td><span class="label label-success">Facturado</span></td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
              <button type="button" class="btn btn-default"><span class="fa fa-times"  aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>38</td>
              <td>1273</td>
              <td>NE-547</td>
              <td>23/05/2017</td>
              <td>123456789</td>
              <td>BOLIVIAN FOODS S.A.</td>
              <td>160</td>
              <td><span class="label label-danger">Anulado</span></td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
              <button type="button" class="btn btn-default"><span class="fa fa-times"  aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>38</td>
              <td>1270</td>
              <td>NE-12</td>
              <td>23/05/2017  </td>
              <td>123456789</td>
              <td>GRANJA AVICOLA INTEGRAL SOFIA LTDA.</td>
              <td>195</td>
              <td><span class="label label-success">Facturado</span></td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
              <button type="button" class="btn btn-default"><span class="fa fa-times"  aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>38</td>
              <td>1269</td>
              <td>VC-698</td>
              <td>23/05/2017</td>
              <td>123456789</td>
              <td>OMNILIFE DE BOLIVIA S.A.</td>
              <td>90</td>
              <td><span class="label label-success">Facturado</span></td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
              <button type="button" class="btn btn-default"><span class="fa fa-times"  aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>38</td>
              <td>1265</td>
              <td>VC-784</td>
              <td>23/05/2017</td>
              <td>123456789</td>
              <td>OSCAR VALDA</td>
              <td>1,150.4</td>
              <td><span class="label label-success">Facturado</span></td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
              <button type="button" class="btn btn-default"><span class="fa fa-times"  aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>38</td>
              <td>1260</td>
              <td>NE-987</td>
              <td>23/05/2017</td>
              <td>123456789</td>
              <td>EMBOL S.A.</td>
              <td>858</td>
              <td><span class="label label-success">Facturado</span></td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
              <button type="button" class="btn btn-default"><span class="fa fa-times"  aria-hidden="true"></span></button></td>
            </tr>
            <tr>
              <td>38</td>
              <td>1251  </td>
              <td>VC-321</td>
              <td>22/05/2017</td>
              <td>123456789</td>
              <td>SOBOCE S.A.</td>
              <td>680</td>
              <td><span class="label label-danger">Anulado</span></td>
              <td><button type="button" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
              <button type="button" class="btn btn-default"><span class="fa fa-times"  aria-hidden="true"></span></button></td>
            </tr>
            </tbody>    
          </table>
          <!--<table 
            cellspacing="0" 
            id="tfacturas"
            data-toolbar="#toolbar2">
          </table>-->
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>




<!-- Modal -->
<div id="modalFacturaDetalle" class="modal fade" role="dialog">
  <div class="modal-dialog modal-95">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <div class="modal-title" ><h4>
            <h2><span class="label label-primary" id="nombreModal">Factura</span>
            <!--<span id="facturadonofacturado">Facturado</span> -->
            <span class="label label-primary" id="numeromovimiento">546</span> </h2>
          <!--</h4>-->          
              
        </div>
        
      </div>
      <div class="modal-body">
         <!-- formulario PRIMERA FILA-->
          <div class="row"> <!--PRIMERA FILA-->
             <div class=" col-xs-6 col-sm-6 col-md-3">
              <label>Almacen:</label>
              <input id="almacen_fac" type="text" class="form-control" name="almacen_fac" readonly="">
             </div>
            <div class=" col-xs-6 col-sm-6 col-md-3">
              <label for="moneda_fac">Fecha:</label>
              <input id="tipomov_fac" type="text" class="form-control" name="tipomov_fac" readonly="">
             </div>
             <div class="col-xs-6 col-sm-6 col-md-2">
                <label for="fechamov_fac" >Moneda:</label>
                <input id="fechamov_fac" type="text" class="form-control" name="fechamov_fac" readonly="">
             </div>
             <div class="col-xs-6 col-sm-6 col-md-2">
                <label for="moneda_fac">Tipo Mov:</label>
                <input id="moneda_fac" type="text" class="form-control" name="moneda_fac" readonly="">

             </div>

             <div style="position: fixed;right: 0;z-index:9999" class="col-xs-4 col-sm-4 col-md-2">
                       <label>N° Movimiento:</label>
                       <table  class="table table-condensed">
                          <tbody>
                            <tr class="success">
                              <td>154</td>
                            </tr>
                          </tbody>
                        </table>
                 </div>

          </div> <!-- div class="form-group-sm row" PRIMERA FILA -->
          <div class="row"> <!--SEGUNDA FILA-->
                 <div class="col-xs-12 col-lg-6 col-md-6">
                   <label >Cliente:</label>
                   <input id="cliente_fac" type="text" class="form-control" name="cliente_fac" readonly="">
                 </div>
                
              </div><!-- div class="form-group-sm row" SEGUNDA FILA-->
              <hr>
         <table class="table-striped"
              data-toggle="table"
              id="tFacturaDetalle">
          </table>
          
          

          <div class="col-md-6 col-xs-12 pull-right" style="padding: 0px">
            <div class = "input-group col-md-12 col-xs-12">
              <span class = "input-group-addon">$</span>
              <!--mostrar el total dolares factura-->
              <input type = "text" class="form-control form-control-sm text-right tiponumerico" id="" disabled="">
              <span class = "input-group-addon">Bs</span>
              <!--mostrar el total bolivivanos sistema-->
              <input type = "text" class="form-control form-control-sm text-right tiponumerico" id="totalbsdetalle" disabled="">
             </div>
          </div>
          <div class="clearfix"></div>
          <hr>
          <div class="row">
                <div class="col-xs-12 col-md-12">

                  <label for="observaciones_fac">Observaciones:</label>
                  <input type="text" class="form-control" id="observaciones_fac" name="observaciones_fac" readonly="" /> 
              </div>
              
                
          </div>
          <div class="clearfix"></div>

      </div>
      <div class="modal-footer">
        <span id="pendienteaprobado"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
