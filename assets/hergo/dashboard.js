$(document).ready(function(){
    getVentas()
    getventasIngresosHoy()
    getInfoHoy()
})

let today = moment().subtract(0, 'month').startOf('day').format('YYYY-MM-DD')
let yearAgo = moment().subtract(11, 'month').startOf('month').format('YYYY-MM-DD')

function ventasChart(meses, alm1, alm2, alm3) {
    var ctx = document.getElementById('ventas').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: "LA PAZ",
                borderColor: "#3e95cd",
                data: alm1,
                fill: false,
                pointStyle: 'rectRounded',
                lineTension: 0,
            },
            {
                label: "POTOSI",
                borderColor: "#ff0000",
                //data: alm2, 
                data: [332391.2 , 246947.7 , 721275.85 , 447259.5 , 123229.37 , 1027070.1 , 703953.9 , 290102.35 , 218533.7 , 195155.2 , 0 , 0],
                fill: false,
                pointStyle: 'rectRounded',
                lineTension: 0,
            },
            {
              label: "SANTA CRUZ",
              borderColor: "#009f00",
              //data: alm2,
              data:[690434.22 , 847221.72 , 598371.63 , 458979.74 , 717003.87 , 803260.22 , 373704.07 , 433670.264 , 996002.96 , 405019.7788 , 0 , 0],
              fill: false,
              pointStyle: 'rectRounded',
              lineTension: 0,
          }
        ]},
        options: { 
          scales: { 
            yAxes: [{ 
              ticks: { 
                beginAtZero: true,
                callback: function (value) { 
                  return formatNumber.new(value) 
                } 
              } 
            }] 
          },
          tooltips:{
            enabled: true,
            callbacks: {
              label: function(tooltipItem, data) {
                  var label = data.datasets[tooltipItem.datasetIndex].label || '';
                  if (label) {
                      label += ': ';
                  }
                  label += formatNumber.new(tooltipItem.yLabel);
                  return label;
              }
            },
          },
        },
    });
}

function getVentas() {
    ini = yearAgo
    fin = today
    $.ajax({
      type: "POST",
      url: base_url('index.php/Principal/ventasGestion'),
      dataType: "json",
      data: {
        i: ini,
        f: fin,
      }, 
      
    }).done(function (res) {
      console.log(res)
      let laPaz = res.map (ventas => ventas.lp)
      let potosi = res.map (ventas => ventas.pts)
      let santaCruz = res.map (ventas => ventas.scz)
      let tiempo = res.map( ventas => ventas.mes + " " + ventas.gestion)
      ventasChart(tiempo, laPaz, potosi, santaCruz)
    }).fail(function (jqxhr, textStatus, error) {
      var err = textStatus + ", " + error;
      console.log("Request Failed: " + err);
    });
}

function getventasIngresosHoy() {
  ini = today
  ini = '2018-05-18'
  $.ajax({
    type: "POST",
    url: base_url('index.php/Principal/ventasIngresosHoy'),
    dataType: "json",
    data: {
      i: ini,
    }, 
  }).done(function (res) {
    console.log(res)
    let ingresosHoyLP = res[1].lp
    let ventasHoyLP = res[0].lp

    $("#ventasHoy").html(formatNumber.new(ventasHoyLP)+"<small> Bs</small>")
    $("#ingresosHoy").html(formatNumber.new(ingresosHoyLP)+"<small> Bs</small>")


  }).fail(function (jqxhr, textStatus, error) {
    var err = textStatus + ", " + error;
    console.log("Request Failed: " + err);
  });
}
function getInfoHoy() {
  $.ajax({
    type: "POST",
    url: base_url('index.php/Principal/infoHoy'),
    dataType: "json",
    data: {
      i: ini,
    }, 
  }).done(function (res) {
    console.log(res)
    let lpNeg = res[0].cant
    let ptsNeg = res[1].cant
    let sczNeg = res[2].cant
    let lpAct = res[3].cant
    let ptsAct = res[4].cant
    let sczAct = res[5].cant
    $("#negativos").text(lpNeg)
    $("#activos").text(lpAct)

  }).fail(function (jqxhr, textStatus, error) {
    var err = textStatus + ", " + error;
    console.log("Request Failed: " + err);
  });
}
