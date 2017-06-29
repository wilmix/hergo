<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Traspasos_model extends CI_Model
{
	public $idTraspasos;
	public $idIngreso;
	public $idEgreso;
	public $estado;
	public $fecha;
	public $total;

	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function listar($ini=null,$fin=null)
	{
		$sql="SELECT t.idTraspasos, a.almacen as origen,b.almacen as destino,t.estado,t.fecha, t.total
		FROM traspasos t
		INNER JOIN ingresos i
		ON t.idIngreso=i.idIngresos
		INNER JOIN egresos e
		ON t.idEgreso=e.idEgresos
		INNER JOIN almacenes a
		on e.almacen=a.idalmacen 
		INNER JOIN almacenes b
		on i.almacen=b.idalmacen 
		WHERE t.fecha BETWEEN '$ini' AND '$fin'
		ORDER BY t.fecha DESC";				
		$query=$this->db->query($sql);		
		return $query;
	}
	public function obtener($id)
	{
		$sql="SELECT t.idTraspasos, a.almacen as origen,b.almacen as destino,t.estado,t.fecha, t.total
		FROM traspasos t
		INNER JOIN ingresos i
		ON t.idIngreso=i.idIngresos
		INNER JOIN egresos e
		ON t.idEgreso=e.idEgresos
		INNER JOIN almacenes a
		on e.almacen=a.idalmacen 
		INNER JOIN almacenes b
		on e.almacen=b.idalmacen 
		WHERE t.idTraspasos=$id
		LIMIT 1";				
		$query=$this->db->query($sql);	
		if($query->num_rows()>0)
    	{
    		$fila=$query->row();
    		return $fila;
    	}
    	else
    	{
    		return false;
    	}	
	}
	public function guardar()
	{		
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="INSERT INTO traspasos (idIngreso, idEgreso, estado, fecha, total) VALUES('$this->idIngreso','$this->idEgreso','$this->estado','$fecha','$this->total')";
		$query=$this->db->query($sql);
		return true;		
	}
	public function cambiarEstado($estado)
	{		
		$sql="UPDATE traspasos SET estado='$estado' where idTraspasos='$this.idTraspasos'";     
        $this->db->query($sql);	
	}
	
}
