$(document).ready(function(){
    retornarTablaIngresos()
})


var glob_tipoCambio=6.96;
var glob_agregar=false;
function retornarTablaIngresos()
{
    $.ajax({
        type:"POST",
        url: base_url('index.php/ingresos/mostrarIngresos'),
        dataType: "json",
        data: {},
    }).done(function(res){

        $("#tingresos").bootstrapTable('destroy');
        $("#tingresos").bootstrapTable({
            
            data:res,           
            striped:true,
            pagination:true,           
            clickToSelect:true,
            search:true,         
            columns:[
            {   
                field: 'n',            
                title: 'N',
                align: 'center',
                sortable:true,
            },  
            {   
                field: 'sigla',            
                title: 'Tipo',
                align: 'center',
                sortable:true,
            },         
            {
                field:'fechamov',
                title:"Fecha",
                sortable:true,
            },
            {
                field:'nombreproveedor',
                title:"Proveedor",
                sortable:true,
            },
            {
                field:'nfact',
                title:"Factura",

                sortable:true,
            },
            {
                field:'total',
                title:"Total",
                align: 'right',
                sortable:true,
            },
            {
                field:"estado",
                title:"Estado",
                sortable:true,
                formatter: operateFormatter2,
                align: 'center'
            },
            
            
            {               
                title: 'Acciones',
                align: 'center',
                events: operateEvents,
                formatter: operateFormatter
            }]
        });

        $("#tarticulo").bootstrapTable('hideLoading');
        $("#tarticulo").bootstrapTable('resetView');
        
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
    //$("body").css("padding-right","0px");
}
function operateFormatter(value, row, index) 
{
    return [
        '<button type="button" class="btn btn-default verIngreso" aria-label="Right Align">',
	    '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
	    '<button type="button" class="btn btn-default editarIngreso" aria-label="Right Align">',
	    '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>',
	    '<button type="button" class="btn btn-default imprimirIngreso" aria-label="Right Align">',
	    '<span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>'
    ].join('');
}
function operateFormatter2(value, row, index) 
{
	$ret=''
	if(value==0)
		$ret='<span class="label label-danger">PENDIENTE</span>';
	if(value==1)
		$ret='<span class="label label-success">APROBADO</span>';
    return ($ret);
}
/***********Eventos*************/
window.operateEvents = {
    'click .verIngreso': function (e, value, row, index) {
     // fila=JSON.stringify(row);
    	verdetalle(row)       
    },
    'click .editarIngreso': function (e, value, row, index) {
      alert(JSON.stringify(row));
    },
    'click .imprimirIngreso': function (e, value, row, index) {
     //alert(JSON.stringify(row));
    }
};
function verdetalle(fila)
{
    console.log(fila)
	id=fila.idIngresos
	datos={id:id}
	retornarajax(base_url("index.php/ingresos/mostrarDetalle"),datos,function(data)
	{
		estado=validarresultado_ajax(data);
		if(estado)
		{	
			
			mostrarDetalle(data.respuesta);
			//console.log(glob_tipoCambio)
            var sus=fila.total;
            sus=sus.replace(',','')
            sus=sus.replace(',','')
            sus=sus.replace(',','')
            sus=sus.replace(',','')
            sus=sus.replace(',','')
            sus=sus.replace(',','')
            sus=sus/glob_tipoCambio;
            sus=parseFloat(sus)
            console.log(sus)
            sus=sus.toLocaleString()

            $("#almacen_imp").val(fila.almacen)
            $("#tipomov_imp").val(fila.tipomov)
            $("#fechamov_imp").val(fila.fechamov)
            $("#moneda_imp").val(fila.monedasigla)
            $("#nmov_imp").val(fila.n)
            $("#proveedor_imp").val(fila.nombreproveedor)
            $("#ordcomp_imp").val(fila.ordcomp)
            $("#nfact_imp").val(fila.nfact)
            $("#ningalm_imp").val(fila.ningalm)
            /***pendienteaprobado***/
            var boton="";
            if(fila.estado=="0")
                boton='<button type="button" class="btn btn-success" datastd="'+fila.idIngresos+'" id="btnaprobado">Aprobado</button>';               
            else
                boton='<button type="button" class="btn btn-danger" datastd="'+fila.idIngresos+'" id="btnpendiente">Pendiente</button>';
                
            $("#pendienteaprobado").html(boton)
			$("#totalsusdetalle").val(sus)
			$("#totalbsdetalle").val(fila.total)
			$("#modalIgresoDetalle").modal("show");
		}
	})	
}
$(document).on("click","#btnaprobado",function(){
    id=$(this).attr("datastd");
    datos={d:1,id:id}
    retornarajax(base_url("index.php/ingresos/revisarStd"),datos,function(data)
    {
        estado=validarresultado_ajax(data);
        if(estado)
        {   
            retornarTablaIngresos()
            $("#modalIgresoDetalle").modal("hide");
        }
    })  
})
$(document).on("click","#btnpendiente",function(){
    id=$(this).attr("datastd");
    datos={d:0,id:id}
    retornarajax(base_url("index.php/ingresos/revisarStd"),datos,function(data)
    {
        estado=validarresultado_ajax(data);
        if(estado)
        {   
            retornarTablaIngresos()
            $("#modalIgresoDetalle").modal("hide");
        }
    })  
})
function mostrarDetalle(res)
{
	$("#tingresosdetalle").bootstrapTable('destroy');
        $("#tingresosdetalle").bootstrapTable({
            
            data:res,           
            striped:true,
            pagination:true,           
            clickToSelect:true,
            search:true,         
            columns:[
            {   
                field: 'CodigoArticulo',            
                title: 'Código',
                align: 'center',
                sortable:true,
            },  
            {   
                field: 'Descripcion',            
                title: 'Descripcion',
                sortable:true,
            },         
            {
                field:'cantidad',
                title:"Cantidad",
                align: 'right',
                sortable:true,
            },
            {
                field:'punitario',
                title:"Costo",
                align: 'right',
                sortable:true,
            },
            {
                field:'total',
                title:"total",
                align: 'right',
                sortable:true,
            },
            ]
        });
}


 $( function() {

    $("#articulo_imp").autocomplete(
    {      
      minLength: 2,
      source: function (request, response) {        
        $("#cargandocodigo").show(150)
        $("#Descripcion_imp").val('');
        $("#codigocorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
        glob_agregar=false;
        $.ajax({
            url: base_url("index.php/ingresos/retornararticulos"),
            dataType: "json",
            data: {
                b: request.term
            },
            success: function(data) {
               response(data);    
               $("#cargandocodigo").hide(150)
              
            }
          });        
    }, 
     /* focus: function( event, ui ) {
          //$(".solicitanuevo").val( ui.item.nombre );//si se instancio como indice un valor en este caso array("nombre"=> "valor")
          $(".solicitanuevo").val( ui.item[0] );
         // carregarDados();
          return false;
      },*/
      select: function( event, ui ) {
      
          $("#articulo_imp").val( ui.item.CodigoArticulo);
          $("#Descripcion_imp").val( ui.item.Descripcion);
          $("#unidad_imp").val(ui.item.Unidad);
          $("#codigocorrecto").html('<i class="fa fa-check" style="color:#07bf52" aria-hidden="true"></i>');
          glob_agregar=true;
          return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      
      return $( "<li>" )
        .append( "<a><div>" + item.CodigoArticulo + " </div><div style='color:#615f5f; font-size:10px'>" + item.Descripcion + "</div></a>" )
        .appendTo( ul );
    };
 });
$(document).on("click","#agregar_articulo",function(){
    if(glob_agregar)
    {
        agregarArticulo();
    }
})
$(document).on("click",".eliminarArticulo",function(){    
    $(this).parents("tr").remove()
    calcularTotal()
})
function limpiarArticulo()
{
    $("#articulo_imp").val("")
    $("#Descripcion_imp").val("")
    $("#cantidad_imp").val("")
    $("#punitario_imp").val("")
    glob_agregar=false;
    $("#codigocorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
}
function limpiarCabecera()
{
    $("#ordcomp_imp").val("")
    $("#nfact_imp").val("")
    $("#ningalm_imp").val("")
    $("#obs_imp").val("")
    $("#totalacostosus").val("")
    $("#totalacostobs").val("")
   
}
function limpiarTabla()
{
    $("#tbodyarticulos").find("tr").remove();
}
function calcularTotal()
{
    var totalCosto=0;
    var totales=$(".totalCosto").toArray();
    var total=0;
    var dato=0;
    $.each(totales,function(index, value){
        dato=$(value).html()
        total+=(dato=="")?0:parseFloat(dato)
    })
    total=total.toFixed(2);
    $("#totalacostobs").val(total)
    var totalDolares=total/glob_tipoCambio;
    $("#totalacostosus").val(totalDolares.toFixed(2))

}
function agregarArticulo()
{
    var codigo=$("#articulo_imp").val()
    var descripcion=$("#Descripcion_imp").val()
    var cant=$("#cantidad_imp").val()
    var costo=$("#punitario_imp").val()
    var cant=(cant=="")?0:cant;
    var costo=(costo=="")?0:costo;
    //costo=costo.toFixed(2);
    var total=cant*costo;
    total=total.toFixed(2);
    var articulo='<tr>'+
      '<td><label>'+codigo+'</label></td>'+
      '<td><label>'+descripcion+'</label></td>'+
      '<td class="text-right"><label>'+cant+'</label></td>'+
      '<td class="text-right"><label>'+costo+'</label></td>'+
      '<td class="text-right"><label class="totalCosto">'+total+'</label></td>'+
      '<td class="text-center"><button type="button" class="btn btn-default eliminarArticulo" aria-label="Left Align">'+
      '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'+
    '</td>';
    $("#tbodyarticulos").append(articulo)
    calcularTotal()
    limpiarArticulo();
}
function guardarmovimiento()
{     
    var valuesToSubmit = $("#form_ingresoImportaciones").serialize();
    var tablaaux=tablatoarray();
    if(tablaaux.length>0)
    {
        var tabla=JSON.stringify(tablaaux);

        valuesToSubmit+="&tabla="+tabla;    
        retornarajax(base_url("index.php/ingresos/guardarmovimiento"),valuesToSubmit,function(data)
        {
            estado=validarresultado_ajax(data);
            if(estado)
            {               
                if(data.respuesta)
                {
                    
                    $("#modalIgresoDetalle").modal("hide");
                    limpiarArticulo();
                    limpiarCabecera();
                    limpiarTabla();
                    $(".mensaje_ok").html("Datos almacenados correctamente");
                    $("#modal_ok").modal("show");
                }
                else
                {
                    $(".mensaje_error").html("Error al almacenar los datos, intente nuevamente");
                    $("#modal_error").modal("show");
                }
                
            }
        })      
    }
    else
    {
        alert("no se tiene datos en la tabla para guardar")
    }
}
function tablatoarray()
{
    var tabla=new Array()
    var filas=$("#tbodyarticulos").find("tr").toArray()
    var datos=""
    $.each(filas,function(index,value){
        datos=$(value).find("label").toArray()
        tabla.push(Array($(datos[0]).html(),$(datos[1]).html(),$(datos[2]).html(),$(datos[3]).html(),$(datos[4]).html()))
        //console.log(datos);
    })
    return(tabla)
    //console.log(filas)
}
$(document).on("click","#guardarMovimiento",function(){
    guardarmovimiento();
})