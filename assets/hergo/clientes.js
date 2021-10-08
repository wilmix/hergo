 $(document).ready(function() {
    retornarTablaClientes()
    validarCliente('cliente')
});
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
    $("#diasCredito").val(fila.diasCredito)
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
                field:"diasCredito",
                title:"Días Credito",
                sortable:true,
                searchable: false,
                align: 'right',
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
