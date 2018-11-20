<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Configuracion extends CI_Controller
{
	private $datos;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
        $this->load->model("Ingresos_model");
		/*******/
		$this->load->library('LibAcceso');
		
		/*******/
		$this->load->model("Configuracion_model");
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
		$this->datos['user_id_actual']=$this->session->userdata['user_id'];
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

	public function DatosFactura(){
		$this->libacceso->acceso(9);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Datos Factura";
			$this->datos['opcion']="Configuracion";
			$this->datos['titulo']="Datos Factura";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/configuracion/datosFactura.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacenes']=$this->Configuracion_model->retornar_tabla("almacenes");


			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('administracion/configuracion/datosFactura.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarDatosFactura()//******cambiar a funcion del modelo
	{
		if($this->input->is_ajax_request())
        {
			$res=$this->Configuracion_model->mostrarDatosFactura(); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function agregarDatosFactura(){
		if($this->input->is_ajax_request()){
				$id_lote = addslashes($this->security->xss_clean($this->input->post('id_lote')));
				$almacen = addslashes($this->security->xss_clean($this->input->post('almacen')));
				$autorizacion = addslashes($this->security->xss_clean($this->input->post('autorizacion')));
				$desde = addslashes($this->security->xss_clean($this->input->post('desde')));
				$hasta = addslashes($this->security->xss_clean($this->input->post('hasta')));
				$fechaLimite = addslashes($this->security->xss_clean($this->input->post('fechaLimite')));
				$tipo = addslashes($this->security->xss_clean($this->input->post('tipo')));
				$llave = addslashes($this->security->xss_clean($this->input->post('llave')));
				$leyenda1 = addslashes($this->security->xss_clean($this->input->post('leyenda1')));
				$leyenda2 = addslashes($this->security->xss_clean($this->input->post('leyenda2')));
				$leyenda3 = addslashes($this->security->xss_clean($this->input->post('leyenda3')));
				$uso = addslashes($this->security->xss_clean($this->input->post('uso')));
				
				if($id_lote==""){
					$this->Configuracion_model->agregarDatosFactura_model($id_lote,$almacen,$autorizacion,$desde,$hasta,$fechaLimite,$tipo,$llave,$leyenda1,$leyenda2,$leyenda3,$uso);
				} else {
					$this->Configuracion_model->editarDatosFactura_model($id_lote,$almacen,$autorizacion,$desde,$hasta,$fechaLimite,$tipo,$llave,$leyenda1,$leyenda2,$leyenda3,$uso);
				}
		}
        echo "{}";       
	}
	
	public function TipoCambio(){
		$this->libacceso->acceso(10);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');
			$this->datos['menu']="Tipo de Cambio";
			$this->datos['opcion']="Configuracion";
			$this->datos['titulo']="Tipo de Cambio";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/configuracion/tipoCambio.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacenes']=$this->Configuracion_model->retornar_tabla("almacenes");


			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('administracion/configuracion/tipoCambio.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function agregarTipoCambio(){
		if($this->input->is_ajax_request()){
			$tipocambio = addslashes($this->security->xss_clean($this->input->post('tipoCambio')));
			$this->Configuracion_model->agregarTipoCambio_model($tipocambio);
		}
	}
	public function mostrarTipoCambio()
	{
		if($this->input->is_ajax_request()){
			$res=$this->Configuracion_model->mostrarTipoCambio();
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function updateTipoCambio()
	{
		if($this->input->is_ajax_request()){
			$id = addslashes($this->security->xss_clean($this->input->post('id')));
			$fecha = addslashes($this->security->xss_clean($this->input->post('fechaCambio')));
			$tipocambio = addslashes($this->security->xss_clean($this->input->post('tipocambio')));
			
			if ($id == '') {
				$res = $this->Configuracion_model->agregarTipoCambio_model($tipocambio, $fecha);
			} else if($id == 'egreso') {
				$fecha = date('Y-m-d',strtotime($fecha));
				$res = $this->Configuracion_model->agregarTipoCambio_model($tipocambio, $fecha);
			}
			else {
				$res=$this->Configuracion_model->updateTipoCambio($id, $fecha, $tipocambio);
			}
			
			$res = new stdclass();
			$res->id =$id;
			$res->fecha = $fecha;
			$res->TipoCambio = $tipocambio;
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

	
}