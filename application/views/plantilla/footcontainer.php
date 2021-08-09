	</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- food container -->
    <?php
      if (isset($tipodocumento) && isset($tipocliente)) {
        $this->load->view('modals/cliente-modal');
      }
      $this->load->view('modals/tipoCambio-modal');
      $this->load->view('facturas/vistaPrevia');
    ?>  