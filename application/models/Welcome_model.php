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

		$query=$this->db->query($sql)->result();		
		return $query;
	}
}