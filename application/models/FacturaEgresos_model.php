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
		$sql="SELECT f.`idFactura`, f.`lote`, df.`manual`, f.`nFactura`, f.`fechaFac`, f.`ClienteNit`, f.`ClienteFactura`,  t.`sigla`, 
		f.`total`, CONCAT(u.first_name,' ', u.last_name) AS vendedor, f.`anulada`, f.fecha,
		GROUP_CONCAT(DISTINCT e.nmov ORDER BY e.nmov ASC SEPARATOR ' - ') AS movimientos, f.glosa,
		f.`pagada`, f.almacen idAlmacen,
		CASE
			WHEN f.moneda = 1 THEN 'BOB'
			WHEN f.moneda = 2 THEN CONCAT('$','U$')
		END moneda,
		CASE
			WHEN f.`anulada` = 1 THEN 'ANULADA'
			WHEN f.`pagada` = 0 THEN 'NO PAGADA'
			WHEN f.`pagada` = 1 THEN 'PAGADA'
			WHEN f.`pagada` = 2 THEN 'PAGO PARCIAL'
		END pagadaF, CONCAT(ua.first_name,' ', ua.last_name) emisor
		FROM factura_egresos fe 
		INNER JOIN egresos e on e.idegresos=fe.idegresos
		INNER JOIN factura f on f.idFactura=fe.idFactura
		INNER JOIN tmovimiento t on e.tipomov=t.id
		INNER JOIN datosfactura df on df.idDatosFactura = f.lote
		INNER JOIN users u on u.id = e.vendedor
		INNER JOIN users ua ON ua.id = f.autor
		WHERE f.fechaFac
		BETWEEN '$ini' AND '$fin'";        
        if($alm>0)         
            $sql.=" and f.`almacen`=$alm";    
        if($tipo>0)         
            $sql.=" and e.tipomov=$tipo";            
       
            $sql.="
             GROUP BY fe.idFactura
            ORDER BY f.idFactura  DESC
            ";
       // die($sql);
       
		$query=$this->db->query($sql);
        
        return ($query->result_array());
        
        
	}
	public function showFacturasManuales($alm,$lote)
	{
		$sql="SELECT f.`idFactura`, f.`lote`, f.`nFactura`, f.`fechaFac`, f.`ClienteFactura`, f.`ClienteNit`,  f.`moneda`, f.`pagada`, f.`total`, f.`autor`,f.`fecha`,
		f.`almacen`, df.`fechaLimite`, df.`desde`, df.`hasta`
		FROM factura f
		INNER JOIN datosfactura df ON df.`idDatosFactura` = f.`lote`
		WHERE df.`manual` = 1
		AND f.`almacen` = $alm
		AND df.`idDatosFactura` = $lote
		ORDER BY f.`nFactura` DESC";
       
		$query=$this->db->query($sql);
        
        return ($query->result_array());
	}
	public function showLotes()
	{
		$sql="SELECT df.`idDatosFactura` idLote
		FROM datosfactura df
		WHERE df.`manual` = 1
		ORDER BY df.`idDatosFactura` DESC";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function obtenerEgresosPorFactura($idFactura)
	{
		$sql="SELECT *
            FROM factura_egresos
            WHERE idFactura=$idFactura
            GROUP BY idegresos
            ";
        $query=$this->db->query($sql);
        if($query->num_rows()>0)
        {                   
            $res=$query->result();
            return ($res); 
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
	public function updateFacturaManual($id, $data)
    {
		$this->db->where('idFactura', $id);
		$this->db->update('factura', $data);
    }
}
