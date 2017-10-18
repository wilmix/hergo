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
        moment.locale('es');
        function cb(start, end) {
            $('#fechapersonalizada span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
            iniciofecha=start
            finfecha=end
        }
        
        $('#fechapersonalizada').daterangepicker({

            locale: {
                  format: 'DD/MM/YYYY',
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
$(document).on("change","#tipo_filtro",function(){
    retornarTablaIngresos();
})
function mostrarTablaIngresosTraspaso(res)
{
    $("#tingresos").bootstrapTable({

        data:res,
        striped:true,
        pagination:true,
        pageSize:"100",
        //height:"550", error con filtros
        //clickToSelect:true,
        search:true,
        //strictSearch:true,
        searchOnEnterKey:true,
        filter:true,
        showColumns:true,



        columns:[
        {
            field: 'n',
            width: '5%',
            title: 'N',
            align: 'center',
            sortable:true,
            filter: {type: "input"}
        },
        {
            field: 'sigla',
            width: '5%',
            title: 'Tipo',
            align: 'center',
            visible:false,
            sortable:true,
            
            filter: {
                    type: "select",
                    data: datosselect[1]
                }
        },
        {
            field:'fechamov',
            width: '7%',
            title:"Fecha",
            align: 'right',
            sortable:true,
            //align: 'center',
            
            formatter: formato_fecha_corta,
        },
        {
            field:'origen', //para traspasos
            title:"Origen",
            width: '20%',
            filter: {
                type: "select",
                data: datosselect[3]
            },
            sortable:true,
            
        },
        {
            field:'nfact',
            title:"Factura",
            width: '7%',
            sortable:true,
            //searchable:false,
            filter: {type: "input"},
            
            
        },
        {
            field:'monedasigla',
            title:"Moneda",
            width: '7%',
            align: 'right',
            sortable:true,
            
            
        },
        {
            field:'totalsus',
            title:"Total Sus",
            width: '7%',
            align: 'right',
            sortable:true,
            formatter: operateFormatter3,
            filter: {type: "input"},
            
        },
        {
            field:'total',
            title:"Total Bs",
            width: '7%',
            align: 'right',
            sortable:true,
            formatter: operateFormatter3,
            filter: {type: "input"},
            
        },
        {
            field:"estado",
            title:"Estado",
            width: '7%',
            sortable:true,
            filter: {
                type: "select",
                data:["APROBADO","PENDIENTE","ANULADO"]
            },
            formatter: operateFormatter2,
            align: 'center',
            
        },
        {
            field:"autor",
            width: '10%',
            title:"Autor",
            sortable:true,
            filter: {
                type: "select",
                data: datosselect[2]
            },
            visible:false,
            align: 'center',
            
        },
        {
            field:"fecha",
            width: '10%',
            title:"Fecha",
            sortable:true,
            formatter: formato_fecha_corta,
            visible:false,
            align: 'center',
            
        },
        {
            title: 'Acciones',
            align: 'center',
            width: '10%',
            events: operateEvents,
            formatter: operateFormatter
        }]
    });
}
function mostrarTablaIngresos(res)
{
    $("#tingresos").bootstrapTable({

        data:res,
        striped:true,
        pagination:true,
        pageSize:"100",
        //height:"550", error con filtros
        //clickToSelect:true,
        search:true,
        //strictSearch:true,
        searchOnEnterKey:true,
        filter:true,
        showColumns:true,



        columns:[
        {
            field: 'n',
            width: '5%',
            title: 'N',
            align: 'center',
            sortable:true,
            filter: {type: "input"}
        },
        {
            field: 'sigla',
            width: '5%',
            title: 'Tipo',
            align: 'center',
            visible:false,
            sortable:true,
            
            filter: {
                    type: "select",
                    data: datosselect[1]
                }
        },
        {
            field:'fechamov',
            width: '7%',
            title:"Fecha",
            align: 'right',
            sortable:true,
            //align: 'center',
            
            formatter: formato_fecha_corta,
        },
        {
            field:'nombreproveedor',
            title:"Proveedor",
            width: '20%',
            filter: {
                type: "select",
                data: datosselect[0]
            },
            sortable:true,
            
        },
        {
            field:'nfact',
            title:"Factura",
            width: '7%',
            sortable:true,
            //searchable:false,
            filter: {type: "input"},
            
            
        },
        {
            field:'monedasigla',
            title:"Moneda",
            width: '7%',
            align: 'right',
            sortable:true,
            
            
        },
        {
            field:'totalsus',
            title:"Total Sus",
            width: '7%',
            align: 'right',
            sortable:true,
            formatter: operateFormatter3,
            filter: {type: "input"},
            
        },
        {
            field:'total',
            title:"Total Bs",
            width: '7%',
            align: 'right',
            sortable:true,
            formatter: operateFormatter3,
            filter: {type: "input"},
            
        },
        {
            field:"estado",
            title:"Estado",
            width: '7%',
            sortable:true,
            filter: {
                type: "select",
                data:["APROBADO","PENDIENTE","ANULADO"]
            },
            formatter: operateFormatter2,
            align: 'center',
            
        },
        {
            field:"autor",
            width: '10%',
            title:"Autor",
            sortable:true,
            filter: {
                type: "select",
                data: datosselect[2]
            },
            visible:false,
            align: 'center',
            
        },
        {
            field:"fecha",
            width: '10%',
            title:"Fecha",
            sortable:true,
            formatter: formato_fecha_corta,
            visible:false,
            align: 'center',
            
        },
        {
            title: 'Acciones',
            align: 'center',
            width: '10%',
            events: operateEvents,
            formatter: operateFormatter
        }]
    });
}
function retornarTablaIngresos()
{
    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val()
    tipoingreso=$("#tipo_filtro").val()
    //console.log(tipoingreso)
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/ingresos/mostrarIngresos'),
        dataType: "json",
        data: {i:ini,f:fin,a:alm,ti:tipoingreso},
    }).done(function(res){
        quitarcargando();
        datosselect= restornardatosSelect(res)
       
        $("#tingresos").bootstrapTable('destroy');
        if(tipoingreso==3)
            mostrarTablaIngresosTraspaso(res);
        else
            mostrarTablaIngresos(res);

        $("#tarticulo").bootstrapTable('hideLoading');
        $("#tarticulo").bootstrapTable('resetView');
        mensajeregistrostabla(res,"#tarticulo");
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
function operateFormatter3(value, row, index)
{       
    num=Math.round(value * 100) / 100
    num=num.toFixed(2);
    return (formatNumber.new(num));
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

	datos={id:id,mon:fila.moneda}
	retornarajax(base_url("index.php/ingresos/mostrarDetalle"),datos,function(data)
	{
		estado=validarresultado_ajax(data);
		if(estado)
		{
            var totaldoc=0;
            var totalsis=0;
			mostrarDetalle(data.respuesta);
			$.each(data.respuesta,function(index, value){
                totaldoc+=value.totaldoc
                totalsis+=value.total
            })
            var totalnn=fila.total
            
            if(fila.moneda==2)
            {
 
               $("#nombretotaldoc").html("$us Doc")
               $("#nombretotalsis").html("$us Sis")
            }
            else
            { 
               $("#nombretotaldoc").html("Bs Doc")
               $("#nombretotalsis").html("Bs Sis")
            }
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
            $("#nmovingre").html(fila.n)

            /***pendienteaprobado***/
            var boton="";
            if(fila.estado=="0")
                boton='<button type="button" class="btn btn-success" datastd="'+fila.idIngresos+'" id="btnaprobado">Aprobado</button>';
            else
                boton='<button type="button" class="btn btn-danger" datastd="'+fila.idIngresos+'" id="btnpendiente">Pendiente</button>';
            var csFact="Sin factura";
            if(fila.nfact!="SF")
                csFact="Con factura";

            totaldoc=totaldoc*100/100;
            totaldoc=totaldoc.toFixed(2);
            totalsis=totalsis*100/100;
            totalsis=totalsis.toFixed(2);
            $("#pendienteaprobado").html(boton);
			$("#totaldocdetalle").val(totaldoc);
			$("#totalsisdetalle").val(totalsis);
            $("#titulo_modalIgresoDetalle").html(fila.tipomov);
            $("#tituloDetalleFac").html(csFact);
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
            search:false,
            columns:[
            {
                field: 'CodigoArticulo',
                title: 'Código',
                align: 'center',
                width: '10%',
                sortable:true,
            },
            {
                field: 'Descripcion',
                title: 'Descripcion',
                width: '40%',
                sortable:true,
            },
            {
                field:'cantidad',
                title:"Cantidad",
                align: 'right',
                width: '10%',
                sortable:true,
            },
            

            //PARA COMPARAR CON FACTURA
            {
                field:'',
                title:"P/U Documento",
                align: 'right',
                width: '10%',
                sortable:true,
                formatter: punitariofac,
                //formatter: operateFormatter3,
            },
            {
                field:'totaldoc',
                title:"Total Documento",
                align: 'right',
                width: '10%',
                sortable:true,
                formatter: operateFormatter3,
            },




            {
                field:'punitario',
                title:"C/U Sistema",
                align: 'right',
                width: '10%',
                sortable:true,
                formatter: operateFormatter3,
            },
            {
                field:'total',
                title:"Total",
                align: 'right',
                width: '10%',
                sortable:true,
                formatter: operateFormatter3,
            },
            ]
        });
}
function punitariofac(value, row, index)
{       
    
    console.log(row);
    var punit=row.cantidad==""?0:row.cantidad;
    punit=row.totaldoc/punit;
    punit=redondeo(punit,3);
    punit=punit.toFixed(2);
    
    return (formatNumber.new(punit));
   //return(num)
}
function restornardatosSelect(res)
{
    var proveedor = new Array()
    var tipo = new Array()
    var autor = new Array()
    var origen = new Array()
    var datos =new Array()
    $.each(res, function(index, value){

        proveedor.push(value.nombreproveedor)
        tipo.push(value.sigla)
        autor.push(value.autor)
        origen.push(value.origen)
    })
    proveedor.sort();
    tipo.sort();
    autor.sort();
    datos.push(proveedor.unique());
    datos.push(tipo.unique());
    datos.push(autor.unique());
    datos.push(origen.unique());
    return(datos);
}
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});
