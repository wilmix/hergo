<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class FacturaEgresos_model extends CI_Model
{

	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	
	public function guardar($obj)
	{		
		$sql=$this->db->insert("factura_egresos", $obj);
		if($sql)
		{
			return $this->db->insert_id();
		}
		else
		{
			return 0;
		}
	}
	public function guardarArray($obj)
	{		
		$sql=$this->db->insert_batch("factura_egresos", $obj);
		return $sql;
	}
	
	public function Listar($ini,$fin,$alm)
	{
		$sql="SELECT * from factura e
		WHERE e.fechaFac
		BETWEEN '$ini' AND '$fin'";        
        if($alm>0)         
            $sql.=" and e.almacen=$alm";                
       
            $sql.="
            ORDER BY e.idFactura DESC";
       
		$query=$this->db->query($sql);
        
        return ($query->result_array());
        
        
	}

}
