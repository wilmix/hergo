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
	public function Listar($ini,$fin,$alm)
	{
		$sql="SELECT * from factura e
		WHERE e.fechaFac
		BETWEEN '$ini' AND '$fin'";        
        if($alm>0)         
            $sql.=" and e.almacen=$alm";                
       
            $sql.="
            ORDER BY e.idFactura DESC           
            ";
       
		$query=$this->db->query($sql);
        
        return ($query->result_array());
               
	}
	public function obtenerFactura($idFactura)
	{
		$sql="SELECT f.*,t.tipocambio cambiovalor from factura f
		INNER JOIN tipocambio t 
		ON f.tipoCambio=t.id
		Where f.idFactura=$idFactura";
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
	public function obtenerDetalleFactura($idFactura)
	{
		$sql=" 
		SELECT f.*, u.Sigla from facturadetalle f 
		INNER JOIN articulos a
		ON a.idArticulos=f.articulo
		INNER JOIN unidad u
		ON u.idUnidad=a.idUnidad
		Where idFactura=$idFactura";
		$query=$this->db->query($sql);        
        return ($query->result_array());
	}
	public function anularFactura($idFactura)
	{
		$sql="UPDATE factura set anulada=1 where idFactura=$idFactura";
       
		$query=$this->db->query($sql);
        
        return $query;
	}
}
