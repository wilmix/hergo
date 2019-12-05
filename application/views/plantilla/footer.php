<!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <strong>Sistema Diseñado por Willy Salas</strong> 
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2019 <a href="https://hergo.com.bo/">Hergo Ltda.</a></strong> 
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<!-- CARGANDO - MODAL ERROR CORRECTO -->
<div class="espera" id="cargando" style="display: none">
    <div class="centro">
        <img src="<?php echo base_url("assets/imagenes/loading_2.gif") ?> ">
    </div>
</div>
<div id="mensaje" style="display:none">
    <div class="centro">
        <div class="bloque">
            <div class="textocentrado">
           <!--     Correcto <span class="ok"><img src="<?php //echo base_url("assets/imagenes/ok.jpg") ?> "></span>-->
            </div>
        </div>
    </div>
</div>


<!--MODAL-->
<div id="modal_error" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modaltamanio">
        
           <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>       
                <strong>ERROR!</strong><span class="mensaje_error"></span>
            </div>        
    </div>
</div>
<div id="modal_ok" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modaltamanio">
        
           <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong>Correcto!</strong><span class="mensaje_ok"></span>
            </div>        
    </div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.20/af-2.3.4/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sl-1.3.1/datatables.min.js"></script>

</body>
</html>

