const today = moment().format('DD-MM-YYYY');

$(document).ready(function() {
    retornarSaldosActuales();
});

$(document).on("click", ".imagenminiatura", function() {
    const rutaimagen = $(this).attr('src');
    const imagen = `<img class="maximizada" src="${rutaimagen}">`;
    $("#imagen_max").html(imagen);
    $("#prev_imagen").modal("show");
});

function retornarSaldosActuales() {
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarSaldos'),
        dataType: "json",
    }).done(function(res) {
        const columns = getColumns();
        initializeDataTable(res, columns);
        quitarcargando();
    }).fail(function(jqxhr, textStatus, error) {
        const err = `${textStatus}, ${error}`;
        console.log("Request Failed: " + err);
    });
}

function getColumns() {
    const columns = [
        { data: 'id', title: 'ID', className: 'text-center', searchable: false, visible: false },
        { data: 'codigo', title: 'Codigo', className: 'text-center' },
        { data: 'url', title: 'Imagen', searchable: false, render: mostrarimagen },
        { data: 'descripcion', title: 'Descripción', className: 'text-left', width: '30%' },
        { data: 'uni', title: 'Unidad', sorting: false, searchable: false, className: 'text-center' },
        { data: 'precio', title: 'Precio', className: 'text-right', searchable: false, render: numberDecimal },
        { data: 'laPaz', title: 'La Paz', className: 'text-right', searchable: false, render: numberDecimal },
        { data: 'reserva', title: 'Reserva LP', className: 'text-right', searchable: false, render: numberDecimal },
        { data: 'elAlto', title: 'El Alto', className: 'text-right', searchable: false, render: numberDecimal },
        { data: 'potosi', title: 'Potosí', className: 'text-right', searchable: false, render: numberDecimal },
        { data: 'santacruz', title: 'Santa Cruz', className: 'text-right', searchable: false, render: numberDecimal },
        { data: 'reserva_scz', title: 'Reserva SCZ', className: 'text-right', searchable: false, render: numberDecimal },
        { data: 'pendienteAprobar', title: 'Por Aprobar', className: 'text-right', searchable: false },
        { data: 'subTotal', title: 'Total', className: 'text-right', searchable: false, render: numberDecimal },
        { data: 'pasbol', title: 'PasBol', className: 'text-right', searchable: false, visible: false, render: numberDecimal },
        { data: 'total', title: 'Total General', className: 'text-right', searchable: false, visible: false, render: numberDecimal },
        { data: 'backOrder', title: 'BackOrder', className: 'text-right', searchable: false, render: numberDecimal },
        { data: 'estadoPedido', title: 'Detalle BackOrder', className: 'text-left', width: '20%' },
    ];

    // Agregar columnas cpp y ultimoCosto solo si verCPP es verdadero
    if (verCPP) {
        columns.splice(5, 0, { data: 'cpp', title: 'CPP', className: 'text-right', searchable: false, render: numberDecimal });
        columns.splice(6, 0, { data: 'ultimoCosto', title: 'Ultimo Costo', className: 'text-right', searchable: false, render: numberDecimal });
    }

    return columns;
}

function initializeDataTable(data, columns) {
    $('#tablaSaldosActuales').DataTable({
        data: data,
        destroy: true,
        dom: 'Bfrtip',
        responsive: true,
        lengthMenu: [
            [5, 10, 100, -1],
            ['5 filas', '10 filas', '100 filas', 'Todo']
        ],
        pageLength: 100,
        columns: columns,
        stateSave: true,
        stateSaveParams: function(settings, data) {
            data.order = [];
        },
        buttons: getButtons(),
        order: [],
        fixedHeader: {
            header: true,
            footer: true
        },
        language: getLanguageSettings(),
    });
}

function getButtons() {
    return [
        {
            extend: 'copy',
            text: '<i class="fas fa-copy" style="font-size:18px;"> </i>',
            titleAttr: 'Copiar',
            title: null,
            exportOptions: {
                columns: [':visible'],
                title: null,
                modifier: {
                    order: 'current',
                }
            }
        },
        {
            extend: 'excel',
            text: '<i class="fas fa-file-excel" aria-hidden="true" style="font-size:18px;"> </i>',
            titleAttr: 'ExportExcel',
            autoFilter: true,
            title: 'Saldos Articulos al ' + today,
            exportOptions: {
                columns: ':visible'
            },
        },
        {
            text: '<i class="fas fa-print" aria-hidden="true" style="font-size:18px;"></i>',
            action: function(e, dt, node, config) {
                window.print();
            }
        },
        {
            text: '<i class="fas fa-sync" aria-hidden="true" style="font-size:18px;"></i>',
            action: function(e, dt, node, config) {
                retornarSaldosActuales();
            }
        },
        {
            extend: 'collection',
            text: '<i class="fa fa-cogs" aria-hidden="true" style="font-size:18px;"></i>',
            titleAttr: 'Configuracion',
            autoClose: true,
            buttons: [
                'pageLength',
                {
                    extend: 'colvis',
                    text: '<i class="fas fa-eye" aria-hidden="true"> Ver/Ocultar</i>',
                    collectionLayout: 'fixed two-column',
                    postfixButtons: ['colvisRestore']
                },
                {
                    text: '<i class="fas fa-redo" aria-hidden="true"> Reestablecer</i>',
                    className: 'btn btn-link',
                    action: function(e, dt, node, config) {
                        table.state.clear();
                        retornarSaldosActuales();
                    }
                },
            ]
        },
    ];
}

function getLanguageSettings() {
    return {
        buttons: {
            colvisRestore: "Restaurar",
            copyTitle: 'Información copiada',
            pageLength: {
                _: "VER %d FILAS",
                '-1': "VER TODO"
            },
            copySuccess: {
                _: '%d lineas copiadas',
                1: '1 linea copiada'
            },
        },
        decimal: "",
        emptyTable: "No hay información",
        info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
        infoFiltered: "(Filtrado de _MAX_ total entradas)",
        infoPostFix: "",
        thousands: ",",
        lengthMenu: "Mostrar _MENU_ Registros",
        loadingRecords: "Cargando...",
        processing: "Procesando...",
        search: "Buscar:",
        zeroRecords: "Sin resultados encontrados",
        paginate: {
            first: "Primero",
            last: "Ultimo",
            next: "Siguiente",
            previous: "Anterior"
        },
    };
}

function mostrarimagen(value, row, index) {
    const urlSpaces = value ? `https://images.hergo.app/${value}` : "https://images.hergo.app/hg/general/hergo.jpg";
    const clase = value ? "imagenminiatura" : "";
    return `<div class=\"contimg\"><img src=\"${urlSpaces}\" class=\"${clase}\"></div>`;
}
