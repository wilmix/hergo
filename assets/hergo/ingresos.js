var iniciofecha=moment().subtract(0, 'year').startOf('year')
var finfecha=moment().subtract(0, 'year').endOf('year')

$(document).ready(function(){
     $(".tiponumerico").inputmask({
        alias:"decimal",
        digits:2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask:true
    });

    var start = moment().subtract(0, 'year').startOf('year')
    var end = moment().subtract(0, 'year').endOf('year')
    var actual=moment().subtract(0, 'year').startOf('year')
    var unanterior=moment().subtract(1, 'year').startOf('year')
    var dosanterior=moment().subtract(2, 'year').startOf('year')
    var tresanterior=moment().subtract(3, 'year').startOf('year')

   /* var ractual="Gestion "+actual.format('YYYY')
    var runo="Gestion "+unanterior.format('YYYY')
    var rdos="Gestion "+dosanterior.format('YYYY')
    var rtres="Gestion"+tresanterior.format('YYYY')
   
    var rango={};
    rango[ractual]=[actual,actual];
    rango[runo]=[unanterior,unanterior];
    rango[rdos]=[dosanterior,dosanterior];
    rango[rtres]=[tresanterior,tresanterior];

    jsonrango=JSON.stringify(rango)
    console.log(jsonrango)
*/
    
    $(function() {

        function cb(start, end) {
            $('#fechapersonalizada span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
            iniciofecha=start
            finfecha=end
        }
        moment.locale('es');
        $('#fechapersonalizada').daterangepicker({
            locale: {
                  applyLabel: 'Aplicar',
                  cancelLabel: 'Cancelar',
                  customRangeLabel: 'Personalizado',
                },
            startDate: start,
            endDate: end,
            //ranges:jsonrango
            ranges: {
               'Gestion Actual': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
               "Hace un Año": [moment().subtract(1, 'year').startOf('year'),moment().subtract(1, 'year').endOf('year')],
               'Hace dos Años': [moment().subtract(2, 'year').startOf('year'),moment().subtract(2, 'year').endOf('year')],
               'Hace tres Años': [moment().subtract(3, 'year').startOf('year'),moment().subtract(3, 'year').endOf('year')],
               /*'Hoy': [moment(), moment()],
               'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],               
               'Este Mes': [moment().startOf('month'), moment().endOf('month')],*/

            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function(ev, picker) {
      retornarTablaIngresos();
    });
    retornarTablaIngresos();
})
$(document).on("change","#almacen_filtro",function(){
    retornarTablaIngresos();
})


function retornarTablaIngresos()
{


    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val()
    $.ajax({
        type:"POST",
        url: base_url('index.php/ingresos/mostrarIngresos'),
        dataType: "json",
        data: {i:ini,f:fin,a:alm},
    }).done(function(res){
       datosselect= restornardatosSelect(res)
       //console.log((datosselect))
        $("#tingresos").bootstrapTable('destroy');
        $("#tingresos").bootstrapTable({

            data:res,
            striped:true,
            pagination:true,
            pageSize:"100",
            //height:"550", error con filtros
            clickToSelect:true,
            search:true,
            strictSearch:true,
            searchOnEnterKey:true,
            filter:true,
            showColumns:true,



            columns:[
            {
                field: 'n',
                width: '70px',
                title: 'N',
                align: 'center',
                sortable:true,
                filter: {type: "input"}
            },
            {
                field: 'sigla',
                width: '70px',
                title: 'Tipo',
                align: 'center',
                sortable:true,
                filter: {
                        type: "select",
                        data: datosselect[1]
                    }
            },
            {
                field:'fechamov',
                width: '90px',
                title:"Fecha",
                sortable:true,
                align: 'center',
                formatter: formato_fecha_corta,
            },
            {
                field:'nombreproveedor',
                title:"Proveedor",
                filter: {
                    type: "select",
                    data: datosselect[0]
                },
                sortable:true,
            },
            {
                field:'nfact',
                title:"Factura",
                width: '90px',
                sortable:true,
                //searchable:false,
                filter: {type: "input"},
                
            },
            {
                field:'total',
                title:"Total",
                width: '150px',
                align: 'right',
                sortable:true,
                //searchable:false,
                filter: {type: "input"},
            },
            {
                field:"estado",
                title:"Estado",
                width: '90px',
                sortable:true,
                filter: {
                    type: "select",
                    data:["APROBADO","PENDIENTE","ANULADO"]
                },
                formatter: operateFormatter2,
                align: 'center'
            },
            {
                field:"autor",
                width: '100px',
                title:"Autor",
                sortable:true,
                filter: {
                    type: "select",
                    data: datosselect[2]
                },
                visible:false,
                align: 'center'
            },
            {
                field:"fecha",
                width: '90px',
                title:"Fecha",
                sortable:true,
                formatter: formato_fecha_corta,
                visible:false,
                align: 'center'
            },
            {
                title: 'Acciones',
                align: 'center',
                width: '150px',
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
    if(row.anulado==1)
    {        
        $ret='<span class="label label-warning">ANULADO</span>';
    }
    else
    {
        if(value==0)
            $ret='<span class="label label-danger">PENDIENTE</span>';
        if(value==1)
            $ret='<span class="label label-success">APROBADO</span>';
    }
	
    return ($ret);
}
/***********Eventos*************/
window.operateEvents = {
    'click .verIngreso': function (e, value, row, index) {
     // fila=JSON.stringify(row);
    	verdetalle(row)
    },
    'click .editarIngreso': function (e, value, row, index) {
      //console.log(row.idIngresos);

      var editar=base_url("Ingresos/editarimportaciones/")+row.idIngresos;

        window.location.href = editar;
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
            var totalnn=fila.total
            totalnn=totalnn.replace(',','')
            totalnn=totalnn.replace(',','')
            totalnn=totalnn.replace(',','')
            totalnn=totalnn.replace(',','')
            totalnn=totalnn.replace(',','')
            totalnn=totalnn.replace(',','')
            var totalsus=totalnn;
            var totalbs=totalnn;
            if(fila.moneda==1)
            {
                totalsus=totalsus/glob_tipoCambio;
                totalsus=parseFloat(totalsus)    
            }
            else
            {
                totalbs=totalbs*glob_tipoCambio;
            }
            

            //console.log(sus)
            //sus=sus.toLocaleString()

            $("#almacen_imp").val(fila.almacen)
            $("#tipomov_imp").val(fila.tipomov)
            $("#fechamov_imp").val(fila.fechamov)
            $("#moneda_imp").val(fila.monedasigla)
            $("#nmov_imp").val(fila.n)
            $("#proveedor_imp").val(fila.nombreproveedor)
            $("#ordcomp_imp").val(fila.ordcomp)
            $("#nfact_imp").val(fila.nfact)
            $("#ningalm_imp").val(fila.ningalm)
            $("#obs_imp").val(fila.obs)
            /***pendienteaprobado***/
            var boton="";
            if(fila.estado=="0")
                boton='<button type="button" class="btn btn-success" datastd="'+fila.idIngresos+'" id="btnaprobado">Aprobado</button>';
            else
                boton='<button type="button" class="btn btn-danger" datastd="'+fila.idIngresos+'" id="btnpendiente">Pendiente</button>';

            $("#pendienteaprobado").html(boton)
			$("#totalsusdetalle").val(totalsus)
			$("#totalbsdetalle").val(totalbs)
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
function restornardatosSelect(res)
{
    var proveedor = new Array()
    var tipo = new Array()
    var autor = new Array()
    var datos =new Array()
    $.each(res, function(index, value){

        proveedor.push(value.nombreproveedor)
        tipo.push(value.sigla)
        autor.push(value.autor)
    })
    datos.push(proveedor.unique());
    datos.push(tipo.unique());
    datos.push(autor.unique());
    return(datos);
}
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});
