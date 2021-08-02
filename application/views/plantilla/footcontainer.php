	</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- food container -->
    <?php
      if (isset($tipodocumento)) {
        $this->load->view('modals/cliente-modal');
      }
      $this->load->view('modals/tipoCambio-modal');
    ?>  