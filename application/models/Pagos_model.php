<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pagos_model extends CI_Model  ////////////***** nombre del modelo 
{

	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function mostrarPagos($ini=null,$fin=null,$alm="") { 
		$sql="SELECT p.`idPago`, a.`almacen`, p.`numPago`, p.`fechaPago`, c.`nombreCliente`, p.`totalPago`, p.almacen idAlmacenPago,
		p.`anulado`, CONCAT(u.`first_name`, ' ' , u.`last_name`) autor, p.`fecha`, f.`pagada`, m.`sigla`, tp.`tipoPago`
		FROM pago_factura pf
		INNER JOIN pago p ON p.`idPago`= pf.`idPago`
		INNER JOIN factura f ON f.`idFactura` = pf.`idFactura`
		INNER JOIN clientes c ON c.`idCliente` = p.`cliente`
		left JOIN users u ON u.`id`= p.`autor`
		INNER JOIN moneda m ON m.`id` = p.`moneda`
		INNER JOIN almacenes a ON a.`idalmacen` = p.`almacen`
		INNER JOIN tipoPago tp ON tp.`id`= p.`tipoPago`
		WHERE p.fechaPago
		BETWEEN '$ini' AND '$fin' AND p.almacen like '%$alm'
		-- AND f.`anulada` = 0
		GROUP BY p.`idPago`
		ORDER BY p.`idPago` DESC";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarPendientePago($ini=null,$fin=null,$alm="")
	{
		$sql="SELECT f.`idFactura`, f.almacen, f.nFactura, f.fechaFac, f.cliente, f.total, f.pagada,c.nombreCliente,
					f.`total` - IFNULL(SUM(ppa.monto),0) saldoPago,
					IFNULL(SUM(ppa.monto),0) totalPago
				FROM factura f
				LEFT JOIN pagosPendientesActivos ppa ON ppa.`idFactura` = f.`idFactura`
				LEFT JOIN clientes c ON c.idCliente = f.cliente
				WHERE 
				f.fechaFac BETWEEN '$ini' AND '$fin'
						AND f.almacen LIKE '%$alm'
						AND f.pagada <> 1
						AND  f.anulada<>1
				GROUP BY f.idFactura
				ORDER BY f.`idFactura` DESC";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornarEdicion($idPago) {
		$sql="SELECT p.`idPago`, p.`almacen`, p.`numPago`, p.`moneda`, p.`cliente`, p.`totalPago` ,p.`anulado`, p.`glosa`, p.`fechaPago`,
		p.`autor`, p.fecha, p.`tipoCambio`, p.`tipoPago`, p.`cheque` , p.`banco`, p.`transferencia`, p.imagen, p.`gestion`, tp.`tipoPago`,p.`cheque`,
		c.`idCliente`, c.`nombreCliente`, c.`documento`, a.`almacen` nomAlmacen, a.`sucursal`, concat(u.`first_name`, ' ',u.`last_name`) userName, p.`tipoPago` idTipoPago , b.`sigla` nomBanco
			FROM pago p
			INNER JOIN clientes c ON c.`idCliente` = p.`cliente`
			INNER JOIN almacenes a ON a.idalmacen = p.almacen
			INNER join tipoPago tp on tp.`id` = p.`tipoPago`
			LEFT JOIN users u on u.id = p.autor
			LEFT JOIN bancos b ON b.`id` = p.`banco`
			WHERE p.idPago = $idPago";
		//die($sql);
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornarEdicionDetalle($idPago) {
		$sql="SELECT 
		f.`almacen`, 
		f.`cliente`, 
		f.`fechaFac`, 
		f.`idFactura`,
		f.`nFactura`, 
		c.`nombreCliente`, 
		f.`pagada`,  
		f.`total` ,
		(f.total-sum(pf.`monto`)) saldoNuevo,
		sum(pf.`monto`) pagar,
		pf.`saldoNuevo` saldoPago, f.`lote`
		
		FROM pago_factura pf
		INNER JOIN factura f ON f.`idFactura` =pf.`idFactura`
		INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
		WHERE pf.`idPago` = $idPago
		GROUP BY f.`idFactura`";
		//die($sql);
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornarDetallePago($idPago)
	{
		$sql="SELECT f.`lote`, f.`fechaFac`,f.`nFactura`, c.`nombreCliente`, pf.`monto`, f.`pagada`, 
		a.`almacen`, cp.`nombreCliente` nombre, p.`glosa`, p.`fechaPago`, p.`numPago`, tp.`tipoPago`, 
		b.`sigla` banco, p.`transferencia`, p.`cheque`, p.anulado
		FROM pago_factura pf
		INNER JOIN pago p ON p.`idPago`= pf.`idPago`
		INNER JOIN factura f ON f.`idFactura` = pf.`idFactura`
		INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
		INNER JOIN clientes cp ON cp.`idCliente` = p.`cliente`
		LEFT JOIN users u ON u.`id`= p.`autor`
		INNER JOIN moneda m ON m.`id` = p.`moneda`
		INNER JOIN almacenes a ON a.`idalmacen` = p.`almacen`
		INNER JOIN tipoPago tp ON tp.`id` = p.`tipoPago`
		LEFT JOIN bancos b ON b.`id`=p.`banco`
		WHERE p.idPago = $idPago
		ORDER BY f.`nFactura`";
		
		$query=$this->db->query($sql)->result_array();		
		return $query;
	}
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function obtenerNumPago($almacen, $gestion)
	{
		$sql="SELECT p.`numPago`
		FROM pago p 
		WHERE p.`almacen` = '$almacen'
		AND p.`gestion` = '$gestion'
		ORDER BY p.`numPago` DESC LIMIT 1";		
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
	public function obtenerIdPago($numPago,$almacen)
	{
		$sql="SELECT idPago
		FROM pago p
		WHERE p.`numPago`=$numPago
		AND p.`almacen`=$almacen";		
		$query=$this->db->query($sql);	
		if($query->num_rows()>0)
        {
            $idPago=$query->row();
            return ($idPago->idPago);
		}	
		else
		{
			return 0;
		}		
	}
	
	public function guardarPago($obj)
	{		
		$sql=$this->db->insert("pago", $obj);
		return $sql;		
	}
	public function storePago($pago)
	{		
		$this->db->trans_start();
			$this->db->insert("pago", $pago);
            $idPago=$this->db->insert_id();
            if($idPago>0)
        	{
				$pagosFactura = array();
				foreach ($pago->pagos as $fila) {
				$pagos=new stdclass();
				$pagos->idPago= $idPago;
				$pagos->idFactura=$fila->idFactura;
				$pagos->monto=$fila->pagar;		
				$pagos->saldoNuevo=$fila->saldoNuevo;	
				array_push($pagosFactura,$pagos);	
				$this->Facturacion_model->actualizar_estadoPagoFactura($fila->idFactura,$fila->saldoNuevo,$fila->saldoPago);
				}
			$this->db->insert_batch("pago_factura", $pagosFactura);	
            }
        	else
        	{
        		echo false;
        	}
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
			return false;
			
        } else {
            
            return $idPago;
        }	
	}

	public function editarPago($idPago,$pago,$detalle) {	
		$this->db->trans_start();	
			$this->db->where('idPago', $idPago);		
			$this->db->update("pago", $pago);

			$this->db->where('idPago', $idPago);
			$this->db->delete('pago_factura');

			$pagosFactura = array();
				foreach ($detalle as $fila) {
					$pagos=new stdclass();
					$pagos->idPago=$idPago;
					$pagos->idFactura=$fila->idFactura;
					$pagos->monto=$fila->pagar;		
					$pagos->saldoNuevo=$fila->saldoNuevo;	
					array_push($pagosFactura,$pagos);	
					$this->Facturacion_model->actualizar_estadoPagoFactura($fila->idFactura,$fila->saldoNuevo,$fila->saldoPago);
				}
			$this->db->insert_batch("pago_factura", $pagosFactura);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return false;
		} else {
			return $idPago;
		}
		
	}
	public function guardarPago_Factura($obj)
	{		
		$sql=$this->db->insert_batch("pago_factura", $obj);
		return $sql;		
	}
	public function anularPago($idPago,$msj)
	{
		$sql="UPDATE pago SET anulado=1, glosa='$msj' WHERE idPago=$idPago";
		$this->db->query($sql);
	}
	public function retornarIdFacturas($idPago)
	{
		$sql="SELECT pf.`idFactura`, f.`total` AS totalFactura
		FROM pago_factura pf
		inner join factura f on f.`idFactura`=pf.`idFactura`
		WHERE pf.`idPago` = $idPago";
		$query = $this->db->query($sql);
		return $query;
	}
	public function modificarPagadaFactura($pagada,$idFactura) {
		$sql="UPDATE factura f
		SET f.`pagada` = $pagada, update_at=NOW()
		WHERE f.`idFactura`=$idFactura";
		$this->db->query($sql);
	}
	public function totalPago($idFactura) {
		$sql="SELECT ifnull(sum(ppa.`monto`),0) totalPago
		FROM pagosPendientesActivos ppa
		WHERE ppa.`idFactura`=$idFactura";
		$query = $this->db->query($sql);
		return $query;
	}
}
