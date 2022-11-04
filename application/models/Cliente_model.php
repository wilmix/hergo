<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Cliente_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function mostrarclientes_model()
	{
		$sql="SELECT
				c.idCliente,
				d.descripcion documentotipo,
				c.documento,
				c.complemento,
				c.nombreCliente,
				cl.clientetipo,
				c.direccion,
				c.email,
				c.web,
				c.telefono,
				c.diasCredito,
				c.fecha,
				Concat(u.first_name, ' ', u.last_name) as autor
			FROM
				clientes c
				LEFT JOIN documentotipo d ON c.idDocumentoTipo = d.idDocumentoTipo
				LEFT JOIN clientetipo cl ON cl.idClienteTipo = c.idClientetipo
				LEFT JOIN users u ON u.id = c.autor
			ORDER BY
		c.idCliente DESC";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function storeCliente($id ,$cliente)
	{
		if ($id>0) 
		{
			$this->db->trans_start();
                $this->db->where('idCliente', $id);
                $this->db->update('clientes', $cliente);
            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
		} 
		else 
		{
			$this->db->trans_start();
				$this->db->insert("clientes", $cliente);
				$id=$this->db->insert_id();
			$this->db->trans_complete();
			return ( $this->db->trans_status() === FALSE ) ? false : $id;
		}
		
	}
	public function obtenerCliente($id)
	{

		$sql="SELECT *
		FROM clientes c
		WHERE c.idCliente=$id";
		
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
	public function getClientByNIT($nit)
	{	

		$sql="select c.`idCliente`, c.`documento`, c.`nombreCliente`, c.`fecha`, concat(u.`first_name`, ' ' , u.`last_name`) autor
		from clientes c
		inner join users u on u.`id` = c.`autor`
		where c.`documento` = '$nit'";
		$query=$this->db->query($sql);	
		
		if($query->num_rows()>0)
        {
			if ($nit == 99001 || $nit == 0) {
				return false;
			} else {
				$fila=$query->row();
				return $fila;
			}
        }
        else
        {
            return false;
        }			
	}
	public function getClientByDocName($nit,$nombre)
	{	
		$sql="  select
					c.idCliente,
					c.documento,
					c.nombreCliente,
					c.fecha,
					concat(u.first_name, ' ', u.last_name) autor
				from
					clientes c
					inner join users u on u.id = c.autor
				where
					c.documento = '$nit'
					and c.nombreCliente = '$nombre' 
		";
		$query=$this->db->query($sql);	
		return $query;
	}
	public function getClientByDoc($nit)
	{	
		$sql="  select
					c.idCliente,
					c.documento,
					c.nombreCliente,
					c.fecha,
					concat(u.first_name, ' ', u.last_name) autor
				from
					clientes c
					inner join users u on u.id = c.autor
				where
					c.documento = '$nit'
		";
		$query=$this->db->query($sql);	
		return $query;
	}
}
