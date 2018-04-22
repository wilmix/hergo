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
            let valuesToSubmit = $("#form_tipoCambio").serialize();  
            let formData = new FormData($('#form_tipoCambio')[0]);  
            $.ajax({
                url: base_url("index.php/Configuracion/agregarTipoCambio"),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                    $(".mensaje_ok").html("Se establecio el tipo de cambio Correctamente");
                    $("#modal_ok").modal("show");
                    $('#contact-form-success').show().fadeOut(1000);
                    $('#modalTipoCambio').modal('hide');
                    setTipoCambio();
                    retornarTablaTipoCambio();
                }
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
                }
            ]
         });
   
   
   }).fail(function (jqxhr, textStatus, error) {
       var err = textStatus + ", " + error;
       console.log("Request Failed: " + err);
   });
}