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
		$sql=" SELECT pagos.idPagos, almacenes.almacen, pagos.numPago, pagos.fechaPago, moneda.sigla, clientes.nombreCliente, pagos.montoPago,pagos.saldoPago, pagos.glosaPago, pagos.anulado, CONCAT(users.first_name,' ',users.last_name) AS autor, pagos.fecha
			FROM pagos 
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
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";
		
		$query=$this->db->query($sql);		
		return $query;
	}


}
