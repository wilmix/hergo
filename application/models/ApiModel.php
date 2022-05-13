<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class ApiModel extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
	}
	public function showMenuItems()
	{
		$sql="  SELECT
                    'n1' nivel,
                    n1.id id,
                    n1.name menu,
                    '' parent 
                FROM
                    web_nivel1 n1
                UNION ALL
                SELECT
                    'n2' nivel,
                    n2.id id,
                    n2.name menu,
                    n2.id_nivel1 parent 
                FROM
                    web_nivel2 n2
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
	}
    public function prueba()
	{
		$sql=   "SELECT
                    n1.id n1_id,
                    n1.name n1,
                    n2.id n2_id,
                    n2.id_nivel1,
                    n2.name n2,
                    n3.id n3_id,
                    n3.name n3
                FROM
                    web_nivel1 n1
                LEFT JOIN web_nivel2 n2 ON n2.id_nivel1 = n1.id
                LEFT JOIN web_nivel3 n3 ON n3.id_nivel2 = n2.id 
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
	}
    public function nivel_1()
	{
		$sql=   "SELECT
                    n1.id,
                    n1.`name`,
                    n1.url
                FROM
                    web_nivel1 n1
                WHERE n1.is_active = 1
                ";
		$query=$this->db->query($sql);		
		return $query->result();
	}
    public function nivel_2($id_n1)
	{
		$sql=   "SELECT
                    n2.id,
                    n2.`name`,
                    n2.url
                FROM
                    web_nivel2 n2
                WHERE 
                    n2.id_nivel1 = $id_n1
                    AND n2.is_active = 1
                ";
		$query=$this->db->query($sql);		
		return $query->result();
	}
    public function nivel_3($id_n2)
	{
		$sql=   "SELECT
                    *
                FROM
                    web_nivel3 n3
                WHERE
                    n3.id_nivel2 = $id_n2
                    AND n3.is_active = 1
                ";
		$query=$this->db->query($sql);		
		return $query->result();
	}
    public function lineProducts()
	{
		$sql=   "SELECT
                    n.`name`,
                    n.description,
                    n.img,
                    n.url
                FROM
                    web_nivel1 n
                WHERE
                    n.is_active = 1
                    AND n.is_service = 0
                ";
		$query=$this->db->query($sql);		
		return $query->result();
	}
    public function services()
	{
		$sql=   "SELECT
                    n.`name`,
                    n.description,
                    n.img,
                    n.url
                FROM
                    web_nivel1 n
                WHERE
                    n.is_active = 1
                    AND n.is_service = 1
                ";
		$query=$this->db->query($sql);		
		return $query->result();
	}
}
