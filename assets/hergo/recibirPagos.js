
let iniciofecha=moment().subtract(10, 'year').startOf('year')
let finfecha=moment().subtract(0, 'year').endOf('year')
let hoy = moment().format('DD-MM-YYYY');
let almacen = $("#almacen_filtro").val();
let fechaPagoHoy
let idPago=$("#idPago").val();
let idClientePago
let nombreCliente
let clienteCorrecto
let checkTipoCambio = false

$( function() {
    $("#cliente_factura").autocomplete(
    {      
      minLength: 3,
      autoFocus: true,
      source: function (request, response) {        
        $("#cargandocliente").show(150) 
        $("#clientecorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
        clienteCorrecto=false;
        $.ajax({
            url: base_url("index.php/Egresos/retornarClientes"),
            dataType: "json",
            data: {
                b: request.term
            },
            success: function(data) {
               response(data);    
               $("#cargandocliente").hide(150)
            }
          });        
       
    }, 
      select: function( event, ui ) {       
         
          $("#clientecorrecto").html('<i class="fa fa-check" style="color:#07bf52" aria-hidden="true"></i>')
          $("#cliente_factura").val( ui.item.nombreCliente + " - " + ui.item.documento)
          $("#idCliente_Pago").val( ui.item.idCliente)
          $("#errorCliente").removeClass("hidden")
          clienteCorrecto = true
          return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      
      return $( "<li>" )
        .append( "<a><div>" + item.nombreCliente + " </div><div style='color:#615f5f; font-size:10px'>" + item.documento + "</div></a>" )
        .appendTo( ul );
    };
 });
$(document).ready(function(){
    fileImg()
    if (idPago==0) {
        console.log(idPago)
        hoy = hoy
        console.log(hoy)
    } else {
        fechaEditar = $("#fechaEditar").val();
        hoy  = moment(fechaEditar).format('DD-MM-YYYY')
        console.log(hoy)
    }
    
    $('#fechaPago').daterangepicker({
        singleDatePicker: true,
        startDate:hoy,
        autoApply:true,
        locale: {
            format: 'DD-MM-YYYY'
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
    let start = moment().subtract(10, 'year').startOf('year')
    let end = moment().subtract(0, 'year').endOf('year')

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
function fileImg() {
    // Si FileUtils está disponible, usar su método para mostrar imagen previa
    if (typeof FileUtils !== 'undefined' && $('#img_route').length) {
        const existingImg = $('#img_route').data('existing-img');
        FileUtils.setupFileInput($('#img_route')[0], {}, existingImg);
        FileUtils.handleFileClear($('#img_route')[0], '#imagenEliminada');
    } else {
        // Fallback básico si FileUtils no está definido
        $("#img_route").fileinput({
            language: "es",
            showUpload: false,
            previewFileType: "image",
            maxFileSize: 1024,
            showPreview: false,
        });
    }
}
$(document).on("change","#fechaPago",function(){
    fechaPagoHoy = $('#fechaPago').val()
    let fecha = $('#fechaPago').val()
    if (hoy == fecha) {
        checkTipoCambio = true
    } else {
        console.log('buscar');
        $.ajax({
            type: "POST",
            async: false,
            url: base_url("index.php/Egresos/consultarTipoCambio"),
            dataType: "json",
            data: {
                fecha: fecha
            },
            success: function(data) {

                data ? checkTipoCambio = data : checkTipoCambio = false
               
            }
        });
        if (checkTipoCambio == false) {
            mostrarModal()
        } else {
            glob_tipoCambio = checkTipoCambio.tipocambio
            console.log(checkTipoCambio.fecha+ ' - ' +glob_tipoCambio)
        }
    }
}) 
$(document).on("click", "#setTipoCambio", function () {
    let fecha = $('#fechaPago').val()
    let tc = $('#tipocambio').val()
    if (!tc || tc == 0) {
        swal("Atencion!", "Ingrese tipo cambio valido")
        return false
    }
    $.ajax({
        type: "POST",
        url: base_url("index.php/Configuracion/updateTipoCambio"),
        dataType: "json",
        data: {
            id: 'egreso',
            fechaCambio: fecha,
            tipocambio: tc
        },
        success: function(data) {
            console.log(data);
            checkTipoCambio = true
            glob_tipoCambio = data.TipoCambio
            console.log(glob_tipoCambio);
            $('#modalTipoCambio').modal('hide')
            swal("Atencion!", "Agrego un tipo de cambio para " + formato_fecha_corta(data.fecha))
            $('#tipocambio').val('')
        }
    });
})
$(document).on("change","#cliente_factura",function(){
    idClientePago = $('#idCliente_Pago').val()
}) 
$(document).on("change","#almacen_filtro",function(){
    retornarPagosPendientes();
}) 
$(document).on("change","#almacen",function(){
    swal("Atencion!", "Usted esta cambiado de Almacen")
}) 
$(document).on("click", "#refresh", function () {
    retornarPagosPendientes();
})


function retornarPagosPendientes() //*******************************
{   
    ini='2010-01-01'
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

        let autor = new Array()
        let cliente = new Array()
        let datos =new Array()
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
function getIdAlmacen() {
    $.ajax({
    type:"POST",
    url: base_url('index.php/Facturas/tipoCambio'),
    dataType: "json",
    data: {},
    }).done(function(res){
    console.log(res.idAlmacenUsuario);
    glob_idAlmacenUsuario = res.idAlmacenUsuario;
    return glob_idAlmacenUsuario
    })
}
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
        for (let index = 0; index < res.detalle.length; index++) {
            
            saldoNuevo = Math.round(res.detalle[index].saldoNuevo*100)/100
            pagar = Math.round(res.detalle[index].pagar*100)/100
            saldoPago = Math.round(res.detalle[index].saldoPago*100)/100
            total = Math.round(res.detalle[index].total*100)/100
                res.detalle[index].saldoPago = parseFloat(total.toFixed(2))
                res.detalle[index].saldoNuevo = parseFloat(saldoNuevo.toFixed(2))
                res.detalle[index].pagar = parseFloat(pagar.toFixed(2))
        }
        data =  {
            almacen: res.cabecera.almacen,
            almacenes: [
                { alm: 'CENTRAL HERGO', value: '1' },
                { alm: 'DEPOSITO EL ALTO', value: '2' },
                { alm: 'POTOSI', value: '3' },
                { alm: 'SANTA CRUZ', value: '4' },
                ],
            fechaPago: res.cabecera.fechaPago,
            banco:res.cabecera.banco,
            transferencia:res.cabecera.transferencia,
            cheque:res.cabecera.cheque,
            cliente:res.cabecera.cliente,
            tipoPago: res.cabecera.idTipoPago,
                options: [
                { tipo: 'EFECTIVO', value: '1' },
                { tipo: 'TRANSFERENCIA', value: '2' },
                { tipo: 'CHEQUE', value: '3' },
                { tipo: 'TARJETA', value: '5' },
                { tipo: 'OTROS', value: '4' }
                ],
            totalPago:'',
            porPagar:res.detalle,
            anulado:0,
            moneda:1,
            glosa:res.cabecera.glosa,
            guardar:true,
            idPago:idPago,
            nombreCliente:res.cabecera.nombreCliente,
            numPago:res.cabecera.numPago,
            nombreCliente:res.cabecera.nombreCliente,
            img_route:res.cabecera.img_route,
        }
        console.log(data);
    })
    return data
}
function datosEditar(idPago) {
    let idAlmacenActual = $('#idAlmacenActual').val()
    if (idPago == 0) {
        data = {
                almacen:idAlmacenActual,
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
                cliente:'',
                tipoPago: '1',
                    options: [
                    { tipo: 'EFECTIVO', value: '1' },
                    { tipo: 'TRANSFERENCIA', value: '2' },
                    { tipo: 'CHEQUE', value: '3' },
                    { tipo: 'TARJETA', value: '5' },
                    { tipo: 'OTROS', value: '4' }
                    ],
                totalPago:'',
                porPagar:[],
                anulado:0,
                moneda:1,
                glosa:'',
                guardar:false,
                nombreCliente:'',
                numPago: '',
                img_route:''
        }
    } else {
        editarPago(idPago); 
    }
    return data
}
function mostrarModal()
{
    $('#modalTipoCambio').modal('show');
}

Vue.component('app-row',{
    
    template:'#row-template',
    props:['pagar','index'],

    data: function(){
        return{
            montopagar:0, 
            editing:false,            
            error:'',           
        }
    },
    created:function(){   
        this.montopagar=this.pagar.saldoPago;
    
    },
    methods:{     
        remove:function(){
            //console.log(vmPago.porPagar);
            //vm.tasks.splice(this.index,1);
            this.$emit('removerfila',this.index);
            vmPago.selectClient(vmPago.porPagar)
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
            vmPago.guardar=false;        
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
                
                //vmPago.guardar=true; **************************
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
    data:datosEditar(idPago),
   
    components: {
        vuejsDatepicker,
    },
    methods:{
        deleteRow:function(index){        
            this.porPagar.splice(index,1);
            if (this.porPagar.length>0){
                this.guardar=true;
            }
            else   {
                this.guardar=false;
            }
        },
        getImagen:function(e){
            let file = e.target.files[0]
            //console.log(file)
            this.img_route = file

        },
        agregarPago:function(row){
            //console.log(this.porPagar.map((el) => el.cliente));
            
            //this.selectClient(this.porPagar)
            if(this.porPagar.length>0)
            {                
                if(this.porPagar.map((el) => el.idFactura).indexOf(row.idFactura)>=0)
                {
                    swal("Atencion", "Esta factura ya fue agregada","info");
                    return false;
                }
                this.porPagar.push(row)
                //this.nombreCliente = row.nombreCliente
                //this.cliente = row.cliente
                this.selectClient(this.porPagar)
            }
            else
            {
                this.porPagar.push(row)
                //this.nombreCliente = row.nombreCliente
                //this.cliente = row.cliente
                this.selectClient(this.porPagar)
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
        editarPago:function(e){
            e.preventDefault();
            agregarcargando();  
            nombreCliente = $("#cliente_factura").val();
            fechaPagoHoy = $("#fechaPago").val();
            nombreCliente = $("#cliente_factura").val();
            cliente = $("#idCliente_Pago").val();
            let datos={
                almacen: this.almacen,
                fechaPago:fechaPagoHoy,
                moneda:this.moneda,
                cliente: cliente,
                totalPago:this.totalPago,
                anulado:this.anulado,
                glosa:this.glosa,
                tipoPago: this.tipoPago,
                cheque: this.cheque,
                banco:this.banco,
                transferencia:this.transferencia,
                porPagar:this.porPagar,
                guardar:true,
                idPago:idPago,
                nombreCliente:nombreCliente,
                img_route: this.img_route
            };
            let  numPago = this.numPago

            if (datos.cliente =='' || datos.nombreCliente == '') {
                quitarcargando();
                swal({
                    title: "No se puede guardar Pago",
                    text: "Seleccione cliente y pago",
                    type: "warning",        
                    allowOutsideClick: false,                                                                        
                 }).then(function(){
                     console.log('error en cliente')
                 })
                 return false
            }
            if (!this.guardar) {
                quitarcargando();
                swal("Atencion", "Confime ediciòn de monto de pago","info");
                return false
            }
            
            let formPagos = new FormData($('#formPagos')[0]);
                formPagos.append("porPagar", JSON.stringify(this.porPagar))
                formPagos.append("moneda", JSON.stringify(this.moneda))
                formPagos.append("anulado", JSON.stringify(this.anulado))
                formPagos.append("totalPago", JSON.stringify(this.totalPago))
                formPagos.append("img_name", (this.img_route))

            /* for(let pair of formPagos.entries()) {
                console.log(pair[0]+ ', '+ pair[1]); 
            } */
            $.ajax({
                url: base_url('index.php/Pagos/editarPagos'),
                type: "post",      
                data: formPagos,                                        
                processData: false,
                contentType: false,
                cache:false, 
            }).done(function(res){
                res = JSON.parse(res)
                if(res.status ==200)
                {
                    quitarcargando();
                    swal({
                        title: 'Pago Modificado',
                        text: `El pago ${numPago} por ${datos.totalPago.toFixed(2)} Bs. de ${nombreCliente} 
                                se modificó con éxito`,
                        type: 'success', 
                        showCancelButton: false,
                        allowOutsideClick: false,  
                    }).then(
                        function(result) {  
                            agregarcargando();                 
                            window.location.href = base_url("Pagos")
                            let imprimir = base_url("pdf/Recibo/index/") + res.id;
                            window.open(imprimir);
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
                })
            });
        },
        anularPago(){
            almForm = $('#almacen').val()
            almUser = $('#idAlmacenUsuario').val()
            isAdmin = $('#isAdmin').val()
            if (almForm != almUser && isAdmin == '') {
                swal("Error", "No se puede Anular", "error")
                return false
            }
            swal({
              title: 'Esta seguro?',
              text: `Se anulara el recibo ${this.numPago} de ${this.nombreCliente}`,      
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Aceptar',
              cancelButtonText:'Cancelar',                
            }).then(function () {
                swal({
                    title: 'Anular movimiento',
                    text: 'Cual es el motivo de anulacion?',
                    input: 'text',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText:'Cancelar',                
                }).then(function (texto) {
                     vmPago.anular(texto) 
                     console.log(texto);
                        swal({
                            type: 'success',
                            title: 'Anulado!',
                            allowOutsideClick: false, 
                            html: `RECIBO ${this.numPago} ANULADA POR:  ${texto}`
                        }).then(function(){ 
                            window.location.href = base_url("Pagos")  
                        })
                  })
            })
        },
        anular:function(texto){
            agregarcargando();
            $.ajax({
                type:"POST",
                url: base_url('index.php/Pagos/anularPago'),
                dataType: "json",
                data: {
                    idPago:this.idPago,
                    msj:this.glosa + ' ANULADO: ' + texto ,
                },
            }).done(function(res){
                if(res.status=200)
                {
                    
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
            })
        },
        cancelarPago:function(){
            window.location.href = base_url("Pagos")
        },
        guardarPago:function(e){
            e.preventDefault();
            agregarcargando();   
            almForm = $('#almacen').val()
            almUser = $('#idAlmacenUsuario').val()
            isAdmin = $('#isAdmin').val()
            if (almForm != almUser && isAdmin == '') {
                swal("Error", "No se puede guardar movimiento", "error")
                console.log('error');
                return false
            }
             nombreCliente = $("#cliente_factura").val();
             cliente = $("#idCliente_Pago").val();

            let datos={
                almacen: this.almacen,
                fechaPago: fechaPagoHoy,
                moneda:this.moneda,
                cliente: cliente,
                totalPago:this.totalPago,
                anulado:this.anulado,
                glosa:this.glosa,
                tipoPago: this.tipoPago,
                cheque: this.cheque,
                banco:this.banco,
                transferencia:this.transferencia,
                porPagar:this.porPagar,
                nombreCliente:nombreCliente,
                numPago: this.numPago,
                img_route: this.img_route
            };
          
           if (datos.cliente =='' || datos.nombreCliente == '') {
            quitarcargando();
            swal({
                title: "No se puede guardar Pago",
                text: "Seleccione cliente y pago",
                type: "warning",        
                allowOutsideClick: false,                                                                        
             }).then(function(){
                 console.log('error en cliente')
             })
             return false
           }
            if (!checkTipoCambio) {
                swal("Error", "No se tiene tipo de cambio para esta Fecha", "error")
                return false;
            }
            if (!this.guardar) {
                quitarcargando();
                swal("Atencion", "Confime ediciòn de monto de pago","info");
                return false
            }
            cliente = $("#idCliente_Pago").val();
            
            datos.cliente = cliente

            let clientes2 = datos.porPagar.map(p=>p.cliente)
            cliRed = clientes2.reduce((a , b) => (a == b) ? a : false)
            cliRed = (cliRed == false) ? 'varios' : cliRed

            let formPagos = new FormData($('#formPagos')[0]);
                formPagos.append("porPagar", JSON.stringify(this.porPagar))
                formPagos.append("moneda", JSON.stringify(this.moneda))
                formPagos.append("anulado", JSON.stringify(this.anulado))
                formPagos.append("totalPago", JSON.stringify(this.totalPago))
            for(let pair of formPagos.entries()) {
                console.log(pair[0]+ ', '+ pair[1]); 
            }

            $.getJSON({
                url: base_url('index.php/Pagos/guardarPagos'),
                type: "post",      
                data: formPagos,                                        
                processData: false,
                contentType: false,
                cache:false, 
            }).done(function(res){
                if(res.status == 200)
                {
                    quitarcargando();
                    swal({
                        title: 'Pago almacenado',
                        text: `El pago por ${datos.totalPago.toFixed(2)} Bs. de ${nombreCliente} se guardó con éxito`,
                        type: 'success', 
                        showCancelButton: false,
                        allowOutsideClick: false,  
                    }).then(
                        function(result) {   
                        agregarcargando();                 
                        location.reload();
                        let imprimir = base_url("pdf/Recibo/index/") + res.id;
                        window.open(imprimir);
                    });
                } else if (res.status ==400) {
                    quitarcargando();
                    let errors = ''
                    for (const prop in res.msj) {
                        errors += `${res.msj[prop]}`
                    }
                    swal({
                        title: 'Error',
                        html: `${errors}`,
                        type: 'error', 
                        showCancelButton: false,
                        allowOutsideClick: false,  
                    });
                } else {
                    swal({
                        title: 'Error',
                        text: "Error desconocido",
                        type: 'error', 
                        showCancelButton: false,
                        allowOutsideClick: false,  
                    })
                }
            }).fail(function( jqxhr, textStatus, error ) {
            let err = textStatus + ", " + error;
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
                    console.log(result)
                });
            });
        },
        selectClient:function (datosCliente) {
                if (datosCliente.length <= 0) {
                    this.cliente = ''
                this.nombreCliente = ''
                } else {
                    let clientes2 = datosCliente.map(p=>p.cliente)
                    cliRed = clientes2.reduce((a , b) => (a == b) ? a : false)
                    cliRed = (cliRed == false) ? 1 : cliRed
                    let clientesNombres = datosCliente.map(p=>p.nombreCliente)
                    clientesNombres = clientesNombres.reduce((a , b) => (a == b) ? a : false)
                    clientesNombres = (clientesNombres == false) ? 'VARIOS' : clientesNombres
                    this.cliente = cliRed
                    this.nombreCliente = clientesNombres
                    return false
                }
                

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

