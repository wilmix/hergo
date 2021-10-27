
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
}) 
$(document).on("click", "#refresh", function () {
    retornarTablaPagos();
})


function retornarTablaPagos()
{   
    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Pagos/mostrarPagos'),
        dataType: "json",
        data: {i:ini,f:fin,a:alm},
    }).done(function(res){
        quitarcargando();
        datosselect= restornardatosSelect(res)
        $("#tpagos").bootstrapTable('destroy');
        $("#tpagos").bootstrapTable({

                data:res,           
                    striped: true,
                    //pagination: false,
                    //pageSize: "100",
                    search: true,
                    filter: true,
                    showColumns: true,
                    stickyHeader: true,
                    stickyHeaderOffsetY: '50px',
                    strictSearch: false,
                    showToggle:true,
                    //strictSearch: true,
                columns:
                [
                    {   
                        field: 'idPago',            
                        title: 'ID',
                        visible:false,
                        sortable:true,
                        searchable: false,
                        align: 'center',
                    },
                    {   
                        field: 'almacen',            
                        title: 'Almacen',
                        visible:alm == '' ? true : false,
                        sortable:true,
                        searchable: false,

                    },
                    {   
                        field: 'numPago',            
                        title: 'N° Pago',
                        visible:true,
                        sortable:true,
                        searchable: true,
                        align: 'center',
                        filter: 
                            {
                                type: "select",
                                data: datosselect[2]
                            },
                        
                    },
                    {   
                        field: 'fechaPago',            
                        title: 'Fecha Pago',
                        visible:true,
                        sortable:true,
                        formatter: formato_fecha_corta,
                        searchable: false,
                        align: 'center',
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
                        field: 'banco',            
                        title: 'Banco',
                        visible:true,
                        sortable:true,
                        searchable: false,
                        align: 'center',
                    },
                    {   
                        field: 'totalPago',            
                        title: 'Monto Pago',
                        visible:true,
                        sortable:true,
                        align: 'right',
                        formatter: operateFormatter3,
                        searchable: false,
                    },  
                    {   
                        field: 'anulado',            
                        title: 'Anulado',
                        visible:false,
                        sortable:true,
                        searchable: false,
                    },
                    {   
                        field: 'tipoPago',            
                        title: 'Tipo',
                        visible:true,
                        align: 'center',
                        formatter: tipoPago,
                        filter: 
                        {
                            type: "select",
                            data: datosselect[3]
                        }
                    },
                    {   
                        field: 'transferencia',            
                        title: '# Transacción',
                        visible:true,
                        sortable:true,
                        searchable: true,
                        align: 'center',
                    },
                    {   
                        field: 'pagada',            
                        title: 'Estado',
                        sortable:true,
                        formatter: operateFormatter2,
                        searchable: false,
                        align: 'center',
                        visible:false,
                    },
                    {   
                        field: 'autor',            
                        title: 'Recibido por:',
                        //visible:false,
                        sortable:true,
                        filter: 
                            {
                                type: "select",
                                data: datosselect[0]
                            },
                    },
                    {   
                        field: 'fecha',            
                        title: 'Fecha Registro',
                        visible:false,
                        sortable:true,
                        searchable: false,
                    },
                    {   
                        field: 'img_route',            
                        title: 'Comprobante',
                        align: 'center',
                        searchable: false,
                        formatter: mostrarimagen
                    },   
                    {
                        title: 'Acciones',
                        align: 'center',
                        width: '150px',
                        searchable: false,
                        events: operateEvents,
                        formatter: operateFormatter
                    },
                    {   
                        field: 'rTotalPago',            
                        title: '',
                        visible:false,
                        sortable:true,
                        align: 'right',
                        searchable: true,
                    },  
                ]
            });
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
}

    function mostrarimagen(value, row, index)
    {
        let ruta=""
        let imagen=""
        if((value=="")||(value==null))
        {
            ruta=""
            clase=""
        }
        else
        {
            clase="imagenminiatura"
            ruta="assets/img_pagos/"+value
            imagen = '<div class="contimg"><img src="'+base_url(ruta)+'" class="'+clase+'"></div>'
            return [imagen].join('')
        }

    }
    $(document).on("click",".imagenminiatura",function(){
        rutaimagen=$(this).attr('src')
        window.open(rutaimagen);
    })
    function operateFormatter3(value, row, index)
    {       
        num=Math.round(value * 100) / 100
        num=num.toFixed(2);
        return (formatNumber.new(num));
    }
    function operateFormatter(value, row, index)  {
        if(row.anulado==1)    
            return [
                '<button type="button" class="btn btn-default verPago" aria-label="Right Align" data-toggle="tooltip" title="Ver Pago">',
                '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
                '<button type="button" class="btn btn-default imprimirPago" aria-label="Right Align" data-toggle="tooltip" title="Imprimir">',
                '<span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>',
                '<button type="button" class="btn btn-default "  disabled aria-label="Right Align">',
                '<span class="fa fa-times " aria-hidden="true"></span></button>',
            ].join('');
        else
            return [
                '<button type="button" class="btn btn-default verPago" aria-label="Right Align" data-toggle="tooltip" title="Ver Pago">',
                '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
                '<button type="button" class="btn btn-default imprimirPago" aria-label="Right Align" data-toggle="tooltip" title="Imprimir">',
                '<span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>',
                '<button type="button" class="btn btn-default editarPago" aria-label="Right Align" data-toggle="tooltip" title="Editar">',
                '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>'
            ].join('');
    }
    function operateFormatter2(value, row, index) {
        let $ret=''
        if(row.anulado==1){
            $ret='<span class="label label-warning">Anulado</span>';
        } else {
            // 0=factura NO Pagada 1=Factura pagada 2=Pago  parcialmente a cuetna
            if(value==0)
                $ret='<span class="label label-danger">No Pagada</span>';
            if(value==1)
                $ret='<span class="label label-success">Pagada</span>';
            if(value==2)
                $ret='<span class="label label-info">A Cuenta</span>';
        }
        return ($ret);
    }
    function tipoPago (value, row, index) {
        let $ret=''
        if(row.anulado==1){
            $ret='<span class="label label-warning">Anulado</span>';
        } else {
           $ret = row.tipoPago
        }
        return ($ret);
    }
    function operateFormatterEstado(value, row, index) {
        let $ret=''
            // 0=factura NO Pagada 1=Factura pagada 2=Pago  parcialmente a cuetna
            if(value==0)
                $ret='No Pagada';
            if(value==1)
                $ret='Pagada';
            if(value==2)
                $ret='A Cuenta';
        
        return ($ret);
    }
    function restornardatosSelect(res)
    {

    let autor = new Array()
    let cliente = new Array()
    let numPago = new Array()
    let tipoPago = new Array()
    let datos =new Array()
    $.each(res, function(index, value){

        autor.push(value.autor)
        cliente.push(value.nombreCliente)
        numPago.push(value.numPago)
        tipoPago.push(value.tipoPago)
    })

    autor.sort()
    cliente.sort()
    tipoPago.sort()
    datos.push(autor.unique())
    datos.push(cliente.unique())
    datos.push(numPago.unique())
    datos.push(tipoPago.unique())
    return(datos)
    }
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});

