
$(document).ready(function() {
    $('#form_FacturaManual').bootstrapValidator({
        feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
        },
        excluded: ':disabled',
        fields: {          
            newNumFac: {
                validators: {
                    notEmpty: {
                        message: 'Establesca número de Factura'
                    },
                    numeric: {
                        message: 'Debe ser de tipo Numérico'
                    }                   
                }
            },
            newFechaFac: {
                validators: {
                    notEmpty: {
                        message: 'Establesca fecha de Factura'
                    },
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'La fecha no es valida Ej: 2019-01-31 (año-mes-dia)'
                    }
                }
            },
        }
        }).on('success.form.bv', function(e) {
            e.preventDefault();
            let formData = new FormData($('#form_FacturaManual')[0]);  
        $.ajax({
                url: base_url("index.php/Facturas/updateFacturaManual"),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                    console.log(returndata);
                    if (returndata == true) {
                        limpiarModal()
                        resetForm('#form_FacturaManual')
                        swal({
                            title: 'Se establecio el tipo de cambio Correctamente',
                            type: 'success',
                            showCancelButton: false,
                            allowOutsideClick: false,
                        })
                        $('#modalFacturaManual').modal('hide')
                        limpiarModal()
                        retFacturasManuales()
                    } else {
                        swal(
                            'Error',
                            returndata,
                            'error'
                        )
                    }
                },
                error : function (returndata) {
                    swal(
                        'Error',
                        'El numero de Factura ya existe, corrobore los datos',
                        'error'
                    )
                    //limpiarModal()
                    //console.log(returndata);
                },
            });
        });
});
$(document).on("click", "#refresh", function () {
    retFacturasManuales();
})
function retFacturasManuales()
{
    agregarcargando();
    alm=$("#almacen_filtro").val()
    lote=$("#lote_filtro").val()
    $.ajax({
        type:"POST",
        url: base_url('index.php/Facturas/showFactuasManuales'),
        dataType: "json",
        data: {alm:alm,lote:lote},
    }).done(function(res){
         quitarcargando();
        //datosselect= restornardatosSelect(res)

        $("#facturasManuales").bootstrapTable('destroy');
        $('#facturasManuales').bootstrapTable({
                   
            data:res,
            striped:true,
            pagination:false,
            pageSize:"100",    
            search:true,        
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            filter:true,
            showColumns:true,
            strictSearch: true,
            showToggle:true,
            columns: [            
            {
                field: 'idFactura',                
                title: 'ID',                            
                visible:true,
                searchable: false,
            },
            {
                field: 'lote',                
                title: 'Lote',                            
                visible:true,
                searchable: false,
            },
            {
                field:'nFactura',
                title:"N° Fac",
                sortable:true,
                align: 'center',
            },
            {
                field:'fechaFac',
                title:"Fecha",
                align:'center',
                sortable:true,
                formatter: formato_fecha_corta,
                searchable: false,
            },
            {
                field:'ClienteNit',
                title:"N° Cliente",                
                sortable:true,
                visible:false,
                searchable: false,
            },
            {
                field:'ClienteFactura',
                title:"Cliente",                
                sortable:true,
                /*filter: {
                    type: "select",
                    data: datosselect[0]
                }*/
            },
            {
                field:'moneda',
                title:'Moneda',                
                visible:false,
                /*filter: {
                            type: 'select',
                            data: datosselect[3]
                        }*/
            },
            {
                field:'total',
                title:"Total",                
                sortable:true,
                align: 'right',
                searchable: false,
                width:'100px',
                formatter:operateFormatter3,
            },
            {
                field:'autor',
                title:"Emitido por:",
                align: 'center',
                visible:false,
            },
            {
                field:'fecha',
                title:"Fecha",
                align:'center',
                sortable:true,
                visible:false,
                searchable: false,
            },
            {
                title: 'Modificar',
                align: 'center',
                width: '10%',
                width:'150px',
                searchable: false,
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
function formatoBotones(value, row, index)
{
        return [
        '<button type="button" class="btn btn-default update"  aria-label="Right Align" data-toggle="tooltip" title="Modificar">',
        '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>',        
        ].join('');    
}
window.eventosBotones = {
    'click .update': function (e, value, row, index) {          
        showModal(row)
    },
};
function showModal(row)
{
    nFacAnt = row.nFactura
    fechaFacAnt = formato_fecha_corta(row.fechaFac)
    nomCliente = row.ClienteFactura
    $("#id").val(row.idFactura)
    $("#alm").val(row.almacen)
    $("#fechaLimite").val(row.fechaLimite)
    $("#desde").val(row.desde)
    $("#hasta").val(row.hasta)
    $("#msjeBeforeUpdate").html(`Se modificará la factura de <b>${nomCliente}</b>, número  <b>${nFacAnt}</b> del  <b>${fechaFacAnt}</b> por:`)
    $('#modalFacturaManual').modal('show');
    
}
$(document).on("click",".botoncerrarmodal",function(){
    limpiarModal()
})

function limpiarModal(fia)
{
    resetForm('#form_FacturaManual')
     $("#msjeBeforeUpdate").val('')
    $("#id").val('')
    $("#newNumFac").val('')
    $("#newFechaFac").val('')

}