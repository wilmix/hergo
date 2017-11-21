
    <div id="facPrev" class="modal fade" role="dialog" >
        <div class="modal-dialog modal-lg" id="app">

            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <!--<h1 class="modal-title modal-ce">FACTURA</h1>-->
            </div>
            <div class="modal-body">
                <div class="row">
                <div class="col-md-4" class="text-center">
                <img align="center" src="<?php echo base_url("images/hergo.jpeg") ?>" alt="hergo" width="200" height="40">
                <div id="direction">
                    <p><b>{{almacen.sucursal}}</b> <br>
                    {{almacen.direccion}} <br>
                    {{almacen.ciudad}} - Bolvia </p>
                </div>
                </div>
                <div class="col-md-4" class="text-center">
                <h1 class="text-center"><b>FACTURA</b></h1>
                </div>
                <div class="text-center" class="col-md-4">
                    <p id="nitcss"><b>NIT: <span id="fNit">{{nit}} </span></b></p>
                    <p>FACTURA N°: <b id="fnumero">{{numero}}</b> <br>
                    AUTORIZACION N°: <span id="fauto">{{autorizacion}}</span></p>
                </div>
                </div>
                <div class="row">
                <div class="col-md-8">
                <p>Lugar y Fecha: <span id="fechaFacturaModal">{{lugarFecha()}}</span><br>
                Señor(es): <span id="clienteFactura">{{ClienteFactura}}</span>  <br>
                OC/Pedido: <span id="clientePedido">{{pedido}}</span></p>
                </div>
                    <div class="col-md-4">
                    <p class="text-center">NIT/CI:  <b><span id="clienteFacturaNit">{{ClienteNit}}</span></b></p>
                    <p id="direction" class="text-center">Actividad economica: VENTA AL POR MAYOR DE MAQUINARIA, EQUIPO Y MATERIALES</p>
                </div>
                </div>
                <div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Unid.</th>
                        <th>Codigo</th>
                        <th>Articulo</th>
                        <th class="text-right">Precio Unit.</th>
                        <th class="text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody id="cuerpoTablaFActura">
                        <tr v-for="fila in datosFactura">
                            <td>
                                {{fila.facturaCantidad}}
                            </td>
                            <td>
                                {{fila.Sigla}}
                            </td>
                            <td>
                                {{fila.ArticuloCodigo}}
                            </td>
                            <td>
                                {{fila.ArticuloNombre}}
                            </td>
                            <td class="text-right">
                                {{fila.facturaPUnitario | moneda}}
                            </td>
                            <td class="text-right">
                                {{fila.facturaCantidad*fila.facturaPUnitario | moneda}}
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
                <div class="row">
                <div class="col-md-9">
                    <p>SON: <b id="totalTexto">{{retornarTotal() | literal}}</b></p>
                    <p>NOTA: <span id="notaFactura">{{glosa}}</span></p>
                    <br>
                    <br>
                    <template v-if="manual==0">
                        <p>CODIGO DE CONTROL: <span id="codigoControl">{{codigoControl}}</span></p>
                    </template>
                    <p>FECHA LIMITE DE EMISIÓN: <span id="fechaLimiteEmision">{{fechaLimiteEmision | fechaCorta}}</span></p>
                </div>
                <div class="col-md-3">
                    <input id="totalsinformatobs" class="hidden">
                    <template v-if="moneda==2"><p>Total $US:   <span id="totalFacturaSusModal1">{{ dolares(retornarTotal()) | moneda }}</span></p></template>
                    <p>Total Bs: <span id="totalFacturaBsModal1">{{retornarTotal()| moneda}}</span></p>
                    <template v-if="moneda==2"><p>T/C <span id="tipoCambioFacturaModal">{{tipocambio}}</span></p></template>
                
                    <div id="qrcodeimg"></div>                
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <p align="center">"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LA LEY"</p>
                <p align="center">Ley Nº 453: Está prohibido importar, distribuir  o comercializar productos expirados o prontos a expirar </p>
                <button v-if="guardar" type="button" class="btn btn-primary" id="guardarFactura">Grabar Factura</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
            </div>

        </div>    
    </div>

 
