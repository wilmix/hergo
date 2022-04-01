<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class ArticulosWeb_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		//date_default_timezone_set("America/La_Paz");
	}
	public function show($table)
	{
		$sql="  SELECT * , '$table' level
                FROM $table
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
	}
	public function store($item, $table)
	{
        if ($item->id > 0) {
            $this->db->where('id', $item->id);
            $this->db->update($table, $item);
            return $item->id;
        } else if ($item->id == 0)  {
            $this->db->insert($table, $item);
            $id=$this->db->insert_id();
            return $id;
        }
	}

}
