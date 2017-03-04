$(document).ready(function(){
	retornarTablaIngresos()
    cargarArticulosAux()
})


var glob_tipoCambio=6.96
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
var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];
var glob_articulos="";
function cargarArticulosAux()
{
    $.ajax({
         url: base_url("index.php/ingresos/retornararticulos"),
          dataType: "json",
          success: function(data) {
              //de objeto a array
           
            glob_articulos=data; 
            console.log(glob_articulos)
          }
      });
}
$(document).on("keyup","#articulo_imp",function(){
  //  console.log(glob_articulos)
})
 $( function() {

    $("#articulo_imp").autocomplete(
    {      
     // minLength: 2,
      source: function (request, response) {
           response($.map(glob_articulos, function (value, key) {
                return {
                    label: value.CodigoArticulo,
                    value: value.Descripcion
                }
            }));
        
    }, 
     /* focus: function( event, ui ) {
          //$(".solicitanuevo").val( ui.item.nombre );//si se instancio como indice un valor en este caso array("nombre"=> "valor")
          $(".solicitanuevo").val( ui.item[0] );
         // carregarDados();
          return false;
      },*/
      select: function( event, ui ) {
          //$(".solicitanuevo").val( ui.item.nombre );
        //  console.log(ui)
          $("#articulo_imp").val( ui.item.label);
          $("#Descripcion_imp").val( ui.item.value);
          
          return false;
      }
    })
    /*.autocomplete( "instance" )._renderItem = function( ul, item ) {
      
      return $( "<li>" )
        .append( "<a><div>" + item.CodigoArticulo + " </div><div class='mailage'>" + item.Descripcion + "</div></a>" )
        .appendTo( ul );
    };*/
 });