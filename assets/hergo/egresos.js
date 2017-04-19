$(document).ready(function() {
    $('#tegresos').bootstrapTable({
            striped:true,
            pagination:true,
            pageSize:"10",
            clickToSelect:true,
            search:true,
            strictSearch:true,
            searchOnEnterKey:true,
            filter:true,
            showColumns:true,
            columns: [
            {
                field: '',
                width: '3%',
                title: 'N',
                align: 'center',
                sortable:true,
            },
            {
                field:'',
                width: '7%',
                title:"Fecha",
                sortable:true,
                align: 'center',
            },
            {
                field:'',
                title:"Cliente",
                width: '17%',
                sortable:true,
            },
            {
                field:'',
                title:"Factura",
                width: '7%',
                sortable:true,
                
            },
            {
                field:'',
                title:"Total",
                width: '7%',
                align: 'right',
                sortable:true,

            },
            {
                field:"",
                title:"Estado",
                width: '7%',
                sortable:true,
                align: 'center'
            },
            {
                field:"",
                title:"Acciones",
                width: '7%',
                sortable:true,
                align: 'center'
            },
            {
                field:"",
                width: '8%',
                title:"N° Pedido",
                sortable:true,
                visible:false,
                align: 'center'
            },
                        {
                field:"",
                width: '8%',
                title:"PlazoPago",
                sortable:true,
                visible:false,
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
                visible:false,
                align: 'center'
            }]
    
});

});