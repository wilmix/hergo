<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Dashboard_model extends CI_Model
{

	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		$this->db->query("SET lc_time_names = 'es_BO'");
    }
    public function mostrarVentasGestion($ini=null,$fin=null)
	{ 
		$sql="SELECT tbl.id, tbl.mes, tbl.gestion
		, 
		(ventasPotosi.montoPTS) montoPTS,
		(ventasSantaCruz.montoSCZ) montoSCZ,
		(ventasLaPaz.montoLP) montoLP,
		((ventasPotosi.montoPTS) + (ventasSantaCruz.montoSCZ) + (ventasLaPaz.montoLP))totalMes
		FROM
		(
			SELECT 
				CONCAT((LEFT(UPPER(DATE_FORMAT(f.`fechaFac`,'%M')),3)),(YEAR(f.`fechaFac`))) id, 
				LEFT(UPPER(DATE_FORMAT(f.`fechaFac`,'%M')),3) mes,
				YEAR(f.`fechaFac`) gestion
			FROM factura f
			WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin' 
			GROUP BY id
			ORDER BY f.`fechaFac`
		)tbl					
			LEFT JOIN (
					SELECT CONCAT((LEFT(UPPER(DATE_FORMAT(f.`fechaFac`,'%M')),3)),(YEAR(f.`fechaFac`))) id, LEFT(UPPER(DATE_FORMAT(f.`fechaFac`,'%M')),3) mes,YEAR(f.`fechaFac`) gestion, ROUND(SUM(f.`total`),2) montoPTS
					FROM factura f
					WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin'
					AND f.`anulada`=0
					AND f.`almacen` = 3
					GROUP BY id
					ORDER BY f.`fechaFac`
			) ventasPotosi ON ventasPotosi.id = tbl.id
			LEFT JOIN (
					SELECT CONCAT((LEFT(UPPER(DATE_FORMAT(f.`fechaFac`,'%M')),3)),(YEAR(f.`fechaFac`))) id, LEFT(UPPER(DATE_FORMAT(f.`fechaFac`,'%M')),3) mes,YEAR(f.`fechaFac`) gestion, ROUND(SUM(f.`total`),2) montoSCZ
					FROM factura f
					WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin'
					AND f.`anulada`=0
					AND f.`almacen` = 4
					GROUP BY id
								ORDER BY f.`fechaFac`
			) ventasSantaCruz ON ventasSantaCruz.id = tbl.id
			LEFT JOIN (
					SELECT CONCAT((LEFT(UPPER(DATE_FORMAT(f.`fechaFac`,'%M')),3)),(YEAR(f.`fechaFac`))) id, LEFT(UPPER(DATE_FORMAT(f.`fechaFac`,'%M')),3) mes,YEAR(f.`fechaFac`) gestion, ROUND(SUM(f.`total`),2) montoLP
					FROM factura f
					WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin'
					AND f.`anulada`=0
					AND f.`almacen` = 1
					GROUP BY id
					ORDER BY f.`fechaFac`
			) ventasLaPaz ON ventasLaPaz.id = tbl.id";
		$query=$this->db->query($sql);		
		return $query;
	}

	public function mostrarVentasHoy($ini)
	{ 
		$sql="SELECT SUM(total) ventasHoy, sum(facturaCantidad) cantidadHoy
		FROM (
		SELECT f.`idFactura`, f.`nFactura`, f.`fechaFac`, f.`total`, fd.`facturaCantidad`
		FROM factura f
		inner join facturadetalle fd on fd.`idFactura` = f.`idFactura`
		WHERE f.`fechaFac`BETWEEN '$ini' AND '$ini' + INTERVAL 1 DAY
		AND f.`anulada` = 0
		AND f.`almacen` = 1
		GROUP BY f.`idFactura`) AS tb
		";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarIngresosHoy($ini)
	{ 
		$sql="SELECT 'ingresos' info, i.`fechamov` hoy , 
			IFNULL(ROUND ((SUM(id.`cantidad`*id.`punitario`)),2),0) lp ,
			IFNULL(ROUND ((SUM(id3.`cantidad`*id3.`punitario`)),2),0) pts,
			IFNULL(ROUND ((SUM(id4.`cantidad`*id4.`punitario`)),2),0) scz
				FROM ingresos  i
				LEFT JOIN ingdetalle AS id ON id.`idIngreso` = i.`idIngresos`  AND i.`almacen` = 1
				LEFT JOIN ingdetalle AS id3 ON id3.`idIngreso` = i.`idIngresos`  AND i.`almacen` = 3
				LEFT JOIN ingdetalle AS id4 ON id4.`idIngreso` = i.`idIngresos`  AND i.`almacen` = 4
		WHERE i.`fecha` = '$ini' -- BETWEEN '$ini' AND '$ini' + INTERVAL 1 DAY
		AND i.`anulado`=0
		AND i.`tipomov`= 2 AND 16
		GROUP BY DAY(i.`fechamov`)";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarInfo()
	{ 
		$sql="	SELECT 'lp-NEG' alm, COUNT(a.`laPaz`) cant
				FROM `articulos_activos` a
				WHERE a.`laPaz` < -0.001
				AND SUBSTRING(CodigoArticulo,1,2)<>'SR'
				
				UNION ALL 
				SELECT 'pts-NEG', COUNT(a.`potosi`)
				FROM `articulos_activos` a
				WHERE a.`potosi` < -0.001
				AND SUBSTRING(CodigoArticulo,1,2)<>'SR'
				UNION ALL 
				SELECT 'scz-NEG', COUNT(a.`santacruz`)
				FROM `articulos_activos` a
				WHERE a.`santacruz` < -0.001
				AND SUBSTRING(CodigoArticulo,1,2)<>'SR'
				
				UNION ALL
				SELECT 'lp-ACT' , COUNT(a.`laPaz`) 
				FROM `articulos_activos` a
				WHERE a.`laPaz` <> '-'
				UNION ALL
				SELECT 'pts-ACT', COUNT(a.`potosi`)
				FROM `articulos_activos` a
				WHERE a.`potosi` <> '-'
				UNION ALL
				SELECT 'scz-ACT', COUNT(a.`santacruz`)
				FROM `articulos_activos` a
				WHERE a.`santacruz` <> '-'";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function notaEntregaHoy($ini) { 
		$sql="SELECT SUM(total) notaEntrega
		FROM (
		SELECT f.`idFactura`, f.`nFactura`, f.`fechaFac`, e.`tipomov`, f.`total`
		FROM factura_egresos fe
		INNER JOIN factura f ON f.`idFactura` = fe.`idFactura`
		INNER JOIN egresos e ON e.`idegresos` =fe.`idegresos`
		WHERE f.`fechaFac`BETWEEN '$ini' AND '$ini' + INTERVAL 1 DAY
		AND f.`anulada` = 0
		AND e.`tipomov` = 7
		AND f.`almacen` = 1
		GROUP BY f.`idFactura`) AS tb";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function ventaCajaHoy($ini) { 
		$sql="SELECT SUM(total) ventaCaja
		FROM (
		SELECT f.`idFactura`, f.`nFactura`, f.`fechaFac`, e.`tipomov`, f.`total`
		FROM factura_egresos fe
		INNER JOIN factura f ON f.`idFactura` = fe.`idFactura`
		INNER JOIN egresos e ON e.`idegresos` =fe.`idegresos`
		WHERE f.`fechaFac`BETWEEN '$ini' AND '$ini' + INTERVAL 1 DAY
		AND f.`anulada` = 0
		AND e.`tipomov` = 6
		AND f.`almacen` = 1
		GROUP BY f.`idFactura`) AS tb";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function negatives($alm,$ges) 
	{ 
		$sql="CALL negatives_sp('$alm','$ges')";
		$query=$this->db->query($sql);	
		return $query;
	}
	public function notasPendientes($tipo, $autor)
	{
		$sql = "SELECT
					e.`cliente`,
					e.nmov n,
					e.idEgresos,
					t.sigla,
					e.fechamov,
					c.nombreCliente,
					ROUND(
						(SUM(d.`total`)) - (SUM(d.`cantFact` * d.`punitario`)),
						2
					) total,
					e.estado,
					e.fecha,
					CONCAT(u.first_name, ' ', u.last_name) autor,
					e.obs,
					a.almacen,
					m.sigla monedasigla,
					ROUND(
						ROUND(
							(SUM(d.`total`)) - (SUM(d.`cantFact` * d.`punitario`)),
							2
						) / tc.`tipocambio`,
						2
					) totalDol
				FROM
					egresos e
					INNER JOIN egredetalle d ON e.idegresos = d.idegreso
					INNER JOIN tmovimiento t ON e.tipomov = t.id
					INNER JOIN clientes c ON e.cliente = c.idCliente
					INNER JOIN users u ON u.id = e.autor
					INNER JOIN almacenes a ON a.idalmacen = e.almacen
					INNER JOIN moneda m ON e.moneda = m.id
					INNER JOIN tipocambio tc ON e.`fechamov` = tc.`fecha`
				WHERE
					e.`estado` <> 1
					AND t.id = 7
					AND e.anulado = 0
					AND e.fechamov > '2010-01-01'
					AND e.autor = $autor
				GROUP BY
					e.idegresos
				";
		$query=$this->db->query($sql);	
		return $query;
	}
}
