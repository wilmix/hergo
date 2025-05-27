<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CodigoControl extends MY_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
	
        $this->load->model("Ingresos_model");
				
	}
	public function index()
	{

		$this->accesoCheck(8);
		$this->titles('CodigoControl','Codigo Control','Administracion');
		$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/AllegedRC4.js');
		$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/Base64SIN.js');
		$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/ControlCode.js');
		$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/Verhoeff.js');
		$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/qrcode.min.js');
		$this->datos['foot_script'][]=base_url('assets/hergo/admin/codigoControl.js') .'?'.rand();
		$this->setView('administracion/codigoControl');
	}
	function prueba()
	{
		$this->datos['menu']="Administracion";
			$this->datos['opcion']="Almacen";
			$this->datos['titulo']="Agregar Almacen";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			/**************TABLA*****************/
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/datatables/dataTables.bootstrap.css');
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/datatables/jquery.dataTables.min.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/datatables/dataTables.bootstrap.min.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js');
			/***********************************/

			$this->datos['almacen']=$this->almacen_model->retornar_tabla("almacen");
			//print_r($this->datos['almacen']);
			$this->load->view('plantilla/head.php',$this->datos);
			
			$this->load->view('administracion/almacen/alm.php',$this->datos);
			//$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function ajaxSubmit()
	{
		echo "ajax";
	}
	public function agregarAlmacen()
	{
		if($this->input->is_ajax_request())
        {
        	$alm = $this->security->xss_clean($this->input->post('almacen'));
        	$dir = $this->security->xss_clean($this->input->post('direccion'));
        	$ciu = $this->security->xss_clean($this->input->post('ciudad'));
        	$enu = $this->security->xss_clean($this->input->post('enuso'));
        	$cod = $this->security->xss_clean($this->input->post('cod'));     
        	if($cod=="")
        		$this->almacen_model->agregarAlmacen_model($alm,$dir,$ciu,$enu);
        	else
        		$this->almacen_model->editarAlmacen_model($alm,$dir,$ciu,$enu,$cod);
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
        	$this->almacen_model->editarAlmacen_model($alm,$dir,$ciu,$enu,$cod);
        }
        echo "{}";
	}
}