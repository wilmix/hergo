<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Provedores extends CI_Controller
{
	private $datos;
	public function __construct()
	{	
		parent::__construct();
		/*******/
		$this->load->library('LibAcceso');
		$this->libacceso->acceso(7);
		/*******/
		$this->load->helper('url');	
		$this->load->model("Proveedores_model");
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
				
			);
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['user_id_actual']=$this->session->userdata['user_id'];
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
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
			$this->datos['opcion']="Provedores";
			$this->datos['titulo']="Provedores";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/provedores.js');
			
			/*************TABLE***************/
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/css/bootstrap-table.css'); 
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-export.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/tableExport.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-filter-control.js');
			/*********UPLOAD******************/
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/FileInput/css/fileinput.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/FileInput/js/fileinput.min.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/FileInput/js/locales/es.js');
			/****************MOMENT*******************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/moment.min.js');
			/***********************************/
			/***********************************/
			/***********************************/
			/***********************************/
	
			$this->datos['tipodocumento']=$this->Proveedores_model->retornar_tabla("documentotipo");			

			/***********************************/
			/***********************************/
			/***********************************/
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('administracion/provedores/provedores.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);						
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