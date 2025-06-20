var glob_tipoCambio = 0;
var glob_art = [];
var glob_alm_usu = $('#idAlmacenUsuario').val()
var glob_user_id = $('#user_id_actual').val()
var fechaHoySystem 
const CPV = 0
var PermisosUser
let gestionActual = $('#gestionActual').val()
const isAdminGlobal = $('#isAdmin').val()
const isNacionalGlobal = $('#nacional').val()
glob_tipoCambio = parseFloat($("#mostrarTipoCambio").text())
datatableLangage = {
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
  "decimal": "",
  "emptyTable": "No hay información",
  "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
  "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
  "infoFiltered": "(Filtrado de _MAX_ total entradas)",
  "infoPostFix": "",
  "thousands": ",",
  "lengthMenu": "Mostrar _MENU_ Registros",
  "loadingRecords": "Cargando...",
  "processing": "Procesando...",
  "search": "Buscar:",
  "zeroRecords": "Sin resultados encontrados",
  "paginate": {
    "first": "Primero",
    "last": "Ultimo",
    "next": "Siguiente",
    "previous": "Anterior"
  },
}
permisos()

$(document).ready(function () 
{
  glob_tipoCambio = parseFloat($("#mostrarTipoCambio").text())
  fechaHoySystem = moment().endOf('day')
  fechaHoySystem = moment(fechaHoySystem).format("YYYY-MM-DD");
  setTipoCambio(fechaHoySystem);
  mantenerMenu();
})
function permisos() {
  $.getJSON({
    type: "POST",
    async:false,
    url: base_url('index.php/Importaciones/pedidos/permisos'),
  }).done(function (res) {
    PermisosUser = res
    //console.table(res);
  });
}

function setTipoCambio(fechaActual) {
  $.ajax({
    type: "POST",
    //async:false,
    url: base_url('index.php/Facturas/tipoCambio'),
    dataType: "json",
    data: {fechaActual:fechaActual},
  }).done(function (res) {
    if (!res) {
      swal("Atencion!", "No se tiene tipo de cambio para la fecha")
    } 
    
    glob_alm_usu = res.idAlmacenUsuario
    console.log(glob_alm_usu);
    //$('#mostrarTipoCambio').text(res.tipoCambio);
    //glob_tipoCambio = res.tipoCambio;
    console.log(glob_tipoCambio);
  }).fail(function (jqxhr, textStatus, error) {
    var err = textStatus + ", " + error;
    console.log("Request Failed: " + err);
  });
}

function base_url(complemento) {
  complemento = (complemento) ? complemento : '';
  let baseurl = $('#baseurl').val();
  return baseurl + complemento;
}
function base_url_siat(complemento) {
  complemento = (complemento) ? complemento : '';
  let baseurl = $('#base_url_siat').val();
  return baseurl + complemento;
}

function agregarcargando() {
  $("#cargando").css("display", "block")
}

function quitarcargando() {
  $("#cargando").css("display", "none")
}
/******************AJAX************************/
/**********************************************/
function retornarajax(url, datos, callback) {
  let retornar = new Object();
  $("#cargando").css("display", "block")

  return $.ajax({
    type: "POST",
    url: url,
    dataType: "json",
    data: datos,
    // processData: false, //UP
    //  contentType: false  //UP
  }).done(function (data) {
    let retornar = new Object();
    datos_retornados = "retorno";
    retornar.estado = "ok";
    retornar.respuesta = data;
    $("#cargando").css("display", "none")
    if (callback)
      callback(retornar);


  }).fail(function (jqxhr, textStatus, error) {
    quitarcargando()
    let retornar = new Object();
    let err = textStatus + ", " + error;
    console.log("Request Failed: " + err);
    if (jqxhr.status === 0) {
      errorajax = "No existe conexion, veirique su red";
    } else if (jqxhr.status == 404) {
      errorajax = "No se encontro la pagina [404]";
    } else if (jqxhr.status == 500) {
      errorajax = "Error interno del servidor [500]";
    } else if (textStatus === 'parsererror') {
      errorajax = "Requested JSON parse failed, error de sintaxis en retorno json ";
    } else if (textStatus === 'timeout') {
      errorajax = "Error de tiempo de espera";
    } else if (textStatus === 'abort') {
      errorajax = "Solicitud de ajax abortada";
    } else {
      errorajax = "error desconocido " + jqxhr.responseText;
    }
    retornar.estado = "error";
    retornar.respuesta = errorajax;

    if (callback)
      callback(retornar);

  });
}

