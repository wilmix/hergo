var iniciofecha=moment().subtract(0, 'year').startOf('year')
var finfecha=moment().subtract(0, 'year').endOf('year')

$(document).ready(function(){
    mostrarTablaDetalle();
   // mostrarTablaFactura();
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
    retornarTablaFacturacion();
})
$(document).on("change","#almacen_filtro",function(){
    retornarTablaFacturacion();
})
$(document).on("change","#tipo_filtro",function(){
    retornarTablaFacturacion();
})


function retornarTablaFacturacion()
{


    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val()
    tipo=$("#tipo_filtro").val()
    console.log({ini:ini,fin:fin,alm:alm,tipo:tipo})
    $.ajax({
        type:"POST",
        url: base_url('index.php/facturas/MostrarTablaFacturacion'),
        dataType: "json",
        data: {ini:ini,fin:fin,alm:alm,tipo:tipo},
    }).done(function(res){
       //datosselect= restornardatosSelect(res)
        $("#tfacturas").bootstrapTable('destroy');
        $('#tfacturas').bootstrapTable({
                   
            data:res,
            striped:true,
            pagination:true,
            pageSize:"10",
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
            },
            {
                field:'total',
                title:"Total",
                width: '7%',
                align: 'right',
                sortable:true,
                formatter: operateFormatter3
            },
            {
                field:'sigla',
                title:"TipoMov",
                width: '7%',
                sortable:true,
                
            },
            {
                field:'n',
                title:"N Mov",
                width: '7%',
                sortable:true,
                
            },
            {
                field:'monedasigla',
                title:"Moneda",
                width: '7%',
                visible:false,
                sortable:true,
                
            },    
            {   //Estado poner Pagado - Pendiente - Anulado
                field:"estado",
                title:"Estado",
                width: '7%',
                sortable:true,
                align: 'center',
                formatter: operateFormatter2
            },                  
            {
                field:"autor",
                width: '8%',
                title:"Autor",
                sortable:true,
                visible:false,
                align: 'center'
            },
            {
                field:"fecha",
                width: '8%',
                title:"Fecha",
                sortable:true,
                visible:false,
                align: 'center',
                //formatter: formato_fecha_corta,
            },
            {
                title: 'Acciones',
                align: 'center',
                width: '10%',
                events: operateEvents,
                formatter: operateFormatter
            }]
            
        });
        
        $("#tfacturas").bootstrapTable('hideLoading');
        $("#tfacturas").bootstrapTable('resetView');



    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
    

}
function mostrarTablaDetalle()
{
    $("#tabla2detalle").bootstrapTable('destroy');
        $("#tabla2detalle").bootstrapTable({
        
            height:250,        
            clickToSelect:true,
            search:false,
            columns:[
            {
                field: 'idEgreDetalle',
                title: 'id',
                align: 'center',
                width: '0%',                
                visible:false,
            },
            {
                field: 'CodigoArticulo',
                title: 'Código',
                align: 'center',            
                class:"col-sm-1",
            },
            {
                field: 'Descripcion',
                title: 'Descripcion',                
                class:"col-sm-7",                
            },
            {
                field:'cantidad',
                title:"Cantidad",
                align: 'right',                
                class:"col-sm-1",                
            },
            {
                field:'punitario',
                title:"P/U Bs",
                align: 'right',
                class:"col-sm-1",                
            },
            {
                field:'total',
                title:"Total",
                align: 'right',
                class:"col-sm-1",                                
            },
            {
                
                title:'<button type="button" class="btn btn-default"><span class="fa fa-arrow-circle-right" aria-hidden="true"></span></button></th>',
                align: 'center',
                class:"col-sm-1",
                events: operateEvents,
                formatter: retornarBoton
            },
            ]
        });
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
}

window.operateEvents = {
    'click .agregartabla': function (e, value, row, index) {          
     AgregarTabla(row.idEgresos);
    },
    'click .quitardetabla': function (e, value, row, index) {          
     QuitardeTabla(row.idEgresos);
    },
    'click .enviartabla3': function (e, value, row, index) {
     console.log(row);
    },
  
};
function operateFormatter(value, row, index)
{
    return [
        '<button type="button" class="btn btn-default agregartabla" data-view="'+row.idEgresos+'" aria-label="Right Align">',
        '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
        '<button type="button" class="btn btn-default quitardetabla hidden" data-remove="'+row.idEgresos+'" aria-label="Right Align">',
        '<span class="fa fa-minus-square-o " aria-hidden="true"></span></button>',

    ].join('');
}
function retornarBoton(value, row, index)
{
    return [
       '<button type="button" class="btn btn-default enviartabla3"><span class="fa fa-arrow-right" aria-hidden="true"></span></button>',
    ].join('');
}
function QuitardeTabla(dato)
{
    var view=$('[data-view="'+dato+'"]').removeClass("hidden");
    var remove=$('[data-remove="'+dato+'"]').addClass("hidden");
    var tr=view.parents("tr");              
    tr.removeClass("view")
}
function AgregarTabla(dato)
{   
    console.log(dato);
    $.ajax({
        type:"POST",
        url: base_url('index.php/facturas/retornarTabla2'),
        dataType: "json",
        data: {idegreso:dato},
    }).done(function(res){
       if(res.detalle)
       {
            $("#valuecliente").val(res.cliente);           
            swal({
              title: "Agregado!",
              text: res.mensaje,
              type: "success",                                                  
            },
            function(){              
              agregarRegistrosTabla2(res.detalle);
              var view=$('[data-view="'+dato+'"]').addClass("hidden");
              var remove=$('[data-remove="'+dato+'"]').removeClass("hidden");
              var tr=view.parents("tr");              
              tr.addClass("view")
            });            
       }
       else
       {
            swal("Error", res.mensaje,"error")
       }
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
}
function agregarRegistrosTabla2(detalle)
{
    var rows=[];
    $.each(detalle,function(index,value){  
        var boton='<button type="button" class="btn btn-default enviartabla3"><span class="fa fa-arrow-right" aria-hidden="true"></span></button>';       
        rows.push({
                    idEgreDetalle:value.idingdetalle,
                    CodigoArticulo:value.CodigoArticulo,
                    Descripcion:value.Descripcion,
                    cantidad:value.cantidad,
                    punitario:value.punitario,
                    total:value.total,
            
                } )
    })
    $("#tabla2detalle").bootstrapTable('append', rows);
}
