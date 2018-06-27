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
      retornarTablaTraspasos();
    });
    retornarTablaTraspasos();
})
$(document).on("change","#almacen_filtro",function(){
    retornarTablaTraspasos();
})
$(document).on("change","#tipo_filtro",function(){
    retornarTablaTraspasos();
})


function retornarTablaTraspasos()
{


    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')    
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Traspasos/motrarTraspasos'),
        dataType: "json",
        data: {i:ini,f:fin},
    }).done(function(res){
        console.log(res)
       datosselect= restornardatosSelect(res)
       quitarcargando();
        $("#tTraspasos").bootstrapTable('destroy');
        $('#tTraspasos').bootstrapTable({
                   
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
                    
            columns:[{
                field:'fecha',
                title:"Fecha",
                sortable:true,
                align: 'center',
                formatter: formato_fecha_corta,
            },

            {
                field:'origen',
                title:"Almacen Origen",
                sortable:true,                 
                filter: {
                        type: "select",
                        data: datosselect[0]
                    }
            },
            {
                field:'destino',
                title:"Almacen Destino",
                sortable:true,                 
                filter: {
                        type: "select",
                        data: datosselect[1]
                    }
            },
            {
                field:'total',
                title:"Total",
                align: 'right',
                sortable:true,
                formatter: operateFormatter3,
            },  
            {
                field:"estado",
                title:"Estado",
                sortable:true,
                align: 'center',                    
                filter: {
                    type: "select",
                    data: ["Pendiente", "Aprobado"],
                        },
                formatter: operateFormatter2,
            },                  
            
            {
                title: 'Acciones',
                align: 'center',
                width: '100px',
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
        '<button type="button" class="btn btn-default verTraspaso" aria-label="Right Align">',
        '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',        
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
    'click .verTraspaso': function (e, value, row, index) {
     // fila=JSON.stringify(row);
        verdetalle(row)
    },
    'click .editarEgreso': function (e, value, row, index) {
      //console.log(row.idIngresos);
      var editar=base_url("Egresos/editaregresos/")+row.idEgresos;
      if(row.estado==0)
      {
        window.location.href = editar;
      }
      else
      {
        swal("Error", "No se puede editar el registro seleccionado","error")
      }        
    },
    'click .imprimirIngreso': function (e, value, row, index) {
     //alert(JSON.stringify(row));
    }
};
function verdetalle(fila)
{
  //console.log(fila)
    id=fila.idEgreso
    //console.log(id);
    datos={id:id}
    //console.log(fila)
    retornarajax(base_url("index.php/Traspasos/mostrarDetalle"),datos,function(data)
    {
        estado=validarresultado_ajax(data);
        if(estado)
        {
            //console.log(data.respuesta)
            mostrarDetalle(data.respuesta);
            //console.log(glob_tipoCambio)
            var totalnn=fila.total
            console.log(totalnn);
            
           /* var totalsus=totalnn;
            var totalbs=totalnn;
            if(fila.moneda==1)
            {
                totalsus=totalsus/glob_tipoCambio;
                totalsus=parseFloat(totalsus)    
            }
            else
            {
                totalbs=totalbs*glob_tipoCambio;
            }*/
            

            //console.log(sus)
            //sus=sus.toLocaleString()
      
            $("#facturadonofacturado").html(operateFormatter2(fila.estado, fila))
            $("#almacen_ori").val(fila.origen)
            $("#almacen_des").val(fila.destino)
            $("#fechamov_egr").val(formato_fecha_corta(fila.fecha));
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
            $("#totalsusdetalle").val();
            $("#totalbsdetalle").val(totalnn);
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
    //console.log(res)
    $("#tTraspasodetalle").bootstrapTable('destroy');
        $("#tTraspasodetalle").bootstrapTable({

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
                width:'100px',
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
                width:'100px',
                sortable:true,
            },
            {
                field:'punitario',
                title:"P/U Bs",
                align: 'right',
                width:'100px',
                sortable:true,
            },
            {
                field:'total',
                title:"Total",
                align: 'right',
                width:'100px',
                sortable:true,
            },

            ]
        });
}

function restornardatosSelect(res)
{

    var origen = new Array()
    var destino = new Array()
    
    var datos =new Array()
    $.each(res, function(index, value){

        origen.push(value.origen)
        destino.push(value.destino)
    })
    origen.sort();
    destino.sort();
   
    datos.push(origen.unique());
    datos.push(destino.unique());    
    return(datos);
}
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});
