<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Egresos_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function mostrarEgresos($id=null,$ini=null,$fin=null,$alm="",$tin="")
	{
		if($id==null) //no tiene id de entrada
        {
		  $sql="
			SELECT e.nmov n,e.idEgresos,t.sigla,t.tipomov, e.fechamov, c.nombreCliente, sum(d.total) total,  e.estado,e.fecha, CONCAT(u.first_name,' ', u.last_name) autor, e.moneda, a.almacen, m.sigla monedasigla, e.obs, e.anulado, e.plazopago, e.clientePedido
			FROM egresos e
			INNER JOIN egredetalle d
			on e.idegresos=d.idegreso
			INNER JOIN tmovimiento t 
			ON e.tipomov = t.id 
			INNER JOIN clientes c 
			ON e.cliente=c.idCliente
			INNER JOIN users u 
			ON u.id=e.autor 
			INNER JOIN almacenes a 
			ON a.idalmacen=e.almacen 
			INNER JOIN moneda m 
			ON e.moneda=m.id 
			WHERE e.fechamov 
			BETWEEN '$ini' AND '$fin' and e.almacen like '%$alm' and t.id like '%$tin'
			Group By e.idegresos
			ORDER BY e.idEgresos DESC	
            ";

        }
        else/*REVISAR!!!!!!!!!!!!!!!!!!*/
        {
            $sql="SELECT i.nmov n,i.idIngresos,t.sigla,t.tipomov,t.id as idtipomov, i.fechamov, p.nombreproveedor,p.idproveedor, i.nfact,
				(SELECT FORMAT(SUM(d.total),2) from ingdetalle d where  d.idIngreso=i.idIngresos) total, i.estado,i.fecha, CONCAT(u.first_name,' ', u.last_name) autor, i.moneda, m.id as idmoneda, a.almacen, a.idalmacen, m.sigla monedasigla, i.ordcomp,i.ningalm, i.obs, i.anulado
			FROM ingresos i
			INNER JOIN tmovimiento  t
			ON i.tipomov = t.id
			INNER JOIN provedores p
			ON i.proveedor=p.idproveedor
			INNER JOIN users u
			ON u.id=i.autor
			INNER JOIN almacenes a
			ON a.idalmacen=i.almacen
			INNER JOIN moneda m
			ON i.moneda=m.id
            WHERE idIngresos=$id
			ORDER BY i.idIngresos DESC
            LIMIT 1
            ";
        }
       
		$query=$this->db->query($sql);
		return $query;
	}
	public function mostrarDetalle($id)
	{
		$sql="SELECT a.CodigoArticulo, a.Descripcion, e.cantidad, FORMAT(e.punitario,3) punitario, FORMAT(e.total,3) total, e.descuento
		FROM egredetalle e
		INNER JOIN articulos a
		ON e.articulo = a.idArticulos
 		WHERE e.idegreso=$id";

		$query=$this->db->query($sql);
		return $query;
	}
	public function guardarmovimiento_model($datos)
    {

		$almacen_ne=$datos['almacen_ne'];
    	$tipomov_ne=$datos['tipomov_ne'];
    	$fechamov_ne=$datos['fechamov_ne'];
    	$fechapago_ne=$datos['fechapago_ne'];
    	$moneda_ne=$datos['moneda_ne'];
    	$idCliente=$datos['idCliente'];
    	$pedido_ne=$datos['pedido_ne'];
    	$obs_ne=$datos['obs_ne'];
    
        $tipocambio=$this->retornarTipoCambio();

        
        $gestion= date("Y", strtotime($fechamov_ne));
       // echo $almacen_imp;
    	$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
        $nummov=$this->retornarNumMovimiento($tipomov_ne,$gestion,$almacen_ne);
    	$sql="INSERT INTO egresos (almacen,tipomov,nmov,fechamov,cliente,moneda,obs,tipocambio,autor,fecha,plazopago,clientePedido) VALUES('$almacen_ne','$tipomov_ne','$nummov','$fechamov_ne','$idCliente','$moneda_ne','$obs_ne','$tipocambio','$autor','$fecha','$fechapago_ne','$pedido_ne')";
    	$query=$this->db->query($sql);
    	$idIngreso=$this->db->insert_id();
    	if($idIngreso>0)/**Si se guardo correctamente se guarda la tabla*/
    	{
            
    		foreach ($datos['tabla'] as $fila) {
    			//print_r($fila);
    			$idArticulo=$this->retornar_datosArticulo($fila[0]);    			
                $totalbs=$fila[6];
                $punitariobs=$fila[5];
                $totaldoc=$fila[4];
    			if($idArticulo)
    			{
    				$sql="INSERT INTO egredetalle(idegreso,nmov,articulo,moneda,cantidad,punitario,total,descuento) VALUES('$idIngreso','0','$idArticulo','$moneda_ne','$fila[2]','$fila[3]','$fila[5]','$fila[4]')";
    				$this->db->query($sql);
    			}
    		}
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    public function retornarNumMovimiento($tipo,$gestion,$almacen)
    {
        $sql="SELECT nmov from egresos WHERE YEAR(fechamov)= '$gestion' and almacen='$almacen' and tipomov='$tipo' ORDER BY nmov DESC LIMIT 1";
        
        $resultado=$this->db->query($sql);
        if($resultado->num_rows()>0)
        {
            $fila=$resultado->row();
            return ($fila->nmov)+1;
        }
        else
        {

            return 1;
        }
    }
    public function retornarTipoCambio()/*retorna el ultimo tipo de cambio*/
    {
        //$sql="SELECT nmov from ingresos WHERE YEAR(fechamov)= '$gestion' and almacen='$almacen' and tipomov='$tipo' ORDER BY nmov DESC LIMIT 1";
        $sql="SELECT id from tipocambio ORDER BY id DESC LIMIT 1";

        $resultado=$this->db->query($sql);
        if($resultado->num_rows()>0)
        {
            $fila=$resultado->row();
            return ($fila->id);
        }
        else
        {
            return 1;
        }
    }
     public function retornar_datosArticulo($dato)
    {
    	$sql="SELECT idArticulos from articulos where CodigoArticulo='$dato' LIMIT 1";
    	$query=$this->db->query($sql);
    	if($query->num_rows()>0)
    	{
    		$fila=$query->row();
    		return $fila->idArticulos;
    	}
    	else
    	{

    		return false;
    	}
    }
	
}