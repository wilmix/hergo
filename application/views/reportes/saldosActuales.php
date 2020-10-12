<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
       <!--  <button class="btn btn-success pull-right" id="excel" data-toggle="tooltip" title="Excel"><i class="far fa-file-excel"></i> Excel</button>
        <button onclick="window.print();" class="btn btn-primary pull-right" ><i class="fa fa-print"> </i> Imprimir</button> -->
          <div class="text-center">
            <h2>SALDOS ACTUALES</h2>
          </div>
          <table id="tablaSaldosActuales" class="table table-hover display compact" style="width:100%">
          </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
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