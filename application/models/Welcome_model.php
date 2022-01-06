<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Welcome_model extends CI_Model  
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
    public function kardexByCode($alm,$mon,$ini,$fin) 
	{ 
		$sql="CALL kardexByCode('$alm','$mon','$ini','$fin');";
		mysqli_next_result( $this->db->conn_id );
		$query=$this->db->query($sql);	
		$res = $query->result();
		$query->next_result(); 
		$query->free_result(); 
		return $res;
	}
	public function update_date($id, $data)
	{
		$this->db->where('idIngresos', $id);
		$this->db->update('ingresos', $data);
	}
}