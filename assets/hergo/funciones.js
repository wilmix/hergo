$(document).ready(function()
{
   /*  $(".tablahergo").DataTable({
         "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "responsive": true,
    });*/
    setTipoCambio();
})
var glob_tipoCambio=6.96;   
function setTipoCambio()
{
     $.ajax({
        type:"POST",
        url: base_url('index.php/facturas/tipoCambio'),
        dataType: "json",
        data: {},
    }).done(function(res){
        console.log(res.tipoCambio);
        glob_tipoCambio=res.tipoCambio;
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });

}
function base_url(complemento)
{
     complemento = (complemento) ? complemento : '';
    var baseurl=$('#baseurl').val();
    return baseurl+complemento;
}
function agregarcargando()
{
    $("#cargando").css("display","block")
}
function quitarcargando()
{
    $("#cargando").css("display","none") 
}
/******************AJAX************************/
/**********************************************/
function retornarajax(url,datos,callback)
    {
        var retornar=new Object();
        $("#cargando").css("display","block")
        
        return $.ajax({
                type:"POST",
                url: url,
                dataType: "json",
                data: datos,
               // processData: false, //UP
              //  contentType: false  //UP
            }).done(function(data){
                var retornar=new Object();      
                datos_retornados="retorno";
                retornar.estado="ok";
                retornar.respuesta=data;
                $("#cargando").css("display","none")        
                if(callback)
                    callback(retornar);
                
                
            }).fail(function( jqxhr, textStatus, error ) {
                var retornar=new Object();      
                var err = textStatus + ", " + error;
                console.log( "Request Failed: " + err );
                if(jqxhr.status===0)
                {
                    errorajax="No existe conexion, veirique su red";
                }
                else if(jqxhr.status==404)
                {
                    errorajax="No se encontro la pagina [404]";
                }
                else if(jqxhr.status==500)
                {
                    errorajax="Error interno del servidor [500]";
                }
                else if (textStatus==='parsererror')
                {
                    errorajax="Requested JSON parse failed, error de sintaxis en retorno json ";
                }
                else if(textStatus==='timeout')
                {
                    errorajax="Error de tiempo de espera";
                }
                else if(textStatus==='abort')
                {
                    errorajax="Solicitud de ajax abortada";
                }
                else
                {
                    errorajax="error desconocido "+ jqxhr.responseText;
                }
                retornar.estado="error";
                retornar.respuesta=errorajax;
                
                if(callback)
                    callback(retornar);
                
            });
    }
    function validarresultado_ajax(resultado)
    {
        if(resultado.estado=="ok")
        {
            return true;
        }
        else
        {
            $(".mensaje_error").html(resultado.respuesta);
            $("#modal_error").modal("show");
            //setTimeout(function(){$("#modal_error").modal("hide");},5000)
            return false;
        }
    }

/**********************************************************/
/**********************************************************/
function resetForm(id)
{
    $(id)[0].reset();
    $(id).bootstrapValidator('resetForm', true);
}
$(document).on("click",".sidebar-toggle",function(){
   
    setTimeout(function(){
       $('table').bootstrapTable('resetWidth');    
    }, 500);
})
$( window ).resize(function() {
    setTimeout(function(){
       $('table').bootstrapTable('resetWidth');    
    }, 500);
});

function formato_fecha(value, row, index)
{
    var fecha = ""
    //console.log(value)
    if((value=="0000-00-00 00:00:00")||(value=="")||(value==null))
        fecha="sin fecha de registro"
    else
        fecha = moment(value,"YYYY/MM/DD HH:mm:ss").format("DD/MM/YYYY HH:mm:ss")
    return [fecha]
}
function formato_fecha_corta(value, row, index)
{
    var fecha = ""
    //console.log(value)
    if((value=="0000-00-00 00:00:00")||(value=="")||(value==null))
        fecha="sin fecha de registro"
    else
        fecha = moment(value,"YYYY/MM/DD HH:mm:ss").format("DD/MM/YYYY")
    return [fecha]
}
function asignarselect(text1,select)
{
    text1=text1.trim()
    $("option",select).filter(function() {
        var aux=$(this).text()
        aux=aux.trim()
        return aux.toUpperCase() == text1.toUpperCase();
    }).prop('selected', true);
}
/*******************formato de numeros***************/
var formatNumber = {

 separador: ",", // separador para los miles
 sepDecimal: '.', // separador para los decimales
 formatear:function (num){
    
  num +='';
  var splitStr = num.split('.');
  var splitLeft = splitStr[0];
  var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
  var regx = /(\d+)(\d{3})/;
  while (regx.test(splitLeft)) {
  splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
  }
  return this.simbol + splitLeft  +splitRight;
 },
 new:function(num, simbol){
  this.simbol = simbol ||'';
  return this.formatear(num);
 }
}
function redondeo(numero, decimales)
{
    var flotante = parseFloat(numero);
    var resultado = Math.round(flotante*Math.pow(10,decimales))/Math.pow(10,decimales);
    return resultado;
}
function mensajeregistrostabla(res,idtabla) //agrega o quita mensaje de registros encontrados
{
    if(Object.keys(res).length<=0) $("tbody td","table"+idtabla).html("No se encontraron registros")        
    else $("tbody","table"+idtabla).show() 
}