<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
          <div>
              <h1>Prueba de Codigo de Control</h1>
          </div>      
          <div>
          <form action="#" method="get" id="myForm">
          	<div class="row">
          		<div class="col-xs-12 col-sm-3 col-md-3">
          			N° de Autorización:<br>
		      		<input class="form-control" type="number" id="idAuto" value="262401800035215">
          		</div>
          		
          		<div class="col-xs-12 col-sm-3 col-md-3">
          			NIT:<br>
		      		<input class="form-control" type="number" id="idNITprincipal" value="1000991026">
          		</div>
          		<div class="col-xs-12 col-sm-6 col-md-6">
          			Llave:<br>
		      		<input class="form-control" type="text" id="idLlave" value="jwXVT2(t]7p{\#GRxXBNCDwy@3yFWtwK=9dpZc5R6KW$hM-L%-wWtKBraU#DuH=I"><br>	
          		</div>
          		
          	</div>
          	 <div class="row">
          		<div class="col-xs-12 col-sm-3 col-md-3">
          			N° de Factura:<br>
		      		<input class="form-control" type="number" id="idFac">
          		</div>
          		<div class="col-xs-12 col-sm-3 col-md-3">
          			 Fecha:<br>
		      		<input class="form-control" type="date" id="idFecha" value="">	
          		</div>
          		<div class="col-xs-12 col-sm-3 col-md-3">
          			Monto:<br>
		      		<input class="form-control"  type="number" step="any" id="idMonto">
          		</div>
          		<div class="col-xs-12 col-sm-3 col-md-3">
          			NIT:<br>
		      		<input class="form-control" type="number" id="idNit">
          		</div>
          	</div>
		     
		      <br>
		      <button id="_save" type="submit" class="btn btn-primary">Generar</button>
		    </form>
          </div>
          
                       
          
          <h3></h3> <h2></h2><p></p>
          <br>
		   <div id="qrcode"></div>
		   <div id="qrcodeimg"></div>
		   <br>
		   <div id="micapa"></div><br>

		   <button class="btn btn-primary" id="PageRefresh" onclick="location.reload()">Limpiar</button>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
