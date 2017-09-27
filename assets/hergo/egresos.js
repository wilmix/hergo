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
            ranges: {
               'Gestion Actual': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
               "Hace un Año": [moment().subtract(1, 'year').startOf('year'),moment().subtract(1, 'year').endOf('year')],
               'Hace dos Años': [moment().subtract(2, 'year').startOf('year'),moment().subtract(2, 'year').endOf('year')],
               'Hace tres Años': [moment().subtract(3, 'year').startOf('year'),moment().subtract(3, 'year').endOf('year')],               
            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function(ev, picker) {
      retornarTablaEgresos();
    });
    retornarTablaEgresos();
})
$(document).on("change","#almacen_filtro",function(){
    retornarTablaEgresos();
})
$(document).on("change","#tipo_filtro",function(){
    retornarTablaEgresos();
})


function retornarTablaEgresos()
{


    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val()
    tipoingreso=$("#tipo_filtro").val()
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/egresos/mostrarEgresos'),
        dataType: "json",
        data: {i:ini,f:fin,a:alm,ti:tipoingreso},
    }).done(function(res){
       // console.log(res[0])
       datosselect= restornardatosSelect(res)
       quitarcargando();
        $("#tegresos").bootstrapTable('destroy');
        $('#tegresos').bootstrapTable({
                   
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

            columns: [
            {
                field: 'n',
                width: '3%',
                title: 'N',
                align: 'center',
                sortable:true,
                filter: {type: "input"}
            },
            {
                field:'fechamov',
                width: '7%',
                title:"Fecha",
                sortable:true,
                align: 'center',
                formatter: formato_fecha_corta,
            },
            {
                field:'nombreCliente',
                title:"Cliente",
                width: '17%',
                sortable:true,
                filter: {type: "input"}

            },
            {
                field:'factura',
                title:"Factura",
                width: '7%',
                sortable:true,
                formatter:mostrarFactura,
                filter: {type: "input"}

            },
            {
                field:'total',
                title:"Total",
                width: '7%',
                align: 'right',
                sortable:true,
                formatter: operateFormatter3,
                filter: {type: "input"}
            },  
            {
                field:"estado",
                title:"Estado",
                width: '7%',
                sortable:true,
                align: 'center',            
                filter: {
                    type: "select",
                    data: ["T. Facturado", "No facturado", "Parcial", "Anulado"],
                        },
                formatter: operateFormatter2,

            },                  
            {
                field:"clientePedido",
                width: '8%',
                title:"N° Pedido",
                sortable:true,
                visible:false,
                align: 'center',
                filter: {type: "input"},
            },
            {
                field:"plazopago",
                width: '8%',
                title:"PlazoPago",
                sortable:true,
                visible:false,
                align: 'center',
                formatter: formato_fecha_corta,
            },
            {
                field:"autor",
                width: '8%',
                title:"Autor",
                sortable:true,
                visible:false,
                align: 'center',
//<<<<<<< HEAD
                 filter: {
                    type: "select",
                    data: datosselect[2]
                },
//=======
                filter: {type: "input"},

//>>>>>>> f819dc692e694fd3fa2c082dbd2195630a638a65
            },
            {
                field:"fecha",
                width: '8%',
                title:"Fecha",
                sortable:true,
                visible:false,
                align: 'center',
                formatter: formato_fecha_corta,
            },
            {
                title: 'Acciones',
                align: 'center',
                width: '10%',
                events: operateEvents,
                formatter: operateFormatter
            }]
            
        });
        
        //$("#tegresos").bootstrapTable('showLoading');
        $("#tegresos").bootstrapTable('resetView');
        mensajeregistrostabla(res,"#tegresos");

        /*if(Object.keys(res).length<=0) $("tbody td","table#tegresos").html("No se encontraron registros")        
        else $("tbody","table#tegresos").show()            */

    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
    //$("body").css("padding-right","0px");

}
function operateFormatter(value, row, index)
{
    return [
        '<button type="button" class="btn btn-default verEgreso" aria-label="Right Align">',
        '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
        '<button type="button" class="btn btn-default editarEgreso" aria-label="Right Align">',
        '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>',
        '<button type="button" class="btn btn-default imprimirEgreso" aria-label="Right Align">',
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
            $ret='<span class="label label-danger">No facturado</span>';
        if(value==1)
            $ret='<span class="label label-success">T. Facturado</span>';
        if(value==2)
            $ret='<span class="label label-info">Facturado Parcial</span>';
    }
    
    return ($ret);
}

function operateFormatter3(value, row, index)
{       
    num=Math.round(value * 100) / 100
    return (formatNumber.new(num));
   //return(num)
}
function mostrarFactura(value, row, index)
{       
    var cadena=""
    $.each(value,function(index,val){
        cadena+=val.nFactura
        // cadena+=" - ";
        if(index<value.length-1)
            cadena+=" - ";                           
    })
    
    return (cadena); 
}
/***********Eventos*************/
window.operateEvents = {
    'click .verEgreso': function (e, value, row, index) {
     // fila=JSON.stringify(row);
        verdetalle(row)
    },
    'click .editarEgreso': function (e, value, row, index) {
      //console.log(row.idIngresos);
      var editar=base_url("egresos/editaregresos/")+row.idEgresos;
      if(row.estado==0)
      {
        window.location.href = editar;
      }
      else
      {
        swal("Error", "No se puede editar el registro seleccionado. El registro ya se encuentra Facturado.","error")
      }        
    },
    'click .imprimirIngreso': function (e, value, row, index) {
     //alert(JSON.stringify(row));
    }
};
function verdetalle(fila)
{
  console.log(fila)
    id=fila.idEgresos
    datos={id:id}
    console.log(fila)
    retornarajax(base_url("index.php/egresos/mostrarDetalle"),datos,function(data)
    {
        estado=validarresultado_ajax(data);
        if(estado)
        {
            //console.log(data.respuesta)
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
      
            $("#facturadonofacturado").html(operateFormatter2(fila.estado, fila))
            $("#almacen_egr").val(fila.almacen)
            $("#tipomov_egr").val(fila.tipomov)
            $("#fechamov_egr").val(formato_fecha_corta(fila.fechamov));
            $("#moneda_egr").val(fila.monedasigla)
            $("#nmov_egr").val(fila.n)
            $("#cliente_egr").val(fila.nombreCliente)
            $("#pedido_egr").val(fila.clientePedido)
            $("#fechaPago").val(formato_fecha_corta(fila.plazopago));
           // $("#vacioEgr").val("?????????????????????")
            $("#obs_egr").val(fila.obs);
            $("#numeromovimiento").html(fila.n);
            $("#nombreModal").html(fila.tipomov);
            /***pendienteaprobado***/
            var boton="";
            //if(fila.estado=="0")
               // boton='<button type="button" class="btn btn-success" datastd="'+fila.idIngresos+'" id="btnaprobado">Aprobado</button>';
            //else
              //  boton='<button type="button" class="btn btn-danger" datastd="'+fila.idIngresos+'" id="btnpendiente">Pendiente</button>';
            var csFact="Sin factura";
            if(fila.nfact!="SF")
                csFact="Con factura";


            $("#pendienteaprobado").html(boton);
            $("#totalsusdetalle").val(totalsus);
            $("#totalbsdetalle").val(totalbs);
            $("#titulo_modalIgresoDetalle").html(" - "+fila.tipomov+ " - "+csFact);
            $("#modalEgresoDetalle").modal("show");

           //$("#nrofactura").html("")                    
            var cadena=""
            $.each(fila.factura,function(index,val){

                cadena+="<option>"+val.nFactura+"</option>"
            })
            $("#facturasnum").html(cadena);                   

        }
    })
}
function mostrarDetalle(res)
{
    $("#tegresosdetalle").bootstrapTable('destroy');
        $("#tegresosdetalle").bootstrapTable({

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
                field:'punitario',
                title:"P/U Bs",
                align: 'right',
                width: '10%',
                sortable:true,
            },
            {
                field:'descuento',
                title:"% Dscnt",
                align: 'right',
                width: '10%',
                sortable:true,
            },
            {
                field:'total',
                title:"Total",
                align: 'right',
                width: '10%',
                sortable:true,
            },
            {
                field:'cantFact',
                title:"CantFact",
                align: 'right',
                width: '10%',
                sortable:true,
            },
            ]
        });
}
function punitariofac(value, row, index)
{       
    
    console.log(row);
    var punit=row.cantidad==""?0:row.cantidad;
    punit=row.totaldoc/punit;
    punit=redondeo(punit,3)
    
    return (formatNumber.new(punit));
   //return(num)
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
    proveedor.sort();
    tipo.sort();
    autor.sort();
    datos.push(proveedor.unique());
    datos.push(tipo.unique());
    datos.push(autor.unique());
    return(datos);
}
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});
