<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Dashboard_model extends CI_Model
{

	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		$this->db->query("SET lc_time_names = 'es_BO'");
    }
    public function mostrarVentasGestion($interval)
	{ 
		$sql="SELECT
				CONCAT(
					UPPER(LEFT(MONTHNAME(f.fechaFac), 3)),
					YEAR(f.fechaFac)
				) AS id,
				UPPER(LEFT(MONTHNAME(f.fechaFac), 3)) AS mes,
				YEAR(f.fechaFac) AS gestion,
				SUM(
					CASE
						WHEN f.almacen = '3' THEN total
						ELSE 0
					END
				) AS montoPTS,
				SUM(
					CASE
						WHEN f.almacen = '4' THEN total
						ELSE 0
					END
				) AS montoSCZ,
				SUM(
					CASE
						WHEN f.almacen = '1'
						OR f.almacen = 9 THEN total
						ELSE 0
					END
				) AS montoLP,
				SUM(total) AS totalMes
			FROM
				factura f
				INNER JOIN almacenes a ON a.idalmacen = f.almacen
			WHERE
    			f.anulada = 0
    			AND DATE_FORMAT(f.fechaFac, '%Y-%m') BETWEEN 
        		DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL $interval MONTH), '%Y-%m') AND 
        		DATE_FORMAT(DATE_SUB(LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), INTERVAL 1 DAY), '%Y-%m')
			GROUP BY
				id,
				mes,
				gestion;";
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
}
