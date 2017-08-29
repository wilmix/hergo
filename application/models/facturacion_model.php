<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Facturacion_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	
	public function guardar($obj)
	{		
		$sql=$this->db->insert("factura", $obj);
		if($sql)
		{
			return $this->db->insert_id();
		}
		else
		{
			return 0;
		}
	}
	public function obtenerRegistro()
	{
		$sql="SELECT * from factura ORDER BY idFactura desc Limit 1";
		$query=$this->db->query($sql);
        if($query->num_rows()>0)
        {
            $fila=$query->row();
            return ($fila);
        }
        else
        {
            return 1;
        }
	}
}
