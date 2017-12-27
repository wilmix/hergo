
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
      retornarTablaPagos();
    });
    retornarTablaPagos();
})
$(document).on("change","#almacen_filtro",function(){
    retornarTablaPagos();
}) //para cambio filtro segun cada uno



function retornarTablaPagos() //*******************************
{   
    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Pagos/mostrarPagos'), //******controlador
        dataType: "json",
        data: {i:ini,f:fin,a:alm}, //**** variables para filtro
    }).done(function(res){
        console.log(res);
        console.log(alm);
        datosselect= restornardatosSelect(res)
        $("#tpagos").bootstrapTable('destroy');
        $("#tpagos").bootstrapTable({            ////********cambiar nombre tabla viata

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
                        field: 'idPagos',            
                        title: 'ID',
                        visible:false,
                        sortable:true,
                    },
                    {   
                        field: 'almacen',            
                        title: 'Almacen',
                        visible:false,
                        sortable:true,
                    },
                    {   
                        field: 'numPago',            
                        title: 'N° Pago',
                        visible:true,
                        sortable:true,
                        width: '4%',
                        filter: 
                            {
                                type: "select",
                                data: datosselect[2]
                            },
                    },
                    {   
                        field: 'fechaPago',            
                        title: 'Fecha',
                        visible:true,
                        sortable:true,
                        width: '10%',
                        formatter: formato_fecha_corta,
                    },
                    {   
                        field: 'sigla',            
                        title: 'Moneda',
                        visible:false,
                        sortable:true,
                        width: '1%',
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
                        field: 'nFactura',            
                        title: 'N°Fac',
                        visible:true,
                        sortable:true,
                        filter: { type: "input" },
                    },                   
                    {   
                        field: 'montoPago',            
                        title: 'Monto Pago',
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
                        field: 'anulado',            
                        title: 'Anulado',
                        visible:false,
                        sortable:true,
                    },                 
                    {   
                        field: 'autor',            
                        title: 'Autor',
                        visible:false,
                        sortable:true,
                    },
                    {   
                        field: 'fecha',            
                        title: 'Fecha',
                        visible:false,
                        sortable:true,
                    },
                    {
                        title: 'Acciones',
                        align: 'center',
                        width: '15%',
                        events: operateEvents,
                        formatter: operateFormatter
                    }


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
     
        if(row.anulado==0)    
            return [
                '<button type="button" class="btn btn-default verPago" aria-label="Right Align">',
                '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
                '<button type="button" class="btn btn-default anularPago" aria-label="Right Align" title="Anular">',
                '<span class="fa fa-ban" aria-hidden="true"></span></button>',
                '<button type="button" class="btn btn-default imprimirPago" aria-label="Right Align">',
                '<span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>'
            ].join('');
        else
            return [
                '<button type="button" class="btn btn-default verPago" aria-label="Right Align">',
                '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
                '<button type="button" class="btn btn-default recuperarPago" aria-label="Right Align" title="Recuperar">',
                '<span class="fa fa-check-square-o" aria-hidden="true"></span></button>',
                '<button type="button" class="btn btn-default imprimirPago" aria-label="Right Align">',
                '<span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>'
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
            $ret='<span class="label label-info">A Cuenta</span>';
        return ($ret);
    }

function restornardatosSelect(res)
{

    var autor = new Array()
    var cliente = new Array()
    var nPago = new Array()
    var datos =new Array()
    $.each(res, function(index, value){

        autor.push(value.autor)
        cliente.push(value.nombreCliente)
        nPago.push(value.numPago)
    })

    autor.sort();
    cliente.sort();
    nPago.sort();
    console.log(nPago);
    datos.push(autor.unique());
    datos.push(cliente.unique());
    datos.push(nPago.unique());
    return(datos);
}
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});

/***********Eventos*************/
window.operateEvents = {
    'click .verPago': function (e, value, row, index) {
        verdetalle(row)
    },
    'click .anularPago': function (e, value, row, index) {
        console.log(this);       
        var anulado=0;
        if($(this).hasClass('anularPago'))
            anulado=0;
        else
            anulado=1;
        anularRecuperarPago(row,this,anulado);
    },
    'click .recuperarPago': function (e, value, row, index) {      
        var anulado=0;
        if($(this).hasClass('recuperarPago'))
            anulado=1;
        else
            anulado=0;
        anularRecuperarPago(row,this,anulado);
    },
    'click .imprimirPago': function (e, value, row, index) {
     //alert(JSON.stringify(row));
    }
};
function anularRecuperarPago(row,t,anulado)
{
    agregarcargando(); 
    $.ajax({
        type:"POST",
        url: base_url('index.php/Pagos/anularRecuperarPago'),
        dataType: "json",
        data: {
            numPago:row.numPago,
            anulado:anulado
        },
    }).done(function(res){
        if(res.status=200)
        {
            console.log($(t));
            if(anulado==0)
            {
                $(t).removeClass('anularPago');
                $(t).addClass('recuperarPago');
                $(t).html('<span class="fa fa-check-square-o" aria-hidden="true"></span></button>');
            }
            else
            {
                $(t).removeClass('recuperarPago');
                $(t).addClass('anularPago');
                $(t).html('<span class="fa fa-ban" aria-hidden="true"></span></button>');
            }                    
            quitarcargando();
        }
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
        quitarcargando();
        swal({
            title: 'Error',
            text: "Intente nuevamente",
            type: 'error', 
            showCancelButton: false,
            allowOutsideClick: false,  
        })
    });
}

function verdetalle(row)
{
    
    agregarcargando();           
               
    $.ajax({
        type:"POST",
        url: base_url('index.php/Pagos/retornarDetallePago'), //******controlador
        dataType: "json",
        data: {
            numPago:row.numPago
        },
    }).done(function(res){
        quitarcargando();
        vm.cargarDatos(res);
       
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
        quitarcargando();
        swal({
            title: 'Error',
            text: "Intente nuevamente",
            type: 'error', 
            showCancelButton: false,
            allowOutsideClick: false,  
        })
    });
    
}
var vm=new Vue({
    el:'#app',
    data:{
       almacen:'',
       fecha:'',
       cliente:'',       
       numPago:'',
       anulado:'',
       tabla:[],

    },
    methods:{
        cargarDatos:function(res){         
            this.almacen=res[0].almacen;
            this.fecha=res[0].fechaPago;
            this.cliente=res[0].nombreCliente;
            this.numPago=res[0].numPago;
            this.anulado=res[0].anulado;
            this.tabla=res;
            $("#modalPagos").modal("show");
        },
        retornarTotal:function(){
            var total=0
            $.each(this.tabla,function(index,value){
                console.log(value);
                total+=parseFloat(value.montoPago);
            })
            return total;
        },
    },
    filters:{
        literal:function(value){                            
            return NumeroALetras(value)
        },
        moneda:function(value){
            return numeral(value).format('0,0.00');
        },
        fechaCorta:function(value){
            return formato_fecha_corta(value);
        },  
    }

})