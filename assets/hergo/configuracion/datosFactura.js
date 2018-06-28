$(document).ready(function() {
    retornarTablaDatosFactura();
    $('#form_datosFactura').bootstrapValidator({
             
        feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
        },
        excluded: ':disabled',
        fields: {          
            almacen: {
                validators: {
                    notEmpty: {
                        message: 'Elija almacen'
                    }                    
                }
            },
            autorizacion: {
                validators: {
                    notEmpty: {
                        message: 'Inserte número de autorización'
                    },
                    numeric: {
                        message: 'Debe ser de tipo Numérico'
                    }
                }
            },
            desde: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese valor'
                    },
                    numeric: {
                        message: 'Debe ser de tipo Numérico'
                    }                    
                }
            },
            hasta: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese valor'
                    },
                    numeric: {
                        message: 'Debe ser de tipo Numérico'
                    }                   
                }
            },


            fechaLimite: {
                validators: {
                    notEmpty: {
                        message: 'Elija fecha limite de emisión'
                    },
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'Fecha no valida'
                    }                    
                }
            },
            tipo: {
                validators: {
                    notEmpty: {
                        message: 'Elija el tipo'
                    }                    
                }
            },
            llave: {
                validators: {
                    notEmpty: {
                        message: 'Inserte llave de dosificacion'
                    }                    
                }
            },
            uso: {
                validators: {
                    notEmpty: {
                        message: 'Elija si  esta en uso'
                    }                    
                }
            }

        }
        }).on('success.form.bv', function(e) {
            e.preventDefault();
            let valuesToSubmit = $("#form_datosFactura").serialize();  
            let formData = new FormData($('#form_datosFactura')[0]);  
            console.log(formData)      
            $.ajax({
                url: base_url("index.php/Configuracion/agregarDatosFactura"),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                   $(".mensaje_ok").html(" Los datos se guardaron correctamente");
                    $("#modal_ok").modal("show");
                    $('#contact-form-success').show().fadeOut(1000);
                    $('#modalDatosFactura').modal('hide');
        
                    retornarTablaDatosFactura();
                }
            }); 
        });





});

function retornarTablaDatosFactura() {
     agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Configuracion/mostrarDatosFactura'),
        dataType: "json",
        data: {},
    }).done(function (res) {
        quitarcargando();
        datosselect = restornardatosSelect(res);
        $("#tablaDatosFactura").bootstrapTable('destroy');    
        $("#tablaDatosFactura").bootstrapTable({ ////********cambiar nombre tabla viata
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
                    field: 'idDatosFactura',            
                    title: 'Lote',
                    align: 'center',
                    sortable: true,
                },
                {   
                    field: 'almacen',            
                    title: 'Almacen',
                    align: 'center',
                    filter: {
                                type: "select",
                                data: datosselect[0]
                            }
                },
                {   
                    field: 'nit',            
                    title: 'NIT',
                    align: 'center',
                    visible: false,
                },
                {   
                    field: 'autorizacion',            
                    title: 'Autorización',
                    align: 'center',
                },
                {   
                    field: 'desde',            
                    title: 'Desde',
                    align: 'center',
                    align: 'right'
                },
                {   
                    field: 'hasta',            
                    title: 'Hasta',
                    align: 'right'
                },
                {   
                    field: 'fechaLimite',            
                    title: 'Fecha Limite',
                    align: 'center',
                    formatter: formato_fecha_corta
                },
                {   
                    field: 'manual',            
                    title: 'Tipo',
                    align: 'center',
                    filter: {
                        type: "select",
                        data: ["Manual", "Computarizada"]
                      },
                    formatter: tipoDosificacion
                },
                {   
                    field: 'enUso',            
                    title: 'Estado',
                    align: 'center',
                    filter: {
                        type: "select",
                        data: ["En Uso", ""]
                      },
                    formatter: estadoDosificacion
                },
                {   
                    field: 'llaveDosificacion',            
                    title: 'Llave de Dosificación',
                },
                {   
                    field: 'glosa01',            
                    title: 'Leyenda 01',
                    visible: false
                },
                {   
                    field: 'glosa02',            
                    title: 'Leyenda 02',
                    visible: false
                },
                {   
                    field: 'glosa03',            
                    title: 'Leyenda 03',
                    visible: false
                },
                {   
                    field: 'fecha',            
                    title: 'Fecha',
                    visible: false
                },
                {   
                    field: 'autor',            
                    title: 'Autor',
                    visible: false
                },
                {   
                    field: 'acciones',            
                    title: 'Editar',
                    align: 'center',
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
function tipoDosificacion(value, row, index) {
    $ret = ''
    if (row.manual == 1) {
        $ret = '<div style="font-size:1.5em;"><span class="fas fa-edit manual"></span></div>';
    } else {
        $ret = '<div style="font-size:1.5em; color:Tomato"><span class="fas fa-laptop computarizada"></span></div>';
        
    }
    return ($ret);
}
function estadoDosificacion(value, row, index) {
    $ret = ''
    if (row.enUso == 1) {
        $ret = '<div style="font-size:1.5em; color:Green"><span class="fas fa-check-circle en uso"></span></div>';
    } else {
        $ret = '';
    }
    return ($ret);
}
function restornardatosSelect(res) {
    var almacen = new Array()
    var datos = new Array()
    $.each(res, function (index, value) {
        almacen.push(value.almacen)
    })
    almacen.sort();
    datos.push(almacen.unique());
    return (datos);
}
    Array.prototype.unique = function (a) {
        return function () {
            return this.filter(a)
        }
    }(function (a, b, c) {
        return c.indexOf(a, b + 1) < 0
    });

function asignarselect(text1,select)
{
    $("option",select).filter(function() {
        var aux=$(this).text()
        return aux.toUpperCase() == text1.toUpperCase();
    }).prop('selected', true);
}
function mostrarModal(fila)
{
    console.log(fila)
    $("#id_lote").val(fila.idDatosFactura)
    $(".modalTitulo").html("Editar Dosificación")
    asignarselect(fila.almacen,"#almacen")
    $("#autorizacion").val(fila.autorizacion)
    $("#desde").val(fila.desde)
    $("#hasta").val(fila.hasta)
    $("#fechaLimite").val(fila.fechaLimite)
    $("#tipo").val(fila.manual)
    $("#llave").val(fila.llaveDosificacion)
    console.log(fila.manual);
    $("#leyenda1").val(fila.glosa01)
    $("#leyenda2").val(fila.glosa02)
    $("#leyenda3").val(fila.glosa03)
    $("#uso").val(fila.enUso)
    $("#guardarDatosFactura").html("Editar")
    $('#modalDatosFactura').modal('show');
    
  
}


$(document).on("click",".botoncerrarmodal",function(){
    resetForm('#form_datosFactura')
 })

 $(document).on("click",".btnnuevo",function(){
    resetForm('#form_datosFactura')
    $(".modal-title").html("Agregar Dosificación")
    $("#guardarDatosFactura").html("Guardar")
})
window.operateEvents = {
    'click .editar': function (e, value, row, index) {
        console.log('You click like action, row: ' + JSON.stringify(row));
         resetForm('#form_datosFactura')
         mostrarModal(row)
           // $("#tarticulo").bootstrapTable('hideLoading');            
    }
};
