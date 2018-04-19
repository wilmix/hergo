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
            leyenda1: {
                validators: {
                    notEmpty: {
                        message: 'Inserte leyenda'
                    }                    
                }
            },
            leyenda2: {
                validators: {
                    notEmpty: {
                        message: 'Inserte leyenda'
                    }                    
                }
            },
            leyenda3: {
                validators: {
                    notEmpty: {
                        message: 'Inserte leyenda'
                    }                    
                }
            },
            uso: {
                validators: {
                    notEmpty: {
                        message: 'Elija si articulo esta en uso'
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
        //datosselect = restornardatosSelect(res);
        $("#tablaDatosFactura").bootstrapTable('destroy');    
        $("#tablaDatosFactura").bootstrapTable({ ////********cambiar nombre tabla viata
            data: res,
            striped: true,
            pagination: true,
            pageSize: "10",
            search: true,
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
                },
                {   
                    field: 'almacen',            
                    title: 'Almacen',
                    align: 'center',
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
                },
                {   
                    field: 'hasta',            
                    title: 'Hasta',
                    align: 'center',
                },
                {   
                    field: 'fechaLimite',            
                    title: 'Fecha Limite',
                    align: 'center',
                },
                {   
                    field: 'manual',            
                    title: 'Manual',
                    align: 'center',
                },
                {   
                    field: 'enUso',            
                    title: 'Estado',
                    align: 'center',
                },

                {   
                    field: 'llaveDosificacion',            
                    title: 'Llave de Dosificación'
                
                },
                {   
                    field: 'glosa01',            
                    title: 'Leyenda 01'
                
                },
                {   
                    field: 'glosa02',            
                    title: 'Leyenda 02'
                
                },
                {   
                    field: 'glosa03',            
                    title: 'Leyenda 03'
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
        '<i class="fa fa-pencil"></i>',
        '</a>  '       
    ].join('');
}
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
