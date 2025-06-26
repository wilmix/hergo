<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}


    public function storePedido($id, $pedido)
	{	
        if ($id) {
            $this->db->trans_start();
                $this->db->where('id', $id);
                $this->db->update('pedidos', $pedido);
                
                $this->db->where('idPedido', $id);
                $this->db->delete('pedidos_items');
                $this->storeItems($id, $pedido);

            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        } else {
            $this->db->trans_start();
                $this->db->insert("pedidos", $pedido);
                $id=$this->db->insert_id();

                $this->storeItems($id, $pedido);
            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        }
    }
    public function storeOrden($id, $orden)
	{	
        if ($id) {
            $this->db->trans_start();
                $this->db->where('id', $id);
                $this->db->update('ordenescompra', $orden);
            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        } else {
            $this->db->trans_start();
                $this->db->insert("ordenescompra", $orden);
                $id=$this->db->insert_id();
            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        }
    }
    public function storeAsociarFactura($id, $asoFac)
	{	
        if ($id) {
            $this->db->trans_start();
                $this->db->where('id', $id);
                $this->db->update('fact_prov', $asoFac);
            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        } else {
            $this->db->trans_start();
                $this->db->insert("fact_prov", $asoFac);
                $id=$this->db->insert_id();
            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        }
    }
    public function storeItems($id, $pedido)
    {
        $itemsArray = array();
        foreach ($pedido->items as $fila) {
            $item=new stdclass();
            $item->idPedido = $id;
            $item->articulo = $fila->id;
            $item->cantidad = $fila->cantidad;
            $item->precioFabrica = $fila->precioFabrica;
            $item->saldo = $fila->saldo;
            $item->rotacion = $fila->rotacion;
            $item->precio = $fila->precio;
            array_push($itemsArray,$item);	
        }
        $this->db->insert_batch("pedidos_items", $itemsArray);
    }
    public function aprobar($aprobar)
	{	
        $this->db->trans_start();
            $this->db->insert("pedidos_aprobado", $aprobar);
            $id=$this->db->insert_id();
        $this->db->trans_complete();
        return ( $this->db->trans_status() === FALSE ) ? false : $id;
    }
    public function getPedidos($ini, $fin, $condicion)
	{ 
    	$sql=" SELECT p.`id` id_pedido, p.`n` nPedido, p.`fecha`, p.`recepcion`, p.`formaPago`, p.`pedidoPor`,pro.`nombreproveedor` proveedor, CONCAT(u.`first_name`, ' ', u.`last_name`) autor, oc.`id` id_ordenCompra, items.total$, items.totalBOB,
            COUNT(*) nAprobados, p.`created_at` created_at_pedido,IFNULL(p.flete,0) flete,
            oc.`id`id_ordenCompra, oc.`n` nOrden,  oc.`fecha` fechaOC, pro.`direccion`, pro.`telefono` fono, pro.`fax` fax, oc.created_at created_at_orden,
            oc.`atencion`, oc.`referencia`, oc.`condicion`, oc.`formaEnvio`, p.`formaPago`, CONCAT(u.`first_name`, ' ', u.`last_name`) autorOC, oc.`diasCredito`, p.`proveedor` id_proveedor,
            (items.total$ - IFNULL(saldoFac.totalFacCom,0))saldoAsoFac,
                CASE
                    WHEN saldoFac.totalFacCom IS NULL THEN 'NO FACTURA'
                    WHEN saldoFac.totalFacCom<items.total$ THEN 'PARCIAL'
                    ELSE 'FACTURADA'
                END estadoOrden
            FROM pedidos_aprobado pa
            RIGHT JOIN pedidos p ON p.`id` = pa.`id_pedido`
                LEFT JOIN ordenescompra oc ON oc.`id_pedido` = p.`id`
                INNER JOIN provedores pro ON pro.`idproveedor` = p.`proveedor`
                INNER JOIN users u ON u.`id` = p.`autor`
                LEFT JOIN 
                (
                    SELECT fp.`id_orden`, SUM(fp.`monto`) totalFacCom
                    FROM fact_prov fp
                    GROUP BY fp.`id_orden`
                )saldoFac ON saldoFac.`id_orden` = oc.`id`
                INNER JOIN (SELECT
                            pit.`idPedido`,
                            (ROUND(SUM(pit.`cantidad` * pit.`precioFabrica`), 2) + IFNULL(p.flete,0)) total$,
                            SUM(ROUND(pit.`cantidad` * pit.`precioFabrica` * tc.`tipocambio`,2)) + ROUND(IFNULL(p.flete * tc.`tipocambio`,0),2) totalBOB
                            FROM
                            pedidos_items pit
                            INNER JOIN pedidos p
                                ON p.`id` = pit.`idPedido`
                            INNER JOIN `tipocambio` tc
                                ON tc.`fecha` = p.`fecha`
                            GROUP BY pit.`idPedido`
                        )items 
                ON items.idPedido = p.`id`
            GROUP BY p.`id`
            HAVING  
                CASE
                    WHEN '$condicion' = 'todos' THEN p.`fecha` BETWEEN '$ini' AND '$fin' 
                    WHEN '$condicion' = 'aprobadosNoOrden' THEN p.`fecha` BETWEEN '$ini' AND '$fin' AND COUNT(*) > 2  AND oc.`id` IS  NULL
                    WHEN '$condicion' = 'ordenProcess' THEN p.`fecha` BETWEEN '$ini' AND '$fin' AND COUNT(*) > 2  AND oc.`id` IS NOT NULL
                END                
            ORDER BY  p.`n` DESC
            ";

        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getFacturaProveedores($ini, $fin, $filtro)
	{ 
    	$sql="  SELECT
                    oc.id,
                    fp.`id` id_fact_prov,
                    IF(oc.id = 0 , 'SERV' , CONCAT('HG-',oc.`n`,'/',DATE_FORMAT (oc.`fecha`, '%y'))) orden,
                    pro.`idproveedor` id_proveedor,
                    pro.`nombreproveedor` proveedor,
                    fp.`glosa`,
                    fp.`transporte`,
                    CONCAT(fp.`tiempo_credito`, ' días') tiempo_credito,
                    fp.`fecha`,
                    fp.`n` facN,
                    fp.`monto` montoFactPro,
                    fp.`monto`,  
                    DATE_ADD(fp.`fecha`,INTERVAL fp.`tiempo_credito` DAY) vencimiento,
                    IF (CURDATE() < DATE_ADD(fp.`fecha`,INTERVAL fp.`tiempo_credito` DAY),'VIGENTE','VENCIDA') estado,
                    fp.`url`,
                    fp.url_pdf,
                    tpp.totalPago, 
                    (IFNULL(fp.`monto`,0) - IFNULL(tpp.totalPago,0)) saldo
                FROM
                    fact_prov fp
                    INNER JOIN provedores pro
                    ON pro.`idproveedor` = fp.`proveedor`
                    INNER JOIN ordenescompra oc
                    ON oc.`id` = fp.`id_orden`
                    LEFT JOIN (
                        SELECT fpp.`id_fact_prov`, SUM(fpp.`monto`) totalPago
                        FROM fact_pago_prov fpp
                        GROUP BY fpp.`id_fact_prov`
                    )tpp
                    ON tpp.id_fact_prov = fp.`id`
                HAVING  
                    CASE
                        WHEN '$filtro' = 'servicios' THEN fp.`fecha` BETWEEN '$ini' AND '$fin' AND (fp.`monto` - IFNULL(tpp.totalPago,0)) > 0 AND oc.id = 0
                        WHEN '$filtro' = 'pedidos' THEN fp.`fecha` BETWEEN '$ini' AND '$fin' AND (fp.`monto` - IFNULL(tpp.totalPago,0)) > 0 AND oc.id > 0
                    END  
                ORDER BY fp.`fecha` DESC
            ";

        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getPedido($id)
	{ 
    	$sql="SELECT p.id, p.`n`, p.`fecha`, p.`recepcion`,pro.`idproveedor` idProv,  pro.`nombreproveedor` proveedor, p.`pedidoPor`, p.`cotizacion`, p.`diasCredito`,  p.`formaPago`, p.`glosa`,
        pro.`direccion`, pro.`telefono`, pro.`fax`, IFNULL(p.flete,0) flete
        FROM pedidos p
        INNER JOIN provedores pro ON pro.`idproveedor` = p.`proveedor`
        WHERE p.`id` = '$id'";

        $query=$this->db->query($sql)->row();		
		return $query;
    }
    public function getOrden($id)
	{ 
    	$sql="  SELECT
                    oc.`id`,
                    p.`id` id_pedido,
                    oc.`n`,
                    oc.`fecha`,
                    pro.`nombreproveedor`,
                    pro.`telefono`,
                    pro.`fax`,
                    oc.`atencion`,
                    pro.`direccion`,
                    oc.`referencia`,
                    oc.`condicion`,
                    oc.`formaEnvio`,
                    p.`formaPago`,
                    p.`diasCredito`,
                    oc.`glosa`,
                    CONCAT(
                        u.`first_name`,
                        ' ',
                        u.`last_name`
                        ) autor,
                    oc.`diasCredito` diasCreditoOC,
                    p.`flete`
                FROM
                    ordenescompra oc
                    INNER JOIN pedidos p
                    ON p.`id` = oc.`id_pedido`
                    INNER JOIN provedores pro
                    ON pro.`idproveedor` = p.`proveedor`
                    INNER JOIN users u
                    ON u.`id` = oc.`created_by`
                WHERE oc.`id` = '$id'";

        $query=$this->db->query($sql)->row();		
		return $query;
    }
    public function getPedidoItems($id)
	{ 
    	$sql="SELECT a.`idArticulos` id, a.`CodigoArticulo` codigo, a.`NumParte` numParte, a.`detalleLargo` descripFabrica, a.`Descripcion` descripcion,
        u.`Unidad` unidad, pit.`saldo`, pit.`rotacion`, pit.`precio`, pit.`cantidad`, pit.`precioFabrica`,
        (pit.`cantidad`* pit.`precioFabrica`) total, p.`flete`, tc.`tipocambio` tc
        FROM pedidos_items pit
        INNER JOIN pedidos p ON p.`id` = pit.`idPedido`
        INNER JOIN tipocambio tc ON tc.`fecha` = p.`fecha`
        INNER JOIN articulos a ON a.`idArticulos` = pit.`articulo`
        INNER JOIN unidad u ON a.`idUnidad` = u.`idUnidad`
        WHERE pit.`idPedido` = '$id'";

        $query=$this->db->query($sql)->result();		
		return $query;
    }
    public function getOrdenItems($id)
	{ 
    	$sql="SELECT a.`idArticulos` id, a.`CodigoArticulo` codigo, a.`NumParte` numParte, a.`detalleLargo` descripFabrica, a.`Descripcion` descripcion,
        u.`Unidad` unidad, pit.`saldo`, pit.`rotacion`, pit.`precio`, pit.`cantidad`, pit.`precioFabrica`,
        (pit.`cantidad`* pit.`precioFabrica`) total, ROUND(p.`flete`,2) flete
        FROM pedidos_items pit
        INNER JOIN pedidos p ON p.`id` = pit.`idPedido`
        INNER JOIN articulos a ON a.`idArticulos` = pit.`articulo`
        INNER JOIN unidad u ON a.`idUnidad` = u.`idUnidad`
        INNER JOIN ordenescompra oc ON oc.`id_pedido` = pit.`idPedido`
        WHERE oc.`id` = '$id'";

        $query=$this->db->query($sql)->result();		
		return $query;
    }
    public function getAprobadoPor($id)
	{ 
    	$sql="SELECT  pa.`id_pedido`, pa.`id_user`, CONCAT(upa.`first_name`, ' ',upa.`last_name`) aprobado_por, pa.`created_at`
        FROM pedidos_aprobado pa
        INNER JOIN users upa ON upa.`id` = pa.`id_user`
        INNER JOIN pedidos ppa	ON ppa.`id` = pa.`id_pedido`
        WHERE pa.`id_pedido` = '$id'";

        $query=$this->db->query($sql)->result_array();		
		return $query;
    }
    public function getAprobadoUser($id, $user)
    {
        $sql="SELECT *
        FROM pedidos_aprobado pa
        WHERE pa.`id_pedido` = '$id'
        AND pa.`id_user` = '$user' ";

        $query=$this->db->query($sql)->row();		
		return $query;
    }
    public function getNumMov($gestion)
    {
        $sql="SELECT p.`n`+1 AS numDoc
		FROM pedidos p
		WHERE YEAR(p.`fecha`) = $gestion
		ORDER BY p.`n` DESC LIMIT 1";
        
		$numDoc=$this->db->query($sql);
		
		return $numDoc->row() ? $numDoc->row()->numDoc : 1;
    }
    public function getPermisos($user)
	{ 
    	$sql="SELECT au.`subMenu` id_sub, sub.`subMenu`
        FROM ACCESO_USUARIO au
        INNER JOIN ACCESO_SUBMENU sub ON sub.`id` = au.`subMenu`
        INNER JOIN ACCESO_MENU menu ON menu.`id` = sub.`idMenu`
        WHERE au.`idUsuario` = $user
        ORDER BY au.`subMenu`";

        $query=$this->db->query($sql)->result_array();		
		return $query;
    }
    public function getNumMovOrden($gestion)
    {
        $sql="SELECT oc.`n`+1 AS numDoc
		FROM ordenescompra oc
		WHERE YEAR(oc.fecha) = '$gestion'
		ORDER BY oc.n DESC LIMIT 1";
        
		$numDoc=$this->db->query($sql);
		
		return $numDoc->row() ? $numDoc->row()->numDoc : 1;
    }
    public function storePago($pago)
	{		
		$this->db->trans_start();
			$this->db->insert("pago_prov", $pago);
            $id=$this->db->insert_id();
            if($id>0)
        	{
				$pagosProv = array();
				foreach ($pago->pagos as $fila) {
				$pagos=new stdclass();
				$pagos->id_pago_prov= $id;
				$pagos->id_fact_prov=$fila->id_fact_prov;
				$pagos->monto=$fila->monto;		
				array_push($pagosProv,$pagos);	
				}
			$this->db->insert_batch("fact_pago_prov", $pagosProv);	
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
            
            return $id;
        }	
    }
    public function storeFactServ($factServ)
	{		
		$this->db->trans_start();
			$this->db->insert("fact_serv", $factServ);
            $id=$this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
			return false;
			
        } else {
            
            return $id;
        }	
    }
    public function getEstadoCuentas($condicion,$signo)
	{ 
    	$sql="  SELECT  
                    oc.id idOC,
                    fp.id idFP,
                    pp.id,
                    tpp.id_fact_prov,
                    IF(oc.id = 0 , 'SERV' , CONCAT('HG-',oc.`n`,'/',DATE_FORMAT (oc.`fecha`, '%y'))) orden,
                    fp.glosa,
                    fp.transporte,
                    pro.`nombreproveedor`, 
                    IF(fp.`id`,fp.`tiempo_credito`,oc.`diasCredito`) credito,
                    IF(fp.`id`,fp.`n`,'PENDIENTE') nFactProv,
                    pp.fecha fechaPago,
                    IF(fp.`id`,fp.`fecha`,'PENDIENTE') fechaEmision,
                    IF(fp.`id`,fp.`monto`,pit.totalOrden) monto,
                    IF(fp.`id`,DATE_ADD(fp.`fecha`,INTERVAL fp.`tiempo_credito` DAY),'PENDIENTE') fechaVencimiento,
                    tpp.totalPago pagoCuenta,
                    (IFNULL(fp.`monto`,0) - IFNULL(tpp.totalPago,0)) saldo,
                    CASE
                        WHEN tpp.totalPago IS NULL THEN 'PENDIENTE'
                        WHEN (IFNULL(fp.`monto`,0) - IFNULL(tpp.totalPago,0)) <= 0 THEN 'PAGADA'
                        WHEN (IFNULL(fp.`monto`,0) - IFNULL(tpp.totalPago,0)) > 0 THEN 'PARCIAL'
                        ELSE ''
                    END estadoFac,
                    CASE
                        WHEN fp.id IS NULL THEN 'VIGENTE'
                        WHEN (CURDATE() < DATE_ADD(fp.`fecha`,INTERVAL fp.`tiempo_credito` DAY)) THEN 'VIGENTE'
                        ELSE 'VENCIDA'
                    END estadoOrden,
                    fp.url_pdf facturaComercialPdf,
                    pp.url_pdf pagoFacturaComercialPdf
                FROM ordenescompra oc
                    INNER JOIN pedidos p 
                        ON p.`id` = oc.`id_pedido`
                    LEFT JOIN (
                    SELECT pit.`idPedido`, SUM(ROUND(pit.`cantidad` * pit.`precioFabrica`,2)) totalOrden
                    FROM pedidos_items pit
                    GROUP BY pit.`idPedido`
                    )pit
                        ON pit.idPedido = p.`id`
                    
                    INNER JOIN provedores pro
                        ON pro.`idproveedor` = p.`proveedor`
                    LEFT JOIN fact_prov fp
                        ON fp.`id_orden` = oc.`id`
                    LEFT JOIN (
                    SELECT fpp.`id_fact_prov`, fpp.`id_pago_prov`, SUM(fpp.`monto`) totalPago
                    FROM fact_pago_prov fpp
                    GROUP BY fpp.`id_fact_prov`
                    )tpp
                        ON tpp.id_fact_prov = fp.`id`
                    LEFT JOIN pago_prov pp ON pp.id = tpp.id_pago_prov
                    WHERE oc.id $signo 0
                HAVING
                        CASE
                            WHEN '$condicion' = 'historico' THEN saldo>=0 OR saldo IS NULL
                            WHEN '$condicion' = 'pendiente' THEN saldo>=0 AND estadoFac <> 'PAGADA'
                            WHEN '$condicion' = 'pagada' THEN estadoFac='PAGADA'
                        END  
                
                ORDER BY  oc.id DESC";

        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getBackOrderList($filter, $ini, $fin)
	{ 
    	$sql="SELECT 
        pit.`id`,
        pit.`idPedido`,
        IF(oc.id = 0 , 'SERV' , CONCAT('HG-',oc.`n`,'/',DATE_FORMAT (oc.`fecha`, '%y'))) orden,
        pro.`nombreproveedor` proveedor,
        a.`CodigoArticulo` codigo,
        a.`Descripcion` descripcion,
        pit.cantidad,
        oc.`formaEnvio`,
        oc.`fecha` fechaOrden,
        p.`recepcion` estimada,
        pid.totalOrden,
        IF( pit.status = 1 , 'INGRESADO', pit.`estado`) estado,
        pit.`recepcion`, 
        pit.`embarque`,
        pit.`status`
        FROM pedidos_items pit
        INNER JOIN articulos a ON a.`idArticulos` = pit.`articulo`
        INNER JOIN pedidos p ON p.`id` = pit.`idPedido`
        INNER JOIN ordenescompra oc ON oc.`id_pedido` = p.`id`
        INNER JOIN provedores pro ON pro.`idproveedor` = p.`proveedor`
        LEFT JOIN (
                      SELECT pit.`idPedido`, SUM(ROUND(pit.`cantidad` * pit.`precioFabrica`,2)) totalOrden
                      FROM pedidos_items pit
                       GROUP BY pit.`idPedido`
                   )pid
          ON pid.idPedido = p.`id`
        WHERE p.`recepcion` BETWEEN '$ini' AND '$fin'
        HAVING
            CASE
                WHEN '$filter' = 'pendientes' THEN pit.`status` = 0
                WHEN '$filter' = 'ingresados' THEN pit.`status` = 1
                WHEN '$filter' = 'todos' THEN pit.status = 0 OR pit.`status` = 1
            END
          ";

        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function updateStatusPedido($idPedido, $status)
	{	
        $this->db->trans_start();

            $this->db->where('idPedido', $idPedido);
            $this->db->update('pedidos_items', $status);

            $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        } else {
            
            return true;
        }
    }
    public function updateStatusPedidoItem($id, $status)
	{	
        $this->db->trans_start();

            $this->db->where('id', $id);
            $this->db->update('pedidos_items', $status);

            $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        } else {
            
            return true;
        }
    }
    public function getFacturaTipoPago($alm, $ini, $fin)
	{ 
    	$sql="SELECT id, fechaFac, 
        -- pagada,
        IF(id IS NULL ,'' ,nFactura) nFactura,
        IF(id IS NULL ,'' ,cliente) cliente,
        -- IF(id IS NULL ,'' ,total) total, 
        SUM(porPagar) porPagar,
        SUM(efectivo) efectivo,
        SUM(transf) transf,
        SUM(cheque) cheque,
        SUM(otros) otros,
        IF(id IS NULL ,'' ,CONCAT('[',GROUP_CONCAT(DISTINCT '{','\"id\":',id,',', '\"n\":',nFactura,'}' ORDER BY nFactura ASC SEPARATOR ','),']')) AS facturaJson 
        FROM
        (
        SELECT 		f.`idFactura` id,f.`fechaFac`,  f.`nFactura`, f.`total`, f.pagada, c.`nombreCliente` cliente,
                (CASE WHEN f.`tipoPago`=0 THEN f.`total` ELSE 0 END ) AS porPagar,
                (CASE WHEN f.`tipoPago`=1 THEN f.`total` ELSE 0 END ) AS efectivo,
                (CASE WHEN f.`tipoPago`=2 THEN f.`total` ELSE 0 END ) AS transf,
                (CASE WHEN f.`tipoPago`=3 THEN f.`total` ELSE 0 END ) AS cheque,
                (CASE WHEN f.`tipoPago`=4 THEN f.`total` ELSE 0 END ) AS otros
        
        FROM factura f
        INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
        WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin'
        AND f.`almacen` = $alm
        GROUP BY f.`idFactura`
        ORDER BY f.`idFactura` DESC
        )tb
        GROUP BY fechaFac, id WITH ROLLUP 
        ";

        $query=$this->db->query($sql);	
		return $query->result_array();
    }
}
