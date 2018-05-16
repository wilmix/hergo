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
$(document).on("click", "#refresh", function () {
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
        url: base_url('index.php/Facturas/MostrarTablaConsultaFacturacion'),
        dataType: "json",
        data: {ini:ini,fin:fin,alm:alm,tipo:tipo},
    }).done(function(res){
         quitarcargando();
      //   console.log(res);
        datosselect= restornardatosSelect(res)

        $("#facturasConsulta").bootstrapTable('destroy');
        $('#facturasConsulta').bootstrapTable({
                   
            data:res,
            striped:true,
            pagination:true,
            pageSize:"25",    
            search:true,        
            //searchOnEnterKey:true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            filter:true,
            showColumns:true,

            columns: [            
            {
                field: 'lote',                
                title: 'lote',                            
                visible:false,
                //class:"col-sm-1",
            },
            {
                field: 'manual',                
                title: 'Tipo',                            
                visible:true,
                align: 'center',
                //class:"col-sm-1",
                    filter: {
                        type: "select",
                        data: ["Manual", "Computarizada"]
                      },
                    formatter: tipoDosificacion
            },

            
            //agregado para que muestre el numero de factura 
            {
                field:'nFactura',
                title:"N° Fac",
                sortable:true,
                //class:"col-sm-1",
                align: 'center',
               
            },
            {
                field:'fechaFac',
                title:"Fecha",
                //class:"col-sm-1",
                sortable:true,
                formatter: formato_fecha_corta,
            },
            {
                field:'ClienteNit',
                title:"N° Cliente",                
                //class:"col-sm-1",                                
                sortable:true,
                visible:false
            },
            {
                field:'ClienteFactura',
                title:"Cliente",                
                //class:"col-sm-4",         
                sortable:true,
                 filter: {
                    type: "select",
                    data: datosselect[0]
                }
            },
            {
                field:'sigla',
                title:"Movimiento",
                align: 'center',
                formatter: tipoNumeroMovimiento
                
            },
            {
                field:'total',
                title:"Total",                
                sortable:true,
                align: 'right',
                formatter:operateFormatter3,
                filter: { type: "input" },
            },
            {
                field:'vendedor',
                title:"Vendedor",
                sortable:true,
                align: 'center',
                filter: {
                    type: "select",
                    data: datosselect[1]
                }
            },
            /*{
                field:'estado',
                title:"Estado",
                //width: '7%',
                sortable:true,
                align: 'center',
                formatter: formatoEstadoFactura,
                filter: 
                {
                type: "select",
                data: ["T. Facturado", "No facturado","Facturado Parcial","ANULADO"],
                },                
            },*/
            {
                field:'pagada',
                title:"Pagado",
                sortable:true,
                align: 'center',
                formatter: formatoFacturaPagada,
                filter: 
                {
                type: "select",
                data: ["No Pagada", "T. Pagada","Parcial"],
                
                }               
            },           
            {
                title: 'Acciones',
                align: 'center',
                width: '10%',
                events: eventosBotones,                
                formatter: formatoBotones
            }]
            
        });
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
}

function restornardatosSelect(res)
{
    var cliente = new Array()
    let vendedor = new Array()
    var datos =new Array()
    $.each(res, function(index, value){

        cliente.push(value.ClienteFactura)
        vendedor.push(value.vendedor)
    })
    cliente.sort();
    datos.push(cliente.unique());
    datos.push(vendedor.unique());
    return(datos);
}
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});

function operateFormatter3(value, row, index)
    {       
        num=Math.round(value * 100) / 100
        num=num.toFixed(2);
        return (formatNumber.new(num));
    }

Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});

function tipoDosificacion(value, row, index) {
    $ret = ''
    if (row.manual == 1) {
        $ret = '<div style="font-size:1.5em;"><span class="fas fa-edit manual"></span></div>';
    } else {
        $ret = '<div style="font-size:1.5em; color:Tomato"><span class="fas fa-laptop computarizada"></span></div>';
        
    }
    return ($ret);
}
function tipoNumeroMovimiento(value, row, index) {
    $ret = row.sigla + "-" + row.movimientos;
    return ($ret);
}

