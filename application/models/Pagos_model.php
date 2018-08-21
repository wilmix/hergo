<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pagos_model extends CI_Model  ////////////***** nombre del modelo 
{

	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function mostrarPagos($ini=null,$fin=null,$alm="") { 
		$sql="SELECT p.`idPago`, a.`almacen`, p.`numPago`, p.`fechaPago`, c.`nombreCliente`, p.`totalPago`, 
		p.`anulado`, CONCAT(u.`first_name`, ' ' , u.`last_name`) autor, p.`fecha`, f.`pagada`, m.`sigla`, tp.`tipoPago`
		FROM pago_factura pf
		INNER JOIN pago p ON p.`idPago`= pf.`idPago`
		INNER JOIN factura f ON f.`idFactura` = pf.`idFactura`
		INNER JOIN clientes c ON c.`idCliente` = p.`cliente`
		INNER JOIN users u ON u.`id`= p.`autor`
		INNER JOIN moneda m ON m.`id` = p.`moneda`
		INNER JOIN almacenes a ON a.`idalmacen` = p.`almacen`
		INNER JOIN tipoPago tp ON tp.`id`= p.`tipoPago`
		WHERE p.fechaPago
		BETWEEN '$ini' AND '$fin' AND p.almacen like '%$alm'
		AND f.`anulada` = 0
		GROUP BY p.`idPago`
		ORDER BY p.`idPago` DESC";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarPendientePago($ini=null,$fin=null,$alm="")
	{
		$sql="SELECT f.`idFactura`, f.almacen, f.nFactura, f.fechaFac, f.cliente, f.total, f.pagada,c.nombreCliente,
					f.`total` - IFNULL(SUM(ppa.monto),0) saldoPago,
					IFNULL(SUM(ppa.monto),0) totalPago
				FROM factura f
				LEFT JOIN pagosPendientesActivos ppa ON ppa.`idFactura` = f.`idFactura`
				LEFT JOIN clientes c ON c.idCliente = f.cliente
				WHERE 
				f.fechaFac BETWEEN '$ini' AND '$fin'
						AND f.almacen LIKE '%$alm'
						AND f.pagada <> 1
						AND  f.anulada<>1
				GROUP BY f.idFactura
				ORDER BY f.`idFactura` DESC";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornarEdicion($idPago)
	{
		$sql="SELECT *
		FROM pago p
		WHERE p.idPago = $idPago";
		//die($sql);
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornarDetallePago($idPago)
	{
		$sql="SELECT f.`lote`, f.`fechaFac`,f.`nFactura`, c.`nombreCliente`, pf.`monto`, f.`pagada`, 
		a.`almacen`, cp.`nombreCliente` nombre, p.`glosa`, p.`fechaPago`, p.`numPago`, tp.`tipoPago`, 
		b.`sigla` banco, p.`transferencia`, p.`cheque`, p.anulado
		FROM pago_factura pf
		INNER JOIN pago p ON p.`idPago`= pf.`idPago`
		INNER JOIN factura f ON f.`idFactura` = pf.`idFactura`
		INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
		INNER JOIN clientes cp ON cp.`idCliente` = p.`cliente`
		INNER JOIN users u ON u.`id`= p.`autor`
		INNER JOIN moneda m ON m.`id` = p.`moneda`
		INNER JOIN almacenes a ON a.`idalmacen` = p.`almacen`
		INNER JOIN tipoPago tp ON tp.`id` = p.`tipoPago`
		LEFT JOIN bancos b ON b.`id`=p.`banco`
		WHERE p.idPago = $idPago
		ORDER BY f.`nFactura`";
		
		$query=$this->db->query($sql)->result_array();		
		return $query;
	}
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function obtenerNumPago($almacen)
	{
		$gestion= date('Y');		

		$sql="SELECT MAX(p.numPago) numPago
		FROM pago p
		 WHERE p.almacen=$almacen
		 AND YEAR(p.fechaPago)='$gestion'";		
		$query=$this->db->query($sql);	
		if($query->num_rows()>0)
        {
            $numPago=$query->row();
            return ($numPago->numPago);
		}	
		else
		{
			return 0;
		}		
	}
	public function obtenerIdPago($numPago,$almacen)
	{
		$sql="SELECT idPago
		FROM pago p
		WHERE p.`numPago`=$numPago
		AND p.`almacen`=$almacen";		
		$query=$this->db->query($sql);	
		if($query->num_rows()>0)
        {
            $idPago=$query->row();
            return ($idPago->idPago);
		}	
		else
		{
			return 0;
		}		
	}
	
	public function guardarPago($obj)
	{		
		$sql=$this->db->insert("pago", $obj);
		return $sql;		
	}
	public function guardarPago_Factura($obj)
	{		
		$sql=$this->db->insert_batch("pago_factura", $obj);
		return $sql;		
	}
	public function anularPago($idPago)
	{
		$sql="UPDATE pago SET anulado=1 WHERE idPago=$idPago";
		$this->db->query($sql);
	}
	public function retornarIdFacturas($idPago)
	{
		$sql="SELECT pf.`idFactura`, f.`total` AS totalFactura
		FROM pago_factura pf
		inner join factura f on f.`idFactura`=pf.`idFactura`
		WHERE pf.`idPago` = $idPago";
		$query = $this->db->query($sql);
		return $query;
	}
	public function modificarPagadaFactura($pagada,$idFactura) {
		$sql="UPDATE factura f
		SET f.`pagada` = $pagada
		WHERE f.`idFactura`=$idFactura";
		$this->db->query($sql);
	}
	public function totalPago($idFactura) {
		$sql="SELECT ifnull(sum(ppa.`monto`),0) totalPago
		FROM pagosPendientesActivos ppa
		WHERE ppa.`idFactura`=$idFactura";
		$query = $this->db->query($sql);
		return $query;
	}
}
