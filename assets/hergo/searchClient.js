/*******************CLIENTE*****************/
console.log('searchClient');
$(function () {
    $("#cliente_egreso").autocomplete({
            minLength: 2,
            autoFocus: true,
            source: function (request, response) {
                $("#cargandocliente").show(150)
                $("#clientecorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
                glob_guardar_cliente = false;
                $.ajax({
                    url: base_url("index.php/Egresos/retornarClientes"),
                    dataType: "json",
                    data: {
                        b: request.term
                    },
                    success: function (data) {
                        response(data);
                        $("#cargandocliente").hide(150)
                    }
                });

            },

            select: function (event, ui) {
                $("#clientecorrecto").html('<i class="fa fa-check" style="color:#07bf52" aria-hidden="true"></i>');
                $("#cliente_egreso").val(ui.item.nombreCliente + " - " + ui.item.documento);
                $("#idCliente").val(ui.item.idCliente);
                glob_guardar_cliente = true;
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {

            return $("<li>")
                .append("<a><div>" + item.nombreCliente + " </div><div style='color:#615f5f; font-size:10px'>" + item.documento + "</div></a>")
                .appendTo(ul);
        };

});