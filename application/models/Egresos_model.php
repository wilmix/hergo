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
			SELECT e.nmov n,e.idEgresos,t.sigla,t.tipomov, e.fechamov, c.nombreCliente, sum(d.total) total,  e.estado,e.fecha, CONCAT(u.first_name,' ', u.last_name) autor, e.moneda, a.almacen, m.sigla monedasigla, e.obs, e.anulado, e.plazopago
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
}