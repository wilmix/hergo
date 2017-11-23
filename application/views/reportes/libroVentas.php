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
              <option value="">TODOS</option>
            </select>
          </div>
          <table 
            id="tablaLibroVentas" 
            data-toolbar="#toolbar2"
            data-toggle="table">
          </table>
          <div class="form-group row">
            <div class="col-md-6 col-xs-12">
              <div class = "input-group col-md-12 col-xs-12">
               <span class = "input-group-addon">M</span>
               <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalFacturaSus" value="">
               <span class = "input-group-addon" >C</span>
               <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalFacturaBs" value="">
                <span class = "input-group-addon" >V</span>
                <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalFacturaBs" value="">
                <span class = "input-group-addon" >A</span>
                <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalFacturaBs" value="">
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class = "input-group col-md-12 col-xs-12">
               <span class = "input-group-addon">Total</span>
               <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalFacturaSus" value="">
               <span class = "input-group-addon" >Debito</span>
               <input type = "text" class="form-control form-control-sm text-right tiponumerico" disabled id="totalFacturaBs" value="">
              </div>
          </div>
           </div><!--row-->
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>

