$(document).ready(function(){
    var n=$("#numpago").val();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Pagos/retornarEdicion'), //******controlador
        dataType: "json",
        data: {n:n},
    }).done(function(res){
        agregarPagos(res)
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
        quitarcargando();
        swal({
            title: 'Error',
            text: "Intente nuevamente",
            type: 'error', 
            showCancelButton: false,
            allowOutsideClick: false,  
        }).then(
        function(result) {   
            
      
        });
    });
})
function agregarPagos(datos)
{
    $.each(datos,function(index,value){
        var row=value;
        num=Math.round(row.pagado * 100) / 100
        /***/
        row.saldoPago=parseFloat(num.toFixed(2));        
        row.pagar=row.pagado;
        row.saldoNuevo=0;        
        vmPago.agregarPago(row)        
    })
}