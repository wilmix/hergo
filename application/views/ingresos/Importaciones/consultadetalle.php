<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">

          <div id="toolbar2" class="form-inline">
             <button type="button" class="btn btn-default" id="fechapersonalizada">
               <span>
                 <i class="fa fa-calendar"></i> Fecha
               </span>
                <i class="fa fa-caret-down"></i>
             </button>

              <select class="form-control" id="almacen_filtro" name="almacen_filtro">
                <?php foreach ($almacen->result_array() as $fila): ?>
                <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                <?php endforeach ?>
              </select>

              <select class="form-control" name="tipo_filtro" id="tipo_filtro">
                 <option value="">COMPRAS LOCALES</option>
                  <option value="">IMPORTACIONES</option>
                  <option value="">ANULACION EGRESOS</option>
              </select>
           </div>

          <style>
            table { table-layout: fixed; }
          </style>

          <table 
            id="tbconsultadetalle"
            data-toolbar="#toolbar2"
            data-toggle="table"
            data-height="550">
          </table>

          <script>
            $('#tbconsultadetalle').bootstrapTable({
                striped:true,
                pagination:true,
                pageSize:"100",
                clickToSelect:true,
                search:true,
                strictSearch:true,
                searchOnEnterKey:true,
                filter:true,
                showColumns:true,
                columns: [
                {
                    field: 'n',
                    width: '3%',
                    title: 'N',
                    align: 'center',
                    sortable:true,
                },
                {
                    field:'fechamov',
                    width: '7%',
                    title:"Fecha",
                    sortable:true,
                    align: 'center',
                    formatter: formato_fecha_corta,
                },
                {
                    field:'nombreproveedor',
                    title:"Proveedor",
                    width: '17%',
                    sortable:true,
                },
                {
                    field:'nfact',
                    title:"Factura",
                    width: '7%',
                    sortable:true,
                    
                },
                {
                    field:'',
                    title:"Codigo",
                    width: '7%',
                    align: 'right',
                    sortable:true,
                    formatter: operateFormatter3,
                },
                {
                    field:"",
                    title:"Descripción",
                    width: '17%',
                    sortable:true,
                    formatter: operateFormatter2,
                    align: 'center'
                },
                {
                    field:"",
                    title:"Cantidad",
                    width: '7%',
                    sortable:true,
                    formatter: operateFormatter2,
                    align: 'center'
                },
                {
                    field:"",
                    title:"Monto",
                    width: '8%',
                    sortable:true,
                    formatter: operateFormatter2,
                    align: 'center'
                },
                {
                    field:"autor",
                    width: '8%',
                    title:"Autor",
                    sortable:true,
                    visible:false,
                    align: 'center'
                },
                {
                    field:"fecha",
                    width: '8%',
                    title:"Fecha",
                    sortable:true,
                    formatter: formato_fecha_corta,
                    visible:false,
                    align: 'center'
                }]
    
          });
          </script>


     </div>

      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>

<!-- Modal -->
<div id="modalIgresoDetalle" class="modal fade" role="dialog">
  <div class="modal-dialog modal-95">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detalle de ingresos</h4>
      </div>
      <div class="modal-body">
         <!-- formulario PRIMERA FILA-->
          <div class="row"> <!--PRIMERA FILA-->
             <div class=" col-xs-6 col-sm-6 col-md-3">
              <label>Almacen:</label>
              <input id="almacen_imp" type="text" class="form-control" name="almacen_imp" readonly="">
             </div>
             <div class=" col-xs-6 col-sm-6 col-md-3">
              <label for="moneda_imp">Tipo de Ingreso:</label>
              <input id="tipomov_imp" type="text" class="form-control" name="tipomov_imp" readonly="">
             </div>
             <div class="col-xs-6 col-sm-6 col-md-2">
                <label for="fechamov_imp" >Fecha:</label>
                <input id="fechamov_imp" type="text" class="form-control" name="fechamov_imp" readonly="">
             </div>
             <div class="col-xs-6 col-sm-6 col-md-2">
                <label for="moneda_imp">Moneda:</label>
                <input id="moneda_imp" type="text" class="form-control" name="moneda_imp" readonly="">

             </div>
             <div class="col-xs-12 col-sm-6 col-md-2">
                <label for="fechamov_imp" ># Movimiento:</label>
                <input id="nmov_imp" type="text" class="form-control" name="nmov_imp" readonly="">
             </div>
          </div> <!-- div class="form-group-sm row" PRIMERA FILA -->
          <div class="row"> <!--SEGUNDA FILA-->
                 <div class="col-xs-12 col-lg-6 col-md-6">
                   <label >Proveedor:</label>
                   <input id="proveedor_imp" type="text" class="form-control" name="proveedor_imp" readonly="">
                 </div>
                 <div class="col-xs-4 col-sm-4 col-md-2">
                       <label>Orden de Compra:</label>
                       <input id="ordcomp_imp" type="text" class="form-control" name="ordcomp_imp" readonly="">
                 </div>
                 <div class="col-xs-4 col-sm-4 col-md-2">
                       <label>N° Factura:</label>
                       <input id="nfact_imp" type="text" class="form-control" name="nfact_imp" readonly="">
                 </div>
                 <div class="col-xs-4 col-sm-4 col-md-2">
                       <label>N° Ingreso:</label>
                       <input id="ningalm_imp" type="text" class="form-control" name="ningalm_imp" readonly="">
                 </div>
              </div><!-- div class="form-group-sm row" SEGUNDA FILA-->
              <hr>
         <table class="table-striped"
              data-toggle="table"
              data-pagination="true"
              data-search="true"
              id="tingresosdetalle">
          </table>

          <div class="col-md-6 col-xs-12 pull-right" style="padding: 0px">
            <div class = "input-group col-md-12 col-xs-12">
              <span class = "input-group-addon">$</span>
              <!--mostrar el total de dolares-->
              <input type = "text" class="form-control form-control-sm text-right tiponumerico" id="totalsusdetalle" disabled="">
              <span class = "input-group-addon">Bs</span>
              <!--mostrar el total bolivivanos-->
              <input type = "text" class="form-control form-control-sm text-right tiponumerico" id="totalbsdetalle" disabled="">
             </div>
          </div>
          <div class="row">
                <div class="col-xs-12 col-md-12">
                  <!--insertar costo de articulo a ingresar-->
                  <label for="observaciones_imp">Observaciones:</label>
                  <input type="text" class="form-control" id="obs_imp" name="obs_imp"/> 
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
