<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div id="toolbar2" class="form-inline">
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
              <option value="" selected="selected">TODOS</option>
            </select>
            <button  type="button" class="btn btn-primary btn-sm" id="refresh">
                <span>
                <i class="fa fa-share-square"></i>
                </span>
            </button>
          </div>
          <div class="text-center">
            <h2>REPORTE VENTAS 3M - <span id="tituloAlmacen"></span></h2>
            <h4 id="ragoFecha"></h4>
          </div>
        
          <table 
            id="tablaTMventas" 
            data-toolbar="#toolbar2"
            data-toggle="table">
          </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>

