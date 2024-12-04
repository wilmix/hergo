<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">

      <input id="verCPP" type="hidden" value="<?= $verCPP ?>">
          <div class="text-center">
            <h2>SALDOS RESUMEN</h2>
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

<script>
  var verCPP = <?= json_encode($verCPP) ?>;
</script>