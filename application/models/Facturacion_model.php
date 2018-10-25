<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Facturacion_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function storeFactura($factura, $tipoCambioValor)
	{	
        $this->db->trans_start();
            $this->db->insert("ingresos", $factura);
            $idFactura=$this->db->insert_id();
            $facturaDetalle = array();
                foreach ($factura->articulos as $fila) {
                    $detalle=new stdclass();
                    $detalle->idIngreso = $idIngreso;
                    $detalle->nmov = $ingreso->nmov;
                    $detalle->articulo = $this->Ingresos_model->retornar_datosArticulo($fila[0]);
                    $detalle->moneda = $ingreso->moneda;
                    $detalle->cantidad = $fila[2];
                    if ($ingreso->moneda == 2) {
                        $detalle->punitario= $fila[5] * $tipoCambioValor;
                        $detalle->total=$fila[6] * $tipoCambioValor;
                        $detalle->totaldoc=$fila[4] * $tipoCambioValor;
                        
                    }	elseif ($ingreso->moneda == 1) {
                        $detalle->punitario= $fila[5];
                        $detalle->total=$fila[6];
                        $detalle->totaldoc=$fila[4];
                    }
                    array_push($ingresoDetalle,$detalle);	
                }
            $this->db->insert_batch("ingdetalle", $ingresoDetalle);
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        } else {
            
            return $idIngreso;
        }
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
	public function obtenerUltimoRegistro($idAlmacen,$lote)
	{
		$sql="SELECT * 
		from factura f
		WHERE 		
		f.lote=$lote
		AND f.almacen=$idAlmacen
		ORDER BY f.nFactura desc Limit 1";		
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
	public function existeNumFactura($alm,$lote,$nFac)
	{
		$sql="SELECT * 
		FROM factura f
		WHERE f.`lote` = $lote
		AND f.`almacen` = $alm
		AND f.`nFactura` = $nFac
		LIMIT 1";		
		$query=$this->db->query($sql);
        if($query->num_rows()>0)
        {
			$fac=$query->row();
			return ($fac);
			
        }
        else
        {
            return false;
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
		$sql="SELECT f.*,t.tipocambio cambiovalor, a.almacen, a.direccion, a.Telefonos, a.ciudad, a.sucursal
		From factura f
		INNER JOIN tipocambio t 
		ON f.tipoCambio=t.id
		INNER JOIN almacenes a
		ON a.idalmacen = f.almacen
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
	public function obtenerFacturaPDF($idFactura)
	{
		$sql="SELECT f.*,t.tipocambio cambiovalor, a.almacen, a.direccion, a.Telefonos, a.ciudad, a.sucursal, 
		df.idDatosFactura, df.nit, df.autorizacion, df.fechaLimite, df.llaveDosificacion, 
		df.glosa01,df.glosa02, df.glosa03, df.manual
		FROM factura f
		INNER JOIN tipocambio t 
		ON f.tipoCambio=t.id
		INNER JOIN almacenes a
		ON a.idalmacen = f.almacen
		INNER JOIN datosfactura df
		ON df.idDatosFactura = f.lote
		WHERE f.idFactura=$idFactura";
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
	public function obtenerPedido($idFactura)
	{
		$sql="SELECT GROUP_CONCAT(e.clientePedido SEPARATOR '-') pedido, f.`codigoControl`
		FROM factura_egresos fe
		INNER JOIN egresos e
		ON fe.idegresos=e.idegresos
		INNER JOIN factura f ON f.`idFactura`=fe.`idFactura`
		WHERE fe.idFactura=$idFactura
		GROUP BY fe.idFactura
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
	/*SELECT GROUP_CONCAT(e.clientePedido SEPARATOR '-') pedido
		FROM factura_egresos fe
		INNER JOIN egresos e
		ON fe.idegresos=e.idegresos
		Where fe.idFactura=$idFactura
		GROUP BY fe.idFactura  */
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
	public function anularFactura($idFactura,$msj)
	{
		$msj = strval($msj);
		$sql="UPDATE factura set anulada=1,glosa='$msj' where idFactura=$idFactura;";
       
		$query=$this->db->query($sql);
        
        return $query;
	}
	public function actualizar_estadoPagoFactura($idFactura,$saldoNuevo,$saldoPago)
	{	
		if($saldoNuevo==0) //pagado total
			$pagada=1;
		else	//pagado Parcial
			$pagada=2;
		if($saldoNuevo==$saldoPago)
			$pagada=0; //no pago
		$sql="UPDATE factura SET pagada=$pagada, update_at=NOW() WHERE idFactura=$idFactura";		
		$query=$this->db->query($sql);		 
		return $query;
	}
	public function obtenerDetalleFacturaPDF($idFactura)
	{
		$sql="SELECT f.*, u.Sigla from facturadetalle f 
		INNER JOIN articulos a
		ON a.idArticulos=f.articulo
		INNER JOIN unidad u
		ON u.idUnidad=a.idUnidad
		Where idFactura=$idFactura
		order by f.ArticuloCodigo";
		$query=$this->db->query($sql);        
        return $query;
	}
}
