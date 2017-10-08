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
		$sql="SELECT factura.almacen, factura.nFactura, factura.fechaFac, clientes.nombreCliente, factura.total, pagos.saldoPago, pagos.glosaPago, factura.pagada
		FROM factura
		LEFT JOIN factura_pagos
		ON factura.idFactura = factura_pagos.idFactura
		LEFT JOIN pagos
		ON factura_pagos.idPagos= pagos.idPagos
		INNER JOIN clientes
		ON clientes.idCliente = factura.cliente 
		WHERE factura.fechaFac
		BETWEEN '$ini' AND '$fin' AND not factura.pagada=1 AND factura.almacen like '%$alm' AND NOT factura.anulada=1
		ORDER BY factura.nFactura desc";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";
		
		$query=$this->db->query($sql);		
		return $query;
	}


}
