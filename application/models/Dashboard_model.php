<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Dashboard_model extends CI_Model
{

	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		$this->db->query("SET lc_time_names = 'es_BO'");
    }
    public function mostrarVentasGestion($ini=null,$fin=null) ///********* nombre de la funcion mostrar
	{ 
		$sql="SELECT LEFT(UPPER(DATE_FORMAT(f.`fechaFac`,'%M')),3) mes,YEAR(f.`fechaFac`) gestion, 
						IFNULL(ROUND ((SUM(fd.facturaPUnitario*fd.facturaCantidad)),2),0) lp , 
						IFNULL(ROUND ((SUM(fd3.facturaPUnitario*fd3.facturaCantidad)),2),0) pts,
						IFNULL(ROUND ((SUM(fd4.facturaPUnitario*fd4.facturaCantidad)),2),0) scz
			FROM factura AS f
			LEFT JOIN facturadetalle AS fd ON fd.idFactura=f.idFactura  AND f.almacen=1
			LEFT JOIN facturadetalle fd3 ON fd3.idFactura=f.idFactura AND f.almacen= 3
			LEFT JOIN facturadetalle fd4 ON fd4.idFactura=f.idFactura AND f.almacen= 4
			WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin' 
			AND anulada=0
			GROUP BY MONTH(f.`fechaFac`)
			ORDER BY f.`fechaFac`";
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
		WHERE i.`fecha`BETWEEN '$ini' AND '$ini' + INTERVAL 1 DAY
		AND i.`anulado`=0
		AND i.`tipomov`= 2 AND 16
		GROUP BY DAY(i.`fechamov`)";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarInfo()
	{ 
		$sql="	SELECT 'lp-NEG' alm, COUNT(a.`laPaz`) cant
				FROM hergo2.`articulos_activos` a
				WHERE a.`laPaz` < 0
				
				UNION ALL 
				SELECT 'pts-NEG', COUNT(a.`potosi`)
				FROM hergo2.`articulos_activos` a
				WHERE a.`potosi` < 0
				UNION ALL 
				SELECT 'scz-NEG', COUNT(a.`santacruz`)
				FROM hergo2.`articulos_activos` a
				WHERE a.`santacruz` < 0
				
				UNION ALL
				SELECT 'lp-ACT' , COUNT(a.`laPaz`) 
				FROM hergo2.`articulos_activos` a
				WHERE a.`laPaz` <> '-'
				UNION ALL
				SELECT 'pts-ACT', COUNT(a.`potosi`)
				FROM hergo2.`articulos_activos` a
				WHERE a.`potosi` <> '-'
				UNION ALL
				SELECT 'scz-ACT', COUNT(a.`santacruz`)
				FROM hergo2.`articulos_activos` a
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
}
