<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Cuis_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
    public function store($cuis)
	{
            $this->db->insert('siat_cuis', $cuis);
            /* $id=$this->db->insert_id();
            return $id; */
	}


}
