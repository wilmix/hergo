 <!-- Content Header (Page header) -->
 <section class="content-header">
      <h1>
        Dashboard
        <small>Version 1.0</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
          <!-- Info boxes -->
          <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fas fa-sign-in-alt"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Ingresos</span>
              <span class="info-box-number" id="ingresosHoy"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fas fa-exclamation-circle"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Negativos</span>
              <span class="info-box-number" id="negativos"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fas fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Ventas</span>
              <span class="info-box-number" id="ventasHoy"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fas fa-tasks"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Articulos</span>
              <span class="info-box-number" id="activos"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Ventas</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12 hidden-xs">
                  <div class="chart hidden-xs">
                    <!-- Sales Chart Canvas -->
                    <canvas id="ventas" style="height: 180px; width: 645px;" width="645" height="180"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->


            <div class="box-footer">
              <div class="row">
                <div class="col-sm-4 col-xs-12">
                  <div class="description-block border-right">
                    <h5 class="description-header" id="ventaCaja"></h5>
                    <span class="description-text">VENTA CAJA</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-xs-12">
                  <div class="description-block border-right">
                    <h5 class="description-header" id="notaEntrega"></h5>
                    <span class="description-text">NOTA ENTREGA</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-xs-12">
                  <div class="description-block">
                    <h5 class="description-header" id="cantidad"></h5>
                    <span class="description-text">ARTICULOS</span>
                  </div>
                  <!-- /.description-block -->
                </div>

                <hr>
                <br>
                <hr>
                
              </div>
                  <div>
                      <table id="table_id" class="table table-bordered table-hover" style="width:100%">
                      </table>
                  </div>
              <!-- /.row -->
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>


      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Negativos</h3>

              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12 hidden-xs">
                  <div class="chart hidden-xs">
                    

                    <table id="negatives" class="table table-hover table-striped " style="width:100%"></table>  
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <div class="box-footer clearfix">
                    <select   class="btn btn-sm btn-info btn-flat pull-left" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                        <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                        <?php foreach ($almacen->result_array() as $fila): ?>
                        <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                        <?php endforeach ?>
                        <option value="" >TODOS</option>
                    </select>
              
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>

      













      <!--

      <div class="box-body">
        <div class="row">
          <div class="col-md-8">
            <div class="chart-container">
              <canvas id="ventas" style="height: 200px; width: 645px;" width="645" height="200" ></canvas>
            </div>
          </div>
        </div>
      </div> -->
      
        

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->