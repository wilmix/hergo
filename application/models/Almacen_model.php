<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Almacen_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function agregarAlmacen_model($alm,$dir,$ciu,$telefonos,$enu,$sucursal)
	{
		
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="INSERT INTO almacenes (almacen, direccion, ciudad, Telefonos, uso, autor, fecha, sucursal) 
				   			  VALUES('$alm','$dir','$ciu','$telefonos','$enu','$autor','$fecha','$sucursal')";
		$query=$this->db->query($sql);		
	}
	public function editarAlmacen_model($alm,$dir,$ciu,$telefonos,$enu,$sucursal,$cod)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="UPDATE almacenes SET 
					almacen='$alm', 
					direccion='$dir', 
					ciudad='$ciu', 
					uso=$enu, 
					autor='$autor',
					fecha='$fecha',
					Telefonos = '$telefonos',
					sucursal = '$sucursal'
		WHERE idalmacen=$cod";
		$query=$this->db->query($sql);		
	}
	public function siatSucursales()
	{
		$sql = 'SELECT
					a.idalmacen id,
					CONCAT(a.sucursal, " | ", a.almacen, " | PV:" ,sc.codigoPuntoVenta, " | CUIS:", sc.cuis) label,
					a.almacen,
					a.siat_sucursal,
					a.sucursal,
					sc.cuis,
					IF(sc.active, "ACTIVO", "CADUCO") status,
					sc.codigoPuntoVenta,
					sc.fechaVigencia
				FROM
					almacenes a
					INNER JOIN siat_cuis sc ON sc.sucursal = a.siat_sucursal
				WHERE
					a.siat_sucursal IS NOT NULL
					AND sc.active = 1
					';

		$query = $this->db->query($sql);
		return $query;
	}
}