function formatoBotones(value, row, index)
{
    if(row.anulada==1)
    {
        return [
        '<button type="button" class="btn btn-default verFactura"  aria-label="Right Align">',
        '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
        '<button type="button" class="btn btn-default "  disabled aria-label="Right Align">',
        '<span class="fa fa-times " aria-hidden="true"></span></button>',
        '<button type="button" class="btn btn-default printFactura" aria-label="Right Align">',
        '<span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>'
        ].join('');    
    }
    else
    {
        return [
        '<button type="button" class="btn btn-default verFactura"  aria-label="Right Align">',
        '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',        
        '<button type="button" class="btn btn-default anularFactura"  aria-label="Right Align">',
        '<span class="fa fa-times " aria-hidden="true"></span></button>',
        '<button type="button" class="btn btn-default printFactura" aria-label="Right Align">',
        '<span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>'
        ].join('');    
    }
    
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
function formatoFacturaPagada(value, row, index)
{
    $ret=''

    if(row.anulada==1)
    {        
        $ret='<span class="label label-warning">Anulada</span>';
    }
    else
    {
        if(value==0)
            $ret='<span class="label label-danger"><i class="fas fa-times-circle"> No Pagada</i></span>';
        if(value==1)
            $ret='<span class="label label-success"><i class="fas fa-check-circle"></i> T. Pagada</span>';
        if(value==2)
            $ret='<span class="label label-info"><i class="fas fa-exclamation-circle"></i> Parcial</span>';
    }
    
    return ($ret);
}

window.eventosBotones = {
    'click .verFactura': function (e, value, row, index) {          
    //console.log(row);    
        agregarcargando();    
        agregarDatosInicialesFacturaModal(row);
     //   verFacturaModal(row);

    },
    'click .printFactura': function (e, value, row, index) {
        //alert(JSON.stringify(row));
        let imprimir = base_url("pdf/Factura/index/") + row.idFactura;
        window.open(imprimir);
    },
    'click .anularFactura': function (e, value, row, index) {         
     
     /*   swal({
          title: '',
          text: "Se anulara la factura seleccionadahghghg",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',          
          confirmButtonText: 'Si, continuar',
          cancelButtonText: "Cancelar",
        }).then((result) => {       
             anularFactura(row); 
          
        })*/
        swal({
          title: 'Esta seguro?',
          text: 'Se anulara la factura seleccionada',         
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Aceptar',
          cancelButtonText:'Cancelar',                
        }).then(function () {
           anularFactura(row); 
           agregarcargando(); 
        })
        
    },      
};
function anularFactura(row)
{

     var data={
        idFactura:row.idFactura
    }
     $.ajax({
        type:"POST",
        url: base_url('index.php/Facturas/anularFactura'),
        dataType: "json",
        data:data
    }).done(function(res){    

       retornarTablaFacturacion();
      
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    }); 
}
function agregarDatosInicialesFacturaModal(row)
{
    var data={
        nFactura:row.nFactura,
        lote:row.lote,
        idFactura:row.idFactura
    }
     $.ajax({
        type:"POST",
        url: base_url('index.php/Facturas/mostrarDatosDetallesFactura'),
        dataType: "json",
        data:data
    }).done(function(res){
        if(res.response)
        {
            agregarDatosFacturaModal(res.datosFactura,row);
            mostrardatosmodal(res.detalleFactura);
        }      
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    }); 
}
function agregarDatosFacturaModal(res,row)
{
    
    //$("#fNit").html(res.detalle.nit);
    vmVistaPrevia.nit=res.detalle.nit;    
    //$("#fnumero").html(res.nfac);
    vmVistaPrevia.numero=res.nfac;
    //$("#fauto").html(res.detalle.autorizacion);
    vmVistaPrevia.autorizacion=res.detalle.autorizacion;
    //$("#fechaLimiteEmision").html(formato_fecha_corta(res.detalle.fechaLimite));
    vmVistaPrevia.fechaLimiteEmision=res.detalle.fechaLimite//formato_fecha_corta(res.detalle.fechaLimite);
    //$("#codigoControl").html(row.codigoControl)
    vmVistaPrevia.codigoControl=row.codigoControl;
    vmVistaPrevia.llave=res.detalle.llaveDosificacion;    
    var fecha = moment(row.fechaFac, 'YYYY-MM-DD');
    vmVistaPrevia.fecha=fecha;
    vmVistaPrevia.manual=parseInt(res.detalle.manual);



  /*  fechaFormato = fecha.format('YYYY-MM-DD');
    var datos={
        nit:row.ClienteNit,
        fecha:fechaFormato,
        monto:row.total,
    }
    codigoControl(res,datos);
    */
}
function mostrardatosmodal(data)
{

    //$("#cuerpoTablaFActura").html("");

     //   var totalfact=0;
        
              

        /*$.each(data.data2,function(index, value){   
            totalfact+=parseFloat(value.facturaCantidad*value.facturaPUnitario);
            var row =' <tr>'+
                    '<td>'+formato_moneda(value.facturaCantidad)+'</td>'+
                    '<td>'+value.Sigla+'</td>'+
                    '<td>'+value.ArticuloCodigo+'</td>'+
                    '<td>'+value.ArticuloNombre+'</td>'+
                    '<td class="text-right">'+formato_moneda(value.facturaPUnitario)+'</td>'+
                    '<td class="text-right">'+formato_moneda(value.facturaCantidad*value.facturaPUnitario)+'</td>'+
                  '</tr>'
            $("#cuerpoTablaFActura").append(row);
        });*/
        /******LUGAR y fecha*****/
        /*var fecha=data.data1.fechaFac;  
        var fechaFormato = moment(fecha, 'YYYY-MM-DD');
        var dia=fechaFormato.format("DD");
        var mes=fechaFormato.format("MMMM");
        var anio=fechaFormato.format("YYYY");    
        var LugarFecha=("La Paz, "+dia+" de "+mes+" de "+anio);
        $("#fechaFacturaModal").html(LugarFecha);*/
        //console.log(data.data2)
        vmVistaPrevia.datosFactura=data.data2;
        //$("#clienteFactura").html(data.data1.ClienteFactura)
        vmVistaPrevia.ClienteFactura=data.data1.ClienteFactura;        
        //$("#clienteFacturaNit").html(data.data1.ClienteNit);
        vmVistaPrevia.ClienteNit=data.data1.ClienteNit;
        //$("#notaFactura").html(data.data1.glosa);
        vmVistaPrevia.glosa=data.data1.glosa;

        tipocambioFactura=data.data1.cambiovalor;
        vmVistaPrevia.tipocambio=data.data1.cambiovalor;
        vmVistaPrevia.moneda=parseInt(data.data1.moneda);

        vmVistaPrevia.pedido=data.data3.pedido;
        //vmVistaPrevia.generarCodigoControl() //este dato se extrae de la base de datos, solo se usa para generar el codigo
        vmVistaPrevia.generarCodigoQr();
        console.log("REVISAR TIPO DE CAMBIO GUARDADO EN EL MOMENTO DE FACTURA")
      
        /***********LITERAL**************/
      //  $("#totalTexto").html(NumeroALetras(totalfact));
        /**********************************/
       /* $("#totalFacturaBsModal").html(formato_moneda(totalfact));
        $("#totalFacturaSusModal").html(formato_moneda(totalfact/tipocambioFactura));
        $("#tipoCambioFacturaModal").html(tipocambioFactura);*/
        $("#facPrev").modal("show"); 
        quitarcargando();

}
