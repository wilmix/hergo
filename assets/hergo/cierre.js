
$(document).ready(function(){ 
    $("#steps").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true
    })

    $('#form_tipoCambio').bootstrapValidator({
             
        feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
        },
        excluded: ':disabled',
        fields: {          
            tipocambio: {
                validators: {
                    notEmpty: {
                        message: 'Establesca nuevo tipo De cambio'
                    },
                    numeric: {
                        message: 'Debe ser de tipo Numérico'
                    }                   
                }
            },
            fechaCambio: {
                validators: {
                    notEmpty: {
                        message: 'Establesca fecha de tipo de cambio'
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
            let formData = new FormData($('#form_tipoCambio')[0]);  
        $.ajax({
                url: base_url("index.php/Configuracion/updateTipoCambio"),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                    limpiarModal()
                    resetForm('#form_tipoCambio')
                    swal({
                        title: 'Se establecio el tipo de cambio Correctamente',
                        type: 'success',
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                    $('#modalTipoCambio').modal('hide')
                    limpiarModal()
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
})
$(function() {
    $('#fechaCambio').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
        format: 'YYYY-MM-DD'
        }
    });
});
$(document).on("click", "#backupDB", function () {
    agregarcargando()
    let backup = base_url("cierre/backupDataBase/")
    console.log(backup)
    location.href = (backup)
    quitarcargando()
})


$(document).on("click", "#btnVerificarPendientes", function () {
    retornarVerificarPendientes();
})
$(document).on("click", "#btnVerificarNegativos", function () {
    retornarVerificarNegativos();
})
function limpiarModal(fia)
{
    $( ".fecha-cambio" ).removeClass( "hidden" );   
     $("#fechaTipoCambio").val('')
     $("#fechaTitulo").html('')
    $("#id").val('')
    $("#fechaCambio").val('')
    $("#tipocambio").val('')

}
function retornarVerificarPendientes()
{
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Cierre/showPendientes'),
        dataType: "json",
        data: {
        }, 
    }).done(function (res) {
        quitarcargando();
        console.log(res);
        //datosselect = restornardatosSelect(res);
        $("#verificarPendientes").bootstrapTable('destroy');
        $("#verificarPendientes").bootstrapTable({ 
            data: res,
            striped: true,
            searchOnEnterKey: true,
            showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            //rowStyle:rowStyle,
            columns: 
            [
                {
                    field: 'mov',
                    title: 'Movimiento',
                    width:'100px',
                    sortable: true,
                },
                {
                    field: 'almacen',
                    title: 'Almacen',
                    width:'200px',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'tipo',
                    title: 'Tipo Mov.',
                    sortable: true,
                    width:'200px',
                    align: 'center',
                },
                {
                    field: 'fecha',
                    title: 'Fecha',
                    sortable: true,
                    width:'100px',
                    align: 'center',
                },
                {
                    field: 'nmov',
                    title: 'Nº Mov',
                    sortable: true,
                    width:'50px',
                    align: 'center',
                },
                {
                    field: 'nombre',
                    title: 'Cliente | Provedor',
                    sortable: true,
                    align: 'center',
                },
                
            ]
        });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function retornarVerificarNegativos()
{
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Cierre/showNegativos'),
        dataType: "json",
        data: {
        }, 
    }).done(function (res) {
        quitarcargando();
        console.log(res);
        //datosselect = restornardatosSelect(res);
        $("#verificarNegativos").bootstrapTable('destroy');
        $("#verificarNegativos").bootstrapTable({ 
            data: res,
            striped: true,
            filter: true,
            //stickyHeader: true,
            //stickyHeaderOffsetY: '50px',
            //rowStyle:rowStyle,
            columns: 
            [
                {
                    field: 'id',
                    title: 'id',
                    sortable: true,
                    visible: false
                },
                {
                    field: 'almacen',
                    title: 'Almacen',
                    width:'200px',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'codigo',
                    title: 'Codigo',
                    width:'80px',
                    sortable: true,
                },
                {
                    field: 'Descripcion',
                    title: 'Descripción',
                    sortable: true,
                    width:'500px',
                    align: 'center',
                },
                {
                    field: 'ingresos',
                    title: 'Ingresos',
                    sortable: true,
                    width:'10px',
                    align: 'center',
                },
                {
                    field: 'notaEntrega',
                    title: 'Nota Entrega',
                    sortable: true,
                    width:'10px',
                    align: 'center',
                },
                {
                    field: 'Facturado',
                    title: 'Facturado',
                    sortable: true,
                    width:'10px',
                    align: 'center',
                },
                {
                    field: 'otros',
                    title: 'Otros',
                    sortable: true,
                    width:'10px',
                    align: 'center',
                },
                {
                    field: 'saldo',
                    title: 'Saldo',
                    sortable: true,
                    width:'10px',
                    align: 'center',
                },
                {
                    field: 'saldoAlm',
                    title: 'SaldoAlm',
                    sortable: true,
                    width:'10px',
                    align: 'center',
                    visible: false
                },
                
            ]
        });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function base_url(complemento) {
    complemento = (complemento) ? complemento : '';
    var baseurl = $('#baseurl').val();
    return baseurl + complemento;
  }
  function resetForm(id) {
    $(id)[0].reset();
    $(id).bootstrapValidator('resetForm', true);
  }
  function agregarcargando() {
    $("#cargando").css("display", "block")
  }
  
  function quitarcargando() {
    $("#cargando").css("display", "none")
  }