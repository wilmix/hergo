$(document).ready(function(){
    
    //retornarRoles();
}) 
$(document).on("change", "#users_filtro", function () {
    idUser = $('#users_filtro').val();
    retornarRoles(idUser);
})
function retornarRoles() 
{   
    agregarcargando(idUser);
    $.ajax({
        type:"POST",
        url: base_url('index.php/Roles/mostrarRoles'), 
        dataType: "json",
        data: {
            idUser:idUser
        },
    }).done(function(res){
        console.log(res);
    	quitarcargando();
        $("#tablaRoles").bootstrapTable('destroy');
        $("#tablaRoles").bootstrapTable({ 

                data:res,    
                    striped:true,
                    //pagination:true,
                    //pageSize:"100",
                    //search:true,
                    //searchOnEnterKey:true,
                    //showColumns:true,
                    filter:true,
                    //showExport:true,
                    stickyHeader: true,
                    stickyHeaderOffsetY: '50px',
                columns:
                [
                   {   
                        field: 'idSubMenu',            
                        title: 'idSub',
                        sortable:true,
                        align: 'center',
                        visible: false
                    },
                    {   
                        field: 'menu',            
                        title: 'Menu',
                    },

                    {   
                        field: 'subMenu',            
                        title: 'Sub Menu',
                    },
                    {   
                        field: 'activo',            
                        title: 'Estado',
                        formatter: estado,
                        align: 'center',
                        events: operateEvents,
                    },

                ]
                
            });
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
 }
 function estado(value, row, index) {
    $ret = ''
    if (row.activo>0) {
        $ret = '<span class="label label-success cambiar" style="cursor:pointer">Activo</span>'
    } else {
        $ret = '<span class="label label-danger cambiar" style="cursor:pointer">Desactivo</span>'
    } 
    return ($ret);
}
function tituloReporte() {
    nomCliente = $('#clientes_filtro').find(':selected').text();
    $('#tituloReporte').text(almText);
    $('#nombreCliente').text(nomCliente);
}
window.operateEvents = {
    'click .cambiar': function (e, value, row, index) {
        if (row.activo) {
            //enviar idUser y idSubmenu para agragar
            desActivar(idUser, row.idSubMenu)
        } else {
            //enviar idUser y idSubmenu para ELIMINAR
            activar(idUser, row.idSubMenu)
        }
       
    }
}
function activar (idUser, idSubMenu) {
    $.ajax({
        type:"POST",
        url: base_url('index.php/Roles/activar'), 
        dataType: "json",
        data: {
            idUser:idUser,
            idSub:idSubMenu,
        },
    }).done(function(res){
        console.log(idUser, idSubMenu);
        console.log('ok');
        retornarRoles(idUser);
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
}
function desActivar (idUser, idSubMenu) {
    $.ajax({
        type:"POST",
        url: base_url('index.php/Roles/desActivar'), 
        dataType: "json",
        data: {
            idUser:idUser,
            idSub:idSubMenu,
        },
    }).done(function(res){
        console.log(idUser, idSubMenu);
        console.log('ok');
        retornarRoles(idUser);
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
}
