$( document ).ready(function() {
    console.log( "ready!" );
        $("#_save").on('click', function(){
        console.log("Saving form data...")
        let autor    = $( "#idAuto" ).val()
        let nFactura = $( "#idFac" ).val()
        let idNIT    = $( "#idNit" ).val()
        let fecha    = $( "#idFecha" ).val()
        let monto    = $( "#idMonto" ).val()
        let llave    = $( "#idLlave" ).val()
        let nitCasa  = $( "#idNITprincipal" ).val()
        /*console.log(autor)
        console.log(nFactura)
        console.log(idNIT)
        console.log(idNIT)
        console.log(fecha)
        console.log(llave)*/
        //console.log(nitCasa)
        //console.log(fecha)
        let gestion = fecha.substring(0, 4);
        let mes     = fecha.substring(5, 7);
        let dia     = fecha.substring(8, 11);

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
        gestion = fecha.substring(0, 4);
        mes     = fecha.substring(4, 6);
        dia     = fecha.substring(6, 8);

        //console.log(gestion)
        //console.log(mes)
        //console.log(dia)
        let codigoqr = (nitCasa + "|" + nFactura + "|" + autor + "|" + dia + "/" + mes + "/" + gestion + "|" + monto+ "|" + monto +"|" + codigo +"|" + idNIT + "|0|0|0|0");
        console.log(codigoqr)

        $("#micapa").html(codigoqr);
        $("#qr").html(codigoqr);
         new QRCode(document.getElementById("qrcodeimg"), codigoqr);

        });

      
    });