 $(document).ready(function() {
    retornarTablaClientes()
    $('#form_clientes').bootstrapValidator({
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
                        message: 'Campo obligatorio'
                    },
                        between: {
                            min: 1111,
                            max: 999999999,
                            message: 'Igrese un CI o NIT válido'
                        }
                    }
                },
            nombre_cliente: {
                validators: {
                        stringLength: {
                        min: 2,
                        message: 'Ingrese nombre válido'
                    },
                        notEmpty: {
                        message: 'Campo obligatorio'
                    }
                }
            },
          direccion: {
                validators: {
                     stringLength: {
                        min: 5,
                        message: 'Ingrese dirección válida'
                    },
                }
            },
             email: {
                validators: {
                        emailAddress: {
                        message: 'Ingrese un email válido'
                    }
                }
            },
          
         /* website: {
                validators: {
                    uri: {
                        message: 'The website address is not valid'
                    }
                }
            },*/
        
          
          phone: {
                    validators: {
                         between: {
                            min: 1111,
                            max: 99999999,
                            message: 'Igrese número de telefono valido'
                        }
                    }
                },
           }
        })
        .on('success.form.bv', function(e) {
            e.preventDefault();
            //var valuesToSubmit = $("#form_clientes").serialize();  
            var formData = new FormData($('#form_clientes')[0]);  
            console.log(formData)              
            $.ajax({
                url: base_url("index.php/Clientes/agregarCliente"),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                   $(".mensaje_ok").html(" Los datos se guardaron correctamente");
                   // $("#modal_ok").modal("show");
                    $('#contact-form-success').show().fadeOut(10000);
                    $('#modalcliente').modal('hide');
                    retornarTablaClientes()
                    
                }
            }); 
        });
});
$(document).on("click","#botonmodalcliente",function(){
    resetForm('#form_clientes')
    $(".modal-title").html("Agregar Cliente")
    $("#bguardar").html("Guardar")
    
})
$(document).on("click",".botoncerrarmodal",function(){
   resetForm('#form_clientes')
})


function mostrarModal(fila)
{
    console.log(fila)
    $("#id_cliente").val(fila.idCliente)
    $(".modallineatitulo").html("Editar Cliente")
    asignarselect(fila.documentoTipo,"#tipo_doc")
    $("#carnet").val(fila.documento)
    $("#nombre_cliente").val(fila.nombreCliente)
    asignarselect(fila.clientetipo,"#clientetipo")
    $("#direccion").val(fila.direccion)
    $("#phone").val(fila.telefono)
    $("#fax").val(fila.fax)
    $("#email").val(fila.email)
    $("#website").val(fila.web)

    $(".bguardar").html("Editar")
    $('#modalcliente').modal('show');
    
  
}

function retornarTablaClientes()
{
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Clientes/mostrarclientes'),
        dataType: "json",
        data: {},
    }).done(function(res){
        quitarcargando();
        //console.log(res)
        $("#tclientes").bootstrapTable('destroy');
        $("#tclientes").bootstrapTable({
            
            data:res,           
            striped:true,
            pagination:true,
            pageSize:25,
            clickToSelect:true,
            search:true,
            showExport:true,
            exportTypes:['excel','csv'],
            exportDataType:'all',
            exportOptions:{fileName: 'Clientes',worksheetName: "Clientes"},
            columns:[
            {   
                field: 'idCliente',            
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
                field:'nombreCliente',
                title:"Nombre",
                sortable:true,
            },
            {
                field:'clientetipo',
                title:"Tipo Cliente",
                sortable:true,
                visible:false,
            },
            {
                field:"direccion",
                title:"Direccion",
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
                field:"fecha",
                title:"Fecha",
                sortable:true,
                visible:false,
                formatter: formato_fecha
            },          
            {
                field:"autor",
                title:"Autor",
                sortable:true,
                visible:false,
            },          
            {               
                title: 'Editar',
                align: 'center',
                events: operateEvents,
                formatter: operateFormatter
            }]
        });

        $("#tclientes").bootstrapTable('hideLoading');
        $("#tclientes").bootstrapTable('resetView');
        
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
    //$("body").css("padding-right","0px");
}
window.operateEvents = {
    'click .editar': function (e, value, row, index) {
      //  alert('You click like action, row: ' + JSON.stringify(row));
      resetForm('#form_clientes')
        mostrarModal(row)
            $("#tarticulo").bootstrapTable('hideLoading');            
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
