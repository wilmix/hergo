var iniciofecha=moment().subtract(0, 'year').startOf('year')
var finfecha=moment().subtract(0, 'year').endOf('year')

$(document).ready(function(){
    //tablaprueba()
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

    agregarcargando();
    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val()
    tipo=$("#tipo_filtro").val()
    console.log({ini:ini,fin:fin,alm:alm,tipo:tipo})
    $.ajax({
        type:"POST",
        url: base_url('index.php/Facturas/MostrarTablaFacturacion'),
        dataType: "json",
        data: {ini:ini,fin:fin,alm:alm,tipo:tipo},
    }).done(function(res){
        quitarcargando();
       //datosselect= restornardatosSelect(res)
        $("#tfacturas").bootstrapTable('destroy');
        $('#tfacturas').bootstrapTable({
                   
            data:res,
            striped:true,
            pagination:true,
            pageSize:"50",
            //height:"550", error con filtros
            //clickToSelect:true,
            search:true,
            filter:true,
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
                field:'total',
                title:"Total",
                width: '7%',
                align: 'right',
                sortable:true,
                formatter: operateFormatter3,
           
            },
            {
                field:'sigla',
                title:"TipoMov",
                width: '7%',
                sortable:true,
                filter: {type: "input"}
                
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
                formatter: operateFormatter2,
                filter: {
                    type: "select",
                    data:["No facturado","Parcial"]
                },
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
        
     //   $("#tfacturas").bootstrapTable('hideLoading');
       // $("#tfacturas").bootstrapTable('resetView');


    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
    

}
function mostrarTablaDetalle(res)
{
    console.log(res)
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
                formatter: operateFormatter3,
                visible:false              
            },
            {
                field:'cantidadReal',
                title:"Cantidad",
                align: 'right',                
                formatter: operateFormatter3,
                class:"col-sm-1",              
            },
            {
                field:'punitario',
                title:"P/U Bs",
                align: 'right',
                class:"col-sm-1",
                formatter: operateFormatter3,             
            },
            {
                
                title:"Total",
                align: 'right',
                class:"col-sm-1",
                formatter: totalTabla2                                
            },
            {
                
                title:'<button type="button" class="btn btn-default agregarTodos"><span class="fa fa-arrow-circle-right" aria-hidden="true"></span></button>',
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
var $table
function mostrarTablaFactura()
{
    
    $table = $("#tabla3Factura").bootstrapTable('destroy');
        $("#tabla3Factura").bootstrapTable({
           
           // height:250,        
            clickToSelect:true,
            uniqueId:'idEgreDetalle',
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
                field:'cantidadRealAux',
                title:"cantidadRealAux",
                formatter: operateFormatter3,
                visible:false                
            },           
            {
                field:'cantidadReal',
                title:"Cantidad",
                align: 'right',                
                class:"col-sm-1",  
                formatter: operateFormatter3,
                editable:{
                            container: 'body',
                            type : 'text',     
                            params:{a:1,b:2}, 
                            inputclass:"tiponumerico",                
                            validate: validateNum,
                          //  display: formatoMoneda,

                        },              
            },
            {
                field:'punitario',
                title:"P/U Bs",
                align: 'right',
                class:"col-sm-1",  
                              
                editable:{
                            container: 'body',
                            type : 'text',
                            inputclass:"tiponumerico",
                            validate: function(value) {
                                if ($.trim(value) == '') {
                                    return 'El campo es requerido';
                                }
                                if(!$.isNumeric(value))
                                {
                                    return 'El campo es numerico';
                                }                            
                            },
                          //  display: formatoMoneda,
                        },  
                formatter: operateFormatter3,
            },
            {
                
                title:"Total",
                align: 'right',
                class:"col-sm-1", 
               // formatter: operateFormatter3,                               
                formatter:totalTabla2,
            },
            {
                
                title:'<button type="button" class="btn btn-default quitarTodos"><span class="fa fa-times-circle" aria-hidden="true"></span></button>',
                align: 'center',
                class:"col-sm-1",
                events: operateEvents,
                formatter: retornarBoton2
            },
            ]
        });
        $table.on('editable-save.bs.table', function (e, field, row, old, $el) {     
        //console.log(parseFloat(row.punitario),parseFloat(row.cantidadRealAux)) 
        console.log(row)
            var total=parseFloat(row.punitario)*parseFloat(row.cantidadReal);
             $("#tabla3Factura").bootstrapTable('updateByUniqueId', {
                id: row.idEgreDetalle,
                row: {                   
                    total: total
                }
            });
            calcularTotalFactura();
        });
}

var scope = this;
scope.formatoMoneda = function(value) {
        
    console.log(value)
    $(this).html(operateFormatter3(value));
};


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
function totalTabla2(value, row, index)
{    
  
    return (operateFormatter3(row.punitario*row.cantidadReal));   
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
    'click .eliminarElemento': function (e, value, row, index) {     
        quitarElementoTabla(row);
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
function retornarBoton2(value, row, index)
{
    return [
       '<button type="button" class="btn btn-default eliminarElemento" data-view="'+row.idingdetalle+'"><span class="fa fa-times" aria-hidden="true"></span></button>',
    ].join('');
}
/*function QuitardeTabla(dato)
{
    var view=$('[data-view="'+dato+'"]').removeClass("hidden");
    var remove=$('[data-remove="'+dato+'"]').addClass("hidden");
    var tr=view.parents("tr");              
    tr.removeClass("view")
}*/
function quitarElementoTabla(row)
{       
    ids=new Array(row.idEgreDetalle)   
    $("#tabla3Factura").bootstrapTable('remove', {
        field: 'idEgreDetalle',
        values: ids
    });
    console.log(row)
    $.ajax({
        type:"POST",
        url: base_url('index.php/Facturas/eliminarElementoTabla3'),
        dataType: "json",
        data: {idegresoDetalle:row.idEgreDetalle},
    }).done(function(res){

        $('[data-view="'+row.idEgreDetalle+'"]').parents("tr").removeClass("danger");   
        calcularTotalFactura();
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });    
}
function quitarTodosElementosTabla3()
{
    $("#tabla3Factura").bootstrapTable('removeAll');
     $.ajax({
        type:"POST",
        url: base_url('index.php/Facturas/eliminarTodosElementoTabla3'),
        dataType: "json",
        
    }).done(function(res){

        $.each($("#tabla2detalle").find("tbody tr"),function(index,value){    
            $(value).removeClass("danger");
        }) 
        $("#nombreCliente").val("");   
        $("#valuecliente").val("");   
        $("#valueidcliente").val("");        
        calcularTotalFactura();
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });    
   
}
$(document).on("click",".quitarTodos",function(){
    quitarTodosElementosTabla3();
})
function AgregarRegistroTabla3(row,index)
{
    //si no existe ningun registro agregar de cualquier cliente //registro
    //si ya existe=> verificar si ya fue agregado el mismo //false
    //si ya existe=>verificar si es otro cliente //false

     $.ajax({
        type:"POST",
        url: base_url('index.php/Facturas/retornarTabla3'),
        dataType: "json",
        data: {idegreso:row.idegreso,idegresoDetalle:row.idingdetalle},
    }).done(function(res){
       if(res.detalle)
       {
        //console.log(res.detalle)
            $("#nombreCliente").val(res.cliente);   
            $("#valuecliente").val(res.cliente);   
            $("#valueidcliente").val(res.idCliente);        
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
        url: base_url('index.php/Facturas/retornarTabla2'),
        dataType: "json",
        data: {idegreso:dato},
    }).done(function(res){
       if(res.detalle)
       {
           
             agregarRegistrosTabla2(res.detalle);
             $("#idAlm").val(res.alm);
           
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
{   

    console.log(detalle[0])
    detalle=detalle[0];
    var rows=[];
    rows.push({

                idEgreDetalle:detalle.idingdetalle,
                idegreso:detalle.idegreso,
                Sigla:detalle.Sigla,
                CodigoArticulo:detalle.CodigoArticulo,
                Descripcion:detalle.Descripcion,
                cantidadRealAux:detalle.cantidadReal,
                cantidadReal:detalle.cantidadReal,
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
    $("#totalFacturaBsModal").html(formato_moneda(total));
    $("#totalsinformatobs").val(total);
    $("#totalFacturaSusModal").html(formato_moneda(total/glob_tipoCambio));
    $("#tipoCambioFacturaModal").html(glob_tipoCambio);
}
$(document).on("click","#crearFactura",function(){
    var tabla3factura=$("#tabla3Factura").bootstrapTable('getData');
    if(tabla3factura.length>0)
    {
        var datos={
            idAlmacen:$("#idAlm").val(),
            tipoFacturacion:$("#tipoFacturacion").val(),
            fechaFactura:$("#fechaFactura").val()
        }
        console.log(datos)
         $.ajax({
            type:"POST",
            url: base_url('index.php/Facturas/consultarDatosFactura'),
            dataType: "json",
            data: datos,
        }).done(function(res){
            
           if(res.response)
           {                
                vistaPreviaFactura();
                agregarDatosFactura(res);
           }
           else
           {
                var error="";
                $.each(res.error,function(index,value){
                    error+=value+"\n";
                })
       
                swal("Error", error,"error")
           }
        }).fail(function( jqxhr, textStatus, error ) {
        var err = textStatus + ", " + error;
        console.log( "Request Failed: " + err );
        });
    }
    
   
})
function agregarDatosFactura(res)
{
    
    $("#fNit").html(res.detalle.nit);
    $("#fnumero").html(res.nfac);
    $("#fauto").html(res.detalle.autorizacion);
    $("#fechaLimiteEmision").html(formato_fecha_corta(res.detalle.fechaLimite))
    
    var datos={
        nit:$("#clienteFacturaNit").html(),
        fecha:$("#fechaFactura").val(),
        monto:$("#totalsinformatobs").val()
    }
    console.log(datos);
    codigoControl(res,datos);
}
$(document).on("click",".agregarTodos",function(){

    var tabla2detalle=$("#tabla2detalle").bootstrapTable('getData');    
        AgregarRegistroTabla3Array(tabla2detalle)            
})
function vistaPreviaFactura()
{
    var tabla3factura=$("#tabla3Factura").bootstrapTable('getData');
    if(tabla3factura.length>0)
    {
         $("#cuerpoTablaFActura").html("");

    
        $.each(tabla3factura,function(index, value){
       
       console.log(value)
        var row =' <tr>'+
                '<td>'+value.cantidadReal+'</td>'+
                '<td>'+value.Sigla+'</td>'+
                '<td>'+value.CodigoArticulo+'</td>'+
                '<td>'+value.Descripcion+'</td>'+
                '<td class="text-right">'+formato_moneda(value.punitario)+'</td>'+
                '<td class="text-right">'+formato_moneda(value.total)+'</td>'+
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
        
        $("#facPrev").modal("show");    

    }
}
function AgregarRegistroTabla3Array(row)
{
    //si no existe ningun registro agregar de cualquier cliente //registro
    //si ya existe=> verificar si ya fue agregado el mismo //false
    //si ya existe=>verificar si es otro cliente //false

    var datos=JSON.stringify(row);
    console.log(row)
     $.ajax({
        type:"POST",
        url: base_url('index.php/Facturas/retornarTabla3Array'),
        dataType: "json",
        data: {rows:datos},
    }).done(function(res){
        
       if(res.detalle  && res.detalle.length!=0)
       {
           // console.log(res)
            
            $("#valuecliente").val(res.cliente);   
            $("#nombreCliente").val(res.cliente);          
            $("#valueidcliente").val(res.idCliente);           

         
                         
              //var tr=$('[data-index="'+index+'"]',"#tabla2detalle").addClass("danger")
            $("#clienteFactura").html(res.cliente);
            $("#clienteFacturaNit").html(res.clienteNit)
            $("#clientePedido").html(res.clientePedido)
            $.each(res.detalle,function(index, value){
                agregarRegistrosTabla3(value);    
                //console.log(value)
                $('[data-index="'+index+'"]',"#tabla2detalle").addClass("danger")
            })            
            calcularTotalFactura();
            $.each($("#tabla2detalle").find("tbody tr"),function(index,value){    
                $(value).addClass("danger");
            }) 
          
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

var data=[
    {
        "name": "bootstrap-table",
        "stargazers_count": "526",
        "forks_count": "122",
        "description": "An extended Bootstrap table with radio, checkbox, sort, pagination, and other added features. (supports twitter bootstrap v2 and v3)"
    },
    {
        "name": "multiple-select",
        "stargazers_count": "288",
        "forks_count": "150",
        "description": "A jQuery plugin to select multiple elements with checkboxes :)"
    },
    {
        "name": "bootstrap-show-password",
        "stargazers_count": "32",
        "forks_count": "11",
        "description": "Show/hide password plugin for twitter bootstrap."
    },
    {
        "name": "blog",
        "stargazers_count": "13",
        "forks_count": "4",
        "description": "my blog"
    },
    {
        "name": "scutech-redmine",
        "stargazers_count": "6",
        "forks_count": "3",
        "description": "Redmine notification tools for chrome extension."
    }
];
function tablaprueba()
{
     var $table = $('#table').bootstrapTable({
        idField: 'name',
        data: data,
        columns: [{
            field: 'name',
            title: 'Name',

        }, {
            field: 'stargazers_count',
            title: 'Stars',
            editable: {
                type: 'text',
                mode: 'inline',
                container: 'body',
                validate: validateNum
            }
        }, {
            field: 'forks_count',
            title: 'Forks',
            editable: {
                type: 'text',
                mode: 'inline',
                 container: 'body',
            }
        }, {
            field: 'description',
            title: 'Description',
            editable: {
                type: 'textarea',
                 container: 'body',
            }
        }]
    });
    $table.on('editable-save.bs.table', function (e, field, row, old, $el) {
        /*console.log(e)
        console.log(field)
        console.log(row)
        console.log($el)*/

        $el.text=old;
            return false;
    });
}
function validateNum(value) {
    value = $.trim(value);   
    //console.log(editable)
    if ($.trim(value) == '') {
        return 'El dato es requerido';
    }                                
    if(!$.isNumeric(value))
    {
        return 'Debe ingresar un numero';
    }
    if(value<0)
    {
        return 'no puede ser menor a 0';   
    }    
                            
    var data=$("#tabla3Factura").bootstrapTable('getData');
    console.log(data);
    index = $(this).parents('tr').data('index');
    row=(data[index]);
    console.log(row.cantidadRealAux)

    if(parseInt(value)>parseInt(row.cantidadRealAux))
    {
        return 'No puede ser mayor a '+row.cantidadRealAux;   
    }

   
}
$(document).on("click",".editable-click",function(){
    $(".tiponumerico").inputmask({
        alias:"decimal",
        digits:2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask:true
    });
})
$(document).on("click","#guardarFactura",function()
{
    //agregarcargando();
    var tabla3factura=$("#tabla3Factura").bootstrapTable('getData');
    tabla3factura=JSON.stringify(tabla3factura);
    var datos={
        almacen:$("#almacen_filtro").val(),
        fechaFac:$("#fechaFactura").val(),
        moneda:$("#moneda").val(),
        total:$("#totalFacturaBs").val(),
        observaciones:$("#observacionesFactura").val(),
        tipoFacturacion:$("#tipoFacturacion").val(),
        codigoControl:$("#codigoControl").html(),
        textqr:$("#textqr").val(),
        tabla:tabla3factura,
    
    }
    $.ajax({
            type:"POST",
            url: base_url('index.php/Facturas/guardarFactura'),
            dataType: "json",
            data: datos,
        }).done(function(res){
           if(res)
           {
                $("#tabla3Factura").bootstrapTable('removeAll');
                swal({
                        title: 'Factura Grabada!',
                        text: "La factura se guardó con éxito",
                        type: 'success', 
                        showCancelButton: false
                        }).then(
                          function(result) {
                        location.reload();
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
    
})
