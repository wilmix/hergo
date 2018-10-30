
$(document).ready(function() {
    retornarTablaTipoCambio();
    $('#form_tipoCambio').bootstrapValidator({
             
        feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
        },
        excluded: ':disabled',
        fields: {          
            tipoCambio: {
                validators: {
                    notEmpty: {
                        message: 'Establesca nuevo tipo ce cambio'
                    },
                    numeric: {
                        message: 'Debe ser de tipo Numérico'
                    }                   
                }
            },
        }
        }).on('success.form.bv', function(e) {
            e.preventDefault();
            let formData = new FormData($('#form_tipoCambio')[0]);  
            console.log(formData)
        $.ajax({
                url: base_url("index.php/Configuracion/updateTipoCambio"),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                    swal({
                        title: 'Se establecio el tipo de cambio Correctamente',
                        type: 'success',
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                    $('#modalTipoCambio').modal('hide');
                    retornarTablaTipoCambio();
                },
                error : function (returndata) {
                    swal(
                        'Error',
                        'La fecha actual ya tiene un tipo de cambio registrada en la base de datos',
                        'error'
                    )
                    //console.log(returndata);
                },
            }); 
        });
});
$(document).on("click",".botoncerrarmodal",function(){
    resetForm('#form_tipoCambio')
 })

 function retornarTablaTipoCambio() {
    agregarcargando();
   $.ajax({
       type: "POST",
       url: base_url('index.php/Configuracion/mostrarTipoCambio'),
       dataType: "json",
       data: {},
   }).done(function (res) {
       quitarcargando();
       $("#tablaTipoCambio").bootstrapTable('destroy');    
       $("#tablaTipoCambio").bootstrapTable({ ////********cambiar nombre tabla viata
           data: res,
           //showToggle: true,
           toolbarAlign:'right',
           toggle:'table',
           striped: true,
           pagination: true,
           pageSize: "10",
           //search: true,
           showColumns: true,
           filter: true,
           stickyHeader: true,
           stickyHeaderOffsetY: '50px',
           columns: 
            [
                {   
                field: 'id',            
                title: 'ID',
                align: 'center',
                sortable: true,
                visible: false
                },
                {   
                field: 'fecha',            
                title: 'Fecha',
                align: 'center',
                sortable: true,
                formatter: formato_fecha_corta
                },
                {   
                field: 'tipocambio',            
                title: 'Tipo Cambio',
                align: 'right',
                sortable: true,
                },
                {   
                field: 'autor',            
                title: 'Autor',
                align: 'center',
                sortable: true,
                },
                {   
                field: '',            
                title: '',
                align: 'center',
                sortable: true,
                formatter: operateFormatter,
                events:operateEvents
                }
            ]
         });
   
   
   }).fail(function (jqxhr, textStatus, error) {
       var err = textStatus + ", " + error;
       console.log("Request Failed: " + err);
   });
   
}
function operateFormatter(value, row, index){
    return [
        '<a class="editar" title="editar" data-toggle="modal" data-target="#editar" style="cursor:pointer">',
        '<div><i class="fas fa-pencil-alt fa-lg"></i></div>',
        '</a>  '       
    ].join('');
}
window.operateEvents = {
    'click .editar': function (e, value, row, index) {
        //console.log('You click like action, row: ' + JSON.stringify(row));
         //resetForm('#form_datosFactura')
         mostrarModal(row)
           // $("#tarticulo").bootstrapTable('hideLoading');            
    },
}
function mostrarModal(fila)
{
    let fecha = formato_fecha_corta(fila.fecha)
    $("#fechaTipoCambio").html(fecha)
    $("#tipocambio").val(fila.tipocambio)
    $("#id").val(fila.id)
    $("#fecha").val(fila.fecha)


    $('#modalTipoCambio').modal('show');
  
}
