
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
$(document).on("change", "#fechaCambio", function () {
    let fecha = $('#fechaCambio').val()
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
$(document).on("click", "#generarCierre", function () {
    agregarcargando()
    let fecha = $('#fechaCambio').val()
    console.log(fecha);
    //return false
    $.ajax({
        type: "POST",
        url: base_url('index.php/cierre/generarCierre'),
        dataType: "json",
        data: { fecha: fecha,
        },
    }).done(function (res) {
        quitarcargando(); 
        console.log(res);

    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
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

  function mantenerMenu() {
    var pathname = window.location.pathname;
    var dir = pathname.split("/")
    var menu = dir[dir.length - 1];
    var x = $("#masterMenu").find("." + menu).addClass("active").closest(".treeview").addClass("active");;
  
  }
  function formato_fecha_corta(value, row, index) {
    var fecha = ""
    //console.log(value)
    if ((value == "0000-00-00 00:00:00") || (value == "") || (value == null))
      fecha = "sin fecha de registro"
    else
      fecha = moment(value, "YYYY/MM/DD HH:mm:ss").format("DD/MM/YYYY")
    return fecha
  }

  function mostrarModal()
{
    $('#modalTipoCambio').modal('show');
}
$(document).on("click", "#setTipoCambio", function () {
    let fecha = $('#fechaCambio').val()
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
            swal("Atencion!", "Agrego un tipo de cambio para" + formato_fecha_corta(data.fecha))
            $('#tipocambio').val('')
        }
    });
})