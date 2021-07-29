 $(document).ready(function() {
    retornarTablaClientes()
    $('#form_clientes').bootstrapValidator({
            feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
          carnet: {
                    validators: {
                      notEmpty: {
                        message: 'Campo obligatorio'
                    },
                        notEmpty: {
                            message: 'Campo obligatorio'
                        },
                        integer: {
                            message: 'Debe ser dato númerico'
                        }
                    }
                },
            nombre_cliente: {
                validators: {
                        stringLength: {
                        min: 1,
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
                        min: 1,
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
      
          
          phone: {
                    validators: {
                         between: {
                            min: 1,
                            max: 999999999999999,
                            message: 'Igrese número de telefono valido'
                        }
                    }
                },
           }
        })
        .on('success.form.bv', function(e) {
            e.preventDefault();
            var formData = new FormData($('#form_clientes')[0]);  
             /* for(let pair of formData.entries()) { console.log(pair[0]+ ', '+ pair[1]); };  quitarcargando(); return; */        
            $.ajax({
                url: base_url("index.php/Clientes/store"),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                    returndata = JSON.parse(returndata)
                    console.log(returndata)
                    if (returndata.status==true) {
                        $('#modalcliente').modal('hide');
                        resetForm('#form_clientes')
                        retornarTablaClientes()
                        swal(
                            'Cliente Registrado',
                            '',
                            'success'
                            )
                    } else if (returndata.status==false){
                        swal({
                            title: 'Atencion',
                            html: "El NIT <b>" + returndata.cliente.documento + "</b> ya se encuentra registrado a nombre de <b>" + returndata.cliente.nombreCliente + "</b> en fecha  <b>" + formato_fecha_corta(returndata.cliente.fecha) + "</b> registrado por <b>" + returndata.cliente.autor + "</b>.",
                            type: 'warning',
                        }
                        )
                    }

                }, 
                error : function (returndata) {
                    console.log(returndata.status);
                    swal(
                        'Error',
                        'Error en bases de datos status:'+returndata.status,
                        'error'
                    )
                    //console.log(returndata);
                }, 
            })
        });
});
$(document).on("click","#botonmodalcliente",function(){
    $("#carnet").prop('disabled', false);
    resetForm('#form_clientes')
    
    $(".modal-title").html("Agregar Cliente")
    $("#bguardar").html("Guardar")
    
})
$(document).on("click",".botoncerrarmodal",function(){
   resetForm('#form_clientes')
})


function mostrarModal(fila)
{
    //console.log(fila)
    $("#id_cliente").val(fila.idCliente)
    $(".modal-title").html("Editar Cliente")
    asignarselect(fila.documentotipo,"#tipo_doc")
    $("#carnet").prop('disabled', false);
    $("#carnet").val(fila.documento)
    $("#nombre_cliente").val(fila.nombreCliente)
    asignarselect(fila.clientetipo,"#clientetipo")
    $("#direccion").val(fila.direccion)
    $("#phone").val(fila.telefono)
    $("#fax").val(fila.fax)
    $("#email").val(fila.email)
    $("#website").val(fila.web)

    $(".bguardar").html("Guardar")
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
            //pagination:false,
            //pageSize:100,
            clickToSelect:true,
            search:true,
            height:700,
            showColumns: true,
            showExport:true,
            exportTypes:['excel','csv'],
            exportDataType:'all',
            exportOptions:{fileName: 'Clientes',worksheetName: "Clientes"},
            columns:[
            {   
                field: 'idCliente',            
                title: 'id',
                visible:true,
                sortable:true,
            },  
            {   
                field: 'documentotipo',            
                title: 'Tipo Documento',
                searchable: false,
                            
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
                searchable: false,
            },
            {
                field:"direccion",
                title:"Direccion",
                sortable:true,
                visible:false,
                searchable: false,
            },
            {
                field:"email",
                title:"Email",
                sortable:true,
                visible:false,
                searchable: false,
            },
            {
                field:"web",
                title:"Web",
                sortable:true,
                visible:false,
                searchable: false,
            },
            {
                field:"telefono",
                title:"Telefono",
                sortable:true,
                visible:false,
                searchable: false,
            },
            {
                field:"fax",
                title:"Fax",
                sortable:true,
                visible:false,
                searchable: false,
            },
            {
                field:"fecha",
                title:"Fecha",
                sortable:true,
                visible:false,
                searchable: false,
                formatter: formato_fecha
            },          
            {
                field:"autor",
                title:"Autor",
                sortable:true,
                visible:true,
                searchable: false,
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
