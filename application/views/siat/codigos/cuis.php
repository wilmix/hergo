<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <div class="forPrint col-md-3">
            <?php
            

            ?>
        </div>
      </div>
      <div class="box-body">
        <table id="table" class="table table-hover table-striped table-sm" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div>
   <!-- /.class="col-xs-12" -->

   <!-- Modal -->
  <div id="pedidoModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-95">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <div class="col-md-4">
              <img  src="<?php echo base_url("images/hergo.jpeg") ?>" alt="hergo" width="200" height="50">
            </div>
            <div class="col-md-4" class="text-center">
              <h2 class="modal-title text-center">
                <span>SOLICITUD DE IMPORTACION </span>
              </h2>
            </div>
            <div class="col-md-4">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        </div>
        <div class="modal-body">
          <div class="row">

          </div>

        </div>
        <div class="modal-footer">

          <div class="col-md-12">
            <blockquote class="text-left">

            </blockquote>
          </div>

          <template>
            <!-- <button type="button" class="btn btn-success" @click="aprobar">Aprobar</button> -->
          </template>
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
        </div>
      </div>
    </div>
  </div>

</div> <!-- /.class="row" -->



