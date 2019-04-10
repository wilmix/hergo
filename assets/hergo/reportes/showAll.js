$(document).ready(function () {
    retornarKardexAll();
  
  })

function retornarKardexAll() {
    alm = $("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
      type: "POST",
      url: base_url('index.php/Reportes/showKardexAll'),
      dataType: "json",
      data: {

      },
    }).done(function (res) {
        console.log(res);
        quitarcargando();
      //datosselect = restornardatosSelect(res);
      $("#allKardexTable").bootstrapTable('destroy');
      $("#allKardexTable").bootstrapTable({ 
        data: res,
        striped: true,
        pagination: true,
        pageSize: "100",
        search: true,
        showColumns: true,
        filter: true,
        stickyHeader: true,
        stickyHeaderOffsetY: '50px',
        //showFooter: true,
        //footerStyle: footerStyle,
        columns: [
          
          {
            field: 'idArticulo',
            title: 'id',
            visible: true,
            sortable: true,
          },
          {
            field: 'codigo',
            title: 'codigo',
            visible: true,
            sortable: true,
          },
          {
            field: 'descp',
            title: 'Descripción',
            visible: true,
            sortable: true,
          },
          
          {
            field: 'nombreproveedor',
            title: 'Nombre | Razón Social',
            sortable: true,
            searchable: false,
          },
          {
            field: 'numMov',
            title: 'N°',
            align: 'center',
            sortable: true,
            searchable: false,
          },
          {
            field: 'fechakardex',
            title: 'Fecha',
            sortable: true,
            searchable: false,
            formatter: formato_fecha_corta
          },
          {
            field: 'ing',
            title: 'ing',
            sortable: true,
            searchable: false,
            align: 'right',
          },
          {
            field: 'fac',
            title: 'fac',
            sortable: true,
            searchable: false,
            align: 'right',
          },
          {
            field: 'ne',
            title: 'nota',
            sortable: true,
            searchable: false,
            align: 'right',
          },
          {
            field: 'tr',
            title: 'traspaso',
            sortable: true,
            searchable: false,
            align: 'right',
          },
          {
            field: 'saldo',
            title: 'Saldo',
            sortable: true,
            align: 'right',
            searchable: false,
            
          },
          {
            field: 'saldoTotal',
            title: 'saldoTotal',
            sortable: true,
            searchable: false,
            align: 'right',
            formatter:operateFormatter3,
          },
          {
            field: 'cpp',
            title: 'cpp',
            sortable: true,
            align: 'right',
            searchable: false,
            formatter:operateFormatter3,
          },

          
        ]
      });
    }).fail(function (jqxhr, textStatus, error) {
      var err = textStatus + ", " + error;
      console.log("Request Failed: " + err);
    });
  }

   function operateFormatter3(value, row, index)
    {       
        num=Math.round(value * 100) / 100
        num=num.toFixed(2);
        return (formatNumber.new(num));
    }