<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ConfigArticulosWeb extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("ArticulosWeb_model");
	}
	
	public function index()
	{
		//$this->accesoCheck(2);
		$this->titles('ArticulosWebConfig','Configurar Articulos Web','Administracion',);
		$this->datos['foot_script'][]=base_url('assets/hergo/articulosWeb/config.js').'?'.rand();
					
		$this->setView('administracion/webArticulos/config.php');
	}
    public function getLevel1()
    {
        $table = $this->input->post('table');
        $res = $this->ArticulosWeb_model->show($table);
        echo json_encode($res);
    }
    public function store()
    {
        $nivel = new stdclass();
        $nivel->id = $this->input->post('id');
        $nivel->name = $this->input->post('name');
        $nivel->is_active = $this->input->post('isActive');
        $nivel->autor = $this->session->userdata('user_id');
        $table = $this->input->post('table');
        $id = $this->ArticulosWeb_model->store($nivel, $table);
        echo json_encode($id);
    }
}