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
	public function mostrarVentasIngresosHoy($ini)
	{ 
		$sql="SELECT 'ventas' info, f.`fechaFac` hoy , 
			IFNULL(ROUND ((SUM(fd.facturaPUnitario*fd.facturaCantidad)),2),0) lp , 
			IFNULL(ROUND ((SUM(fd3.facturaPUnitario*fd3.facturaCantidad)),2),0) pts,
			IFNULL(ROUND ((SUM(fd4.facturaPUnitario*fd4.facturaCantidad)),2),0) scz
				FROM factura AS f
				LEFT JOIN facturadetalle AS fd ON fd.idFactura=f.idFactura  AND f.almacen=1
				LEFT JOIN facturadetalle fd3 ON fd3.idFactura=f.idFactura AND f.almacen= 3
				LEFT JOIN facturadetalle fd4 ON fd4.idFactura=f.idFactura AND f.almacen= 4
				
				WHERE f.`fechaFac`= '$ini'
				AND anulada=0
				GROUP BY DAY(f.`fechaFac`)
		UNION ALL
		SELECT 'ingresos' info, i.`fechamov` hoy , 
			IFNULL(ROUND ((SUM(id.`cantidad`*id.`punitario`)),2),0) lp ,
			IFNULL(ROUND ((SUM(id3.`cantidad`*id3.`punitario`)),2),0) pts,
			IFNULL(ROUND ((SUM(id4.`cantidad`*id4.`punitario`)),2),0) scz
				FROM ingresos  i
				LEFT JOIN ingdetalle AS id ON id.`idIngreso` = i.`idIngresos`  AND i.`almacen` = 1
				LEFT JOIN ingdetalle AS id3 ON id3.`idIngreso` = i.`idIngresos`  AND i.`almacen` = 3
				LEFT JOIN ingdetalle AS id4 ON id4.`idIngreso` = i.`idIngresos`  AND i.`almacen` = 4
		WHERE i.`fechamov`= '$ini'
		AND i.`anulado`=0
		AND i.`tipomov`= 2 AND 16
		GROUP BY DAY(i.`fechamov`)
		";
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
	public function mostrarVentaDetalle($ini)
	{ 
		$sql="	SELECT 'notasEntrega' info, f.`fechaFac` hoy , 
				IFNULL(ROUND ((SUM(fd.facturaPUnitario*fd.facturaCantidad)),2),0) lp , 
				IFNULL(ROUND ((SUM(fd3.facturaPUnitario*fd3.facturaCantidad)),2),0) pts,
				IFNULL(ROUND ((SUM(fd4.facturaPUnitario*fd4.facturaCantidad)),2),0) scz
					FROM factura AS f
					LEFT JOIN facturadetalle AS fd ON fd.idFactura=f.idFactura  AND f.almacen=1
					LEFT JOIN facturadetalle fd3 ON fd3.idFactura=f.idFactura AND f.almacen= 3
					LEFT JOIN facturadetalle fd4 ON fd4.idFactura=f.idFactura AND f.almacen= 4
					INNER JOIN factura_egresos fe ON fe.`idFactura` = f.`idFactura` 
					INNER JOIN egresos e ON e.`idegresos` = fe.`idegresos`
					WHERE f.`fechaFac`= '$ini'
					AND f.`anulada`=0
					AND e.`tipomov`=7
					GROUP BY DAY(f.`fechaFac`)
		UNION ALL
		SELECT 'ventaCaja' info, f.`fechaFac` hoy , 
				IFNULL(ROUND ((SUM(fd.facturaPUnitario*fd.facturaCantidad)),2),0) lp , 
				IFNULL(ROUND ((SUM(fd3.facturaPUnitario*fd3.facturaCantidad)),2),0) pts,
				IFNULL(ROUND ((SUM(fd4.facturaPUnitario*fd4.facturaCantidad)),2),0) scz
					FROM factura AS f
					LEFT JOIN facturadetalle AS fd ON fd.idFactura=f.idFactura  AND f.almacen=1
					LEFT JOIN facturadetalle fd3 ON fd3.idFactura=f.idFactura AND f.almacen= 3
					LEFT JOIN facturadetalle fd4 ON fd4.idFactura=f.idFactura AND f.almacen= 4
					INNER JOIN factura_egresos fe ON fe.`idFactura` = f.`idFactura` 
					INNER JOIN egresos e ON e.`idegresos` = fe.`idegresos`
					WHERE f.`fechaFac`= '$ini'
					AND f.`anulada`=0
					AND e.`tipomov`=6
					GROUP BY DAY(f.`fechaFac`)
		UNION ALL
		SELECT 'cantidad' info, f.`fechaFac` hoy , 
				COUNT(fd.facturaPUnitario*fd.facturaCantidad) lp , 
				COUNT(fd3.facturaPUnitario*fd3.facturaCantidad) pts,
				COUNT(fd4.facturaPUnitario*fd4.facturaCantidad) scz
					FROM factura AS f
					LEFT JOIN facturadetalle AS fd ON fd.idFactura=f.idFactura  AND f.almacen=1
					LEFT JOIN facturadetalle fd3 ON fd3.idFactura=f.idFactura AND f.almacen= 3
					LEFT JOIN facturadetalle fd4 ON fd4.idFactura=f.idFactura AND f.almacen= 4
					WHERE f.`fechaFac`= '$ini'
					AND f.`anulada`=0
					GROUP BY DAY(f.`fechaFac`)";
		$query=$this->db->query($sql);		
		return $query;
	}
}
