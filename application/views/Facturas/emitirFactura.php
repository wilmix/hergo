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
            <option value="0">TODOS</option>
          </select>
          <select class="btn btn-primary btn-sm" name="tipo_filtro" id="tipo_filtro">
            <option value="0">TODOS</option>
            <option value="6">VENTAS CAJA</option>
            <option value="7">NOTA DE ENTREGA</option>
          </select>
         </div>

         <table
            data-height="250"
            id="tfacturas"
            data-toolbar="#toolbar2"
            >              
          </table>

<br>
          
          <div>
            <form class="pull-right">
              Fecha Factura:
              <input  type="date">
              <select class="btn btn-default btn-sm" id="">
                <option class="success">QR</option>
                <option >MANUAL</option>
              </select>
              <select class="btn btn-default btn-sm"  id="">
                <option class="success">Bolivianos</option>
                <option >Dolares</option>
              </select>
              <a class="btn btn-default text-center btnnuevo" id="" data-toggle="modal" data-target="#facPrev"><span class="fa fa-print"></span> Factura</a>
              
            </form>
          </div>
          <div class="clearfix"></div>
          <input id="valuecliente" hidden="true" class="hidden">
<hr>

        
        <div class="table-responsive" class="table table-condensed">
            <div class="col-md-6 col-xs-12">
             <!-- <table
                id="tabla2detalle"              
                data-toggle="table"
                data-height="250"                        
                >
                <thead>
                  <tr >
                    <th class="col-sm-1" data-field="id" data-visible="false">id</th>
                    <th class="col-sm-1" data-field="codigo">Codigo</th>
                    <th class="col-sm-7" data-field="articulo">Articulo</th>
                    <th class="col-sm-1" data-field="cantidado">Cantidad</th>
                    <th class="col-sm-1" data-field="punitario">P/U Bs</th>
                    <th class="col-sm-1" data-field="total">Total Bs</th>
                    <th class="col-sm-1" data-field="btn"><button type="button" class="btn btn-default"><span class="fa fa-arrow-circle-right" aria-hidden="true"></span></button></th>
                  </tr>
                </thead>
                <tbody id="tbodytabla2">
                                              
                </tbody>    
              </table>-->
              <table id="tabla2detalle"></table>
            </div>

            <div class="table-responsive">
              <div class = "col-md-12 col-xs-12">
                <table id="tabla3Factura">                 
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
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="" value="188.51">
                  <span class = "input-group-addon" >Bs</span>
                  <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="" value="1,312.00">
                 </div>
              </div>
            </div><!--row-->
            
            <div class="row">
                <div class="col-xs-12 col-md-12">
                  <!--insertar costo de articulo a ingresar-->
                  <label for="observaciones_ne">Observaciones:</label>
                  <input type="text" class="form-control" id="" value="" />
              </div>
            </div>
            <br>
            <!--<div class="pull-right" class="row">
              <div class="col-xs-12">
                <button type="button" class="btn btn-primary" id="" data-toggle="modal" data-target="#facPrev" >GrabarFactura</button>
                <button type="button" class="btn btn-danger" id="cancelarMovimiento" tabindex=12>Cancelar</button>
                <button type="button" class="btn btn-info" id="cancelarMovimiento" tabindex=12>Imprimir</button>
            </div>
            </div>-->

       <!-- /.box-body -->
      </div>
    <!-- /.box -->
    </div>
  <!-- /.col -->
  </div>
</div>
<!-- /.class="row" EMITIR FACTURA-->
<style>
#direction {
     font-size: 80%;
     text-align: center;
}
#nitcss {
     font-size: 140%;
     text-align: center;
}

</style>

<!-- Modal -->
<div id="facPrev" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <!--<h1 class="modal-title modal-ce">FACTURA</h1>-->
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4" class="text-center">
          <img align="center" src="http://localhost:8080/hergo/images/hergo.jpeg" alt="hergo" width="200" height="40">
          <div id="direction">
            <p><b>Casa Matriz - 0</b> <br>
            Av. Montes N° 611 * Zona Challapampa * Casilla 1024 <br>
            Telfs.:2285837 - 2285854 * Fax 2126283 <br>
            La Paz - Bolvia </p>
          </div>
          </div>
          <div class="col-md-4" class="text-center">
          <h1 class="text-center"><b>FACTURA</b></h1>
          </div>
          <div class="text-center" class="col-md-4">
            <p id="nitcss"><b>NIT: 1000991026 </b></p>
            <p>FACTURA N°: <b>59</b> <br>
            AUTORIZACION N°: 265656700006546</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
          <p>Lugar y Fecha: La Paz, 23 de Junio de 2017 <br>
          Señor(es): MINERA SAN CRISTOBAL SA  <br>
          OC/Pedido: OL - 65435132</p>
          </div>
            <div class="col-md-4">
            <p class="text-center">NIT/CI:  <b>1020415021</b></p>
            <p id="direction" class="text-center">Actividad economica: VENTA AL POR MAYOR DE MAQUINARIA, EQUIPO Y MATERIALES</p>
          </div>
        </div>
        <div>
           <table class="table">
            <thead>
              <tr>
                <th>Cantidad</th>
                <th>Unid.</th>
                <th>Codigo</th>
                <th>Articulo</th>
                <th>Precio Unit.</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>5</td>
                <td>PZA</td>
                <td>SR7060</td>
                <td>RECARGA DE CILINDRO DE GAS CARBONICO DE 25 LT.</td>
                <td>80.35</td>
                <td>401.75</td>
              </tr>
              <tr>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-md-10">
            <p>SON: <b>DOS MIL SETECIENTOS NOVENTA YY SEIS 18/100 BOLIVIANOS</b></p>
            <p>NOTA: </p>
            <br>
            <br>
            <p>CODIGO DE CONTROL: 80-45-A6-A3</p>
            <p>FECHA LIMITE DE EMISIÓN: 13/12/16</p>
          </div>
          <div class="col-md-2">
            <p>Total $US:   401.75</p>
            <p>Total Bs:  2.796,18</p>
            <p>           T/C 6.96</p>
            <p>Codigo QR</p>

          </div>
        </div>

  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="">GrabarFactura</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


