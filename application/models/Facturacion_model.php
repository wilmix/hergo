<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Facturacion_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		$this->load->model("FacturaDetalle_model");
		$this->load->model("FacturaEgresos_model");
		date_default_timezone_set("America/La_Paz");
	}
	public function storeFactura($factura)
	{	
        $this->db->trans_start();
            $this->db->insert("factura", $factura);
            $idFactura=$this->db->insert_id();
            if($idFactura>0) //se registro correctamente => almacenar la tabla
        	{
				$facturaDetalle = array();
				$facturaEgreso=array();
                foreach ($factura->articulos as $fila) {
                    $detalle=new stdclass();
                    $detalle->idFactura = $idFactura;
					$detalle->articulo = $fila->id;
					$detalle->moneda = $factura->moneda;
					$detalle->facturaCantidad = $fila->cantidadReal;
					$detalle->facturaPUnitario = $fila->punitario;
					$detalle->ArticuloNombre = $fila->Descripcion;
					$detalle->ArticuloCodigo = $fila->CodigoArticulo;
					$detalle->idEgresoDetalle = $fila->idEgreDetalle;
					array_push($facturaDetalle,$detalle);

					$this->Egresos_model->actualizarCantFact($fila->idEgreDetalle,$fila->cantidadReal);
					$this->actualizarEstado($fila->idegreso);	
					
					$factura_egreso = new stdclass();
					$factura_egreso->idegresos = $fila->idegreso;
					$factura_egreso->idFactura = $idFactura;
					array_push($facturaEgreso,$factura_egreso);

                }
			$this->db->insert_batch("facturadetalle", $facturaDetalle);
			$this->db->insert_batch("factura_egresos", $facturaEgreso);
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
            
            return $idFactura;
        }
	}
	public function actualizarEstado($idEgreso)//cambia el estado si esta pendiente o facturado
	{
		$estado=0;
		$cantidad=$this->Egresos_model->evaluarFacturadoTotal($idEgreso); //si es 0 facturado total si no parcial
		if(count($cantidad)==0)//Facturado
			$estado=1;
		else
			$estado=2;
		$this->Egresos_model->actualizarEstado($idEgreso,$estado);
		return $estado;
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
		$sql="SELECT f.*,t.tipocambio cambiovalor, a.almacen, a.direccion, a.Telefonos, a.ciudad, a.sucursal, f.almacen idAlmacen
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
	public function anularFactura($idFactura,$msj,$idAlmacen)
	{
		$this->db->trans_start();
			
			$autor = $this->session->userdata('user_id');
        	$fecha = date('Y-m-d H:i:s'); 
			
			$facturaEgresos=$this->FacturaEgresos_model->obtenerPorFactura($idFactura);

			$msj = strval($msj);
			$sql="UPDATE factura set anulada=1,glosa='$msj',autor='$autor',update_at='$fecha' where idFactura=$idFactura;";
			$this->db->query($sql);

			$facturaDetalle=$this->obtenerDetalleFactura($idFactura);		
			foreach ($facturaDetalle as $fila) 
			{
				
				if($fila["idEgresoDetalle"]!=null)
					$this->Egresos_model->actualizarRestarCantFact($fila["idEgresoDetalle"],$fila["facturaCantidad"]);		
			}

			$this->FacturaEgresos_model->actualizarFparcial_noFacturado($idFactura,$facturaEgresos->idegresos);
			$this->db->query("CALL actualizarTablaSaldoFactura($idFactura, $idAlmacen)");
			
			$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
			
		} else {
			
			return true;
		}
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
