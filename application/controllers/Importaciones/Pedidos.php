<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pedidos extends CI_Controller
{
	private $datos;
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logeado'))
		redirect('auth', 'refresh');
		$this->load->library('LibAcceso');
		$this->load->helper('url');
		$this->load->model("Ingresos_model");
		$this->load->model("Egresos_model");
		$this->load->model("Cliente_model");
		$this->load->model("Pedidos_model");
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
				base_url('assets/sweetalert/sweetalert2.min.css'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.css'),
				base_url('assets/plugins/daterangepicker/daterangepicker.css'),
				base_url('assets/plugins/jQueryUI/jquery-ui.min.css'),//autocomplete
				base_url('assets/plugins/table-boot/plugin/bootstrap-editable.css'),
				base_url('assets/BootstrapToggle/bootstrap-toggle.min.css'),
				base_url('assets/plugins/select/bootstrap-select.min.css'),//select
				'https://unpkg.com/vue-select@3.10.3/dist/vue-select.css',//select



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
				base_url('assets/plugins/jQueryUI/jquery-ui.min.js'),//autocomplete
				/**************INPUT MASK***************/
				base_url('assets/plugins/inputmask/inputmask.js'),
				base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js'),
				base_url('assets/plugins/inputmask/jquery.inputmask.js'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-editable.js'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-editable.js'),
				base_url('assets/BootstrapToggle/bootstrap-toggle.min.js'),
				base_url('assets/plugins/select/bootstrap-select.min.js'),//select
				base_url('assets/plugins/numeral/numeral.min.js'),
				
			);
			$this->foot_script=array(				
        		'https://cdn.jsdelivr.net/npm/vue/dist/vue.js',								
				base_url('assets/vue/vue-resource.min.js'),	
				'https://unpkg.com/vue-select@3.10.3/dist/vue-select.js',
				'https://unpkg.com/vuejs-datepicker',
				'https://unpkg.com/vuejs-datepicker/dist/locale/translations/es.js'
			);
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;

		$this->datos['user_id_actual']=$this->session->userdata['user_id'];
		$this->datos['nombre_actual']=$this->session->userdata['nombre'];
		$this->datos['almacen_actual']=$this->session->userdata['datosAlmacen']->almacen;
		$this->datos['id_Almacen_actual']=$this->session->userdata['datosAlmacen']->idalmacen;
		$this->datos['grupsOfUser'] = $this->ion_auth->in_group('Nacional') ? 'Nacional' : false;
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
		$this->libacceso->acceso(15);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');
			$this->datos['menu']="Consulta Pedidos";
			$this->datos['opcion']="Importaciones";
			$this->datos['titulo']="ConsultaPedidos";
			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/importaciones/pedidos.js');


            //$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
			//$this->datos['tipoingreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
			
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('importaciones/consultaPedidos.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
			
	}
	public function crear()
	{
		$this->libacceso->acceso(17);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Formulario Pedido";
			$this->datos['opcion']="Importaciones";
			$this->datos['titulo']="Form Pedido";
			
			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			$this->datos['foot_script']= $this->foot_script;
			
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['foot_script'][]=base_url('assets/hergo/importaciones/formPedido.js');

			$this->datos['proveedor']=$this->Ingresos_model->retornar_tabla("provedores")->result_array();
			/* var_dump($this->datos['proveedor']);
			die(); */


			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('importaciones/formPedido.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			//$this->load->view('plantilla/footer.php',$this->datos);
			$this->load->view('plantilla/footerscript.php',$this->datos);
	}
	public function store()
	{
		if($this->input->is_ajax_request())
		{
			$pedido = new stdclass();
			$pedido->fecha = $this->input->post('fecha');
			$pedido->recepcion = $this->input->post('recepcion');
			$pedido->proveedor = $this->input->post('proveedor');
			$pedido->pedidoPor = strtoupper($this->input->post('pedidoPor'));
			$pedido->cotizacion = strtoupper($this->input->post('cotizacion'));
			$pedido->formaPago = $this->input->post('formaPago');
			$pedido->glosa = strtoupper($this->input->post('glosa'));
			$pedido->items = json_decode($this->input->post('items'));

			$id = $this->Pedidos_model->storePedido($pedido);

			if($id)
			{
				echo json_encode($id);
			}
			else
			{				
				echo json_encode(false);
			}
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}