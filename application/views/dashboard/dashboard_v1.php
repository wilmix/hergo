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
                <div class="col-md-12">
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
            <div class="box-header with-border" name="notasPendientes">
              <!-- <h3 class="box-title">Mis Notas de Entrega Pendientes de Facturación</h3> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                    <div class="chart">
                      <!-- <table id="notasPendietes" class="table table-hover table-striped " style="width:100%"></table>  --> 
                    </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <div class="box-footer clearfix">
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
          <!-- /.col -->
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Negativos</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="chart">
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
              <select   class="btn btn-sm btn-primary btn-flat pull-left" data-style="btn-primary" id="almacen_filtro" name="almacen_filtro">
                <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
                <?php foreach ($almacen->result_array() as $fila): ?>
                <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Calendario</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="text-center googleCalendar">
                  <iframe src="https://calendar.google.com/calendar/embed?height=500&amp;wkst=2&amp;bgcolor=%23ffffff&amp;ctz=America%2FLa_Paz&amp;src=Zmcxajloa3U0MGM4ZTNnM3VvbnJuZ3I5YzhAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&amp;src=ZXMuYm8jaG9saWRheUBncm91cC52LmNhbGVuZGFyLmdvb2dsZS5jb20&amp;color=%23336699&amp;color=%23227F63&amp;showTitle=0&amp;showNav=1&amp;showDate=1&amp;showTabs=1&amp;showCalendars=0&amp;showTz=0" style="border-width:0" width="790" height="500" frameborder="0" scrolling="no"></iframe>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <div class="box-footer clearfix">

            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>  