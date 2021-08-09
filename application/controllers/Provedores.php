<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Provedores extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('url');	
		$this->load->model("Proveedores_model");
        $this->load->model("Ingresos_model");

	}
	public function index()
	{
		$this->accesoCheck(67);
		$this->titles('Provedores','Provedores','Administracion');
			
		$this->datos['tipodocumento']=$this->Proveedores_model->retornar_tabla("documentotipo");			
		$this->datos['foot_script'][]=base_url('assets/hergo/provedores.js') .'?'.rand();
		$this->setView('administracion/provedores/provedores');
		
	}
	public function mostrarProveedores()
	{
		if($this->input->is_ajax_request())
        {
			$res=$this->Proveedores_model->mostrarProveedores_model();
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function agregarProveedor()
	{
		if($this->input->is_ajax_request())
        {
        	$id = addslashes($this->security->xss_clean($this->input->post('id_proveedor')));
        	$tipo_doc = addslashes($this->security->xss_clean($this->input->post('tipo_doc')));
        	$carnet = addslashes($this->security->xss_clean($this->input->post('carnet')));
        	$nombre = addslashes($this->security->xss_clean($this->input->post('nombre')));        	
        	$direccion = addslashes($this->security->xss_clean($this->input->post('direccion')));           	
        	$nombre_res = addslashes($this->security->xss_clean($this->input->post('nombre_res')));
        	$phone = addslashes($this->security->xss_clean($this->input->post('phone')));
        	$fax = addslashes($this->security->xss_clean($this->input->post('fax')));
        	$email = addslashes($this->security->xss_clean($this->input->post('email')));
        	$website = addslashes($this->security->xss_clean($this->input->post('website')));
        
        	      
        	
        	if($id=="")//es nuevo, agregar
        	{
        		
        		$this->Proveedores_model->agregarProveedor_model($id,$tipo_doc,$carnet,$nombre,$direccion,$nombre_res,$phone,$fax,$email,$website);
        	}
        	else //existe, editar
        	{
        		
        		$this->Proveedores_model->editarProveedor_model($id,$tipo_doc,$carnet,$nombre,$direccion,$nombre_res,$phone,$fax,$email,$website);
        	}
        }
        echo "{}";
	}

	
}