function validarresultado_ajax(resultado) {
  if (resultado.estado == "ok") {
    return true;
  } else {
    $(".mensaje_error").html(resultado.respuesta);
    $("#modal_error").modal("show");
    //setTimeout(function(){$("#modal_error").modal("hide");},5000)
    return false;
  }
}

/**********************************************************/
/**********************************************************/
function resetForm(id) {
  $(id)[0].reset();
  $(id).bootstrapValidator('resetForm', true);
}
$(document).on("click", ".sidebar-toggle", function () {

  setTimeout(function () {
    $('table').bootstrapTable('resetWidth');
  }, 500);
})
/* $(window).resize(function () {
  setTimeout(function () {
    $('table').bootstrapTable('resetWidth');
  }, 500);
}); */

function formato_fecha(value, row, index) {
  var fecha = ""
  //console.log(value)
  if ((value == "0000-00-00") || (value == "0000-00-00 00:00:00") || (value == "") || (value == null))
    fecha = "-"
  else if (value == 'PENDIENTE') {
    fecha = value
  }
  else
    fecha = moment(value, "YYYY/MM/DD").format("DD/MM/YYYY")
    return [fecha]
}

function formato_fecha_corta(value, row, index) {
  var fecha = ""
  //console.log(value)
  if ((value == "0000-00-00 00:00:00") || (value == "") || (value == null))
    fecha = "sin fecha de registro"
  else
    fecha = moment(value, "YYYY/MM/DD HH:mm:ss").format("DD/MM/YYYY HH:mm:ss")
  return fecha
}

function formato_fecha_corta_sub(value, row, index) {
  let fecha = ""
  if (value == '') {
    fecha = ''
  } else {
    if ((value == "0000-00-00 00:00:00") || (value == "") || (value == null))
      fecha = "sin fecha de registro"
    else
      fecha = moment(value, "YYYY/MM/DD HH:mm:ss").format("DD/MM/YYYY")
  }
  return fecha
}

function asignarselect(text1, select) {
  text1 = text1 == null ? '' : text1
  //console.log(text1);
  text1 = text1.trim()
  $("option", select).filter(function () {
    var aux = $(this).text()
    aux = aux.trim()
    if (select == '#codigoSiatSelect') {
      console.log(select);
      
    }
    return aux.toUpperCase() == text1.toUpperCase();
  }).prop('selected', true);
}

function formato_moneda(value, row, index) {
  num = Math.round(value * 100) / 100
  num = num.toFixed(2);
  return (formatNumber.new(num));
}
/*******************formato de numeros***************/
var formatNumber = {

  separador: ",", // separador para los miles
  sepDecimal: '.', // separador para los decimales
  formatear: function (num) {

    num += '';
    var splitStr = num.split('.');
    var splitLeft = splitStr[0];
    var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
    var regx = /(\d+)(\d{3})/;
    while (regx.test(splitLeft)) {
      splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
    }
    return this.simbol + splitLeft + splitRight;
  },
  new: function (num, simbol) {
    this.simbol = simbol || '';
    return this.formatear(num);
  }
}

function redondeo(numero, decimales) {
  var flotante = parseFloat(numero);
  var resultado = Math.round(flotante * Math.pow(10, decimales)) / Math.pow(10, decimales);
  return resultado;
}

function mensajeregistrostabla(res, idtabla) //agrega o quita mensaje de registros encontrados
{
  if (Object.keys(res).length <= 0) $("tbody td", "table" + idtabla).html("No se encontraron registros")
  else $("tbody", "table" + idtabla).show()
}

function codigoControl(res, datos) {

  var autor = res.detalle.autorizacion;
  var nFactura = res.nfac;
  var idNIT = datos.nit;
  var fecha = datos.fecha;
  var monto = datos.monto;
  var llave = res.detalle.llaveDosificacion;
  var nitCasa = res.detalle.nit;

  var gestion = fecha.substring(0, 4);
  var mes = fecha.substring(5, 7);
  var dia = fecha.substring(8, 11);

  fecha = gestion + mes + dia;

  console.log(autor,
    nFactura,
    idNIT,
    fecha,
    monto,
    llave);

  codigo = generateControlCode(
    autor,
    nFactura,
    idNIT,
    fecha,
    monto,
    llave
  );


  $('#codigoControl').html(codigo)
  var gestion = fecha.substring(0, 4);
  var mes = fecha.substring(4, 6);
  var dia = fecha.substring(6, 8);

  var codigoqr = (nitCasa + "|" + nFactura + "|" + autor + "|" + dia + "/" + mes + "/" + gestion + "|" + monto + "|" + monto + "|" + codigo + "|" + idNIT + "|0|0|0|0");
  // $("#textqr").val(codigoqr);
  $("#micapa").html(codigoqr);
  // $("#qr").html(codigoqr);
  $("#qrcodeimg").html("");
  new QRCode(document.getElementById("qrcodeimg"), {
    text: codigoqr,
    width: 128,
    height: 128,
  });
}

