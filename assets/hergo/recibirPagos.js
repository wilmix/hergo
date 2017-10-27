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
    var actual=moment().subtract(0, 'yevr').startOf('year')
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
      retornarPagosPendientes();
    });
    retornarPagosPendientes();
})
$(document).on("change","#almacen_filtro",function(){
    retornarPagosPendientes();
}) //para cambio filtro segun cada uno


function retornarPagosPendientes() //*******************************
{   
    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Pagos/mostrarPendientePago'), //******controlador
        dataType: "json",
        data: {i:ini,f:fin,a:alm}, //**** variables para filtro
    }).done(function(res){
        datosselect= restornardatosSelect(res)
        $("#tPendientes").bootstrapTable('destroy');
        $("#tPendientes").bootstrapTable({            ////********cambiar nombre tabla viata
                data:res,           
                    striped:true,
                    pagination:true,
                    pageSize:"100",
                    search:true,
                    searchOnEnterKey:true,
                    showColumns:true,
                    filter:true,
                columns:
                [
                    {   
                        field: 'almacen',            
                        title: 'Almacen',
                        visible:false,
                        sortable:true,
                    },
                    {   
                        field: 'nFactura',            
                        title: 'N° Factura',
                        visible:true,
                        sortable:true,
                    },
                    {   
                        field: 'fechaFac',            
                        title: 'Fecha',
                        visible:true,
                        sortable:true,
                        formatter: formato_fecha_corta,
                    },
                    {   
                        field: 'nombreCliente',            
                        title: 'Cliente',
                        visible:true,
                        sortable:true,
                        filter: 
                            {
                                type: "select",
                                data: datosselect[1]
                            },
                    },
                    {   
                        field: 'total',            
                        title: 'Total',
                        visible:true,
                        sortable:true,
                        align: 'right',
                        formatter: operateFormatter3,
                    },
                    {   
                        field: 'saldoPago',            
                        title: 'Saldo',
                        visible:true,
                        sortable:true,
                        align: 'right',
                        formatter: operateFormatter3,
                    },
                    {   
                        field: 'glosaPago',            
                        title: 'Glosa',
                        visible:false,
                        sortable:true,
                    },
                    {   
                        field: 'pagada',            
                        title: 'Estado',
                        visible:true,
                        sortable:true,
                        align: 'center',
                        formatter: operateFormatter2,
                        filter: 
                                {
                                type: "select",
                                data: ["A Cuenta", "No Pagada"],
                                },
                        formatter: operateFormatter2,
                    },
                    {
                        title: '',
                        align: 'center',
                        //width: '15%',
                        events: operateEvents,
                        formatter: operateFormatter
                    },
                ]
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
    function operateFormatter(value, row, index)
    {
        return [
            '<button type="button" class="btn btn-default añadirFactura" aria-label="Right Align">',
            '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>',
        ].join('');
    }
    function operateFormatter2(value, row, index)
    {
        $ret=''
        // 0=factura pagada totalmente 1=Factura pagada parcialmente 2=Pago Anulado
        if(value==0)
            $ret='<span class="label label-danger">No Pagada</span>';
         if(value==1)
            $ret='<span class="label label-success">Pagada</span>';
        if(value==2)
            $ret='<span class="label label-primary">A Cuenta</span>';
        return ($ret);
    }

function restornardatosSelect(res)
{

    var autor = new Array()
    var cliente = new Array()
    var datos =new Array()
    $.each(res, function(index, value){

        autor.push(value.autor)
        cliente.push(value.nombreCliente)
    })

    autor.sort();
    cliente.sort();
    
    datos.push(autor.unique());
    datos.push(cliente.unique());
    console.log(cliente);
    return(datos);
}
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});

/***********Eventos*************/
window.operateEvents = {
    'click .verPago': function (e, value, row, index) {
        //verdetalle(row)
    },
    'click .editarPago': function (e, value, row, index) {
      //console.log(row.idPagos);
            
    },
    'click .imprimirPago': function (e, value, row, index) {
     //alert(JSON.stringify(row));
    }
};