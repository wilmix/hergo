 <section class="content-header">
      <h1>
        Ingresos
        <small>Consulta</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url("ingresos") ?>"><i class="fa fa-dashboard"></i> Ingresos</a></li>
        <li class="active">Consultas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
           
          <div class="text-right" class="btn-group" id="toolbar">

            <a class="btn btn-primary btn-sm" href="<?php echo base_url("Ingresos/Compraslocales") ?>" target="_blank"><i class="fa fa-plus-circle fa-lg"></i>  ComprasLocales</a>

            <a class="btn btn-primary btn-sm" href="<?php echo base_url("Ingresos/Importaciones") ?>" target="_blank"><i class="fa fa-plus-circle fa-lg"></i>  IngresoImportaciones</a>

            <a class="btn btn-primary btn-sm" href="<?php echo base_url("Ingresos/anulacionEgresos") ?>") target="_blank"><i class="fa fa-plus-circle fa-lg"></i>  Anulacion Egresos</a>

          </div>

          <div  id="toolbar2" class="form-inline">
             <button  type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
               <span>
                 <i class="fa fa-calendar"></i> Fecha
               </span>
                <i class="fa fa-caret-down"></i>
             </button>

              <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                <?php foreach ($almacen->result_array() as $fila): ?>
                <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                <?php endforeach ?>
                <option value="">TODOS</option>
              </select>
              
              <select class="btn btn-primary btn-sm" name="tipo_filtro" id="tipo_filtro">
                <?php foreach ($tipoingreso->result_array() as $fila): ?>
                  <option value="<?= $fila['id'] ?>" <?= $fila['id']==2?"selected":""  ?>><?= strtoupper($fila['tipomov']) ?></option>
                <?php endforeach ?>
                <option value="">TODOS</option>
                 
              </select>
           </div>

          <table 
            id="tingresos" 
            data-toolbar="#toolbar2"
            data-toggle="table"
            data-height="550">
          </table>


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

        <h4 class="modal-title">Detalle de ingresos 
        
        </h4>

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
              id="tingresosdetalle">
          </table>
          <!--TOTALES SISTEMA-->
          <div class="col-md-6 col-xs-12 pull-right" style="padding: 0px">
            <div class = "input-group col-md-12 col-xs-12">
              <span class = "input-group-addon">Bs FAC</span>
              <!--mostrar el total bolivianos factura-->
              <input type = "text" class="form-control form-control-sm text-right tiponumerico" id="" disabled="">
              <span class = "input-group-addon">Bs SIS</span>
              <!--mostrar el total bolivivanos sistema-->
              <input type = "text" class="form-control form-control-sm text-right tiponumerico" id="totalbsdetalle" disabled="">
             </div>
          </div>
          <hr>

          <!--TOTALES DOCUMENTO O FACTURA-->

          <div class="col-md-6 col-xs-12 pull-right" style="padding: 0px">
            <div class = "input-group col-md-12 col-xs-12">
              <span class = "input-group-addon">$ FAC</span>
              <!--mostrar el total dolares factura-->
              <input type = "text" class="form-control form-control-sm text-right tiponumerico" id="" disabled="">
              <span class = "input-group-addon">$ SIS</span>
              <!--mostrar el total dolares sistema-->
              <input type = "text" class="form-control form-control-sm text-right tiponumerico" id="totalsusdetalle" disabled="">
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
