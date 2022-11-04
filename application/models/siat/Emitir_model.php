<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Emitir_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
	}
	public function pendientes($almacen)
	{
		$sql="  SELECT
                    dt.codigoClasificador codigoDocumentoTipo,
                    dt.descripcion documentoTipo,
                    dt.documentosigla documentoTipoSigla,
                    a.almacen,
                    e.idegresos id,
                    t.sigla sigla,
                    e.nmov num,
                    CONCAT(t.sigla,'-',e.nmov,'/',SUBSTRING(e.gestion, 3, 3)) movimiento,
                    CONCAT(t.sigla,'-',e.nmov) mov,
                    e.fechamov fecha,
                    c.idcliente cliente_id,
                    c.complemento,
                    c.email,
                    dt.codigoClasificador codigoTipoDocumentoIdentidad,
                    c.documento numeroDocumento,
                    c.nombreCliente nombreCliente,
                    ROUND(
                        SUM(ROUND(d.cantidad, 2) * ROUND(d.punitario, 2)),
                        2
                    ) montoTotal,
                    e.estado estado_id,
                    CASE
                        WHEN e.estado = 0 THEN 'POR FACTURAR'
                        WHEN e.estado = 2 THEN 'PARCIAL'
                    END estado,
                    tc.tipoCambio,
                    CONCAT(u.first_name, ' ', u.last_name) vendedor,
                    m.id moneda_id,
                    m.sigla monedaSigla,
                    e.clientePedido,
                    ROUND(
                        SUM(ROUND(d.cantidad, 2) * ROUND(d.punitario, 2)) / tc.tipocambio,
                        2
                    ) montoTotalDolares
                FROM
                    egresos e
                    INNER JOIN egredetalle d on e.idegresos = d.idegreso
                    INNER JOIN tmovimiento t ON e.tipomov = t.id
                    INNER JOIN clientes c ON e.cliente = c.idCliente
                    INNER JOIN documentotipo dt ON dt.idDocumentoTipo = c.idDocumentoTipo 
                    INNER JOIN users u ON u.id = e.vendedor
                    INNER JOIN almacenes a ON a.idalmacen = e.almacen
                    INNER JOIN moneda m ON e.moneda = m.id
                    INNER JOIN tipocambio tc ON tc.fecha = e.fechamov
                WHERE
                    e.anulado <> 1
                    AND e.almacen = '$almacen'
                    AND (
                        e.tipomov = 6
                        OR e.tipomov = 7
                    )
                    AND (
                        e.estado = 0
                        OR e.estado = 2
                    )
                GROUP BY
                    e.idegresos
                ORDER BY
                    e.idegresos DESC
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
    }
    public function pendientesDetalle($id)
	{
		$sql="  SELECT
                    a.codigoCaeb actividadEconomica,
                    a.codigoProducto codigoProductoSin,
                    a.CodigoArticulo codigoProducto,
                    a.Descripcion descripcion,
                    (d.cantidad - d.cantFact) cantidad,
                    u.siat_codigo unidadMedida,
                    CASE
					    WHEN  e.moneda= '3' THEN ROUND(d.punitario / tc.tipocambio,2) 
					    ELSE d.punitario
					END precioUnitario,
                    0 montoDescuento,
                    CASE
					    WHEN  e.moneda= '3' THEN ROUND(d.cantidad * d.punitario/ tc.tipocambio,2)
					    ELSE ROUND(d.cantidad * d.punitario,2)
					END subTotal,

                    '' numeroSerie,
                    '' numeroImei,
                    IF(e.moneda = '2' , '2', '1') moneda,
                    tc.tipocambio,
                    a.idArticulos articulo_id,
                    d.idingdetalle detalle_egreso_id,
                    d.idegreso egreso_id,
                    (d.cantidad - d.cantFact) cantidad_facturar,
                    siat.descripcion siatDescripcion,
                    u.Sigla unidadHg
                FROM
                    egredetalle d
                    INNER JOIN articulos a ON a.idArticulos = d.articulo
                    INNER JOIN unidad u ON u.idUnidad = a.idUnidad
                    INNER JOIN siat_sincro_unidad_medida siat ON siat.codigoClasificador = u.siat_codigo
                    INNER JOIN egresos e ON e.idegresos = d.idegreso
                    INNER JOIN tipocambio tc ON tc.fecha = e.fechamov
                WHERE
                    d.idegreso = '$id'
                    AND (d.cantidad - d.cantFact) > 0
                ORDER BY a.CodigoArticulo
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
    }
    public function monedas_siat()
	{
		$sql="  SELECT
                    m.codigoClasificador id,
                    descripcion label 
                FROM
                    siat_sincro_tipo_moneda m 
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
    }
    public function metodos_pago_siat()
	{
		$sql="  SELECT
                    p.codigoClasificador id,
                    p.descripcion label 
                FROM
                    siat_sincro_tipo_metodo_pago p 
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
    }
    public function getCodigos($sucursal, $codigoPuntoVenta = 0)
	{
		$sql="  SELECT
                    cuis.sucursal,
                    cuis.codigoPuntoVenta,
                    cuis.cuis,
                    a.ciudad,
                    a.phone,
                    a.address,
                    cuis.fechaVigencia fechaVigenciaCuis,
                    IF(
                        STR_TO_DATE(cuis.fechaVigencia, '%Y-%m-%dT%T') > NOW(),
                        'VIGENTE',
                        'CADUCO'
                    ) estadoCuis,
                    cufd.codigo codigoCufd,
                    cufd.codigoControl codigoControlCufd,
                    IF(
                        STR_TO_DATE(cufd.fechaVigencia, '%Y-%m-%dT%T') > NOW(),
                        'VIGENTE',
                        'CADUCO'
                    ) estadoCufd
                FROM
                    siat_cuis cuis 
                    INNER JOIN almacenes a ON cuis.almacen_id = a.idalmacen
                    INNER JOIN siat_cufd cufd ON cufd.cuis = cuis.cuis 
                WHERE
                    cuis.sucursal = '$sucursal'
                    AND cuis.codigoPuntoVenta = '$codigoPuntoVenta'
                ORDER BY cufd.id DESC, cuis.id DESC 
                LIMIT 1 
                ";
		$query=$this->db->query($sql);		
		return $query->row();
    }
    public function getLeyenda()
	{
		$sql="  SELECT
                    *
                FROM
                    siat_sincro_leyenda_facturas l
                WHERE
                    l.codigoActividad = '465000'
                ORDER BY RAND()
                LIMIT 1;
                ";
		$query=$this->db->query($sql);		
		return $query->row();
    }
    public function getUser($id)
	{
		$sql="  SELECT
                    u.id,
                    CONCAT(SUBSTRING(upper(u.first_name), 1, 1),SUBSTRING(upper(u.last_name), 1, 9)) usuario
                FROM
                    users u
                WHERE
                    u.id = '$id'
                LIMIT 1;
                ";
		$query=$this->db->query($sql);		
		return $query->row();
    }
    public function storeFacturaSiat($factura, $facturaSiat)
	{	
        try {
            $this->db->trans_start();
                $this->db->insert("factura", $factura);
                $idFactura=$this->db->insert_id();

             $facturaSiat->factura_id = $idFactura;
                $this->db->insert("factura_siat", $facturaSiat);


                if($idFactura>0)
                {
                    $facturaDetalle = array();
                    $facturaEgreso=array();
                    foreach ($factura->articulos as $fila) {
                        $detalle=new stdclass();
                        $detalle->idFactura = $idFactura;
                        $detalle->articulo = $fila['articulo'];
                        $detalle->moneda = $factura->moneda;
                        $detalle->facturaCantidad = $fila['facturaCantidad'];
                        $detalle->facturaPUnitario = $fila['facturaPUnitario'];
                        $detalle->ArticuloNombre = $fila['ArticuloNombre'];
                        $detalle->ArticuloCodigo = $fila['ArticuloCodigo'];
                        $detalle->idEgresoDetalle = $fila['idEgresoDetalle'];
                        array_push($facturaDetalle,$detalle);

                        $this->Egresos_model->actualizarCantFact($fila['idEgresoDetalle'],$fila['facturaCantidad']);
                        $this->actualizarEstado($fila['egreso_id']);	
                        
                        $factura_egreso = new stdclass();
                        $factura_egreso->idegresos = $fila['egreso_id'];
                        $factura_egreso->idFactura = $idFactura;
                        array_push($facturaEgreso,$factura_egreso);

                    }
                $this->db->insert_batch("facturadetalle", $facturaDetalle);
                $this->db->insert_batch("factura_egresos", $facturaEgreso);
                }
                else
                {
                    echo false;
                }
            $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
			return false;
			
        } else {
            
            return $idFactura;
        }
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
	}
    public function actualizarEstado($idEgreso)//cambia el estado si esta pendiente o facturado
	{
		$estado=0;
		$cantidad=$this->Egresos_model->evaluarFacturadoTotal($idEgreso); //si es 0 facturado total si no parcial
		if(count($cantidad)==0)//Facturado
			$estado=1;
		else
			$estado=2;
		$this->Egresos_model->actualizarEstado($idEgreso,$estado);
		return $estado;
	}
    public function getFactura($id)
    {
        $sql="  SELECT
                    a.address direccion,
                    a.ciudad,
                    a.phone telefono,
                    a.sucursal,
                    fs.codigoPuntoVenta,
                    f.nFactura numeroFactura,
                    fs.cuf,
                    f.codigoEmision,
                    date_format(fs.fechaEmision, '%d/%m/%Y %H:%i %p') fechaEmision,
                    date_format(fs.fechaEmision, '%y') gestion,
                    f.ClienteNit documentoNumero,
                    fs.complemento,
                    f.ClienteFactura nombreRazonSocial,
                    f.cliente codigoCliente,
                    fs.pedido,
                    fs.leyenda,
                    f.glosa,
                    f.moneda,
                    fs.montoTotalMoneda,
                    f.total
                FROM
                    factura f
                    INNER JOIN factura_siat fs ON fs.factura_id = f.idFactura
                    INNER JOIN clientes c ON c.idCliente = f.cliente 
                    INNER JOIN almacenes a ON a.idalmacen = f.almacen
                WHERE
                    f.idFactura = '$id'
                ";
		$query=$this->db->query($sql);		
		return $query->row();
    }
    public function getDetalleFactura($id)
    {
        $sql="  SELECT
                    fd.ArticuloCodigo codigo,
                    ROUND(fd.facturaCantidad, 2) cantidad,
                    u.Unidad unidadHergo,
                    su.descripcion unidad,
                    fd.ArticuloNombre descripcion,
                    ROUND(fd.facturaPUnitario, 2) precioUnitario,
                    0 descuento,
                    ROUND(fd.facturaCantidad, 2) * ROUND(fd.facturaPUnitario, 2) subTotal
                FROM
                    facturadetalle fd
                    INNER JOIN articulos a ON a.idArticulos = fd.articulo
                    INNER JOIN unidad u ON u.idUnidad = a.idUnidad
                    INNER JOIN siat_sincro_unidad_medida su ON su.codigoClasificador = u.siat_codigo
                WHERE
                    fd.idFactura ='$id'
                ";
		$query=$this->db->query($sql);		
		return $query->result();
    }
    public function getFacturasSiat($ini, $fin, $alm)
	{
		$sql="  SELECT
                    cuis.cuis,
                    fs.codigoSucursal,
                    fs.codigoPuntoVenta,
                    f.`idFactura`,
                    f.`lote`,
                    df.`manual`,
                    -- CONCAT('F',f.`nFactura`) numeroFactura,
                    f.`nFactura` numeroFactura,
                    f.`fechaFac`,
                    f.`ClienteNit`,
                    f.`ClienteFactura`,
                    t.`sigla`,
                    f.`total`,
                    CONCAT(u.first_name, ' ', u.last_name) AS vendedor,
                    f.`anulada`,
                    f.fecha,
                    -- GROUP_CONCAT(DISTINCT e.nmov ORDER BY e.nmov ASC SEPARATOR ' - ') AS movimientos,
                    f.glosa,
                    p.idPago,
                    p.`numPago`,
                    CONCAT(
                        '[',
                        GROUP_CONCAT(
                            DISTINCT '{',
                            '\"id\":',
                            e.`idegresos`,
                            ',',
                            '\"n\":',
                            '\"',
                            t.`sigla`,
                            '-',
                            e.nmov,
                            '\"',
                            '}'
                            ORDER BY
                                e.nmov ASC SEPARATOR ','
                        ),
                        ']'
                    ) AS movEgreso,
                    CONCAT(
                        '[',
                        GROUP_CONCAT(
                            DISTINCT '{',
                            '\"id\":',
                            p.idPago,
                            ',',
                            '\"n\":',
                            p.`numPago`,
                            '}'
                            ORDER BY
                                p.`numPago` ASC SEPARATOR ','
                        ),
                        ']'
                    ) AS pagos,
                    f.`pagada`,
                    f.almacen idAlmacen,
                    e.clientePedido pedido,
                    CASE
                        WHEN f.moneda = 1 THEN 'BOB'
                        WHEN f.moneda = 2 THEN CONCAT('$', 'U$')
                    END moneda,
                    CASE
                        WHEN f.`anulada` = 1 THEN 'ANULADA'
                        WHEN f.`pagada` = 0 THEN 'NO PAGADA'
                        WHEN f.`pagada` = 1 THEN 'PAGADA'
                        WHEN f.`pagada` = 2 THEN 'PAGO PARCIAL'
                    END pagadaF,
                    CONCAT(ua.first_name, ' ', ua.last_name) emisor,
                    tp.tipoPago,
                    fs.codigoRecepcion,
                    fs.cuf,
                    fs.cafc,
                    fs.pedido,
                    sp.descripcion metodoPago,
                    fs.fechaEmision fechaEmisionSiat
                FROM
                    factura_egresos fe
                    INNER JOIN egresos e on e.idegresos = fe.idegresos
                    INNER JOIN factura f on f.idFactura = fe.idFactura
                    LEFT JOIN pago_factura pf ON f.idFactura = pf.idFactura
                    LEFT JOIN pago p ON p.idPago = pf.idPago
                    INNER JOIN tmovimiento t on e.tipomov = t.id
                    INNER JOIN datosfactura df on df.idDatosFactura = f.lote
                    INNER JOIN users u on u.id = e.vendedor
                    INNER JOIN users ua ON ua.id = f.autor
                    INNER JOIN tipoPago tp ON tp.id = f.tipoPago
                    INNER JOIN factura_siat fs ON fs.factura_id = f.idFactura 
                    INNER JOIN siat_sincro_tipo_metodo_pago sp ON sp.codigoClasificador = fs.codigoMetodoPago
                    INNER JOIN siat_cuis cuis ON cuis.sucursal = fs.codigoSucursal AND cuis.codigoPuntoVenta = fs.codigoPuntoVenta AND cuis.active = 1
                WHERE
                    f.fechaFac BETWEEN '$ini'
                    AND '$fin'
                    AND f.almacen = '$alm'
                    AND f.lote = 0 
                GROUP BY
                    fe.idFactura
                ORDER BY
                    f.idFactura DESC  
                ";
		$query=$this->db->query($sql);		
		return $query->result();
    }
    public function getMotivosAnulacion()
	{
		$sql="  SELECT
                    a.codigoClasificador,
                    a.descripcion label 
                FROM
                    siat_sincro_motivo_anulacion a  
                ";
		$query=$this->db->query($sql);		
		return $query->result();
    }
    public function getMotivosEvento()
	{
		$sql="  SELECT
                    e.codigoClasificador codigo,
                    e.descripcion label
                FROM
                    siat_sincro_eventos_significativos e  
                ";
		$query=$this->db->query($sql);		
		return $query->result();
    }
    public function anularFactura($idFactura,$msj,$idAlmacen,$user_id)
	{
		$this->db->trans_start();
			
			$autor = $user_id;
        	$fecha = date('Y-m-d H:i:s'); 
			
			
			$msj = strval($msj);
			$sql="UPDATE factura set anulada=1,glosa='$msj',autor='$autor',update_at=NOW() where idFactura=$idFactura;";
			$this->db->query($sql);

			$facturaDetalle=$this->obtenerDetalleFactura($idFactura);		
			foreach ($facturaDetalle as $fila) 
			{
				
				if($fila["idEgresoDetalle"]!=null)
				$this->Egresos_model->actualizarRestarCantFact($fila["idEgresoDetalle"],$fila["facturaCantidad"]);		
			}
			
			$facturaEgresos=$this->FacturaEgresos_model->obtenerEgresosPorFactura($idFactura);
			foreach ($facturaEgresos as $egre) {
				$this->FacturaEgresos_model->actualizarFparcial_noFacturado($idFactura,$egre->idegresos);
			}
			
			$this->db->query("CALL actualizarTablaSaldoFactura($idFactura, $idAlmacen)");
			
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
			
		} else {
			
			return true;
		}
	}
    public function obtenerDetalleFactura($idFactura)
	{
		$sql="SELECT f.`id`, f.`idFactura`, f.`articulo`,f.`moneda`, f.`facturaPUnitario`, f.`ArticuloCodigo`, f.`ArticuloNombre`, f.`idEgresoDetalle`, u.Sigla, f.`facturaCantidad`
		from facturadetalle f 
		INNER JOIN articulos a
		ON a.idArticulos=f.articulo
		INNER JOIN unidad u
		ON u.idUnidad=a.idUnidad
		Where idFactura=$idFactura";
		$query=$this->db->query($sql);        
        return ($query->result_array());
	}
    public function getFacturasSiatNoEnviadas($ini, $fin, $alm)
	{
		$sql="  SELECT
                    f.idFactura,
                    f.nFactura numeroFactura,
                    f.fechaFac,
                    f.ClienteNit,
                    f.ClienteFactura,
                    fs.cuf,
                    fs.codigoRecepcion,
                    f.total,
                    fs.cufd,
                    fs.codigoPuntoVenta,
                    fs.codigoSucursal,
                    fs.fechaEmision,
                    fs.cafc,
                    CONCAT(ua.first_name, ' ', ua.last_name) emisor
                FROM
                    factura f
                    INNER JOIN factura_siat fs ON fs.factura_id = f.idFactura
                    INNER JOIN users ua ON ua.id = f.autor
                WHERE f.fechaFac BETWEEN '$ini'
                    AND '$fin'
                    AND f.almacen = '$alm' 
                    AND fs.codigoRecepcion = ''
                    OR fs.codigoRecepcion IS NULL
                ";
		$query=$this->db->query($sql);		
		return $query->result();
    }
    public function updateCodigoRecepcion($cuf, $codigoRecepcion)
    {
        $this->db->set('codigoRecepcion', $codigoRecepcion);
        $this->db->where('cuf', $cuf);
        $res = $this->db->update('factura_siat');
        return $res;
    }
}
