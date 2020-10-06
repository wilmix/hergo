
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class FacturaProveedores extends CI_Controller
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
				'https://unpkg.com/vuejs-datepicker/dist/locale/translations/es.js',
				'https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.20/af-2.3.4/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sl-1.3.1/datatables.min.js'
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
		//$this->libacceso->acceso(57);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');
			$this->datos['menu']="Pago Proveedores";
			$this->datos['opcion']="Importaciones";
			$this->datos['titulo']="PagoProveedores";
			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			$this->datos['foot_script']= $this->foot_script;
			
			$this->datos['foot_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['foot_script'][]=base_url('assets/hergo/importaciones/facturaProveedores.js');
		
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('importaciones/FacturaProveedores.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
			$this->load->view('plantilla/footerscript.php',$this->datos);
			
	}
	public function getFacturaProveedores()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("ini"));
			$fin=$this->security->xss_clean($this->input->post("fin"));
			$filtro=$this->security->xss_clean($this->input->post("filtro"));			
			$res=$this->Pedidos_model->getFacturaProveedores($ini, $fin,$filtro); 
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}


	public function storePago()
	{
		if($this->input->is_ajax_request())
		{
			$config = [
				"upload_path" => "./assets/pagoProveedores/",
				"allowed_types" => "pdf"
			];
			$this->load->library("upload",$config);
				if ($this->upload->do_upload('url_pago')) {
					$pdf = array("upload_data" => $this->upload->data());
					$url = $pdf['upload_data']['file_name'];
				}
				else{
					//echo $this->upload->display_errors();
					$url = '';
				}
			$pago = new stdclass();
			$pago->fecha = $this->input->post('fechaPago');
			$pago->url = $url;
			$pago->total = $this->input->post('total');
			$pago->created_by = $this->session->userdata('user_id');
			$pago->pagos = json_decode($this->input->post('pagos'));
			//echo json_encode($pago);die();
			$id = $this->Pedidos_model->storePago($pago);
			if($id)
			{
				$res = new stdclass();
				$res->status = true;
				$res->id = $id;
				$res->orden = $pago;
				echo json_encode($res);
			} else {
				echo json_encode($id);
			}

		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

}