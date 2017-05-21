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

          <table
            id="tegresosnoFac"
            data-toolbar="#toolbar2"
            data-height="250">
          </table>

      <!-- /.box-body -->
      </div>
    <!-- /.box -->
    </div>
  <!-- /.col -->
  </div>
</div>
<!-- /.class="row" -->



