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
		$sql="SELECT t.idTraspasos, a.almacen as origen,b.almacen as destino,i.estado,e.fechamov fecha, 
		t.idEgreso, e.anulado, e.`nmov` numEgreso, i.`nmov` numIngreso, e.clientePedido, e.obs,
		CASE
			WHEN e.anulado = 1 THEN 'ANULADO'
			WHEN i.estado = 0 THEN 'PENDIENTE'
			WHEN i.estado = 1 THEN 'APROBADO'
		END estadoT
		FROM traspasos t
		INNER JOIN ingresos i
		ON t.idIngreso=i.idIngresos
		INNER JOIN egresos e
		ON t.idEgreso=e.idEgresos
		INNER JOIN almacenes a
		on e.almacen=a.idalmacen 
		INNER JOIN almacenes b
		on i.almacen=b.idalmacen 
		WHERE e.fechamov BETWEEN '$ini' AND '$fin'
		ORDER BY t.idTraspasos DESC";				
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
		on i.almacen=b.idalmacen 
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
	public function obtenerUltimoTraspaso($idEgreso)
	{
		$sql="SELECT t.idTraspasos, a.almacen as origen,b.almacen as destino,a.idalmacen as idorigen,b.idalmacen as iddestino, t.idIngreso, t.idEgreso
		FROM traspasos t
		INNER JOIN ingresos i
		ON t.idIngreso=i.idIngresos
		INNER JOIN egresos e
		ON t.idEgreso=e.idEgresos
		INNER JOIN almacenes a
		on e.almacen=a.idalmacen 
		INNER JOIN almacenes b
		on i.almacen=b.idalmacen 
		WHERE e.tipomov=8 /*tipo de egreso traspaso*/
		/*AND e.anulado<>1 distinto de anulado*/
		AND e.idegresos=$idEgreso
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
	public function actualizar()
	{

		$sql="UPDATE traspasos SET total=$this->total where idIngreso=$this->idIngreso AND idEgreso=$this->idEgreso";	
        $this->db->query($sql);
        return true;
	}
	
	public function storeTraspaso($ingreso,$egreso)
	{	
        $this->db->trans_start();
			$idIngreso = $this->Ingresos_model->storeIngreso($ingreso);
			$idEgreso = $this->Egresos_model->storeEgreso($egreso);

			$traspaso = new stdclass();
			$traspaso->idIngreso = $idIngreso;
			$traspaso->idEgreso = $idEgreso;

			$this->db->insert("traspasos", $traspaso);
			$idTraspaso=$this->db->insert_id();
			
			$res = new stdclass();
			$res->ingreso = $idIngreso;
			$res->egreso = $idEgreso;
			$res->traspaso = $idTraspaso;


		$this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        } else {
            
            return $res;
        }
	}
	public function updateTraspaso($idIng, $ingreso,$idEgre, $egreso)
	{	
        $this->db->trans_start();
			$this->Egresos_model->updateEgreso($idEgre, $egreso);
			$this->Ingresos_model->updateIngreso($idIng, $ingreso);

			$this->db->set('estado', '0');
			$this->db->where('idIngresos', $idIng);
			$this->db->update('ingresos');


		$this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        } else {
            
            return true;
        }
	}
  

}
