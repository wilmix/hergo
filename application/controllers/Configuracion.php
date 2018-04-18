<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Configuracion extends CI_Controller
{
	private $datos;
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('url');	
		$this->load->model("Configuracion_model");
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
			if($this->session->userdata('foto')==NULL)
				$this->datos['foto']=base_url('assets/imagenes/ninguno.png');
			else
				$this->datos['foto']=base_url('assets/imagenes/').$this->session->userdata('foto');	
	}
	    
    public function DatosFactura()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');
		
			$this->datos['menu']="Configuración";
			$this->datos['opcion']="Datos Factura";
			$this->datos['titulo']="DatosFactura";
			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/configuracion/datosFactura.js'); /******        JS          ****** */
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
			//$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
			//$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");		
			/***********************************/
			/***********************************/
			/***********************************/
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
            $this->load->view('administracion/configuracion/configuracion.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);						
    }
    public function mostrarDatosFactura()
	{
		if($this->input->is_ajax_request())
        {
			$res=$this->Configuracion_model->mostrarDatosFactura();
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

		

	
}