var iniciofecha=moment().subtract(0, 'year').startOf('year')
var finfecha=moment().subtract(0, 'year').endOf('year')

$(document).ready(function(){
    mostrarTablaDetalle();
    mostrarTablaFactura();
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
function mostrarTablaDetalle(res)
{
 //   console.log(res)
    $("#tabla2detalle").bootstrapTable('destroy');
        $("#tabla2detalle").bootstrapTable({
            data:res,
            height:250,        
            clickToSelect:true,
            search:false,
            rowStyle:rowStyle,
            columns:[            
            {
                field: 'idEgreDetalle',
                title: 'idEgreDetalle',                        
                visible:false,
            },
            {
                field: 'idegreso',
                title: 'idegreso',                        
                visible:false,
            },
            {
                field: 'Sigla',
                title: 'unidad',                        
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
                formatter: operateFormatter3                                
            },
            {
                
                title:'<button type="button" class="btn btn-default"><span class="fa fa-arrow-circle-right" aria-hidden="true"></span></button>',
                align: 'center',
                class:"col-sm-1",
                events: operateEvents,
                formatter: retornarBoton
            },
            ]
        });
}
function rowStyle(row, index) 
{
//console.log(row)
    var existe=false;
    var tabla3factura=$("#tabla3Factura").bootstrapTable('getData');
    $.each(tabla3factura,function(index, value){
       
        if(value.idEgreDetalle==row.idingdetalle)
        {
           existe=true

        }

    })
  
     if(existe)
     {
        return {
                    classes: "danger",
                };
     }
     else
     {
        return {};
     }  
            
    
}
function mostrarTablaFactura()
{
    
    $("#tabla3Factura").bootstrapTable('destroy');
        $("#tabla3Factura").bootstrapTable({
           
            height:250,        
            clickToSelect:true,
            search:false,
            columns:[

            {
                field: 'idEgreDetalle',
                title: 'idEgreDetalle',                        
                visible:false,
            },
            {
                field: 'idegreso',
                title: 'idegreso',                        
                visible:false,
            },
             {
                field: 'Sigla',
                title: 'unidad',                        
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
                formatter: operateFormatter3                               
            },
            {
                
                title:'<button type="button" class="btn btn-default"><span class="fa fa-arrow-circle-right" aria-hidden="true"></span></button>',
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
     AgregarRegistroTabla3(row,index);
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
       '<button type="button" class="btn btn-default enviartabla3" data-view="'+row.idingdetalle+'"><span class="fa fa-arrow-right" aria-hidden="true"></span></button>',
    ].join('');
}
/*function QuitardeTabla(dato)
{
    var view=$('[data-view="'+dato+'"]').removeClass("hidden");
    var remove=$('[data-remove="'+dato+'"]').addClass("hidden");
    var tr=view.parents("tr");              
    tr.removeClass("view")
}*/
function AgregarRegistroTabla3(row,index)
{
    //si no existe ningun registro agregar de cualquier cliente //registro
    //si ya existe=> verificar si ya fue agregado el mismo //false
    //si ya existe=>verificar si es otro cliente //false

     $.ajax({
        type:"POST",
        url: base_url('index.php/facturas/retornarTabla3'),
        dataType: "json",
        data: {idegreso:row.idegreso,idegresoDetalle:row.idingdetalle},
    }).done(function(res){
       if(res.detalle)
       {
            $("#valuecliente").val(res.cliente);           
         //   swal({
          //    title: "Agregado!",
           //   text: res.mensaje,
            //  type: "success",                                                  
           // },
           // function(){          
            //console.log(res);
             agregarRegistrosTabla3(res.detalle);              
              var tr=$('[data-index="'+index+'"]',"#tabla2detalle").addClass("danger")
              $("#clienteFactura").html(res.cliente);
              $("#clienteFacturaNit").html(res.clienteNit)
              $("#clientePedido").html(res.clientePedido)
              calcularTotalFactura();
           // });  
             
           /*   var view=$('[data-view="'+dato+'"]').addClass("hidden");
              var remove=$('[data-remove="'+dato+'"]').removeClass("hidden");
              var tr=view.parents("tr");              
              tr.addClass("view")        */
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
function AgregarTabla(dato)
{   
    //console.log(dato);
    $.ajax({
        type:"POST",
        url: base_url('index.php/facturas/retornarTabla2'),
        dataType: "json",
        data: {idegreso:dato},
    }).done(function(res){
       if(res.detalle)
       {
            $("#valuecliente").val(res.cliente);           
         /*   swal({
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
            });    */
             agregarRegistrosTabla2(res.detalle);
            /*  var view=$('[data-view="'+dato+'"]').addClass("hidden");
              var remove=$('[data-remove="'+dato+'"]').removeClass("hidden");
              var tr=view.parents("tr");              
              tr.addClass("view")        */
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
    mostrarTablaDetalle(detalle);    
    $("#tabla2detalle").bootstrapTable('resetView');    
}
function agregarRegistrosTabla3(detalle)
{//   console.log(detalle[0])
    detalle=detalle[0];
    var rows=[];
    rows.push({

                idEgreDetalle:detalle.idingdetalle,
                idegreso:detalle.idegreso,
                Sigla:detalle.Sigla,
                CodigoArticulo:detalle.CodigoArticulo,
                Descripcion:detalle.Descripcion,
                cantidad:detalle.cantidad,
                punitario:detalle.punitario,
                total:detalle.total,
        
            } )         
    $("#tabla3Factura").bootstrapTable('append', rows);
}
function calcularTotalFactura()
{
    var tabla3factura=$("#tabla3Factura").bootstrapTable('getData');
    var total=0;
    $.each(tabla3factura,function(index, value){
        console.log(value.total)
        total=total+parseFloat(value.total);
    })
    /****************Bs**************/
    $("#totalFacturaBs").val(total);    
    /*************SUS***************/
    $("#totalFacturaSus").val(total/glob_tipoCambio);
    /***********LITERAL**************/
    $("#totalTexto").html(NumeroALetras(total));
    /**********************************/
    $("#totalFacturaBsModal").html(total);
    $("#totalFacturaSusModal").html(total/glob_tipoCambio);
    $("#tipoCambioFacturaModal").html(glob_tipoCambio);
}
$(document).on("click","#crearFactura",function(){
    
    $("#cuerpoTablaFActura").html("");

    var tabla3factura=$("#tabla3Factura").bootstrapTable('getData');
    $.each(tabla3factura,function(index, value){
       
       console.log(value)
        var row =' <tr>'+
                '<td>'+value.cantidad+'</td>'+
                '<td>'+value.Sigla+'</td>'+
                '<td>'+value.CodigoArticulo+'</td>'+
                '<td>'+value.Descripcion+'</td>'+
                '<td>'+value.punitario+'</td>'+
                '<td>'+value.total+'</td>'+
              '</tr>'
        $("#cuerpoTablaFActura").append(row);

    });
    /******LUGAR y fecha*****/
    var fecha=$("#fechaFactura").val()       
    var fechaFormato = moment(fecha, 'YYYY-MM-DD');
    var dia=fechaFormato.format("DD");
    var mes=fechaFormato.format("MMMM");
    var anio=fechaFormato.format("YYYY");    
    var LugarFecha=("La Paz, "+dia+" de "+mes+" de "+anio);
    $("#fechaFacturaModal").html(LugarFecha);
    $("#notaFactura").html($("#observacionesFactura").val())
})
