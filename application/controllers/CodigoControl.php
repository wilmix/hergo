<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CodigoControl extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		if(!$this->session->userdata('logeado'))
		redirect('auth', 'refresh');
		/*******/
		$this->load->library('LibAcceso');
		$this->libacceso->acceso(8);
		/*******/
		$this->load->helper('url');	
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
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
		$this->datos['user_id_actual']=$this->session->userdata['user_id'];
		$this->datos['id_Almacen_actual']=$this->session->userdata['datosAlmacen']->idalmacen;

		$hoy = date('Y-m-d');
		$tipoCambio = $this->Ingresos_model->getTipoCambio($hoy);
		if ($tipoCambio) {
			$tipoCambio = $tipoCambio->tipocambio;
			$this->datos['tipoCambio'] = $tipoCambio;
		} else {
			$this->datos['tipoCambio'] = 'No se tiene tipo de cambio para la fecha';
		}
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
			$this->datos['opcion']="Codigo Control";
			$this->datos['titulo']="Codigo Control";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/**************FUNCION***************/
			//$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['foot_script'][]=base_url('assets/hergo/admin/codigoControl.js');
			/**************TABLA*****************/
        /*$this->datos['cabeceras_css'][] = base_url('assets/plugins/table-boot/css/bootstrap-table.css');
        $this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/bootstrap-table.js');
        $this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js');
        $this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/bootstrap-table-export.js');
        $this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/tableExport.js');*/
			
			/*************TABLE***************/
			/*$this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/css/bootstrap-table.css'); 
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-export.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/tableExport.js');*/
			/***********************************/

			/*************CODIGO CONTROL***************/
			$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/AllegedRC4.js');
			$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/Base64SIN.js');
			$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/ControlCode.js');
			$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/Verhoeff.js');
			/***********************************/
			/*************CODIGO QR***************/
			$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/qrcode.min.js');


			//$this->datos['almacen']=$this->almacen_model->retornar_tabla("almacenes");
			//print_r($this->datos['almacen']);
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('administracion/codigoControl.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
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