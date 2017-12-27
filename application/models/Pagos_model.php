<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pagos_model extends CI_Model  ////////////***** nombre del modelo 
{

	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function mostrarPagos($ini=null,$fin=null,$alm="") ///********* nombre de la funcion mostrar
	{ //cambiar la consulta
		$sql="SELECT p.idPagos, a.almacen, p.numPago,p.fechaPago,m.sigla,c.nombreCliente, GROUP_CONCAT(f.nFactura SEPARATOR ' - ') nFactura, SUM(p.montoPago) montoPago, p.glosaPago,  CONCAT(u.first_name,' ', u.last_name) autor, p.fecha, p.anulado
			FROM pagos p
			INNER JOIN almacenes a
			ON a.idalmacen=p.almacen
			INNER JOIN moneda m
			ON m.id=p.monedaPago
			INNER JOIN clientes c
			ON c.idCliente=p.cliente	
			INNER JOIN factura f
  			ON f.idFactura=p.idFactura		
			INNER JOIN users u
  			ON u.id=p.autor
			WHERE p.fechaPago
			BETWEEN '$ini' AND '$fin' AND p.almacen like '%$alm'
			GROUP BY p.numPago
			ORDER BY p.idPagos desc";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarPendientePago($ini=null,$fin=null,$alm="")
	{
		$sql="SELECT f.idFactura, f.almacen, f.nFactura, f.fechaFac, f.cliente,c.nombreCliente, f.total,  IFNULL(sum(p.montoPago),0) pagado,  f.total - IFNULL(sum(p.montoPago),0) saldoPago, GROUP_CONCAT(p.glosaPago SEPARATOR ', ') glosaPago , f.pagada
		FROM factura f		
		LEFT JOIN pagos p
		ON  f.idFactura= p.idFactura
		INNER JOIN clientes c
		ON c.idCliente = f.cliente 
		WHERE f.fechaFac
		BETWEEN '$ini' AND '$fin' AND not f.pagada=1 AND f.almacen like '%$alm' AND NOT f.anulada=1
    	GROUP BY f.idFactura
		ORDER BY f.nFactura desc";
		//die($sql);
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornarEdicion($n)
	{
		$sql="SELECT p.idpagos, f.idFactura, f.almacen, f.nFactura, f.fechaFac, f.cliente,c.nombreCliente, f.total,  IFNULL(sum(p.montoPago),0) pagado,  f.total - IFNULL(sum(p.montoPago),0) saldoPago, GROUP_CONCAT(p.glosaPago SEPARATOR ', ') glosaPago , f.pagada
		FROM factura f		
		LEFT JOIN pagos p
		ON  f.idFactura= p.idFactura
		INNER JOIN clientes c
		ON c.idCliente = f.cliente 
		WHERE  NOT f.anulada=1
    	AND p.numPago=$n
    	GROUP BY f.idFactura
		ORDER BY f.nFactura desc";
		//die($sql);
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornarDetallePago($numPago)
	{
		$sql="SELECT p.idPagos,f.idFactura,f.lote,f.nFactura,p.montoPago,f.total totalFactura, IFNULL(total-p.montoPago,0) saldo,f.pagada, a.almacen, c.nombreCliente,p.fechaPago, p.numPago, p.anulado		
		FROM pagos p
		INNER JOIN factura f
		ON  p.idFactura=f.idFactura
		INNER JOIN almacenes a
		ON  a.idalmacen=p.almacen
		INNER JOIN clientes c 
		ON f.cliente = c.idCliente
		WHERE p.numPago=$numPago";
		
		$query=$this->db->query($sql)->result_array();		
		return $query;
	}
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function obtenerNumPago($almacen)
	{
		$gestion= date('Y');		

		$sql="SELECT MAX(p.numPago) numPago
				FROM pagos p
  				WHERE p.almacen=$almacen
  				AND year(p.fechaPago)='$gestion'";		
		$query=$this->db->query($sql);	
		if($query->num_rows()>0)
        {
            $numPago=$query->row();
            return ($numPago->numPago);
		}	
		else
		{
			return 0;
		}		
	}
	public function guardarPago($obj)
	{		
		$sql=$this->db->insert_batch("pagos", $obj);
		return $sql;		
	}
	public function anularPago($numPago)
	{
		$sql="Update pagos set anulado=1 Where numPago=$numPago";
		$this->db->query($sql);
	}
	public function recuperarPago($numPago)
	{
		$sql="Update pagos set anulado=0 Where numPago=$numPago";
		$this->db->query($sql);
	}

}
