<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Unidad_model extends CI_Model
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
	public function agregarUnidad_model($uni,$sig)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="INSERT INTO Unidad(Unidad, Sigla) VALUES('$uni','$sig')";
		$query=$this->db->query($sql);		
	}
	public function editarUnidad_model($uni,$sig,$cod)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="UPDATE Unidad SET Unidad='$uni', Sigla='$sig'  WHERE idUnidad=$cod";
		$query=$this->db->query($sql);		
	}
}