/***********Eventos*************/
window.operateEvents = {
    'click .verPago': function (e, value, row, index) {
        verdetalle(row)
    },
    'click .imprimirPago': function (e, value, row, index) {
        //alert(JSON.stringify(row));
        let imprimir = base_url("pdf/Recibo/index/") + row.idPago;
        //console.log(imprimir);
        window.open(imprimir);
    },
    'click .editarPago': function (e, value, row, index) {
        console.log(row);
        almForm = row.idAlmacenPago
        almUser = $('#idAlmacenUsuario').val()
        isAdmin = $('#isAdmin').val()
        if (almForm != almUser && isAdmin == '') {
            swal("Error", "No se puede Editar", "error")
            return false
        }
        let editar = base_url("Pagos/editarPago/") + row.idPago;
        window.location.href = editar;
        //window.open(editar);
    },
},
    
    

function anularPago(idPago) {
    agregarcargando(); 
    $.ajax({
        type:"POST",
        url: base_url('index.php/Pagos/anularPago'),
        dataType: "json",
        data: {
            idPago:idPago,
        },
    }).done(function(res){
        if(res.status=200)
        {
            //retornarTablaPagos()       
            quitarcargando()
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

function verdetalle(row) {
    agregarcargando();           
    $.ajax({
        type:"POST",
        url: base_url('index.php/Pagos/retornarDetallePago'), //******controlador
        dataType: "json",
        data: {
            idPago:row.idPago
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

let vm=new Vue({
    el:'#app',
    data:{
       almacen:'',
       fecha:'',
       cliente:'',       
       numPago:'',
       anulado:'',
       glosa:'',
       tipoPago:'',
       banco:'',
       transferencia:'',
       cheque:'',
       tabla:[],
       img_route:'',

    },
    methods:{
        cargarDatos:function(res){         
            
            this.tabla=res;
            this.almacen=res[0].almacen;
            this.fecha=res[0].fechaPago;
            this.cliente=res[0].nombre;
            this.numPago=res[0].numPago;
            this.anulado=res[0].anulado;
            this.glosa=res[0].glosa
            this.tipoPago=res[0].tipoPago
            this.transferencia=res[0].transferencia
            this.banco=res[0].banco
            this.cheque=res[0].cheque
            this.img_route =res[0].img_route
            
            $("#modalPagos").modal("show");
        },
        retornarTotal:function(){
            var total=0
            $.each(this.tabla,function(index,value){
                total+=parseFloat(value.monto);
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
        estado:function(value){
            return operateFormatterEstado(value)
        },
    }

})