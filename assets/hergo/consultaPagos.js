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
      retornarTablaFacturacion();
    });
})
$(document).on("change","#almacen_filtro",function(){
    retornarTablaFacturacion();
})
$(document).on("change","#tipo_filtro",function(){
    retornarTablaFacturacion();
})


function retornarTablaFacturacion()
{
     agregarcargando();
    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val()
    tipo=$("#tipo_filtro").val()
    console.log({ini:ini,fin:fin,alm:alm,tipo:tipo})
    $.ajax({
        type:"POST",
        url: base_url('index.php/facturas/MostrarTablaConsultaFacturacion'),
        dataType: "json",
        data: {ini:ini,fin:fin,alm:alm,tipo:tipo},
    }).done(function(res){
         quitarcargando();
       //datosselect= restornardatosSelect(res)
        $("#facturasPagos").bootstrapTable('destroy');
        $('#facturasPagos').bootstrapTable({
                   
            data:res,
            striped:true,
            pagination:true,
            pageSize:"10",    
            search:true,        
            searchOnEnterKey:true,
            filter:true,
            showColumns:true,

            columns: [            


            {
                field:'numPago',
                title:"N° Pago",
                sortable:true,
                //class:"col-sm-1",
                align: 'center',
                
            },
            {
                field:'fechaPago',
                title:"Fecha",
                class:"col-sm-1",
                sortable:true,
                formatter: formato_fecha_corta,
            },
            {
                field:'ClienteNit',
                title:"NIT Cliente",                
                class:"col-sm-1",                                
                sortable:true,
                visible:true
            },
            {
                field:'cliente',
                title:"Cliente",                
                class:"col-sm-4",         
                sortable:true,

            },
            {
                field:'nfactura',
                title:"N° Factura",                
                class:"col-sm-2",         
                sortable:true,

            },
            {
                field:'glosaPago',
                title:"Cliente",                
                class:"col-sm-4",         
                sortable:true,

            },
            {
                field:'montoPago',
                title:"Total",                
                sortable:true,
                align: 'right',
                formatter:formato_moneda,
            },
            {
                field:'fecha',
                title:"Fecha",
                //class:"col-sm-1",
                sortable:true,
                visible:false,
                formatter: formato_fecha_corta,
            },
            {
                field:'autor',
                title:"Autor",
                //class:"col-sm-1",
                sortable:true,
                visible:false,
                
            },
            /*{
                field:'estado',
                title:"Estado",
                width: '7%',
                sortable:true,
                formatter: formatoEstadoFactura                
            }, */          
            {
                title: 'Acciones',
                align: 'center',
                class:"col-sm-4",
                //width: '15%',
                events: eventosBotones,                
                formatter: formatoBotones
            }]
            
        });
        
    

    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
}

//tabla para ver modal pagos
function mostrarVerPago(res)
{
    $("#tablaPagoDetalle").bootstrapTable('destroy');
        $("#tablaPagoDetalle").bootstrapTable({

            data:res,
            striped:true,
            pagination:true,
            clickToSelect:true,
            search:false,
            columns:[
            {
                field:'fechaFactura',
                title:"FechaFactura",
                align: 'right',
                width: '10%',
                sortable:true,
            },
            {
                field: 'nFactura',
                title: 'Factura',
                align: 'center',
                width: '10%',
                sortable:true,
            },
            {
                field: 'cliente',
                title: 'Cliente',
                width: '40%',
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



function formatoBotones(value, row, index)
{
    
    
        return [
        '<button type="button" class="btn btn-default"  aria-label="Right Align">',
        '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
        '<button type="button" class="btn btn-default "   aria-label="Right Align">',
        '<span class="glyphicon glyphicon-pencil " aria-hidden="true"></span></button>',
        ].join('');    
    
   
    
}
function formatoEstadoFactura(value, row, index)
{
    $ret=''

    if(row.anulada==1)
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

window.eventosBotones = {
    'click .verFactura': function (e, value, row, index) {          
    //console.log(row);        
        agregarDatosInicialesFacturaModal(row);
        verFacturaModal(row);

    },
    'click .anularFactura': function (e, value, row, index) {         
    swal({
      title: "Esta seguro?",
      text: "Se anulara la factura seleccionada",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Si, continuar",
      cancelButtonText: "Cancelar",
  
    },
    function(){
      anularFactura(row);
    }); 
        
    },      
};

