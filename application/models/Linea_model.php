<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Linea_model extends CI_Model
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
	public function agregarLinea_model($lin,$sig)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="INSERT INTO linea(Linea, Sigla) VALUES('$lin','$sig')";
		$query=$this->db->query($sql);		
	}
	public function editarLinea_model($lin,$sig,$cod)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="UPDATE linea SET Linea='$lin', Sigla='$sig'  WHERE idLinea=$cod";
		$query=$this->db->query($sql);		
	}
}
