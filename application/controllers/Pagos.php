<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pagos extends CI_Controller  /////**********nombre controlador
{
	private $datos;
	public function __construct()
	{	
		parent::__construct();
		/*******/
		$this->load->library('LibAcceso');
	
		/*******/
		$this->load->helper('url');	
		$this->load->model("Pagos_model");//*****************aki poner el modelo
		$this->load->model("Egresos_model");
		$this->load->model("Facturacion_model");
		$this->load->model("Ingresos_model");
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
				base_url('assets/plugins/numeral/numeral.min.js'),
				base_url('assets/hergo/NumeroALetras.js'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.js'),				
				
			);
			$this->foot_script=array(				
        		base_url('assets/vue/vue.js'),								
				base_url('assets/vue/vue-resource.min.js'),	
				'https://unpkg.com/vuejs-datepicker',
			);
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
		$this->datos['almacen_actual']=$this->session->userdata['datosAlmacen']->almacen;
		$this->datos['id_Almacen_actual']=$this->session->userdata['datosAlmacen']->idalmacen;
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
	public function index()
	{
		$this->libacceso->acceso(23);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');
		
			$this->datos['menu']="Pagos";
			$this->datos['opcion']="Consultar Pagos";
			$this->datos['titulo']="Consultar Pagos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			$this->datos['foot_script']= $this->foot_script;
			/*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['foot_script'][]=base_url('assets/hergo/pagos.js');
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			/***********************************/
			$this->datos['almacen']=$this->Pagos_model->retornar_tabla("almacenes");


			/***********************************/
			/***********************************/
			/***********************************/
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('pagos/consultarPagos.php',$this->datos); ///*****aki poner la vista
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footerscript.php',$this->datos);
			//$this->load->view('plantilla/footer.php',$this->datos);						
	}
	public function mostrarPagos()  //******cambiar a funcion del modelo
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//FECHA FIN
        	$alm=$this->security->xss_clean($this->input->post("a")); //almacen
			$res=$this->Pagos_model->mostrarPagos($ini,$fin,$alm); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function RecibirPago()
	{
		$this->libacceso->acceso(24);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');
		
			$this->datos['menu']="Pagos";
			$this->datos['opcion']="Recibir Pago";
			$this->datos['titulo']="Recibir Pago";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			$this->datos['foot_script']= $this->foot_script;

			/*************DATERANGEPICKER**********/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/

			/*************AUTOCOMPLETE**********/
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
			
			
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			 /**************EDITABLE***************/
			 $this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-table-editable.js');
			 $this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.css');
			 $this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.js');
			/***********************************/
			$this->datos['foot_script'][]=base_url('assets/hergo/recibirPagos.js'); 

			$this->datos['almacen']=$this->Pagos_model->retornar_tabla("almacenes");
			$this->datos['tipoPago']=$this->Pagos_model->retornar_tabla("tipoPago");
			$this->datos['bancos']=$this->Pagos_model->retornar_tabla("bancos");


			/***********************************/
			/***********************************/
			/***********************************/
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			//$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('pagos/recibirPagos.php',$this->datos); 
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footerscript.php',$this->datos);
	}
	public function editarPago($idPago=0)
	{
		$this->libacceso->acceso(25);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Pagos";
			$this->datos['opcion']="Editar Pago";
			$this->datos['titulo']="Editar Pago";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			$this->datos['foot_script']= $this->foot_script;

			/*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['foot_script'][]=base_url('assets/hergo/recibirPagos.js');
			$this->datos['foot_script'][]=base_url('assets/hergo/editarPago.js');
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			 /**************EDITABLE***************/
			 $this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-table-editable.js');
			 $this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.css');
			 $this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.js');
			/***********************************/
			/*************AUTOCOMPLETE**********/
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');

			$this->datos['almacen']=$this->Pagos_model->retornar_tabla("almacenes");
			$this->datos['tipoPago']=$this->Pagos_model->retornar_tabla("tipoPago");
			$this->datos['bancos']=$this->Pagos_model->retornar_tabla("bancos");
			
			$this->datos['idPago']=$idPago;
			$this->datos['cab']=$this->getPagoCabecera($idPago);
			//echo json_encode ($this->datos['cab']);


			

			/***********************************/
			/***********************************/
			/***********************************/
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			//$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('pagos/recibirPagos.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footerscript.php',$this->datos);
				
	}
	public function getPagoCabecera($id)
	{
		$res = $this->Pagos_model->retornarEdicion($id);
        if($res->num_rows()>0)
    	{
    		$fila=$res->row();
    		return $fila;
    	}
        else
        {
            return(false);
        }
	}
	public function retornarEdicion() {
		if ($this->input->is_ajax_request()) {
			$idPago=$this->security->xss_clean($this->input->post("idPago"));
			$res = new stdclass();
			$res->cabecera=$this->Pagos_model->retornarEdicion($idPago)->row();
			$res->detalle=$this->Pagos_model->retornarEdicionDetalle($idPago)->result_array();
			//$res=$res->array();
			echo json_encode($res);
		} else {
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function mostrarPendientePago()  //******cambiar a funcion del modelo
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//FECHA FIN
        	$alm=$this->security->xss_clean($this->input->post("a")); //almacen
			$res=$this->Pagos_model->mostrarPendientePago($ini,$fin,$alm); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function guardarPagos()
	{
		if($this->input->is_ajax_request())
        {
			$data=$this->security->xss_clean($this->input->post('d'));
			$data=json_decode($data);
			$gestion = date('Y',strtotime($data->fechaPago));
			$numPago=$this->retornarNumPago($data->almacen, $gestion);

			$pago = new stdclass();
			$pago->almacen=$data->almacen;
			$pago->numPago=$numPago;
			$pago->fechaPago=$data->fechaPago;
			$pago->fechaPago = date('Y-m-d',strtotime($pago->fechaPago));
			$pago->gestion= $gestion;
			$pago->moneda=$data->moneda;
			$pago->cliente=$data->cliente;
			$pago->totalPago=$data->totalPago;
			$pago->anulado=$data->anulado;
			$pago->glosa=strtoupper($data->glosa);
			$pago->autor=$this->session->userdata('user_id');
			//$pago->fecha=date('Y-m-d H:i:s');
			$tipocambio = $this->Ingresos_model->getTipoCambio($pago->fechaPago);
			$pago->tipoCambio = $tipocambio->tipocambio;
			$pago->tipoPago=$data->tipoPago;
			$pago->cheque=$data->cheque;
			$pago->banco=$data->banco;
			$pago->transferencia=$data->transferencia;
			$pago->pagos=$data->porPagar;
			
			$idPago=$this->Pagos_model->storePago($pago);
			if ($idPago == false) {
				$return=new stdClass();
				$return->status=false;
				echo json_encode($return);
			} else {
				$return=new stdClass();
				$return->status=200;
				$return->id=$idPago;
				echo json_encode($return);
			}
			
			
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function retornarNumPago($almacen, $gestion)
	{
		$numPago=$this->Pagos_model->obtenerNumPago($almacen, $gestion);
		return $numPago+1;
	}
	public function retornarIdPago($numPago,$almacen)
	{
		$idPago=$this->Pagos_model->obtenerIdPago($numPago,$almacen);
		return $idPago;
	}
	public function retornarDetallePago()
	{		
		$idPago=$this->security->xss_clean($this->input->post('idPago'));				
		$ret=$this->Pagos_model->retornarDetallePago($idPago);
		echo json_encode($ret);
	}
	public function anularPago() {
		if($this->input->is_ajax_request())
        {

			$idPago=$this->security->xss_clean($this->input->post('idPago'));	
			$msj=strtoupper($this->security->xss_clean($this->input->post('msj')));	
			$this->Pagos_model->anularPago($idPago, $msj);
			$facturas=$this->Pagos_model->retornarIdFacturas($idPago);	
			$return=new stdClass();
			$return->facturas=$facturas->result();
			foreach ($return->facturas as $value) {
				$totalPago=$this->Pagos_model->totalPago($value->idFactura);
				$totalPagado = $totalPago->result()[0]->totalPago;
				if ($totalPagado == 0) {
					$pagada = 0;
					$this->Pagos_model->modificarPagadaFactura($pagada,$value->idFactura);
				} else {
					$pagada = 2;
					$this->Pagos_model->modificarPagadaFactura($pagada,$value->idFactura);
				}
			}
			
			$return->status=200;
			$return->idPago=$idPago;
			$return->msj=$msj;
			echo json_encode($return);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function editarPagos()
	{
		if($this->input->is_ajax_request())
        {
			$data=$this->security->xss_clean($this->input->post('datos'));
			$data=json_decode($data);
			$idPago=$data->idPago;
			$gestion = date('Y',strtotime($data->fechaPago));

			$pago = new stdclass();
			$pago->fechaPago=$data->fechaPago;
			$pago->fechaPago = date('Y-m-d',strtotime($pago->fechaPago));
			$pago->moneda=$data->moneda;
			$pago->cliente=$data->cliente;
			$pago->totalPago=$data->totalPago;
			$pago->glosa=strtoupper($data->glosa);
			$pago->autor=$this->session->userdata('user_id');
			//$pago->fecha=date('Y-m-d H:i:s');
			$tipocambio = $this->Ingresos_model->getTipoCambio($pago->fechaPago);
			$pago->tipoCambio = $tipocambio->tipocambio;
			$pago->tipoPago=$data->tipoPago;
			$pago->cheque=$data->cheque;
			$pago->banco=$data->banco;
			$pago->transferencia = $data->transferencia;
			$pago->gestion = $gestion;
			$editarPago = $this->Pagos_model->editarPago($idPago,$pago,$data->porPagar);
			if ($editarPago) {
				$return=new stdClass();
				$return->status=200;
				$return->id = $editarPago;
				echo json_encode($return);
			} else {
				echo json_encode($pago);
				return false;
			}
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}
