<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public $datos;
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}
		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
		$this->datos['skin']= ($this->config->item('skin')) ? $this->config->item('skin') : 'skin-blue';
		$this->getAssets();
		if ($this->session->userdata('logeado')) {
			$this->getAssets();
			$this->getDatos();
		}
	}

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	public function getDatos()
	{
		if(!$this->session->userdata('logeado'))
		redirect('auth', 'refresh');
		$this->load->model("General_model");
		$this->load->library('LibAcceso');
		$this->load->library('form_validation');
		//$this->datos['skin']= ($this->config->item('skin')) ? $this->config->item('skin') : 'skin-blue';
		$this->datos['database']=$this->db->database;
		date_default_timezone_set("America/La_Paz");

		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;

		$this->datos['user_id_actual']=$this->session->userdata['user_id'];
		$this->datos['nombre_actual']=$this->session->userdata['nombre'];
		$this->datos['almacen_actual']=$this->session->userdata['datosAlmacen']->almacen;
		$this->datos['id_Almacen_actual']=$this->session->userdata['datosAlmacen']->idalmacen;
		$this->datos['grupsOfUser'] = $this->ion_auth->in_group('Nacional') ? 'Nacional' : false;
		$hoy = date('Y-m-d');
		$tipoCambio = $this->General_model->getTipoCambio($hoy);
		if ($tipoCambio) {
			$tipoCambio = $tipoCambio->tipocambio;
			$this->datos['tipoCambio'] = $tipoCambio;
		} 
		else {
			$this->datos['tipoCambio'] = 'No se tiene tipo de cambio para la fecha';
		}
		
			if($this->session->userdata('foto')==NULL){
				$this->datos['foto']=base_url('assets/imagenes/ninguno.png');
			}
			else{
				$this->datos['foto']=base_url('assets/imagenes/').$this->session->userdata('foto');
			}
	}
	public function getAssets()
	{
		$this->cabeceras_css=array(
			base_url('assets/bootstrap/css/bootstrap.min.css'),
			base_url("assets/fa/css/font-awesome.min.css"),
			base_url("assets/dist/css/AdminLTE.min.css"),
			base_url("assets/dist/css/skins/skin-blue.min.css"),
			base_url("assets/hergo/estilos.css") .'?'.rand(),
			base_url('assets/plugins/table-boot/css/bootstrap-table.css'),
			base_url('assets/plugins/table-boot/plugin/select2.min.css'),				
			base_url('assets/sweetalert/sweetalert2.min.css'),
			base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.css'),
			base_url('assets/plugins/daterangepicker/daterangepicker.css'),
			base_url('assets/plugins/jQueryUI/jquery-ui.min.css'),//autocomplete
			base_url('assets/plugins/table-boot/plugin/bootstrap-editable.css'),
			base_url('assets/BootstrapToggle/bootstrap-toggle.min.css'),
			base_url('assets/plugins/select/bootstrap-select.min.css'),//select
			'https://unpkg.com/vue-select@3.10.3/dist/vue-select.css',//select	
			'https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.20/af-2.3.4/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sl-1.3.1/datatables.min.css',
			'https://cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css',
			base_url("assets/dist/css/skins/skin-green.min.css"),
			base_url("assets/dist/css/skins/skin-red.min.css"),
			base_url("assets/dist/css/skins/skin-purple.min.css"),
			base_url("assets/dist/css/skins/skin-yellow.min.css"),

		);
		$this->cabecera_script=array(
			base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'),
			base_url('assets/bootstrap/js/bootstrap.min.js'),
			base_url('assets/plugins/steps/jquery.steps.js'),
			base_url('assets/plugins/popper/popper.min.js'),
			base_url('assets/dist/js/app.min.js'),
			base_url('assets/plugins/validator/bootstrapvalidator.min.js'),
			base_url('assets/plugins/table-boot/js/bootstrap-table.js'),
			base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js'),
			base_url('assets/plugins/table-boot/js/bootstrap-table-export.js'),
			base_url('assets/plugins/table-boot/js/tableExport.js'),
			base_url('assets/plugins/table-boot/js/bootstrap-table-filter.js'),
			base_url('assets/plugins/table-boot/plugin/select2.min.js'),
			base_url('assets/plugins/table-boot/plugin/bootstrap-table-select2-filter.js'),
			base_url('assets/plugins/daterangepicker/moment.min.js'),
			base_url('assets/plugins/slimscroll/slimscroll.min.js'),        		
			base_url('assets/sweetalert/sweetalert2.min.js'),
			base_url('assets/busqueda/underscore-min.js'),
			base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.js'),
			base_url('assets/plugins/daterangepicker/daterangepicker.js'),
			base_url('assets/plugins/daterangepicker/locale/es.js'),
			base_url('assets/plugins/jQueryUI/jquery-ui.min.js'),
			base_url('assets/plugins/datatables/datatables.min.js'),
			base_url('assets/plugins/datatables/dataTables.responsive.min.js'),
			base_url('assets/plugins/inputmask/inputmask.js'),
			base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js'),
			base_url('assets/plugins/inputmask/jquery.inputmask.js'),
			base_url('assets/plugins/table-boot/plugin/bootstrap-table-editable.js'),
			base_url('assets/plugins/table-boot/plugin/bootstrap-editable.js'),
			base_url('assets/BootstrapToggle/bootstrap-toggle.min.js'),
			base_url('assets/plugins/select/bootstrap-select.min.js'),
			base_url('assets/plugins/numeral/numeral.min.js'),
			base_url('assets/plugins/FileInput/js/fileinput.min.js'),
			base_url('assets/plugins/FileInput/js/locales/es.js'),
			base_url('assets/plugins/chartjs/chart3.5.0.js'),
			base_url('assets/vue/vue.js'),	
			base_url('assets/vue/vue-resource.min.js'),	
			base_url('assets/vue-plugins/vue-select/vue-select.js'),	
			base_url('assets/vue-plugins/vue-datapicker/vuejs-datepicker.min.js'),	
			base_url('assets/vue-plugins/vue-datapicker/translations/es.js'),
			base_url('assets/hergo/NumeroALetras.js'),
		);
		$this->foot_script=array(		

			base_url('assets/hergo/funciones.js') .'?'.rand(),
		);

		$this->datos['cabeceras_css']= $this->cabeceras_css;
		$this->datos['cabeceras_script']= $this->cabecera_script;
		$this->datos['foot_script']= $this->foot_script;
		
	}
	public function setView($currentView)
	{
		$this->load->view('plantilla/head',$this->datos);
		$this->load->view('plantilla/header',$this->datos);
		$this->load->view('plantilla/menu',$this->datos);
		$this->load->view('plantilla/headercontainer',$this->datos);
		$this->load->view( $currentView,$this->datos);
		$this->load->view('plantilla/footcontainer',$this->datos);
		$this->load->view('plantilla/footerscript',$this->datos);
		$this->load->view('plantilla/footer',$this->datos);
	}
	public function titles($titulo,$menu,$opcion)
	{
		$this->datos['titulo']=$titulo;
		$this->datos['menu']=$menu;
		$this->datos['opcion']=$opcion;
	}
	public function accesoCheck($acceso_id)
	{
		if(!$this->session->userdata('logeado'))
		redirect('auth', 'refresh');
		if ($acceso_id > 0) {
			$this->libacceso->acceso($acceso_id);
		}
	}
}
