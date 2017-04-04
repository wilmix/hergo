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

    $('#tbconsultadetalle').bootstrapTable({
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
                    field:'',
                    title:"Codigo",
                    width: '7%',
                    align: 'right',
                    sortable:true,
                    formatter: operateFormatter3,
                },
                {
                    field:"",
                    title:"Descripción",
                    width: '17%',
                    sortable:true,
                    formatter: operateFormatter2,
                    align: 'center'
                },
                {
                    field:"",
                    title:"Cantidad",
                    width: '7%',
                    sortable:true,
                    formatter: operateFormatter2,
                    align: 'center'
                },
                {
                    field:"",
                    title:"Monto",
                    width: '8%',
                    sortable:true,
                    formatter: operateFormatter2,
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
}

function retornarTablaIngresos2()
{


    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val()
    tipoingreso=$("#tipo_filtro").val()
    console.log(tipoingreso)
    $.ajax({
        type:"POST",
        url: base_url('index.php/ingresos/mostrarIngresos'),
        dataType: "json",
        data: {i:ini,f:fin,a:alm,ti:tipoingreso},
    }).done(function(res){
       datosselect= restornardatosSelect(res)
       //console.log((datosselect))
        $("#tingresos").bootstrapTable('destroy');
        $("#tingresos").bootstrapTable({

            data:res,
            striped:true,
            pagination:true,
            pageSize:"100",
            //height:"550", error con filtros
            clickToSelect:true,
            search:true,
            strictSearch:true,
            searchOnEnterKey:true,
            filter:true,
            showColumns:true,



            columns:[
            {
                field: 'n',
                width: '70px',
                title: 'N',
                align: 'center',
                sortable:true,
                filter: {type: "input"}
            },
            {
                field: 'sigla',
                width: '70px',
                title: 'Tipo',
                align: 'center',
                sortable:true,
                
                filter: {
                        type: "select",
                        data: datosselect[1]
                    }
            },
            {
                field:'fechamov',
                width: '90px',
                title:"Fecha",
                sortable:true,
                align: 'center',
                
                formatter: formato_fecha_corta,
            },
            {
                field:'nombreproveedor',
                title:"Proveedor",
                filter: {
                    type: "select",
                    data: datosselect[0]
                },
                sortable:true,
                
            },
            {
                field:'nfact',
                title:"Factura",
                width: '90px',
                sortable:true,
                //searchable:false,
                filter: {type: "input"},
                
                
            },
            {
                field:'total',
                title:"Total",
                width: '150px',
                align: 'right',
                sortable:true,
                formatter: operateFormatter3,
                filter: {type: "input"},
                
            },
            {
                field:"estado",
                title:"Estado",
                width: '90px',
                sortable:true,
                filter: {
                    type: "select",
                    data:["APROBADO","PENDIENTE","ANULADO"]
                },
                formatter: operateFormatter2,
                align: 'center',
                
            },
            {
                field:"autor",
                width: '100px',
                title:"Autor",
                sortable:true,
                filter: {
                    type: "select",
                    data: datosselect[2]
                },
                visible:false,
                align: 'center',
                
            },
            {
                field:"fecha",
                width: '90px',
                title:"Fecha",
                sortable:true,
                formatter: formato_fecha_corta,
                visible:false,
                align: 'center',
                
            },
            {
                title: 'Acciones',
                align: 'center',
                width: '150px',
                events: operateEvents,
                formatter: operateFormatter
            }]
        });

        $("#tarticulo").bootstrapTable('hideLoading');
        $("#tarticulo").bootstrapTable('resetView');

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