function generarQr(idContenedor, codigo) {
  new QRCode(document.getElementById(idContenedor), {
    text: codigo,
    width: 128,
    height: 128,
  });
  console.log("Codigo generado", codigo);
}

function mensajeAnular(idObservacion, funcionAnular, postAlert) {
  swal({
    title: 'Anular movimiento',
    text: 'Cual es el motivo de anulacion?',
    input: 'text',
    type: 'info',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    cancelButtonText: 'Cancelar',
  }).then(function (texto) {
    var txt = $(idObservacion).val();
    txt += " ANULADO: " + texto
    $(idObservacion).val(txt);
    console.log(txt)
    funcionAnular();
    swal({
      type: 'success',
      title: 'Anulado!',
      allowOutsideClick: false,
      html: 'Movimiento anulado: ' + texto
    }).then(function () {
      postAlert();
    })
  })
}

function mensajeRecuperar(idObservacion, funcionAnular, postAlert) {
  swal({
    title: 'Recuperar movimiento',
    text: 'Cual es el motivo de la recuperacion?',
    input: 'text',
    type: 'info',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    cancelButtonText: 'Cancelar',
  }).then(function (texto) {
    var txt = $(idObservacion).val();
    txt += " RECUPERADO: " + texto
    $(idObservacion).val(txt);
    console.log(txt)
    funcionAnular();
    swal({
      type: 'success',
      title: 'Recuperado!',
      allowOutsideClick: false,
      html: 'Movimiento recuperado: ' + texto
    }).then(function () {
      postAlert();
    })
  })
}
/*****************************************************************/
/*****************************************************************/
/*****************************************************************/
Array.prototype.regIndexOf = function (rx) {
  for (var i in this) {
    if (this[i].toString().match(rx)) {
      return i;
    }
  }
  return -1;
};
/**
 * Fuzzy Search in a Collection
 *
 * @param search Regex that represents what is going to be searched
 * @return {Array} ArrayObject with an object of what we are looking for
 */
Array.prototype.fuzzy = function (search) {
  var _return = [];
  /**
   * Runs deep the object, to his last nodes and returns an array with all the values.
   *
   * @param object Object that is going to be analized
   * @return {Array} with all the values of the object at the same level
   */
  var recursive = function (object) {
    return _.map(object, function (obj, key) {
      if (typeof obj !== 'object') {
        return obj.toString();
      } else {
        return recursive(obj);
      }
    });
  };
  // Search inside the flatten array which was returned by recursive
  _.each(recursive(this), function (obj, key) {
    if (obj.regIndexOf(search) > -1) {
      _return.push(this[key]);
    }
  }, this);
  return _return;
};


function mantenerMenu() {
  let pathname = window.location.pathname;
  let dir = pathname.split("/")
  let menu = dir[dir.length - 1];
  if(menu) {
    $("#masterMenu").find("[class*='" + menu + "']").addClass("active").closest(".treeview").addClass("active");
  }
}
function numberDecimal(value, row, index) {
  num = Math.round(value * 100) / 100
  num = num.toFixed(2);
  return (formatNumber.new(num));
}

