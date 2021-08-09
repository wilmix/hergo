<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Roles extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Roles_model");
		$this->load->model("Ingresos_model");
	}
	public function roles()
	{
		$this->accesoCheck(38);
		$this->titles('Roles','Roles','Configuración');
		$this->datos['users']=$this->Roles_model->retornar_users();
		$this->datos['foot_script'][]=base_url('assets/hergo/roles.js') .'?'.rand();
		$this->setView('administracion/roles');
	} 
	public function mostrarRoles() 
	{
		if($this->input->is_ajax_request())
        {
            $idUser=$this->security->xss_clean($this->input->post('idUser'));	
			$res=$this->Roles_model->mostrarRoles( $idUser); 
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
    public function activar() 
	{
		if($this->input->is_ajax_request())
        {
            $idUser=$this->security->xss_clean($this->input->post('idUser'));
            $idSub=$this->security->xss_clean($this->input->post('idSub'));	
			$res=$this->Roles_model->activar($idUser, $idSub); 
            $res=200;
            echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
    public function desActivar() 
	{
		if($this->input->is_ajax_request())
        {
            $idUser=$this->security->xss_clean($this->input->post('idUser'));
            $idSub=$this->security->xss_clean($this->input->post('idSub'));	
			$res=$this->Roles_model->desActivar($idUser, $idSub); 
            $res=200;
            echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}