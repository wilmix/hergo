<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Clientes extends CI_Controller
{
	private $datos;
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('url');	
		$this->load->model("Cliente_model");
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
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
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
			$this->datos['opcion']="Clientes";
			$this->datos['titulo']="Clientes";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/clientes.js');
			
			/*************TABLE***************/
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/css/bootstrap-table.css'); 
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-export.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/tableExport.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-filter-control.js');
			
			/****************MOMENT*******************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/moment.min.js');
			/***********************************/
			/***********************************/
			/***********************************/
			/***********************************/

			//$this->datos['clientes']=$this->Cliente_model->mostrarclientes();
			$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
			$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");		
			


			/***********************************/
			/***********************************/
			/***********************************/
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('administracion/clientes/clientes.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);						
	}
	public function mostrarclientes()
	{
		if($this->input->is_ajax_request())
        {
			$res=$this->Cliente_model->mostrarclientes_model();
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function agregarCliente()
	{
		if($this->input->is_ajax_request())
        {
        	$id = addslashes($this->security->xss_clean($this->input->post('id_cliente')));
        	$tipo_doc = addslashes($this->security->xss_clean($this->input->post('tipo_doc')));
        	$carnet = addslashes($this->security->xss_clean($this->input->post('carnet')));
        	$nombre_cliente = addslashes($this->security->xss_clean($this->input->post('nombre_cliente')));
        	$clientetipo = addslashes($this->security->xss_clean($this->input->post('clientetipo')));
        	$direccion = addslashes($this->security->xss_clean($this->input->post('direccion')));           	
        	$phone = addslashes($this->security->xss_clean($this->input->post('phone')));
        	$fax = addslashes($this->security->xss_clean($this->input->post('fax')));
        	$email = addslashes($this->security->xss_clean($this->input->post('email')));
        	$website = addslashes($this->security->xss_clean($this->input->post('website')));
        
        	      
        	
        	if($id=="")//es nuevo, agregar
        	{
        		
        		$this->Cliente_model->agregarCliente_model($id,$tipo_doc,$carnet,$nombre_cliente,$clientetipo,$direccion,$phone,$fax,$email,$website);
        	}
        	else //existe, editar
        	{
        		
        		$this->Cliente_model->editarCliente_model($id,$tipo_doc,$carnet,$nombre_cliente,$clientetipo,$direccion,$phone,$fax,$email,$website);
        	}
        }
        echo "{}";
	}
	

	
}