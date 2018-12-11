<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cierre extends CI_Controller
{
	private $datos;
	public function __construct()
	{	
		parent::__construct();
		$this->load->library('LibAcceso');
		$this->libacceso->acceso(1);
		$this->load->helper('url');	
		$this->load->model("Almacen_model");
		$this->load->model("Ingresos_model");
		$this->cabeceras_css=array(
                base_url('assets/bootstrap/css/bootstrap.min.css'),
                base_url('assets/plugins/jQueryUI/jquery-ui.min.css'),
				base_url("assets/fa/css/font-awesome.min.css"),
				base_url("assets/dist/css/AdminLTE.min.css"),
				base_url("assets/dist/css/skins/skin-blue.min.css"),
                base_url("assets/hergo/estilos.css"),
                base_url('assets/plugins/steps/css/main.css'),
				base_url('assets/plugins/steps/css/jquery.steps.css'),
                
                
			);
		$this->cabecera_script=array(
                base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'),
                base_url('assets/plugins/jQueryUI/jquery-ui.min.js'),
				base_url('assets/plugins/steps/jquery.steps.js'),
				base_url('assets/bootstrap/js/bootstrap.min.js'),
				base_url('assets/dist/js/app.min.js'),
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
			$this->datos['opcion']="Cierre Gestión";
			$this->datos['titulo']="Cierre Gestión";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
            $this->datos['cabeceras_script']= $this->cabecera_script;

			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/cierre.js');



			//print_r($this->datos['almacen']);
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('administracion/configuracion/cierre.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
            $this->load->view('plantilla/footer.php',$this->datos);
            
	}



}