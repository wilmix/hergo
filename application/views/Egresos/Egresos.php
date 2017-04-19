<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <div class="text-right" class="btn-group" id="toolbar">

          <a class="btn btn-primary btn-sm" href="#" target="_blank"><i class="fa fa-plus-circle fa-lg"></i>  VentaCaja</a>

          <a class="btn btn-primary btn-sm" href="<?php echo base_url("egresos/Notaentrega") ?>" target="_blank"><i class="fa fa-plus-circle fa-lg"></i>  NotaEntrega</a>

          <a class="btn btn-primary btn-sm" href="#" target="_blank"><i class="fa fa-plus-circle fa-lg"></i>  BajaProducto</a>

          </div>

            <div  id="toolbar2" class="form-inline">

              <button  type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
               <span>
                 <i class="fa fa-calendar"></i> Fecha
               </span>
                <i class="fa fa-caret-down"></i>
             </button>


              <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                  <option>ALMACEN</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>

              </select>
              
              <select class="btn btn-primary btn-sm" name="tipo_filtro" id="tipo_filtro">
                  <option>TIPOMOV</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                 
              </select>
           </div>





          <table 
            id="tegresos"
            data-toolbar="#toolbar2">
          </table>


      </div>

      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
