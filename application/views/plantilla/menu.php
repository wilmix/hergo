<!-- ***MENU***Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo $foto?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php echo $nombre_usuario ?></p>
              <input type="text" value = "<?= $id_Almacen_actual ?>" class="hidden" id="idAlmacenUsuario">
              <input type="text" value = "<?= ($this->ion_auth->is_admin())?"admin":""?>" class="hidden" id="isAdmin">
              <input type="text" value = "<?= ($grupsOfUser=='Nacional') ? "nacional" : ""?>" class="hidden" id="nacional">
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> <?php echo $almacen_usuario ?></a>
            </div>
          </div>

          <!-- search form (Optional) -->
            <!--<form action="#" method="get" class="sidebar-form">
              <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                      <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                      </button>
                    </span>
              </div>
            </form> -->
            <!-- /.search form -->

            <!-- Sidebar Menu -->
          <ul class="sidebar-menu" id="masterMenu">
            <li class="hidden">MENU <span class="pull-right hidden-xs" >T/C: <b id="mostrarTipoCambio"><?= $tipoCambio ?></b></span></li>
            
            <li class="header">USDT <span class="pull-right hidden-xs"><b id="usdt">...</b></span></li>

            <!-- Administración -->
            <li class="treeview">
              <a href="#"><i class="fa fa fa-server"></i> <span>Administración</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="almacen"><a href="<?php echo base_url("index.php/almacen") ?>"><i class="fa fa-industry "></i> Almacen</a></li>
                <li class="articulos"><a href="<?php echo base_url("index.php/articulos") ?>"><i class="fa fa-list-ol "></i> Articulos</a></li>
                <li class="PrecioArticulos"><a href="<?php echo base_url("index.php/PrecioArticulos") ?>"><i class="fa fa-list-alt"></i> PrecioArticulos</a></li>
                <li class="MarcaArticulos"><a href="<?php echo base_url("index.php/MarcaArticulos") ?>"><i class="fa fa fa-columns"></i> Marca Articulos</a></li>
                <li class="linea"><a href="<?php echo base_url("index.php/linea") ?>"><i class="fa fa fa-columns"></i> Linea</a></li>
                <li class="unidad"><a href="<?php echo base_url("index.php/unidad") ?>"><i class="fa fa fa-columns"></i> Unidad</a></li>
                <li class="clientes"><a href="<?php echo base_url("index.php/clientes") ?>"><i class="fa fa-users"></i> Clientes</a></li>
                <li class="Provedores"><a href="<?php echo base_url("index.php/Provedores") ?>"><i class="fa fa-users"></i> Provedores</a></li>
                <li class="CodigoControl"><a href="<?php echo base_url("index.php/CodigoControl") ?>"><i class="fa fa-qrcode"></i> CodigoControl QR</a></li>
                <li class="datosFactura"><a href="<?php echo base_url("index.php/configuracion/datosFactura") ?>"><i class="fa fa-cog"></i> Datos Factura</a></li>
                <li class="tipoCambio"><a href="<?php echo base_url("index.php/configuracion/tipoCambio") ?>"><i class="fas fa-dollar-sign"></i> Tipo de Cambio</a></li>
                <li class="modificarFacturaManual"><a href="<?php echo base_url("facturas/modificarFacturaManual") ?>"><i class="fa fa-pencil-square-o"></i> Modificar Factura Manual </a> </li>
                <li class="cierre"><a href="<?php echo base_url("index.php/cierre") ?>"><i class="fa fa-server"></i> Cierre </a> </li>
              </ul>
            </li>
            <!-- Articulos Web -->
            <li class="treeview">
              <a href="#"><i class="fa fa fa-server"></i> <span>Web</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="ConfigArticulosWeb"><a href="<?php echo base_url("web/ConfigArticulosWeb") ?>"><i class="fa fa-industry "></i> Niveles</a></li>
                <li class="ArticulosWeb">
                  <a href="<?php echo base_url("web/ArticulosWeb") ?>">
                    <i class="fa fa-list-ol ">
                    </i> Articulos Web 
                  </a>
                </li>
                <li class="promos">
                  <a href="<?php echo base_url("web/WebInfo/promos") ?>">
                    <i class="fa fa-list-ol ">
                    </i> Promociones Web
                  </a>
                </li>
              </ul>
            </li>
            <!-- SIAT -->
            <li class="treeview">
              <a href="#">
                <i class="fa fa-share"></i> <span>SIAT</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="Cuis">
                  <a href="<?php echo base_url("siat/codigos/Cuis") ?>">
                    <i class="fa fa-list-ol ">
                    </i> CUIS
                  </a>
                </li>
                <li class="treeview" style="height: auto;">
                  <a href="#"><i class="fa fa-circle-o"></i> Sincronización de Catálogos
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                  <li class="sincronizarCatalogos">
                      <a href="<?php echo base_url("siat/sincronizacion/sincronizar/sincronizarCatalogos") ?>">
                        <i class="fa fa-list-ol ">
                        </i> Sincronizar Catálogos
                      </a>
                    </li>
                    <li class="actividades">
                      <a href="<?php echo base_url("siat/sincronizacion/sincronizar/actividades") ?>">
                        <i class="fa fa-list-ol ">
                        </i> Actividades Económicas
                      </a>
                    </li>
                    <li class="actividadesDocumentoSector">
                      <a href="<?php echo base_url("siat/sincronizacion/sincronizar/actividadesDocumentoSector") ?>">
                        <i class="fa fa-list-ol ">
                        </i> Actividades Documento Sector
                      </a>
                    </li>
                    <li class="listaLeyendasFactura">
                      <a href="<?php echo base_url("siat/sincronizacion/sincronizar/listaLeyendasFactura") ?>">
                        <i class="fa fa-list-ol ">
                        </i> Lista de Leyendas Factura 
                      </a>
                    </li>
                    <li class="listaMensajesServicios">
                      <a href="<?php echo base_url("siat/sincronizacion/sincronizar/listaMensajesServicios") ?>">
                        <i class="fa fa-list-ol ">
                        </i> Lista Mensajes de Servicio 
                      </a>
                    </li>
                    <li class="listaProductosServicios">
                      <a href="<?php echo base_url("siat/sincronizacion/sincronizar/listaProductosServicios") ?>">
                        <i class="fa fa-list-ol ">
                        </i> Lista Productos y Servicios
                      </a>
                    </li>
                    <li class="parametricas">
                      <a href="<?php echo base_url("siat/sincronizacion/sincronizar/parametricas") ?>">
                        <i class="fa fa-list-ol ">
                        </i> Listado Paramétricas 
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="Cufd">cliente
                  <a href="<?php echo base_url("/siat/codigos/Cufd") ?>">
                    <i class="fa fa-list-ol ">
                    </i> CUFD
                  </a>
                </li>
                <li class="eventosSignificativos">
                  <a href="<?php echo base_url("siat/operaciones/Operaciones/eventosSignificativos") ?>">
                    <i class="fa fa-list-ol ">
                    </i> Eventos Significativos
                  </a>
                </li>
                <li class="puntosVenta">
                  <a href="<?php echo base_url("siat/operaciones/Operaciones/puntosVenta") ?>">
                    <i class="fa fa-list-ol ">
                    </i> Puntos de Venta
                  </a>
                </li>
              </ul>
            </li>
            <!-- Importaciones -->
            <li class="treeview">
              <a href="#"><i class="fa fa fa-truck"></i> <span>Importaciones</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                  <li class="crearPedido"><a href="<?php echo base_url("Importaciones/pedidos/crearPedido") ?>"><i class="fa fa-edit"></i> Crear Solicitud</a></li>
                  <li class="pedidos"><a href="<?php echo base_url("Importaciones/pedidos") ?>"><i class="fa fa-list-ol"></i> Solicitudes</a></li>
                  <li class="crearOrden"><a href="<?php echo base_url("Importaciones/OrdenesCompra/crearOrden") ?>"><i class="fa fa-edit"></i> Crear Orden Compra</a></li>
                  <li class="OrdenesCompra"><a href="<?php echo base_url("Importaciones/OrdenesCompra") ?>"><i class="fa fa-list-ol"></i>Ordenes de Compra</a></li>
                  <li class="FacturaProveedores"><a href="<?php echo base_url("Importaciones/FacturaProveedores") ?>"><i class="fa Example of credit-card fa-credit-card"></i>Pago Proveedores</a></li>
                  <li class="EstadoCuentas"><a href="<?php echo base_url("Importaciones/EstadoCuentas") ?>"><i class="fa fa-money"></i>Estado de Cuentas</a></li>
                  <li class="BackOrder"><a href="<?php echo base_url("Importaciones/BackOrder") ?>"><i class="fa fa-bold"></i>BackOrder</a></li>
              </ul>
            </li>
            <!-- Proformas -->
            <li class="treeview">
                <a href="#"><i class="fa fa-product-hunt"></i> <span>Proformas</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li class="Proforma"><a href="<?php echo base_url("Proforma") ?>"><i class="fa fa-list-ol"></i> Proformas</a></li>
                  <li class="crear"><a href="<?php echo base_url("Proforma/formProforma/crear") ?>"><i class="fa fa-edit"></i> Crear Proforma</a></li>
                </ul>
            </li>
            <!-- Ingresos -->
            <li class="treeview">
              <a href="<?php echo base_url("ingresos") ?>"><i class="fa fa-plus-square"></i> <span>Ingresos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">

                <li class="ingresos"><a href="<?php echo base_url("ingresos") ?>"><i class="fa fa-caret-square-o-down "></i>  Consultas</a></li>
                <!--<li><a href="<?php echo base_url("ingresos/consultadetalle") ?>"><i class="fa fa-cog fa-spin fa-fw"></i> ConsultasDetalle
                  <small class="label pull-right bg-red"> construc</small></a></li>-->
                  <li class="Compraslocales"><a href="<?php echo base_url("Ingresos/Compraslocales") ?>"><i class="fa fa-plus-circle"></i> Compras Locales</a></li>
                  <li class="Importaciones"><a href="<?php echo base_url("Ingresos/Importaciones") ?>"><i class="fa fa-plus-circle"></i> Ingreso Importaciones</a></li>
                  <li class="anulacionEgresos"><a href="<?php echo base_url("Ingresos/anulacionEgresos") ?>"><i class="fa fa-plus-circle"></i> Reingreso</a></li>
                </ul>
            </li>
            <!-- Egresos -->
            <li class="treeview">
              <a href="<?php echo base_url("egresos") ?>"><i class="fa fa-minus-square"></i> <span>Egresos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="egresos"><a href="<?php echo base_url("egresos") ?>">Consultas</a></li>
                <li class="ventaCaja"><a href="<?php echo base_url("egresos/crear/ventaCaja") ?>">Venta Caja</a></li>
                <li class="notaEntrega"><a href="<?php echo base_url("egresos/crear/notaEntrega") ?>">Nota de Entrega</a></li>
                <li class="baja"><a href="<?php echo base_url("egresos/crear/baja") ?>">Baja de Producto</a></li>
              </ul>
            </li>
            <!-- Traspasos -->
            <li class="treeview">
              <a href="#"><i class="fa fa fa-exchange"></i> <span>Traspasos </span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="Traspasos"><a href="<?php echo base_url("Traspasos") ?>">Consultas</a></li>
                <li class="traspasoEgreso"><a href="<?php echo base_url("Traspasos/traspasoEgreso") ?>">FormTraspaso</a></li>
              </ul>
            </li>
            <!-- FacturasSiat -->
            <li class="treeview">
              <a href="#"><i class="fa fa-money"></i> <span>Siat Facturas</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="consultaFacturasSiat"><a href="<?php echo base_url("siat/facturacion/emitir/consultaFacturasSiat") ?>">Siat Facturas Consulta</a></li>
                <li class="emitirFacturaSiat"><a href="<?php echo base_url("facturas/emitirFacturaSiat") ?>">Siat Emitir Factura</a></li>
                <li class="consultaFacturasNoEnviadasSiat"><a href="<?php echo base_url("siat/facturacion/emitir/consultaFacturasNoEnviadasSiat") ?>">Siat Facturas No Enviadas</a></li>

              </ul>
            </li>
            <!-- Pagos -->
            <li class="treeview">
              <a href="#"><i class="fa fa-book"></i> <span>Pagos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="Pagos"><a href="<?php echo base_url("Pagos") ?>">Consulta Pagos</a></li>
                <li class="RecibirPago"><a href="<?php echo base_url("Pagos/RecibirPago") ?>">Recibir Pagos</a></li>
                <!-- <li class=""><a href="#">Modificar Pagos</a></li> -->
              </ul>
            </li>
            <!-- Reportes -->
            <li class="treeview">
              <a href="#"><i class="fa fa fa-line-chart"></i> <span>Reportes</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="listaPrecios"><a href="<?php echo base_url("reportes/listaPrecios") ?>">Lista de Precios</a></li>
                <li class="saldosActuales"><a href="<?php echo base_url("reportes/saldosActuales") ?>">Saldos Resumen</a></li>
                <li class="saldosActualesItems"><a href="<?php echo base_url("reportes/saldosActualesItems") ?>">Saldos Actuales Items</a></li>
                <li class="FacturasPendientesPago"><a href="<?php echo base_url("reports/FacturasPendientesPago") ?>">Facturas Pendientes Pago</a></li>
                <li class="facturasPendietesPago"><a href="<?php echo base_url("reportes/facturasPendietesPago") ?>"><del>Facturas Pendientes Pago</del></a></li>
                <li class="resumenVentasLineaMes"><a href="<?php echo base_url("reportes/resumenVentasLineaMes") ?>">Resumen de Ventas Linea Mes</a></li>
                <li class="notasEntregaPorFacturar"><a href="<?php echo base_url("reportes/notasEntregaPorFacturar") ?>">Notas de Entrega por Facturar</a></li>
                <li class="facturacionClientes"><a href="<?php echo base_url("reportes/facturacionClientes") ?>">Facturacion Clientes</a></li>
                <!-- <li class="diarioIngresos"><a href="<?php echo base_url("reportes/diarioIngresos") ?>">Diario de Ingreso</a></li> -->
                <li class="libroVentas"><a href="<?php echo base_url("reportes/libroVentas") ?>">Libro de Ventas</a></li>
                <li class="kardexIndividualValorado"><a href="<?php echo base_url("reportes/kardexIndividualValorado") ?>">Kardex Individual Valorado</a></li>
                <li class="kardexIndividualCliente"><a href="<?php echo base_url("reportes/kardexIndividualCliente") ?>">Kardex Individual Clientes</a></li>
                <!-- <li class="estadoVentasCostoItem"><a href="<?php echo base_url("reportes/estadoVentasCostoItem") ?>">Estado de Ventas y Costos</a></li> -->
                <li class="estadoVentasCostoItemNew"><a href="<?php echo base_url("reportes/estadoVentasCostoItemNew") ?>">Estado de Ventas y Costos</a></li>
                <li class="pruebaKardex"><a href="<?php echo base_url("reportes/pruebaKardex") ?>">Prueba Kardex</a></li>
                <li class="ventasClientesItems"><a href="<?php echo base_url("reportes/ventasClientesItems") ?>">Movimientos Item Cliente</a></li>
                <li class="reporteClienteItems"><a href="<?php echo base_url("reportes/reporteClienteItems") ?>">Movimientos Cliente Item</a></li>
                <li class="reporteIngresos"><a href="<?php echo base_url("reportes/reporteIngresos") ?>">Reporte Ingresos</a></li>
                <li class="reporteEgresos"><a href="<?php echo base_url("reportes/reporteEgresos") ?>">Reporte Egresos</a></li>
                <li class="reporteFacturas"><a href="<?php echo base_url("reportes/reporteFacturas") ?>">Reporte Facturas</a></li>
                <li class="reportePagos"><a href="<?php echo base_url("reportes/reportePagos") ?>">Reporte Pagos</a></li>
                <li class="FacturaTipoPago"><a href="<?php echo base_url("reports/FacturaTipoPago") ?>">FacturaTipoPago</a></li>
              </ul>
            </li>
            <!-- Reportes 3M -->
            <li class="treeview">
              <a href="#"><i class="fa fa-trademark"></i> <span>Reportes 3M</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                  
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="ventasTM"><a href="<?php echo base_url("reportes/ventasTM") ?>">Ventas TM</a></li>
                <li class="inventarioTM"><a href="<?php echo base_url("reportes/inventarioTM") ?>">Inventario TM</a></li>
                <!-- <li class=""><a href="#">Modificar Pagos</a></li> -->
              </ul>
            </li>
            <!-- Usuarios -->
            <li class="treeview">
              <a href="#"><i class="fa fa-users"></i> <span>Usuarios</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="usuarios"><a href="<?php echo base_url('index.php/auth/usuarios') ?>"><i class="fas fa-users"></i> Ver Usuarios</a></li>
                <li class="create_user"><a href="<?php echo base_url('index.php/auth/create_user') ?>"><i class="fas fa-user-plus"></i> Nuevo Usuario</a></li>
                <!--<li class="create_group"><a href="<?php echo base_url('index.php/auth/create_group') ?>">Nuevo Grupo</a></li>-->
                <li class="roles"><a href="<?php echo base_url("index.php/Roles/roles") ?>"><i class="fas fa-lock"></i> Roles</a></li>
              </ul>
            </li>
            <!-- Gestiones Anteriores -->
            <li class="treeview">
              <a href="#"><i class="fa fa-arrow-left"></i> <span>Gestiones Anteriores</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="https://2024.hergo.app/" target="_blank"><i class="fa fa-arrow-circle-left"></i>Gestión 2024<small class="label pull-right bg-green">new</small></a></li>
                <li class=""><a href="https://2023.hergo.app/" target="_blank"><i class="fa fa-arrow-circle-left"></i>Gestión 2023</a></li>
                <li class=""><a href="https://2022.hergo.app/" target="_blank"><i class="fa fa-arrow-circle-left"></i>Gestión 2022</a></li>
                <li class=""><a href="https://2021.hergo.app/" target="_blank"><i class="fa fa-arrow-circle-left"></i>Gestión 2021</a></li>
                <li class=""><a href="https://2020.hergo.app/" target="_blank"><i class="fa fa-arrow-circle-left"></i>Gestión 2020</a></li>
                <li class=""><a href="https://2019.hergo.app/" target="_blank"><i class="fa fa-arrow-circle-left"></i>Gestión 2019</a></li>
              </ul>
            </li>
            <!-- Facturas Anterior-->
            <li class="treeview">
              <a href="#"><i class="fa fa-money"></i> <span>Facturas Anterior</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="facturas"><a href="<?php echo base_url("facturas") ?>">Consulta de Facturas</a></li>
                <!-- <li class="EmitirFactura"><a href="<?php echo base_url("facturas/EmitirFactura") ?>">Emitir Factura</a></li> -->
              </ul>
            </li>
          </ul>
          <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">