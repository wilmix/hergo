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
         console.log(res);
        datosselect= restornardatosSelect(res)

        $("#facturasConsulta").bootstrapTable('destroy');
        $('#facturasConsulta').bootstrapTable({
                   
            data:res,
            striped:true,
            pagination:true,
            pageSize:"100",    
            search:true,        
            searchOnEnterKey:true,
            filter:true,
            showColumns:true,

            columns: [            
            {
                field: 'lote',                
                title: 'lote',                            
                visible:false,
            },
            //agregado para que muestre el tipo de movimiento (NOTA DE EGRESO = NE o VENTA CAJA =)
            {
                field:'sigla',
                title:"Tipo",
                sortable:true,
                visible:false,
                class:"col-sm-1",
                align: 'center',
                
            },
            //agregado para que muestre el numero de factura 
            {
                field:'nFactura',
                title:"N° Fac",
                sortable:true,
                class:"col-sm-1",
                align: 'center',
                filter: { type: "input" },
                
            },
            {
                field:'movimientos',
                title:"Moviemiento",
                sortable:true,
                class:"col-sm-1",
                align: 'center',
                
            },
            {
                field:'fechaFac',
                title:"Fecha",
                class:"col-sm-1CF",
                sortable:true,
                formatter: formato_fecha_corta,
            },
            {
                field:'ClienteNit',
                title:"N° Cliente",                
                class:"col-sm-1",                                
                sortable:true,
                visible:false
            },
            {
                field:'ClienteFactura',
                title:"Cliente",                
                class:"col-sm-4",         
                sortable:true,
                filter: 
                {
                    type: "select",
                    data: datosselect[1]
                },

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
                field:'estado',
                title:"Estado",
                width: '7%',
                sortable:true,
                align: 'center',
                formatter: formatoEstadoFactura,
                filter: 
                {
                type: "select",
                data: ["T. Facturado", "Facturado Parcial","ANULADO"],
                },                
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
function operateFormatter3(value, row, index)
    {       
        num=Math.round(value * 100) / 100
        num=num.toFixed(2);
        return (formatNumber.new(num));
    }
function restornardatosSelect(res)
{

    var autor = new Array()
    var cliente = new Array()
    var datos =new Array()
    $.each(res, function(index, value){

        autor.push(value.autor)
        cliente.push(value.ClienteFactura)
    })

    autor.sort();
    cliente.sort();
    datos.push(autor.unique());
    datos.push(cliente.unique());
    return(datos);
}
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});

function formatoBotones(value, row, index)
{
    if(row.anulada==1)
    {
        return [
        '<button type="button" class="btn btn-default verFactura"  aria-label="Right Align">',
        '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
        '<button type="button" class="btn btn-default "  disabled aria-label="Right Align">',
        '<span class="fa fa-times " aria-hidden="true"></span></button>',
        ].join('');    
    }
    else
    {
        return [
        '<button type="button" class="btn btn-default verFactura"  aria-label="Right Align">',
        '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',        
        '<button type="button" class="btn btn-default anularFactura"  aria-label="Right Align">',
        '<span class="fa fa-times " aria-hidden="true"></span></button>',
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
function anularFactura(row)
{
     var data={
        idFactura:row.idFactura
    }
     $.ajax({
        type:"POST",
        url: base_url('index.php/facturas/anularFactura'),
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
    }
     $.ajax({
        type:"POST",
        url: base_url('index.php/facturas/mostrarDatosFactura'),
        dataType: "json",
        data:data
    }).done(function(res){
        if(res.response)
        {
            agregarDatosFacturaModal(res,row);
        }      
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    }); 
}
function agregarDatosFacturaModal(res,row)
{
    
    $("#fNit").html(res.detalle.nit);
    $("#fnumero").html(res.nfac);
    $("#fauto").html(res.detalle.autorizacion);
    $("#fechaLimiteEmision").html(formato_fecha_corta(res.detalle.fechaLimite))
    var datos={
        nit:row.ClienteNit,
        fecha:row.fechaFac,
        monto:row.total,
    }
  //  console.log(datos);
    codigoControl(res,datos);
}
function verFacturaModal(row)
{     
    var data={
        idFactura:row.idFactura
    }
     $.ajax({
        type:"POST",
        url: base_url('index.php/facturas/mostrarDetalleFactura'),
        dataType: "json",
        data:data
    }).done(function(res){
        console.log(res)
        mostrardatosmodal(res);
       
      // calcularTotalFactura();
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    }); 
}
function mostrardatosmodal(data)
{

    $("#cuerpoTablaFActura").html("");

        var totalfact=0;
        $.each(data.data2,function(index, value){   
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
        });
        /******LUGAR y fecha*****/
        var fecha=data.data1.fechaFac;  
        var fechaFormato = moment(fecha, 'YYYY-MM-DD');
        var dia=fechaFormato.format("DD");
        var mes=fechaFormato.format("MMMM");
        var anio=fechaFormato.format("YYYY");    
        var LugarFecha=("La Paz, "+dia+" de "+mes+" de "+anio);
        $("#fechaFacturaModal").html(LugarFecha);
        $("#clienteFactura").html(data.data1.ClienteFactura)
        $("#clienteFacturaNit").html(data.data1.ClienteNit)
        $("#notaFactura").html(data.data1.glosa)        
        $("#facPrev").modal("show"); 
        tipocambioFactura=data.data1.cambiovalor;
        console.log("REVISAR TIPO DE CAMBIO GUARDADO EN EL MOMENTO DE FACTURA")
      
        /***********LITERAL**************/
        $("#totalTexto").html(NumeroALetras(totalfact));
        /**********************************/
        $("#totalFacturaBsModal").html(formato_moneda(totalfact));
        $("#totalFacturaSusModal").html(formato_moneda(totalfact/tipocambioFactura));
        $("#tipoCambioFacturaModal").html(tipocambioFactura);


}
