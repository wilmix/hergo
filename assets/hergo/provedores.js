$(document).ready(function() {
    retornarTablaProveedor()
    $('#form_provedor').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
             feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
          tipo_doc: {
                validators: {
                    notEmpty: {
                        message: 'Selecciona NIT o CI'
                    }
                }
            },
          carnet: {
                    validators: {
                      notEmpty: {
                        message: 'Carnet o NIT campo obligatorio'
                    },
                        between: {
                            min: 1111,
                            max: 99999999,
                            message: 'Igrese un CI o NIT valido'
                        }
                    }
                },
            nombre: {
                validators: {
                        stringLength: {
                        min: 2,
                    },
                        notEmpty: {
                        message: 'Campo obligatorio'
                    }
                }
            },
           nombre_res: {
                validators: {
                        stringLength: {
                        min: 2,
                    },
                        
                }
            },
          direccion: {
                validators: {
                     stringLength: {
                        min: 5,
                    },
                }
            },
             email: {
                validators: {
                        emailAddress: {
                        message: 'Please supply a valid email address'
                    }
                }
            },
          
          
        
          
          phone: {
                    validators: {
                         between: {
                            min: 1111,
                            max: 99999999,
                            message: 'Igrese número de telefono valido'
                        }
                    }
                },
          website: {
                validators: {
                        stringLength: {
                        min: 2,
                    },

                }
            },
           }
        })
        .on('success.form.bv', function(e) {
            e.preventDefault();
            //var valuesToSubmit = $("#form_clientes").serialize();  
            var formData = new FormData($('#form_provedor')[0]);  
            console.log(formData)              
            $.ajax({
                url: base_url("index.php/Provedores/agregarProveedor"),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                   $(".mensaje_ok").html(" Los datos se guardaron correctamente");
                   // $("#modal_ok").modal("show");
                    $('#contact-form-success').show().fadeOut(10000);
                    $('#modalprovedor').modal('hide');
                    retornarTablaProveedor()
                    
                }
            }); 
        });
});
$(document).on("click",".btnnuevo",function(){
    resetForm('#form_provedor')
    $(".modal-title").html("Agregar Proveedor")
    $("#bguardar").html("Guardar")
    
})
$(document).on("click",".botoncerrarmodal",function(){
   resetForm('#form_provedor')
})
function mostrarModal(fila)
{
    console.log(fila)
    $("#id_proveedor").val(fila.idproveedor)
    $(".modallineatitulo").html("Editar Proveedor")
    asignarselect(fila.documentoTipo,"#tipo_doc")
    $("#carnet").val(fila.documento)
    $("#nombre").val(fila.nombreproveedor)
    $("#direccion").val(fila.direccion)
    $("#nombre_res").val(fila.responsable)
    $("#phone").val(fila.telefono)
    $("#fax").val(fila.fax)
    $("#email").val(fila.email)
    $("#website").val(fila.web)

    $(".bguardar").html("Editar")
    $('#modalprovedor').modal('show');
    
  
}

function retornarTablaProveedor()
{
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Provedores/mostrarProveedores'),
        dataType: "json",
        data: {},
    }).done(function(res){
        quitarcargando();
        //console.log(res)
        $("#tproveedores").bootstrapTable('destroy');
        $("#tproveedores").bootstrapTable({
            
            data:res,           
            striped:true,
            pagination:true,
            pageSize:25,
            clickToSelect:true,
            search:true,
            showExport:true,
            exportTypes:['excel','csv'],
            exportDataType:'all',
            exportOptions:{fileName: 'Proveedores',worksheetName: "Proveedores"},
            columns:[
            {   
                field: 'idproveedor',            
                title: 'id',
                visible:false,
            },  
            {   
                field: 'documentoTipo',            
                title: 'Tipo Documento',
                            
            },         
            {
                field:'documento',
                title:"N Documento",
                sortable:true,
            },
            {
                field:'nombreproveedor',
                title:"Proveedor",
                sortable:true,
            },  
            {
                field:'direccion',
                title:"Direccion",
                sortable:true,
            },           
            {
                field:"responsable",
                title:"Responsable",
                sortable:true,
                visible:false,
            },
             {
                field:"telefono",
                title:"Telefono",
                sortable:true,
                visible:false,
            },
            {
                field:"fax",
                title:"Fax",
                sortable:true,
                visible:false,
            },
            {
                field:"email",
                title:"Email",
                sortable:true,
                visible:false,
            },
            {
                field:"web",
                title:"Web",
                sortable:true,
                visible:false,
            },                           
            {
                field:"autor",
                title:"Autor",
                sortable:true,
                visible:false,
            },          
            {
                field:"fecha",
                title:"Fecha",
                sortable:true,
                visible:false,
                formatter: formato_fecha
            }, 
            {               
                title: 'Editar',
                align: 'center',
                events: operateEvents,
                formatter: operateFormatter
            }]
        });

        $("#tproveedores").bootstrapTable('hideLoading');
        $("#tproveedores").bootstrapTable('resetView');
        
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
    //$("body").css("padding-right","0px");
}
window.operateEvents = {
    'click .editar': function (e, value, row, index) {
      //  alert('You click like action, row: ' + JSON.stringify(row));
        resetForm('#form_provedor')
        mostrarModal(row)
        $("#tproveedores").bootstrapTable('hideLoading');            
    }
};
function operateFormatter(value, row, index) 
{
    return [
        '<a class="editar" title="editar" data-toggle="modal" data-target="#editar" style="cursor:pointer">',
        '<i class="fa fa-pencil"></i>',
        '</a>  '       
    ].join('');
}
