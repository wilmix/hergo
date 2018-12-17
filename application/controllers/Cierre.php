<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cierre extends CI_Controller
{
	private $datos;
	public function __construct()
	{	
		parent::__construct();
		//$this->load->library('LibAcceso');
		//$this->libacceso->acceso(1);
		$this->load->helper('url');	
		$this->load->model("Cierre_model");
		$this->load->model("Ingresos_model");
		$this->cabeceras_css=array(
                base_url('assets/bootstrap/css/bootstrap.min.css'),
                base_url('assets/plugins/jQueryUI/jquery-ui.min.css'),
				base_url("assets/fa/css/font-awesome.min.css"),
				base_url("assets/dist/css/AdminLTE.min.css"),
				base_url("assets/dist/css/skins/skin-blue.min.css"),
                base_url("assets/hergo/estilos.css"),
                base_url('assets/plugins/steps/css/main.css'),
				base_url('assets/plugins/steps/css/jquery.steps.css'),
				base_url('assets/plugins/table-boot/css/bootstrap-table.css'),
				base_url('assets/plugins/table-boot/plugin/select2.min.css'),				
				base_url('assets/sweetalert/sweetalert2.min.css'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.css'),
				base_url('assets/plugins/daterangepicker/daterangepicker.css')	
		);
		$this->cabecera_script=array(
                base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'),
                base_url('assets/plugins/jQueryUI/jquery-ui.min.js'),
				base_url('assets/plugins/steps/jquery.steps.js'),
				base_url('assets/bootstrap/js/bootstrap.min.js'),
				base_url('assets/dist/js/app.min.js'),
				base_url('assets/plugins/slimscroll/slimscroll.min.js'),
				base_url('assets/plugins/daterangepicker/moment.min.js'),
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
				base_url('assets/plugins/daterangepicker/locale/es.js')
		);
			
			$hoy = date('Y-m-d');
			$tipoCambio = $this->Ingresos_model->getTipoCambio($hoy);
			if ($tipoCambio) {
				$tipoCambio = $tipoCambio->tipocambio;
				$this->datos['tipoCambio'] = $tipoCambio;
			} else {
				$this->datos['tipoCambio'] = 'No se tiene tipo de cambio para la fecha';
			}
		$this->datos['user_id_actual']=$this->session->userdata['user_id'];	
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
		$this->datos['id_Almacen_actual']=$this->session->userdata['datosAlmacen']->idalmacen;

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
			$this->datos['opcion']="Cierre Gestión";
			$this->datos['titulo']="Cierre Gestión";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
            $this->datos['cabeceras_script']= $this->cabecera_script;

			/**************FUNCION***************/
			//$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/cierre.js');



			//print_r($this->datos['almacen']);
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('administracion/configuracion/cierre.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
            $this->load->view('plantilla/footer.php',$this->datos);
            
	}

	public function showPendientes()
	{
		if($this->input->is_ajax_request())
        {
			$gestion = 2018;
        	$res=$this->Cierre_model->showPendientes($gestion);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function showNegativos()
	{
		if($this->input->is_ajax_request())
        {
			$gestion = 2018;
        	$res=$this->Cierre_model->showNegativos();
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function backupDataBase()
	{
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		$this->load->dbutil();

		$prefs = array(     
			'format'      => 'zip',             
			'filename'    => 'hergo_backup.sql'
			);
		
		$backup =& $this->dbutil->backup($prefs); 

		$db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.zip';
		$save = 'pathtobkfolder/'.$db_name;
		
		$this->load->helper('file');
		write_file($save, $backup); 
		
		$this->load->helper('download');
		force_download($db_name, $backup);
	}
	public function generarCierre()
	{
		if($this->input->is_ajax_request())
        {
			$fechaII = $this->security->xss_clean($this->input->post('fecha'));
			$gestionII= date("Y", strtotime($fechaII));
			$invIni = $this->generarInventarioInicial($fechaII);
			$saldos = $this->updateSaldos($gestionII);
			$ret = new stdclass();
			$ret->invIni = $invIni;
			$ret->saldos = $saldos;
			echo json_encode($ret);

		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function generarInventarioInicial($fechaII)
	{
		$idAlmacens = $this->Cierre_model->showIdAlmacenes()->result();
		$res=$idAlmacens;
		$todo = [];
		foreach ($idAlmacens as $value) {
			$ingreso = new stdclass();
			$ingreso->almacen =  $value->id;
			$ingreso->tipomov = 1;
			$ingreso->fechamov = date('Y-m-d',strtotime($fechaII));
			$gestion= date("Y", strtotime($ingreso->fechamov));
			$ingreso->moneda = 1;
			$ingreso->proveedor = 69;

			$tipocambio=$this->Ingresos_model->getTipoCambio($ingreso->fechamov);
			if (!$tipocambio) {
				echo 'No se tiene tipo de cambio para el '.$ingreso->fechamov;
				return false;
			}
			$ingreso->tipoCambio=$tipocambio->tipocambio;

			$ingreso->autor=$this->session->userdata('user_id');
			$ingreso->fecha = date('Y-m-d H:i:s');

			$gestion= date("Y", strtotime($ingreso->fechamov));
			$ingreso->gestion = $gestion;
			$ingreso->obs = 'INVENTARIO INICIAL '.$gestion. ' ' . $value->almacen;
			$ingreso->nmov = $this->Ingresos_model->retornarNumMovimiento($ingreso->tipomov,$gestion,$ingreso->almacen);
			$ingreso->articulos= $this->Cierre_model->itemsSaldos($ingreso->almacen)->result_array();
			//echo json_encode($ingreso);
			if (empty($ingreso->articulos)) {
				$id = '';
				$msj = 'El almacen '.$value->almacen. ' '.'no tiene articulos';
			} else {
				$id = $this->Ingresos_model->storeIngreso($ingreso);
				$msj = 'El Inventrio inicial de '.$value->almacen . ' se generó con éxito.';
			}

			$rta = new stdclass();
			$rta->id = $id;
			$rta->msj = $msj;

			array_push($todo,$rta);
		}

		return ($todo);
	}
	public function updateSaldos($gestion)
	{
		$ingresos = $this->Cierre_model->selectInventarioInicial($gestion)->result();
		$egresos =  $this->Cierre_model->notasEntregaPendientes()->result();

		$saldos = $this->Cierre_model->updateSaldos($ingresos, $egresos, $gestion);
		return $saldos;
	}
}