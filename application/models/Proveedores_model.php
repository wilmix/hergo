<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Proveedores_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function mostrarProveedores_model()
	{
		$sql="SELECT p.idproveedor, d.documentoTipo, p.documento, p.nombreproveedor, p.direccion, p.responsable, p.telefono, p.fax, p.email, p.web, Concat(u.first_name,' ',u.last_name) as autor, p.fecha, p.logo
		FROM provedores p
		INNER JOIN documentoTipo d
		ON p.idDocumentoTipo=d.idDocumentoTipo
		INNER JOIN users u
		ON u.id=p.autor";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function agregarProveedor_model($id,$tipo_doc,$carnet,$nombre,$direccion,$nombre_res,$phone,$fax,$email,$website)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="INSERT INTO provedores (idDocumentoTipo, documento, nombreproveedor, direccion, responsable, telefono, fax, email, web, autor, fecha, logo) VALUES('$tipo_doc','$carnet','$nombre','$direccion','$nombre_res','$phone','$fax','$email','$website' ,'$autor','$fecha','1')";
		$query=$this->db->query($sql);		
	}
	public function editarProveedor_model($id,$tipo_doc,$carnet,$nombre,$direccion,$nombre_res,$phone,$fax,$email,$website)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="UPDATE provedores SET idDocumentoTipo='$tipo_doc', documento='$carnet', nombreproveedor='$nombre', direccion='$direccion', responsable='$nombre_res',telefono='$phone', fax='$fax', email='$email', web='$website', autor='$autor', fecha='$fecha', logo='1' WHERE idproveedor=$id";
		$query=$this->db->query($sql);		
	}
}
