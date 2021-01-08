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
		$sql="SELECT c.idCliente, d.documentotipo, c.documento, c.nombreCliente, cl.clientetipo, c.direccion, c.email, c.web, c.telefono, c.fax, c.fecha, Concat(u.first_name,' ',u.last_name) as autor
		FROM clientes c
		LEFT JOIN documentotipo d
		ON c.idDocumentoTipo=d.idDocumentoTipo
		LEFT JOIN clientetipo cl
		ON cl.idClienteTipo=c.idClientetipo
		LEFT JOIN users u
		ON u.id=c.autor
		ORDER BY c.idCliente DESC ";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function agregarcliente_model($id,$tipo_doc,$carnet,$nombre_cliente,$clientetipo,$direccion,$phone,$fax,$email,$website)
	{
		$autor=$this->session->userdata('user_id');
		$nit = $carnet;

		$prueba = $this->getClientByNIT($nit);
		if ($prueba == false) {
			$sql="INSERT INTO clientes (idDocumentoTipo, documento, nombreCliente, idClientetipo, direccion, telefono, fax, email, web, autor, fecha) 
			VALUES('$tipo_doc','$carnet','$nombre_cliente','$clientetipo','$direccion','$phone','$fax','$email','$website' ,'$autor',NOW())";
			$query=$this->db->query($sql);
			return $query;
		} else {
			return $prueba;
		}
	}
	public function editarCliente_model($id,$tipo_doc,$carnet,$nombre_cliente,$clientetipo,$direccion,$phone,$fax,$email,$website)
	{
		$prueba = $this->getClientByNIT($carnet);
		if ($prueba == '99001') {
		$autor=$this->session->userdata('user_id');
			$sql="UPDATE clientes SET idDocumentoTipo='$tipo_doc', documento='$carnet', nombreCliente=UPPER('$nombre_cliente'), idClientetipo='$clientetipo', 
			direccion='$direccion', telefono='$phone', fax='$fax', email='$email', web='$website', autor='$autor', fecha=NOW() WHERE idCliente=$id";
			$query=$this->db->query($sql);	
			return $query;	
		}else {
			return $prueba;
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
	}public function getClientByNIT($nit)
	{	

		$sql="select c.`idCliente`, c.`documento`, c.`nombreCliente`, c.`fecha`, concat(u.`first_name`, ' ' , u.`last_name`) autor
		from clientes c
		inner join users u on u.`id` = c.`autor`
		where c.`documento` = $nit";
		$query=$this->db->query($sql);	
		
		if($query->num_rows()>0)
        {
			if ($nit == 99001) {
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
}
