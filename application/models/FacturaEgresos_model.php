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
	
	public function Listar($ini,$fin,$alm=0,$tipo=0)
	{
		$sql="
		SELECT *, GROUP_CONCAT(DISTINCT e.nmov
					ORDER BY e.nmov ASC
					SEPARATOR ' - ') as movimientos
		FROM factura_egresos fe 
		INNER JOIN egresos e on e.idegresos=fe.idegresos
		INNER JOIN factura f on f.idFactura=fe.idFactura
		INNER JOIN tmovimiento t on e.tipomov=t.id
		WHERE f.fechaFac
		BETWEEN '$ini' AND '$fin'";        
        if($alm>0)         
            $sql.=" and e.almacen=$alm";    
        if($tipo>0)         
            $sql.=" and e.tipomov=$tipo";            
       
            $sql.="
             GROUP BY fe.idFactura
            ORDER BY f.idFactura DESC
            ";
       // die($sql);
       
		$query=$this->db->query($sql);
        
        return ($query->result_array());
        
        
	}
	public function obtenerPorFactura($idFactura)
	{
		$sql="SELECT *
            FROM factura_egresos
            WHERE idFactura=$idFactura
            limit 1 
            ";
        $query=$this->db->query($sql);
        if($query->num_rows()>0)
        {                   
             $fila=$query->row();
            return ($fila); 
        }
        else
        {
            return false;
        }         
	}
	public function actualizarFparcial_noFacturado($idFactura,$idegresos)
    {
        $sql="SELECT fe.id
		  FROM factura_egresos fe
		  INNER JOIN factura f
		  ON fe.idFactura=f.idFactura and fe.idegresos=$idegresos
		  WHERE f.anulada=0";		  
 		//die($sql);
        $query=$this->db->query($sql);
        if($query->num_rows()>0)
        {               
        	//factura parcial en egresos
        	$this->parcial_NoFacturado($idFactura,$idegresos,2);            
        }
        else
        {
        	//no facturado en egresos
            $this->parcial_NoFacturado($idFactura,$idegresos,0);
        }
    }
    private function parcial_NoFacturado($idFactura,$idegresos, $estado)
    {
    	//estado = 0 no facturado
    	//estado = 2 facturado Parcial
    	$sql="UPDATE egresos set estado=$estado WHERE idegresos=$idegresos";
        $query=$this->db->query($sql);
    }
}
