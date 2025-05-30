<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Nuevo extends MY_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		if(!$this->session->userdata('logeado'))
		redirect('auth', 'refresh');
		$this->load->helper('url');	
		$this->load->model("Nuevo_model");
		$this->load->model("Ingresos_model");
		$this->getAssets();
		$this->getDatos();
		setlocale(LC_ALL,"es_ES");
	}
	public function index()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');
		
			$this->datos['menu']="Administracion";
			$this->datos['opcion']="Clientes";
			$this->datos['titulo']="Clientes";

			$this->datos['cabeceras_script'][]=base_url('assets/hergo/nuevo.js'); 

			
			/****************MOMENT*******************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/moment.min.js');
			/***********************************/
			/***********************************/
			/***********************************/
			/***********************************/

			//$this->datos['clientes']=$this->Cliente_model->mostrarclientes();
			//$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
			//$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");		
			/***********************************/
			/***********************************/
			/***********************************/
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
            $this->load->view('nuevo',$this->datos);   /******        vista          ****** */
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);						
	}

		

	
}