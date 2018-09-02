<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Roles extends CI_Controller
{
	private $datos;
	public function __construct()
	{
		parent::__construct();
		/*******/
		$this->load->library('LibAcceso');
	
		/*******/
		$this->load->helper('url');
		$this->load->model("Roles_model");
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
		$this->cabeceras_css=array(
				base_url('assets/bootstrap/css/bootstrap.min.css'),
				base_url("assets/fa/css/font-awesome.min.css"),
				base_url("assets/dist/css/AdminLTE.min.css"),
				base_url("assets/dist/css/skins/skin-blue.min.css"),
				base_url("assets/hergo/estilos.css"),
				base_url('assets/plugins/table-boot/css/bootstrap-table.css'),
				base_url('assets/plugins/table-boot/plugin/select2.min.css'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-group-by.css'),	
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.css'),				
				base_url('assets/sweetalert/sweetalert2.min.css'),
		);
		$this->cabecera_script=array(
				base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'),
				base_url('assets/bootstrap/js/bootstrap.min.js'),
				base_url('assets/dist/js/app.min.js'),
				base_url('assets/plugins/validator/bootstrapvalidator.min.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table-export.js'),
				base_url('assets/plugins/table-boot/js/tableExport.js'),
				base_url('assets/plugins/table-boot/js/xlsx.core.min.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table-filter.js'),
				base_url('assets/plugins/table-boot/plugin/select2.min.js'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-select2-filter.js'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-group-by.js'),
				base_url('assets/plugins/table-boot/plugin/FileSaver.min.js'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.js'),
        		base_url('assets/plugins/daterangepicker/moment.min.js'),
        		base_url('assets/plugins/slimscroll/slimscroll.min.js'),        		
        		base_url('assets/sweetalert/sweetalert2.min.js'),
        		

		);
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
			if($this->session->userdata('foto')==NULL)
				$this->datos['foto']=base_url('assets/imagenes/ninguno.png');
			else
				$this->datos['foto']=base_url('assets/imagenes/').$this->session->userdata('foto');
	}

	public function roles(){
		$this->libacceso->acceso(26);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Roles";
			$this->datos['opcion']="Roles";
			$this->datos['titulo']="Roles";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/roles.js'); 			
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            $this->datos['users']=$this->Roles_model->retornar_users();

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('/administracion/roles.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
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