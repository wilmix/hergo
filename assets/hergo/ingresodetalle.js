$(document).ready(function() {
    $('#tbconsultadetalle').bootstrapTable({
            striped:true,
            pagination:true,
            pageSize:"100",
            clickToSelect:true,
            search:true,
            strictSearch:true,
            searchOnEnterKey:true,
            filter:true,
            showColumns:true,
            columns: [
            {
                field: 'n',
                width: '3%',
                title: 'N',
                align: 'center',
                sortable:true,
            },
            {
                field:'fechamov',
                width: '7%',
                title:"Fecha",
                sortable:true,
                align: 'center',
                formatter: formato_fecha_corta,
            },
            {
                field:'nombreproveedor',
                title:"Proveedor",
                width: '17%',
                sortable:true,
            },
            {
                field:'nfact',
                title:"Factura",
                width: '7%',
                sortable:true,
                
            },
            {
                field:'',
                title:"Codigo",
                width: '7%',
                align: 'right',
                sortable:true,
                formatter: operateFormatter3,
            },
            {
                field:"",
                title:"Descripción",
                width: '17%',
                sortable:true,
                formatter: operateFormatter2,
                align: 'center'
            },
            {
                field:"",
                title:"Cantidad",
                width: '7%',
                sortable:true,
                formatter: operateFormatter2,
                align: 'center'
            },
            {
                field:"",
                title:"Monto",
                width: '8%',
                sortable:true,
                formatter: operateFormatter2,
                align: 'center'
            },
            {
                field:"autor",
                width: '8%',
                title:"Autor",
                sortable:true,
                visible:false,
                align: 'center'
            },
            {
                field:"fecha",
                width: '8%',
                title:"Fecha",
                sortable:true,
                formatter: formato_fecha_corta,
                visible:false,
                align: 'center'
            }]
    
});

});