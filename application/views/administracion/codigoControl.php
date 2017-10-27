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
		      		<input class="form-control" type="number" id="idAuto" value="262401700038715">
          		</div>
          		
          		<div class="col-xs-12 col-sm-3 col-md-3">
          			NIT:<br>
		      		<input class="form-control" type="number" id="idNITprincipal" value="1000991026">
          		</div>
          		<div class="col-xs-12 col-sm-6 col-md-6">
          			Llave:<br>
		      		<input class="form-control" type="text" id="idLlave" value="MLX5E7YBFGWv(3)zU[8#bF)w4(Bv]iR@UaH)5K47+3CTVQR%J(]TEM*zn%Xj8D6H"><br>	
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

		   <button class="btn btn-primary" id="PageRefresh">Limpiar</button>
       	   <script type="text/javascript">
		    $('#PageRefresh').click(function() {
				location.reload();
			});
		   </script>

      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>

<script>
        $( document ).ready(function() {
        console.log( "ready!" );
            $("#_save").on('click', function(){
            console.log("Saving form data...")
            var autor    = $( "#idAuto" ).val()
            var nFactura = $( "#idFac" ).val()
            var idNIT    = $( "#idNit" ).val()
            var fecha    = $( "#idFecha" ).val()
            var monto    = $( "#idMonto" ).val()
            var llave    = $( "#idLlave" ).val()
            var nitCasa  = $( "#idNITprincipal" ).val()
            /*console.log(autor)
            console.log(nFactura)
            console.log(idNIT)
            console.log(idNIT)
            console.log(fecha)
            console.log(llave)*/
            //console.log(nitCasa)
            //console.log(fecha)
            var gestion = fecha.substring(0, 4);
            var mes     = fecha.substring(5, 7);
            var dia     = fecha.substring(8, 11);

            //console.log(gestion)
            //console.log(mes)
            //console.log(dia)
            fecha = gestion + mes + dia;
            //console.log(fecha)



            codigo = generateControlCode(
                        autor,
                        nFactura,
                        idNIT,
                        fecha,
                        monto,
                        llave
                     );
            //console.log(fecha)
            $('h3').html("El codigo de control es:")
            $('h2').html(codigo)
            var gestion = fecha.substring(0, 4);
            var mes     = fecha.substring(4, 6);
            var dia     = fecha.substring(6, 8);

            //console.log(gestion)
            //console.log(mes)
            //console.log(dia)
            var codigoqr = (nitCasa + "|" + nFactura + "|" + autor + "|" + dia + "/" + mes + "/" + gestion + "|" + monto+ "|" + monto +"|" + codigo +"|" + idNIT + "|0|0|0|0");
            console.log(codigoqr)

            $("#micapa").html(codigoqr);
            $("#qr").html(codigoqr);
             new QRCode(document.getElementById("qrcodeimg"), codigoqr);

            });

          
        });

    </script>