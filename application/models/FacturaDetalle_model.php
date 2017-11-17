<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class FacturaDetalle_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	
	public function guardar($obj)
	{		
		$sql=$this->db->insert_batch("facturadetalle", $obj);
		return $sql;
	}
	
}
