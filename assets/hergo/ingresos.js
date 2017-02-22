$(document).ready(function(){
	retornarTablaIngresos()
})
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
	id=fila.idIngresos
	datos={id:id}
	retornarajax(base_url("index.php/ingresos/mostrarDetalle"),datos,function(data)
	{
		estado=validarresultado_ajax(data);
		if(estado)
		{	
			console.log(data.respuesta)
			mostrarDetalle(data.respuesta);
			var moneda=(fila.moneda==1)?"Bs. ":"Usd. "
			$("#monedaingreso").html(moneda)
			$("#totaldetalle").html(fila.total)
			$("#modalIgresoDetalle").modal("show");
		}
	})	
}
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
                sortable:true,
            },
            {
                field:'punitario',
                title:"Costo",
                sortable:true,
            },
            {
                field:'total',
                title:"total",
                sortable:true,
            }]
        });
}