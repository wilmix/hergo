<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Almacen extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		if(!$this->session->userdata('logeado'))
		redirect('auth', 'refresh');
		$this->load->library('LibAcceso');
		$this->libacceso->acceso(1);
		$this->load->helper('url');	
		$this->load->model("Almacen_model");
		$this->load->model("Ingresos_model");
		$this->cabeceras_css=array(
				base_url('assets/bootstrap/css/bootstrap.min.css'),
				base_url("assets/fa/css/font-awesome.min.css"),
				base_url("assets/dist/css/AdminLTE.min.css"),
				base_url("assets/dist/css/skins/skin-blue.min.css"),
				base_url("assets/hergo/estilos.css"),
			);
		$this->cabecera_script=array(
				base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'),
				base_url('assets/bootstrap/js/bootstrap.min.js'),
				base_url('assets/dist/js/app.min.js'),
				base_url('assets/plugins/validator/bootstrapvalidator.min.js'),
				base_url('assets/plugins/slimscroll/slimscroll.min.js'),
				base_url('assets/plugins/daterangepicker/moment.min.js'),
				
			);
			$hoy = date('Y-m-d');
			$tipoCambio = $this->Ingresos_model->getTipoCambio($hoy);
			if ($tipoCambio) {
				$tipoCambio = $tipoCambio->tipocambio;
				$this->datos['tipoCambio'] = $tipoCambio;
			} else {
				$this->datos['tipoCambio'] = 'No se tiene tipo de cambio para la fecha';
			}
		$this->datos['user_id_actual']=$this->session->userdata['user_id'];	
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
		$this->datos['id_Almacen_actual']=$this->session->userdata['datosAlmacen']->idalmacen;

			if($this->session->userdata('foto')==NULL)
				$this->datos['foto']=base_url('assets/imagenes/ninguno.png');
			else
				$this->datos['foto']=base_url('assets/imagenes/').$this->session->userdata('foto');	
	}
	public function index()
	{
		if(!$this->session->userdata('logeado'))
		redirect('auth', 'refresh');
		
		
			$this->datos['menu']="Administracion";
			$this->datos['opcion']="Almacen";
			$this->datos['titulo']="Agregar Almacen";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			//$this->datos['cabeceras_script']= $this->cabecera_script;
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/almacen.js');
			/**************TABLA*****************/
			$this->datos['cabeceras_css'][] = base_url('assets/plugins/table-boot/css/bootstrap-table.css');
			$this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/bootstrap-table.js');
			$this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js');
			$this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/bootstrap-table-export.js');
			$this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/tableExport.js');
			
			/*************TABLE***************/
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/css/bootstrap-table.css'); 
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-export.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/tableExport.js');
			/***********************************/

			$this->datos['almacen']=$this->Almacen_model->retornar_tabla("almacenes");
			//print_r($this->datos['almacen']);
			$this->load->view('plantilla/head',$this->datos);
			$this->load->view('plantilla/header',$this->datos);
			$this->load->view('plantilla/menu',$this->datos);
			$this->load->view('plantilla/headercontainer',$this->datos);
			$this->load->view('administracion/almacen/almacen',$this->datos);
			$this->load->view('plantilla/footerscript',$this->datos);
			$this->load->view('plantilla/footer',$this->datos);
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