let aYearAgo = moment().subtract(0, 'year').startOf('year')
function dataPicker(start = aYearAgo) {
	//let start = moment().subtract(0, 'year').startOf('year')
	let end = moment().subtract(0, 'year').endOf('year')
	$(function() {

		function cb(start, end) {
				$('#reportrange span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
				ini = start.format('YYYY-MM-DD')
				fin = end .format('YYYY-MM-DD')
		}
		$('#reportrange').daterangepicker({
				startDate: start,
				endDate: end,
				ranges: {
					'Hoy': [moment(), moment()],
					'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()],
					'Mes actual': [moment().startOf('month'), moment().endOf('month')],
					'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					'Gestion Actual': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
					'Hace 1 Año': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
					'Hace 2 Años': [moment().subtract(2, 'year').startOf('year'), moment().subtract(2, 'year').endOf('year')],
					'Hace 3 Años': [moment().subtract(3, 'year').startOf('year'), moment().subtract(3, 'year').endOf('year')],
				},
				locale: {
					format: 'DD/MM/YYYY',
					applyLabel: 'Aplicar',
					cancelLabel: 'Cancelar',
					customRangeLabel: 'Personalizado',
				},
		}, cb);

		cb(start, end);

	})
}
function getRow(tbl, th) {
	let current_row = $(th).parents('tr')
    if (current_row.hasClass('child')) {
		current_row = current_row.prev()
    }
	let row = tbl.row(current_row).data()
	return row
}
function checkAuth(n) {
  let check = PermisosUser.filter((item) => item.id_sub == n)
  return check=='' ? false : true;
}
$(document).on("click","#botonmodalcliente",function(){
  $("#carnet").prop('disabled', false);
  resetForm('#form_clientes')
  
  $(".modal-title").html("Agregar Cliente")
  $("#bguardar").html("Guardar")
  
})
$(document).on("click",".botoncerrarmodal",function(){
 resetForm('#form_clientes')
})
function validarCliente(tipo) {
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
      complemento: {
        validators: {
          stringLength: {
            min: 1,
            message: 'Ingrese complemento válido'
          },
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
      diasCredito: {
        validators: {
          between: {
            min: 0,
            max: 1000,
            message: 'Igrese número de días de Crédito para el cliente'
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
    /* for(let pair of formData.entries()) { console.log(pair[0]+ ', '+ pair[1]); }
    return */
    $.getJSON({
      url: base_url("index.php/Clientes/store"),
      type: 'POST',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function (returndata) {
        //console.log(returndata);return;
        if (returndata.status==true) {
          /* if(typeof retornarTablaClientes === 'function') {
            retornarTablaClientes() 
          }  */
          if (tipo =='cliente') {
            retornarTablaClientes() 
            console.log('desde cliente');
          } else {
            console.log('desde otro lado');
            validarClienteCorrecto(returndata.id,returndata.cliente.nombreCliente,returndata.cliente.documento)
          }
          $('#modalcliente').modal('hide');
          resetForm('#form_clientes')
          //retornarTablaClientes()
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
            })
        }
      }, 
      error : function (returndata) {
        console.log(returndata.status);
        swal(
            'Error',
            'Error en bases de datos status:'+returndata.status,
            'error'
        )
      }, 
    })
  });
}
function validarClienteCorrecto(id,nombreCliente,documento) {
  let nombre = `${nombreCliente} - ${documento}`
  $("#clientecorrecto").html('<i class="fa fa-check" style="color:#07bf52" aria-hidden="true"></i>');
  $("#cliente_egreso").val(nombre);
  $("#idCliente").val(id);
  glob_guardar_cliente = true;
}
$("#input-1").fileinput({
  language: "es",
  showUpload: false,
  previewFileType: "image",
  maxFileSize: 1024,
 
});
function tipoFactura(data, type, row){

  if (row.cafc == '' || row.cafc == null) {
    if(row.pagadaF == 'ANULADA'){
      $icon = '<div style=" font-size:1.5em; color:red;"><span class="fa fa-desktop computarizada"></span></div>'
    } else if (row.codigoRecepcion == '') {
      $icon = '<div style=" font-size:1.5em; "><span class="fa fa-desktop computarizada"></span></div>'
    } else if(row.lote == '138'){
      $icon = '<div style=" font-size:1.5em; color:blue;"><span class="fa fa-external-link computarizada"></span></div>'
    } else if(row.lote == '138' && row.pagadaF == 'ANULADA'){
      $icon = '<div style=" font-size:1.5em; color:red;"><span class="fa fa-external-link computarizada"></span></div>'
    }else {
      $icon = '<div style=" font-size:1.5em; color:green;"><span class="fa fa-desktop computarizada"></span></div>'
    }
  } else {
    if (row.codigoRecepcion == '') {
      $icon = '<div style=" font-size:1.5em; "><span class="fa fa-pencil computarizada"></span></div>'
    } else {
      $icon = '<div style=" font-size:1.5em; color:green;"><span class="fa fa-pencil computarizada"></span></div>' 
    }
  }
	return $icon;
}
