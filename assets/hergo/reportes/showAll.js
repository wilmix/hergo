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
            field: 'nombreproveedor',
            title: 'Nombre | Razón Social',
            sortable: true,
          },
          {
            field: 'numMov',
            title: 'N°',
            align: 'center',
            sortable: true,
          },
          {
            field: 'fechakardex',
            title: 'Fecha',
            sortable: true,
            formatter: formato_fecha_corta
          },
          {
            field: 'ing',
            title: 'ing',
            sortable: true,
            align: 'right',
          },
          {
            field: 'fac',
            title: 'fac',
            sortable: true,
            align: 'right',
          },
          {
            field: 'ne',
            title: 'nota',
            sortable: true,
            align: 'right',
          },
          {
            field: 'tr',
            title: 'traspaso',
            sortable: true,
            align: 'right',
          },
          {
            field: 'saldo',
            title: 'Saldo',
            sortable: true,
            align: 'right',
          },
          {
            field: 'saldoTotal',
            title: 'saldoTotal',
            sortable: true,
            align: 'right',
          },
          {
            field: 'cpp',
            title: 'cpp',
            sortable: true,
            align: 'right',
          },

          
        ]
      });
    }).fail(function (jqxhr, textStatus, error) {
      var err = textStatus + ", " + error;
      console.log("Request Failed: " + err);
    });
  }