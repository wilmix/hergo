var iniciofecha=moment().subtract(0, 'year').startOf('year')
var finfecha=moment().subtract(0, 'year').endOf('year')
let hoy = moment().format('MM-DD-YYYY');
let almacen = $("#almacen_filtro").val();
let fechaPagoHoy = $('.fecha_pago').val();
let idPago=$("#idPago").val();


$(document).ready(function(){
    
    $('.fecha_pago').daterangepicker({
        singleDatePicker: true,
        startDate:hoy,
        autoApply:true,
        locale: {
            format: 'MM-DD-YYYY'
        },
        showDropdowns: true,
      });


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
      retornarPagosPendientes();
    });
    retornarPagosPendientes();
})

$(document).on("change","#almacen_filtro",function(){
    retornarPagosPendientes();
}) 
$(document).on("click", "#refresh", function () {
    retornarPagosPendientes();
})


function retornarPagosPendientes() //*******************************
{   
    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Pagos/mostrarPendientePago'), //******controlador
        dataType: "json",
        data: {i:ini,f:fin,a:alm}, //**** variables para filtro
    }).done(function(res){
        quitarcargando();
        datosselect= restornardatosSelect(res)
        var num=0;
        $("#tPendientes").bootstrapTable('destroy');
        $("#tPendientes").bootstrapTable({            ////********cambiar nombre tabla viata
                data:res,                           
                    striped: true,
                    pagination: true,
                    pageSize: "25",
                    search: true,
                    filter: true,
                    showColumns: true,
                    strictSearch: true,
                    showToggle:true,
                columns:
                [                   
                    {   
                        field: 'almacen',            
                        title: 'Almacen',
                        visible:false,
                        sortable:true,
                        searchable: false,
                    },
                    {   
                        field: 'nFactura',            
                        title: 'N° Factura',
                        visible:true,
                        sortable:true,
                        searchable: true,
                    },
                    {   
                        field: 'fechaFac',            
                        title: 'Fecha',
                        visible:true,
                        sortable:true,
                        formatter: formato_fecha_corta,
                        searchable: false,
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
                        searchable: false,
                    },
                    {   
                        field: 'totalPago',            
                        title: 'Pagado',
                        visible:true,
                        sortable:true,
                        align: 'right',
                        formatter: operateFormatter3,
                        searchable: false,
                    },
                    {   
                        field: 'saldoPago',            
                        title: 'Saldo',
                        visible:true,
                        sortable:true,
                        align: 'right',
                        formatter: operateFormatter3,
                        searchable: false,
                    },
                    {   
                        field: 'glosaPago',            
                        title: 'Glosa',
                        visible:false,
                        sortable:true,
                        searchable: false,
                    },
                    {   
                        field: 'pagada',            
                        title: 'Estado',
                        visible:true,
                        sortable:true,
                        align: 'center',
                        formatter: operateFormatter2,
                        searchable: false,
                    },
                    {
                        title: '',
                        align: 'center',
                        events: operateEvents,
                        searchable: false,
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
       // return(num);
        return (formatNumber.new(num));
    }
  
    function operateFormatter(value, row, index)
    {
        return [
            '<button type="button" class="btn btn-default agregarFactura" aria-label="Right Align">',
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
        //console.log(cliente);
        return(datos);
    }
    Array.prototype.unique=function(a){
    return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
    });

/***********Eventos*************/
window.operateEvents = {
    'click .agregarFactura': function (e, value, row, index) {
        /*para corregir resultado de sum en mysql */
        num=Math.round(row.saldoPago * 100) / 100
        /***/
        row.saldoPago=parseFloat(num.toFixed(2));        
        row.pagar=row.saldoPago;
        row.saldoNuevo=0;        
        vmPago.agregarPago(row)
    },
};

/****************************************** */
let datos
function editarPago(idPago) {
    $.ajax({
        type:"POST",
        url: base_url('index.php/Pagos/retornarEdicion'),
        async: false,
        dataType: "json",
        data: {
            idPago:idPago
        },
    }).done(function(res){
        console.log(res);
        for (let index = 0; index < res.detalle.length; index++) {
            
            saldoNuevo = Math.round(res.detalle[index].saldoNuevo*100)/100
            pagar = Math.round(res.detalle[index].pagar*100)/100
            saldoPago = Math.round(res.detalle[index].saldoPago*100)/100
            total = Math.round(res.detalle[index].total*100)/100
                res.detalle[index].saldoPago = parseFloat(total.toFixed(2))
                res.detalle[index].saldoNuevo = parseFloat(saldoNuevo.toFixed(2))
                res.detalle[index].pagar = parseFloat(pagar.toFixed(2))
        }
        console.log(res.detalle);
        datos =  {
            almacen: res.cabecera.almacen,
            almacenes: [
                { alm: 'CENTRAL HERGO', value: '1' },
                { alm: 'DEPOSITO EL ALTO', value: '2' },
                { alm: 'POTOSI', value: '3' },
                { alm: 'SANTA CRUZ', value: '4' },
                ],
            tipoPago: res.cabecera.tipoPago,
            fechaPago: res.cabecera.fechaPago,
            banco:res.cabecera.banco,
            transferencia:res.cabecera.transferencia,
            cheque:res.cabecera.cheque,
            cliente:'1',
            tipoPago: res.cabecera.tipoPago,
                options: [
                { tipo: 'EFECTIVO', value: '1' },
                { tipo: 'TRANSFERENCIA', value: '2' },
                { tipo: 'CHEQUE', value: '3' }
                ],
            totalPago:'',
            porPagar:res.detalle,
            anulado:0,
            moneda:1,
            glosa:res.cabecera.glosa,
            guardar:false,
        }
    })
    
}

if (idPago == 0) {
    data = {
            almacen:'1',
                almacenes: [
                { alm: 'CENTRAL HERGO', value: '1' },
                { alm: 'DEPOSITO EL ALTO', value: '2' },
                { alm: 'POTOSI', value: '3' },
                { alm: 'SANTA CRUZ', value: '4' },
                ],
            tipoPago:'',
            fechaPago:hoy,
            banco:'1',
            transferencia:'',
            cheque:'',
            cliente:'1',
            tipoPago: '1',
                options: [
                { tipo: 'EFECTIVO', value: '1' },
                { tipo: 'TRANSFERENCIA', value: '2' },
                { tipo: 'CHEQUE', value: '3' }
                ],
            totalPago:'',
            porPagar:[],
            anulado:0,
            moneda:1,
            glosa:'',
            guardar:false,
    }
} else {
    editarPago(idPago); 
    data = datos
}

Vue.component('app-row',{
    
    template:'#row-template',
    props:['pagar','index'],

    data: function(){
        return{
            montopagar:10, 
            editing:false,            
            error:'',           
        }
    },
    created:function(){   
        this.montopagar=this.pagar.saldoPago;
    
    },
    methods:{     
        remove:function(){
            console.log(this.index);
            //vm.tasks.splice(this.index,1);
            this.$emit('removerfila',this.index);
        },
        update:function(){
            this.error="";
           
            if(this.montopagar>this.pagar.saldoPago)
            {
                this.error="El monto a pagar es mayor al saldo";
                vmPago.guardar=false;
                return false;
            }
            this.pagar.pagar=this.montopagar;
            this.editing=false;
           
            this.pagar.saldoNuevo=this.pagar.saldoPago-this.montopagar;
            vmPago.guardar=true;
        },
        discard:function(){
            this.editing=false
            this.montopagar=this.pagar.pagar;
        },
        edit:function(){    
            this.error="";     
            this.editing=true;
            this.montopagar=this.pagar.pagar;        
        },
        retornarSaldoNuevo:function(){
            var _saldoNuevo=this.pagar.saldoPago-this.montopagar;
            if(this.montopagar>this.pagar.saldoPago)        
            {
                this.error="El monto a pagar es mayor al saldo";                            
                vmPago.guardar=false;
            }
            else
            {
                this.error="";
                //vmPago.guardar=true;
            }
            return _saldoNuevo;
        },
        
        
       
    },
    filters:{
       
        moneda:function(value){
            num=Math.round(value * 100) / 100
            num=num.toFixed(2);
            //return(num);
            return numeral(num).format('0,0.00');            
        },                 
    },   
    directives: {
        inputmask: {
          bind: function(el, binding, vnode) {
            $(el).inputmask({
                alias:"decimal",
                digits:2,
                groupSeparator: ',',
                autoGroup: true,
                autoUnmask:true
            }, {
              isComplete: function (buffer, opts) {
                vnode.context.value = buffer.join('');
              }
            });
          },
        }
      },
    
});


var vmPago = new Vue({
    el: '#app',
    data:data,
    components: {
        vuejsDatepicker,
    },
    methods:{
        deleteRow:function(index){        
            this.porPagar.splice(index,1);
            if (this.porPagar.length>0)
                this.guardar=true;
            else   
                this.guardar=false;
        },
        agregarPago:function(row){
            if(this.porPagar.length>0)
            {                
                if(this.porPagar.map((el) => el.nFactura).indexOf(row.nFactura)>=0)
                {
                    swal("Atencion", "Esta factura ya fue agregada","info");
                    return false;
                }
                /*if(this.porPagar.map((el) => el.cliente).indexOf(row.cliente)<0)
                {
                    swal("Atencion", "No se pueden agregar diferentes clientes","info");
                    return false;
                }*/
                this.porPagar.push(row)
                console.log(this.porPagar);                
            }
            else
            {
                this.porPagar.push(row)
            }                      
            this.guardar=true;
        },
        retornarTotal:function(){
            var total=0
            $.each(this.porPagar,function(index,value){
                total+=parseFloat(value.pagar);
            })
            this.totalPago=total
            return total;
        },
        guardarPago:function(){
            agregarcargando();
            var datos={
                almacen: this.almacen,
                fechaPago:moment(this.fechaPago).format('YYYY-MM-DD'),
                moneda:this.moneda,
                cliente: this.cliente,
                totalPago:this.totalPago,
                anulado:this.anulado,
                glosa:this.glosa,
                tipoPago: this.tipoPago,
                cheque: this.cheque,
                banco:this.banco,
                transferencia:this.transferencia,
                porPagar:this.porPagar,
            };

            let clientes = datos.porPagar.map(p=>p.cliente)
            let cliente = clientes.reduce( (a,b) => a==b )
            if (cliente) {
                datos.cliente=clientes[0]
            }
            datos=JSON.stringify(datos);
            if(!this.guardar)
            {
                quitarcargando();
                swal("Error", "No se puede guardar el pago","error");
                return false;
            }
            $.ajax({
                type:"POST",
                url: base_url('index.php/Pagos/guardarPagos'), //******controlador
                dataType: "json",
                data: {d:datos},
            }).done(function(res){
                if(res.status=200)
                {
                    quitarcargando();
                    swal({
                        title: 'Pago almacenado',
                        text: `El pago se guardó con éxito`,
                        type: 'success', 
                        showCancelButton: false,
                        allowOutsideClick: false,  
                    }).then(
                        function(result) {   
                        agregarcargando();                 
                        location.reload();
                    });
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
                }).then(
                function(result) {   
                    agregarcargando();                 
                    location.reload();
                });
            });
        },
        customFormatter(date) {
            return moment(date).format('DD MMMM YYYY');
        },       
    },
    filters:{
        moneda:function(value){
            num=Math.round(value * 100) / 100
            num=num.toFixed(2);
            //return(num);
            return numeral(num).format('0,0.00');            
        },   
        
                            
    },        
});

