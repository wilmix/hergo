<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pagos_model extends CI_Model  ////////////***** nombre del modelo 
{

	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function mostrarPagos($ini=null,$fin=null,$alm="") ///********* nombre de la funcion mostrar
	{ //cambiar la consulta
		$sql=" SELECT pagos.idPagos, almacenes.almacen, pagos.numPago, pagos.fechaPago, moneda.sigla, clientes.nombreCliente,factura.idFactura, factura.nFactura, factura.total AS totalFactura, pagos.montoPago,pagos.saldoPago, pagos.glosaPago,pagos.estadoPago, factura.pagada, CONCAT(users.first_name,' ',users.last_name) AS autor, pagos.fecha
			FROM factura_pagos
			RIGHT JOIN factura
			ON factura.idFactura=factura_pagos.idFactura
			RIGHT JOIN pagos
			ON pagos.idPagos=factura_pagos.idPagos
			INNER JOIN almacenes 
			ON almacenes.idalmacen=pagos.almacen
			INNER JOIN moneda 
			ON moneda.id = pagos.monedaPago
			INNER JOIN clientes 
			ON clientes.idCliente= pagos.cliente
			INNER JOIN users 
			ON users.id= pagos.autor
			WHERE pagos.fechaPago
			BETWEEN '$ini' AND '$fin' AND pagos.almacen like '%$alm'
			ORDER BY pagos.idPagos desc";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarPendientePago($ini=null,$fin=null,$alm="")
	{
		$sql="SELECT f.idFactura, f.almacen, f.nFactura, f.fechaFac, f.cliente,c.nombreCliente, f.total,  IFNULL(sum(p.montoPago),0) pagado,  f.total - IFNULL(sum(p.montoPago),0) saldoPago, GROUP_CONCAT(p.glosaPago SEPARATOR ', ') glosaPago , f.pagada
		FROM factura f		
		LEFT JOIN pagos p
		ON  f.idFactura= p.idFactura
		INNER JOIN clientes c
		ON c.idCliente = f.cliente 
		WHERE f.fechaFac
		BETWEEN '$ini' AND '$fin' AND not f.pagada=1 AND f.almacen like '%$alm' AND NOT f.anulada=1
    	GROUP BY f.idFactura
		ORDER BY f.nFactura desc";
		//die($sql);
		$query=$this->db->query($sql);		
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
				FROM pagos p
  				WHERE p.almacen=$almacen
  				AND year(p.fechaPago)='$gestion'";		
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
	public function guardarPago($obj)
	{		
		$sql=$this->db->insert_batch("pagos", $obj);
		return $sql;		
	}


}
