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
      retornarTablaIngresos();
    });
    retornarTablaIngresos();
})
$(document).on("change","#almacen_filtro",function(){
    retornarTablaIngresos();
})
$(document).on("change","#tipo_filtro",function(){
    retornarTablaIngresos();
})


function retornarTablaIngresos()
{


    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val()
    tipoingreso=$("#tipo_filtro").val()
    console.log(tipoingreso)
    $.ajax({
        type:"POST",
        url: base_url('index.php/ingresos/mostrarIngresosDetalle'),
        dataType: "json",
        data: {i:ini,f:fin,a:alm,ti:tipoingreso},
    }).done(function(res){
       //datosselect= restornardatosSelect(res)
       //console.log((datosselect))
console.log(res)
        $("#tbconsultadetalle").bootstrapTable('destroy');
        $('#tbconsultadetalle').bootstrapTable({

                data:res,
                striped:true,
                pagination:true,
                pageSize:"100",
                clickToSelect:true,
                search:true,
                strictSearch:true,
                searchOnEnterKey:true,
                filter:true,
                showColumns:true,
                columns: [
                {
                    field: 'nmov',
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
                    field:'nombreproveedor',
                    title:"Proveedor",
                    width: '17%',
                    sortable:true,
                },
                {
                    field:'nfact',
                    title:"Factura",
                    width: '7%',
                    sortable:true,
                    
                },
                {
                    field:'CodigoArticulo',
                    title:"Codigo",
                    width: '7%',
                    align: 'right',
                    sortable:true,
                    
                },
                {
                    field:"Descripcion",
                    title:"Descripción",
                    width: '17%',
                    sortable:true,
                
                    align: 'center'
                },
                {
                    field:"cantidad",
                    title:"Cantidad",
                    width: '7%',
                    sortable:true,
                 
                    align: 'center'
                },
                {
                    field:"total",
                    title:"Monto",
                    width: '8%',
                    sortable:true,
                  
                    align: 'center'
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
                    formatter: formato_fecha_corta,
                    visible:false,
                    align: 'center'
                }]
    
          });

        $("#tbconsultadetalle").bootstrapTable('hideLoading');
        $("#tbconsultadetalle").bootstrapTable('resetView');

    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
    //$("body").css("padding-right","0px");

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
            $ret='<span class="label label-danger">PENDIENTE</span>';
        if(value==1)
            $ret='<span class="label label-success">APROBADO</span>';
    }
    
    return ($ret);
}
function operateFormatter3(value, row, index)
{       
    num=Math.round(value * 100) / 100
    return (formatNumber.new(num));
   //return(num)
}

/*******************formato de numeros***************/
var formatNumber = {
 separador: ",", // separador para los miles
 sepDecimal: '.', // separador para los decimales
 formatear:function (num){
  num +='';
  var splitStr = num.split('.');
  var splitLeft = splitStr[0];
  var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
  var regx = /(\d+)(\d{3})/;
  while (regx.test(splitLeft)) {
  splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
  }
  return this.simbol + splitLeft  +splitRight;
 },
 new:function(num, simbol){
  this.simbol = simbol ||'';
  return this.formatear(num);
 }
}
