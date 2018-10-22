<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MarcaArticulo_model extends CI_Model
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
	public function agregarMarca_model($mar,$sig)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="INSERT INTO marca(Marca, Sigla) VALUES('$mar','$sig')";
		$query=$this->db->query($sql);		
	}
	public function editarMarca_model($mar,$sig,$cod)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="UPDATE marca SET Marca='$mar', Sigla='$sig'  WHERE idMarca=$cod";
		$query=$this->db->query($sql);		
	}
}
