<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Almacen extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("Almacen_model");
		$this->getAssets();
		$this->getDatos();
	}
	public function index()
	{
		$this->accesoCheck(1);
		$this->titles('Almacenes','Almacenes','Administracion',);
		$this->datos['foot_script'][]=base_url('assets/hergo/almacen.js').'?'.rand();
		$this->datos['almacen']=$this->Almacen_model->retornar_tabla("almacenes");
		$this->setView('administracion/almacen/almacen');
	}
	public function agregarAlmacen()
	{
		if($this->input->is_ajax_request())
        {
			$alm = strtoupper($this->security->xss_clean($this->input->post('almacen')));
        	$sucursal = strtoupper($this->security->xss_clean($this->input->post('sucursal')));
        	$dir = strtoupper($this->security->xss_clean($this->input->post('direccion')));
			$ciu = strtoupper($this->security->xss_clean($this->input->post('ciudad')));
        	$telefonos = $this->security->xss_clean($this->input->post('telefonos'));
        	$enu = $this->security->xss_clean($this->input->post('enuso'));
			$cod = $this->security->xss_clean($this->input->post('cod')); 
        	if($cod=="")
        		$this->Almacen_model->agregarAlmacen_model($alm,$dir,$ciu,$telefonos,$enu,$sucursal);
        	else
        		$this->Almacen_model->editarAlmacen_model($alm,$dir,$ciu,$telefonos,$enu,$sucursal,$cod);
        }
        echo "{}";       
	}
	public function editarAlmacen()
	{
		if($this->input->is_ajax_request())
        {
        	$alm = $this->security->xss_clean($this->input->post('almacen'));
        	$dir = $this->security->xss_clean($this->input->post('direccion'));
        	$ciu = $this->security->xss_clean($this->input->post('ciudad'));
        	$enu = $this->security->xss_clean($this->input->post('enuso'));
        	$cod = $this->security->xss_clean($this->input->post('cod'));
        	$this->Almacen_model->editarAlmacen_model($alm,$dir,$ciu,$enu,$cod);
        }
        echo "{}";
	}
}