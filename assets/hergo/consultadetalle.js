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


function retornarTablaIngresos()
{


    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val()
    tipoingreso=$("#tipo_filtro").val()
  
    $.ajax({
        type:"POST",
        url: base_url('index.php/Ingresos/mostrarIngresosDetalle'),
        dataType: "json",
        data: {i:ini,f:fin,a:alm,ti:tipoingreso},
    }).done(function(res){
       datosselect= restornardatosSelect(res)

       console.log((datosselect))

        $("#tbconsultadetalle").bootstrapTable('destroy');
        
        
        $('#tbconsultadetalle').bootstrapTable({

                data:res,
                striped:true,
                pagination:true,
                pageSize:"100",
                clickToSelect:true,
                search:true,
                strictSearch:true,
                searchOnEnterKey:true,
                filter:true,
                showColumns:true,
            
                columns: [
                {
                    field: 'nmov',
                    width: '3%',
                    title: 'N',
                    align: 'center',
                    sortable:true,
                    filter: {type: "input"},
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
                    field:'nombreproveedor',
                    title:"Proveedor",
                    width: '17%',
                    sortable:true,
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    }
                },
                {
                    field:'nfact',
                    title:"Factura",
                    width: '7%',
                    sortable:true,
                    filter: {type: "input"},
                    
                },
                {
                    field:'CodigoArticulo',
                    title:"Codigo",
                    width: '7%',
                    align: 'right',
                    sortable:true,
                    filter: {type: "input"},
                    
                },
                {
                    field:"Descripcion",
                    title:"Descripción",
                    width: '17%',
                    sortable:true,
                
                    align: 'center'
                },
                {
                    field:"cantidad",
                    title:"Cantidad",
                    width: '7%',
                    sortable:true,
                    filter: {type: "input"},
                    align: 'right'
                },
                {
                    field:"total",
                    title:"Monto",
                    width: '8%',
                    sortable:true,
                    filter: {type: "input"},
                    align: 'right'
                },
                {
                    field:"autor",
                    width: '8%',
                    title:"Autor",
                    sortable:true,
                    visible:false,
                    align: 'center',
                    filter: {
                        type: "select",
                        data: datosselect[2]
                    }
                },
                {
                    field:"fecha",
                    width: '8%',
                    title:"Fecha",
                    sortable:true,
                    formatter: formato_fecha_corta,
                    visible:false,
                    align: 'center'
                },
                {
                    title: 'Acciones',
                    align: 'center',
                    width: '3%',
                    events: operateEvents,
                    formatter: operateFormatter
                }]
    
          });        
        
        $("#tbconsultadetalle").bootstrapTable('resetView');

        if(Object.keys(res).length<=0) $("tbody td","table#tbconsultadetalle").html("No se encontraron registros")        
        else $("tbody","table#tbconsultadetalle").show()            

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
window.operateEvents = {
    'click .verIngreso': function (e, value, row, index) {
     // fila=JSON.stringify(row);
        verdetalle(row)
    },    
};
function verdetalle(fila)
{
  console.log(fila)
    id=fila.idIngresos
    datos={id:id}
    retornarajax(base_url("index.php/Ingresos/mostrarDetalle"),datos,function(data)
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
            $("#titulo_modalIgresoDetalle").html(fila.tipomov);
            $("#tituloDetalleFac").html(csFact);
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
                title:"P/U Factura",
                align: 'right',
                width: '10%',
                sortable:true,
                formatter: punitariofac
            },
            {
                field:'totaldoc',
                title:"Total Factura",
                align: 'right',
                width: '10%',
                sortable:true,
            },
            {
                field:'punitario',
                title:"C/U Sistema",
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
