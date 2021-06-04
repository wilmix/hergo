<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Proforma extends CI_Controller
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
		$this->load->model("Proforma_model");
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
			$this->datos['menu']="ProFormas";
			$this->datos['opcion']="Consultas";
			$this->datos['titulo']="ConsultaProforma";
			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			$this->datos['foot_script']= $this->foot_script;
			
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['foot_script'][]=base_url('assets/hergo/proforma/proformas.js');
		
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('proforma/consultaProformas.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
			$this->load->view('plantilla/footerscript.php',$this->datos);
	}
	public function getProformas()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("ini"));
        	$fin=$this->security->xss_clean($this->input->post("fin"));
			$alm= $this->input->post("alm");
			$res=$this->Proforma_model->getProformas($ini, $fin, $alm); 
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function formProforma()
	{
		//$this->libacceso->acceso(17);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Proforma";
			$this->datos['opcion']="CrearProforma";
			$this->datos['titulo']="Crear Proforma";
			
			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
            $this->datos['foot_script']= $this->foot_script;
            /*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
			/***************SELECT***********/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/select/bootstrap-select.min.js');
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/select/bootstrap-select.min.css');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			//$this->datos['cabeceras_script'][]=base_url('assets/hergo/proforma/formProforma.js');
            $this->datos['foot_script'][]=base_url('assets/hergo/proforma/formProforma.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/modalClientesEgreso.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			/**************EDITABLE***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-table-editable.js');
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.css');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.js');

			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');

			$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
            $this->datos['tegreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
		  	$this->datos['fecha']=date('Y-m-d');
		  	//$this->datos['clientes']=$this->Ingresos_model->retornar_tabla("clientes");
		  	$this->datos['articulo']=$this->Ingresos_model->retornar_tabla("articulos");
		  	$this->datos['user']=$this->Egresos_model->retornar_tablaUsers("nombre");
		  	//clientes

		  	$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
			$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");
			
			//$this->datos['opcion']="Compras locales";
			$this->datos['idegreso']=7;
			
			//$this->datos['almacen_actual']=$this->session->userdata['datosAlmacen'];
			/*echo '<pre>';
			echo ($this->session->userdata['datosAlmacen']->idalmacen);
			echo ($this->session->userdata['datosAlmacen']->almacen);
			echo '</pre>';*/

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			//$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('proforma/formProforma.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
            $this->load->view('plantilla/footerscript.php',$this->datos);
	}
	public function store()
	{
		if($this->input->is_ajax_request())
		{
			$id = $this->input->post('id');
			$gestion = date("Y", strtotime($this->input->post('fecha')));
			$proforma = new stdclass();
			$proforma->almacen = $this->input->post('almacen');
			$proforma->num = $id ? $this->input->post('n') : $this->Proforma_model->getNumMov($gestion,$proforma->almacen);
			$proforma->fecha = $this->input->post('fecha');
			$proforma->cliente = $this->input->post('cliente');
			$proforma->moneda = $this->input->post('moneda');
			$proforma->condicionesPago = strtoupper($this->input->post('condicionesPago'));
			$proforma->porcentajeDescuento = $this->input->post('porcentajeDescuento');
			$proforma->descuento = round($this->input->post('descuento'),2);
			$proforma->total = round($this->input->post('totalFin'),2);
			$proforma->validezOferta = strtoupper($this->input->post('validez'));
			$proforma->lugarEntrega = strtoupper($this->input->post('lugarEntrega'));
			$proforma->tiempoEntrega = strtoupper($this->input->post('tiempoEntrega'));
			$proforma->industria = strtoupper($this->input->post('industria'));
			$proforma->marca = strtoupper($this->input->post('marca'));
			$proforma->tipo = strtoupper($this->input->post('tipo'));
			$proforma->glosa = nl2br(strtoupper($this->input->post('glosa')));
			$proforma->gestion = $gestion;
			$proforma->autor = $this->session->userdata('user_id');
			$proforma->updated_at = $id ? date('Y-m-d H:i:s') : 0;
			$proforma->items = json_decode($this->input->post('items'));

			$id = $this->Proforma_model->storeProforma($id , $proforma);

			if($id)
			{
				$res = new stdclass();
				$res->status = true;
				$res->id = $id;
				$res->proforma = $proforma;
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
	public function mostrarEgresos()
	{
		if($this->input->is_ajax_request())
        {
        	//$almacen=//retornar almacen al que corresponde el usuario!!!!!
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//fecha fin
        	$alm=$this->security->xss_clean($this->input->post("a"));//almacen
        	$tin=$this->security->xss_clean($this->input->post("ti"));//tipo de ingreso
        	
        	if($tin==8)
        		$res=$this->Egresos_model->mostrarEgresosTraspasos($id=null,$ini,$fin,$alm,$tin);
        	else        		
				$res=$this->Egresos_model->mostrarEgresos($id=null,$ini,$fin,$alm,$tin);
			$res=$res->result_array();
		//	$res2=$this->AgregarFActurasResultado($res);
			
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function searchItem()
    {
        if($this->input->is_ajax_request() && $this->input->post('item'))
        {
			$item = $this->security->xss_clean($this->input->post('item'));
			$alm = $this->security->xss_clean($this->input->post('alm'));
        	$dato=$this->Proforma_model->searchItem($item,$alm);        	
			echo json_encode($dato->result_array());
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function searchCliente()
    {
        if($this->input->is_ajax_request() && $this->input->post('search'))
        {
			$search = $this->security->xss_clean($this->input->post('search'));
        	$dato=$this->Proforma_model->searchClientes($search)->result_array();        	
			echo json_encode($dato);
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
	public function getInfoProformaForm()  
	{
		if($this->input->is_ajax_request())
        {
			$datosProforma = new stdclass();
			$datosProforma->tipos = $this->Proforma_model->getTipos(); 
			$datosProforma->almacenes = $this->Proforma_model->getAlmacenes(); 
			$datosProforma->monedas = $this->Proforma_model->getMonedas(); 
			$datosProforma->articulos = $this->Proforma_model->getArticulos(); 

			echo json_encode($datosProforma);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function getArticulo()
    {
        if($this->input->is_ajax_request() && $this->input->post('id'))
        {
			$id = $this->security->xss_clean($this->input->post('id'));
			$alm = $this->security->xss_clean($this->input->post('alm'));
        	$dato=$this->Proforma_model->getArticulo($id,$alm);        	
			echo json_encode($dato);
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

}