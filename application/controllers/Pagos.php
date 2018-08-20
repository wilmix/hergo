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
			$this->datos['foot_script'][]=base_url('assets/hergo/pagos.js'); //aki poner el nuevo javascript
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
			
			$this->datos['foot_script'][]=base_url('assets/hergo/recibirPagos.js'); //aki poner el nuevo javascript
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			 /**************EDITABLE***************/
			 $this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-table-editable.js');
			 $this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.css');
			 $this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.js');
			/***********************************/

			$this->datos['almacen']=$this->Pagos_model->retornar_tabla("almacenes");
			$this->datos['tipoPago']=$this->Pagos_model->retornar_tabla("tipoPago");
			$this->datos['bancos']=$this->Pagos_model->retornar_tabla("bancos");


			/***********************************/
			/***********************************/
			/***********************************/
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('pagos/recibirPagos.php',$this->datos); ///*****aki poner la vista
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footerscript.php',$this->datos);
			//$this->load->view('plantilla/footer.php',$this->datos);						
	}
	public function editarPago($idPago=0)
	{
		/**********************************************************************/
		//verificar si el num de pago corresponde al almacen del usuario logueado !!!!!
		/**********************************************************************/
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

			$this->datos['almacen']=$this->Pagos_model->retornar_tabla("almacenes");
			$this->datos['tipoPago']=$this->Pagos_model->retornar_tabla("tipoPago");
			$this->datos['bancos']=$this->Pagos_model->retornar_tabla("bancos");
			
			//$this->datos['idPago']=$idPago;

			/***********************************/
			/***********************************/
			/***********************************/
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('pagos/recibirPagos.php',$this->datos); ///*****aki poner la vista
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footerscript.php',$this->datos);
			//$this->load->view('plantilla/footer.php',$this->datos);						
	}
	public function retornarEdicion()  //******cambiar a funcion del modelo
	{
		if($this->input->is_ajax_request())
        {
        	$idPago=$this->security->xss_clean($this->input->post("idPago"));//fecha inicio
        
			//$res=$this->Pagos_model->retornarEdicion($n);
			//$res=$res->result_array();
			echo $idPago;
			//echo json_encode($res);
		}
		else
		{
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
			
			//deberia llegar por lo menos 1 registro
			$numPago=$this->retornarNumPago($data->almacen);
			
			$pago = new stdclass();
			$pago->almacen=$data->almacen;
			$pago->numPago=$numPago;
			$pago->fechaPago=$data->fechaPago;
			$pago->moneda=$data->moneda;
			$pago->cliente=$data->cliente;
			$pago->totalPago=$data->totalPago;
			$pago->anulado=$data->anulado;
			$pago->glosa=$data->glosa;
			$pago->autor=$this->session->userdata('user_id');
			$pago->fecha=date('Y-m-d H:i:s');
			$pago->tipoCambio=$this->Egresos_model->retornarTipoCambio();
			$pago->tipoPago=$data->tipoPago;
			$pago->cheque=$data->cheque;
			$pago->banco=$data->banco;
			$pago->transferencia=$data->transferencia;
			$pago->pagos=$data->porPagar;
			$this->Pagos_model->guardarPago($pago);
			$pago->idPago=$this->retornarIdPago($pago->numPago,$pago->almacen);
			//guardar detalle
			$pagosFactura = array();
			foreach ($pago->pagos as $fila) {
				$pagos=new stdclass();
				$pagos->idPago=$pago->numPago;
				$pagos->idFactura=$fila->idFactura;
				$pagos->monto=$fila->pagar;		
				$pagos->saldoNuevo=$fila->saldoNuevo;	
				array_push($pagosFactura,$pagos);	
				$this->Facturacion_model->actualizar_estadoPagoFactura($fila->idFactura,$fila->saldoNuevo,$fila->saldoPago);
			}
			//echo '<pre>';	print_r("id ".$fila->idFactura."saldoNuevo ".$fila->saldoNuevo."saldoPago ".$fila->saldoPago); echo '</pre>';	        	        	
			$this->Pagos_model->guardarPago_Factura($pagosFactura);
			$return=new stdClass();
			$return->status=200;
			echo json_encode($return);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function retornarNumPago($almacen)
	{
		$numPago=$this->Pagos_model->obtenerNumPago($almacen);
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
			$this->Pagos_model->anularPago($idPago);
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
			echo json_encode($return);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	
}
