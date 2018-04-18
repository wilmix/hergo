$(document).ready(function() {
    retornarTablaDatosFactura()

});
function retornarTablaIngresos() {
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
            pageSize: "100",
            search: true,
            showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            columns: 
            [
                {   
                    field: 'lote',            
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
                    title: ''
                }
                

            ]
          });
    
    
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}



