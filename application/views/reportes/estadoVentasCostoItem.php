<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div> <!-- pull-left -->
            <img src="<?php echo base_url("images/hergo.jpeg") ?>" alt="logoHergo" width="150" height="45" style="position: absolute;">
          </div>
          <div class="btn-group pull-right">
            <button class="btn btn-success pull-right" id="export" data-toggle="tooltip" title="Excel"><i class="far fa-file-excel"> </i> Excel </button>
            <!-- <button id="pdf" class="btn btn-danger" ><i class="far fa-file-pdf"> </i> PDF</button> -->
            <button onclick="window.print();" class="btn btn-primary pull-right" ><i class="fa fa-print"> </i> Imprimir</button>
          </div>
          <hr>
          <div id="toolbar2" class="form-inline">
                <button  type="button" class="btn btn-primary btn-sm" id="fechapersonalizada">
                  <span>
                    <i class="fa fa-calendar"></i> Fecha
                  </span>
                    <i class="fa fa-caret-down"></i>
                </button>
                <select   class="btn btn-primary btn-sm" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                    <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                    <?php foreach ($almacen->result_array() as $fila): ?>
                      <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                    <?php endforeach ?>
                    <!-- <option value="">TODOS</option> -->
                </select>

                <select id="moneda" class="btn btn-primary btn-sm">
                    <option value="0">BOB</option>
                    <option value="1">$U$</option>
                </select>

                <button  type="button" class="btn btn-primary btn-sm" id="refresh">
                    <span>
                    <i class="fa fa-share-square"></i>
                    </span>
                </button>

                
          </div>
          <div class="text-center">
            <h2>ESTADO DE VENTAS Y COSTOS POR ITEM</h2>
            <h3 id="tituloReporte"></h3>
            <h4 id="ragoFecha"></h4>
            <h4 id="monedaTitulo"></h4>
          </div>
          <table 
            id="estadoVentasCostos" 
            data-toolbar="#toolbar2"
            data-toggle="table" >
          </table>
          
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
