<?php
class Excel_model extends CI_Model 
{
	function saverecord($data)
	{
        $this->db->insert('extractos', $data);
	}
	function saveBatch($data)
	{
		$this->db->trans_start();
        	$this->db->insert_batch('extractos', $data);
		$this->db->trans_complete();
	}
	
}