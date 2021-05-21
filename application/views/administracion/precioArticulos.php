<div  id="app" class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
       <!--  <button class="btn btn-success pull-right" id="excel" data-toggle="tooltip" title="Excel"><i class="far fa-file-excel"></i> Excel</button>
        <button onclick="window.print();" class="btn btn-primary pull-right" ><i class="fa fa-print"> </i> Imprimir</button> -->
          <div class="text-center">
            <h2>PRECIO ARTICULOS</h2>
            <!-- <button id="button">Row count</button>
          </div> -->
          <table id="tabla" class="table table-hover display compact" style="width:100%">
          </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->

  <!-- Modal imagen -->
  <div class="modal fade" id="prev_imagen" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body text-center" id="imagen_max">
          <img class="maximizada" src="<?= base_url("/assets/img_articulos/ninguno.png") ?>">
        </div>
      </div>
    </div>
  </div>
  <div id="modalPrecio" class="modal fade" role="dialog">
    <div class="modal-dialog modal-65">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <div class="col-md-12">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="col-md-12" class="text-center">
              <h2 class="modal-title text-center">
                <span>EDITAR PRECIO</span>
              </h2>
              <h3 class="modal-title text-center">
              {{ subtitle }}
              </h3>
            </div>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4 text-center">
              <p>
                <strong>Costo $u$: ({{antCosto}}) </strong>
                <input v-model="costo"  @change="precioSugerido" type="text" >
              </p>
              <p>
                <strong>% Utilidad: ({{antPorcentaje}}) </strong>
                <input v-model="porcentaje"   @change="precioSugerido" type="text">
              </p>
            </div>
            <div class="col-md-4 text-center">
              <p>
                <strong>Sugerido $u$: </strong>
                <p><span v-text="sugeridoDolares"></span></p>
              </p>
              <p>
                <strong>Sugerido BOB: </strong>
                <p><span v-text="sugeridoBOB"></span></p>
              </p>
            </div>
            <div class="col-md-4 text-center">
              <p>
                <strong>Precio $u$: ({{antPrecioDolares}}) </strong>
                <input v-model="precioDolares" type="number">
              </p>
              <p>
                <strong>Precio BOB: ({{antPrecioBol}}) </strong>
                <input v-model="precioBol" type="number">
              </p>
            </div>
            
          </div>
        </div>
        <div class="modal-footer">
          <div class="col-md-12 text-center">
            <button type="button" class="btn btn-primary" @click="editar">Editar</button>
          </div>
        </div>
      </div>
    </div>
< /div>
</div>


