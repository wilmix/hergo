<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reportes extends CI_Controller
{
	private $datos;
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logeado'))
		redirect('auth', 'refresh');
		/*******/
		$this->load->library('LibAcceso');
	
		/*******/
		$this->load->helper('url');
		$this->load->model("Reportes_model");
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
					base_url('assets/plugins/table-boot/plugin/FileSaver.min.js'),
					base_url('assets/plugins/table-boot/js/tableExport.js'),
					base_url('assets/plugins/table-boot/js/xlsx.core.min.js'),
					base_url('assets/plugins/table-boot/js/bootstrap-table-filter.js'),
					base_url('assets/plugins/table-boot/plugin/select2.min.js'),
					base_url('assets/plugins/table-boot/plugin/bootstrap-table-select2-filter.js'),
					base_url('assets/plugins/table-boot/plugin/bootstrap-table-group-by.js'),
					base_url('assets/plugins/table-boot/js/xlsx.full.min.js'),
					base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.js'),
					base_url('assets/plugins/daterangepicker/moment.min.js'),
					base_url('assets/plugins/slimscroll/slimscroll.min.js'),        		
					base_url('assets/sweetalert/sweetalert2.min.js'),
			);
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
		$this->datos['almacen_actual']=$this->session->userdata['datosAlmacen']->almacen;
		$this->datos['id_Almacen_actual']=$this->session->userdata['datosAlmacen']->idalmacen;
		$this->datos['user_id_actual']=$this->session->userdata['user_id'];
		$grupsOfUser=$this->ion_auth->get_users_groups($this->datos['user_id_actual'])->result_array();
		foreach ($grupsOfUser as $value) {
			$this->datos['grupsOfUser'] = $value['name'] == 'Nacional' ? 'Nacional' : false;
		};
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

	public function saldosExcel()
    {       
		$spreadsheet = new Spreadsheet();
		
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Saldos');
		$styleArray = [
			'font' => [
				'bold' => true,
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'top' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
				'rotation' => 90,
				'startColor' => [
					'argb' => 'FFA0A0A0',
				],
				'endColor' => [
					'argb' => 'FFFFFFFF',
				],
			],
		];
		
		$spreadsheet->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);
		$sheet->setCellValue('A1', 'ID');
		$sheet->setCellValue('B1', 'CODIGO');
		$sheet->setCellValue('C1', 'DESCRIPCIÓN');
		$sheet->setCellValue('D1', 'UNIDAD');
		$sheet->setCellValue('E1', 'LA PAZ');
		$sheet->setCellValue('F1', 'EL ALTO');
		$sheet->setCellValue('G1', 'POTOSI');
		$sheet->setCellValue('H1', 'SANTA CRUZ');
		$sheet->setCellValue('I1', 'TOTAL');

		$res=$this->Reportes_model->mostrarSaldos(); 
		$res=$res->result_array();
		//echo '<pre>'; print_r($res); echo '</pre>';
		$spreadsheet->getActiveSheet()
		->fromArray(
			$res,  // The data to set
			NULL,        // Array values with this value will not be set
			'A2'         // Top left coordinate of the worksheet range where
						//    we want to set these values (default is A1)
		);
        
		$writer = new Xlsx($spreadsheet);
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setVisible(false);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
		$spreadsheet->getActiveSheet()->getStyle('E1:I3000')->getNumberFormat()->setFormatCode('#,##0.00');
		$spreadsheet->getActiveSheet()->getStyle('A1');

		$spreadsheet->getActiveSheet()->setAutoFilter(
			$spreadsheet->getActiveSheet()
				->calculateWorksheetDimension()
		);
		
		$filename = 'saldosArticulos';
		$fecha = date('d-m-Y');
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename . ' ' . $fecha .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
 
    }

	public function listaPrecios(){
		$this->libacceso->acceso(26);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Lista de Precios";
			$this->datos['opcion']="Reportes";
			$this->datos['titulo']="Lista de Precios";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
			
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/listaPrecios.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-table-group-by.css');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-table-group-by.js');

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/listaPrecios.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarListaPrecios()  //******cambiar a funcion del modelo
	{
		if($this->input->is_ajax_request())
        {
			$res=$this->Reportes_model->mostrarListaPrecios(); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function saldosActuales()
	{
		$this->libacceso->acceso(27);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Saldos Actuales";
			$this->datos['opcion']="Reportes";
			$this->datos['titulo']="Saldos Actuales";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/saldosActuales.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            //$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");				//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/saldosActuales.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarSaldos()  //******cambiar a funcion del modelo
	{
		if($this->input->is_ajax_request())
        {
			$res=$this->Reportes_model->mostrarSaldos(); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}


	public function estadoVentasCostoItem()
	{
		$this->libacceso->acceso(42);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Estado de Ventas y Costos por Item";
			$this->datos['titulo']="Estado de Ventas y Costos por Item";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/estadoVentasCosto.js'); 				
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
        	$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/estadoVentasCostoItem.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarEstadoVentasCosto()  
	{
		if($this->input->is_ajax_request())
        {
			$alm=$this->security->xss_clean($this->input->post("alm"));
        	$ini=$this->security->xss_clean($this->input->post("ini")); 
			$fin=$this->security->xss_clean($this->input->post("fin")); 
			$mon=$this->security->xss_clean($this->input->post("mon")); 
			$res=$this->Reportes_model->mostrarEstadoVentasCosto($alm,$ini,$fin,$mon); 
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

	public function estadoVentasCostoItemNew()
	{
		$this->libacceso->acceso(42);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Estado de Ventas y Costos por Item New";
			$this->datos['titulo']="Estado de Ventas y Costos por Item New";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/estadoVentasCostoNew.js'); 				
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
        	$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/estadoVentasCostoItemNew.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function pruebaKardex()
	{
		$this->libacceso->acceso(42);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Prueba Kardex";
			$this->datos['titulo']="Prueba Kardex";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/pruebaKardex.js'); 				
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
        	$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/pruebaKardex.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarEstadoVentasCostoNew()  
	{
		if($this->input->is_ajax_request())
        {
			$alm=$this->security->xss_clean($this->input->post("alm"));
        	$ini=$this->security->xss_clean($this->input->post("ini")); 
			$fin=$this->security->xss_clean($this->input->post("fin")); 
			$mon=$this->security->xss_clean($this->input->post("mon")); 
			$res=$this->Reportes_model->showEstadoVentasCostoNew($alm,$ini,$fin,$mon); 
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

	public function kardexIndividualValorado()
	{
		$this->libacceso->acceso(34);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Kardex Individual Itemes Valorado";
			$this->datos['titulo']="Kardex Individual Itemes Valorado";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/kardexValorado.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
			$this->datos['articulos']=$this->Reportes_model->retornarArticulos();
			/*echo '<pre>';
			print_r($this->Reportes_model->retornarArticulos()->result());
			echo '</pre>';*/

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/kardexIndividualValorado.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarArticulos() 
	{
		if($this->input->is_ajax_request())
        {
			$res=$this->Reportes_model->retornarArticulos(); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function kardexIndividual()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Kardex Individual";
			$this->datos['titulo']="Kardex Individual";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			//$this->datos['cabeceras_script'][]=base_url('assets/hergo/egresos.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            //$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");				//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/kardexIndividual.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}

	public function resumenVentasLineaMes()
	{
		$this->libacceso->acceso(29);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Resumen de Ventas por Linea y Mes";
			$this->datos['titulo']="Resumen de Ventas por Linea y Mes";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/resumenVentasLineaMes.js');				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            $this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");					//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/resumenVentasLineaMes.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarVentasLineaMes()  //******cambiar a funcion del modelo
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//FECHA FIN
        	$alm=$this->security->xss_clean($this->input->post("a")); //almacen
			$res=$this->Reportes_model->mostrarVentasLineaMes($ini,$fin,$alm); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function facturasPendietesPago()
	{
		$this->libacceso->acceso(28);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Facturas Pendientes de Pago";
			$this->datos['titulo']="Facturas Pendientes de Pago";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/facturasPendientesPago.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");	

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/facturasPendietesPago.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarFacturasPendientesPago() 
	{
		if($this->input->is_ajax_request())
        {
			$almacen=$this->security->xss_clean($this->input->post('almacen')); 
			$ini=$this->security->xss_clean($this->input->post('ini')); 
			$fin=$this->security->xss_clean($this->input->post('fin')); 
			$res=$this->Reportes_model->facturasPendientesPago($almacen, $ini, $fin); 
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function notasEntregaPorFacturar()
	{
		$this->libacceso->acceso(30);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Notas de Entrega por Facturar";
			$this->datos['opcion']="Reportes";
			$this->datos['titulo']="Notas de Entrega por Facturar";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/notasEntregaPorFacturar.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            $this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");				//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/notasEntregaPorFacturar.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function notasEntregaPorFacturarNew()
	{
		$this->libacceso->acceso(30);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Notas de Entrega por Facturar";
			$this->datos['opcion']="Reportes";
			$this->datos['titulo']="Notas de Entrega por Facturar";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/notasEntregaPorFacturarNew.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/searchClient.js'); 				//*******agregar js********
			 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            $this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");				//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/notasEntregaPorFacturarNew.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarNEporFac()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));
        	$fin=$this->security->xss_clean($this->input->post("f"));
			$alm=$this->security->xss_clean($this->input->post("a"));
			$alm = $alm == 'all' ? '' : $alm;
			$idCliente=$this->security->xss_clean($this->input->post("c"));
			$res=$this->Reportes_model->mostrarNEporFac($ini,$fin,$alm,$idCliente); 
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function mostrarNEporFacServer()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));
        	$fin=$this->security->xss_clean($this->input->post("f"));
			$alm=$this->security->xss_clean($this->input->post("a"));
			$alm = $alm == 'all' ? '' : $alm;
			$idCliente=$this->security->xss_clean($this->input->post("c"));

			$draw = intval($this->input->post("draw"));
			$start = intval($this->input->post("start"));
			$length = intval($this->input->post("length"));
			$order = $this->input->post("order");
			$search= $this->input->post("search");
			$search = $search['value'];

			$col = 0;
			$dir = "";
			if(!empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
			$dir = "desc";
			}

			$valid_columns = array(
												0=>'cliente',
												1=>'n',
												2=>'idEgresos',
												3=>'sigla',
												4=>'fechamov',
												5=>'nombreCliente',
												6=>'total',
												7=>'estado',
												8=>'fecha',
												9=>'autor',
												11=>'almacen',
												12=>'monedaSigla',
												13=>'totalDol',
									);

		
			$res=$this->Reportes_model->mostrarNEporFac($ini,$fin,$alm,$idCliente); 
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function facturacionClientes()
	{
		$this->libacceso->acceso(31);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Facturación Clientes";
			$this->datos['opcion']="Reportes";
			$this->datos['titulo']="Facturación Clientes";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/facturacionClientes.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            $this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");					//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/facturacionClientes.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarFacturacionClientes()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//FECHA FIN
        	$alm=$this->security->xss_clean($this->input->post("a")); //almacen
			$res=$this->Reportes_model->mostrarFacturacionClientes($ini,$fin,$alm); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function movimientosClientes()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Movimientos Item Clientes";
			$this->datos['titulo']="Movimientos Item Clientes";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			//$this->datos['cabeceras_script'][]=base_url('assets/hergo/egresos.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            //$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");				//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/movimientosClientes.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function resumenVentaCliente()
	{
		
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Resumen de Ventas por Cliente";
			$this->datos['titulo']="Resumen de Ventas por Cliente";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			//$this->datos['cabeceras_script'][]=base_url('assets/hergo/egresos.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            //$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");				//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/resumenVentaCliente.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function diarioIngresos()
	{
		$this->libacceso->acceso(32);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Diario de Ingresos";
			$this->datos['titulo']="Diario de Ingresos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/diarioIngresos.js');
			//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
			//$this->datos['tingreso']=$this->Reportes_model->retornar_tablaMovimiento("+");
			$this->datos['tipoingreso']=$this->Reportes_model->retornar_tablaMovimiento("+");

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/diarioIngresos.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarDiarioIngresos()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//FECHA FIN
			$alm=$this->security->xss_clean($this->input->post("a")); //almacen
			$ti=$this->security->xss_clean($this->input->post("ti"));//tipo de ingreso
			$res=$this->Reportes_model->mostrarDiarioIngresos($ini,$fin,$alm,$ti); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
		
	}
	public function kardexAll()
	{
		//$this->libacceso->acceso(32);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Kardex All";
			$this->datos['titulo']="Kardex All";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			 $this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/showAll.js');

			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
			//$this->datos['tingreso']=$this->Reportes_model->retornar_tablaMovimiento("+");
			$this->datos['tipoingreso']=$this->Reportes_model->retornar_tablaMovimiento("+");

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/showKardexAll.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function showKardexAll()  
	{

			$res=$this->Reportes_model->showKardexAll(); 
			$res=$res->result();
			//unset($res[0]);
			$saldoTotal = 0;
			$aux = 0;
			$cost = 0;
			$idArticulo = 0;
			$items = array();
			$titulo = new stdClass();
			$titulo->titulo = 'titulo';
			$cpp = 0;
			foreach ($res as $line) {
				if ($line->id == null) {
					$idArticulo = $line->idArticulo;
					$line->saldo = $line->ing - $line->fac - $line->ne - $line->tr;
					$line->nombreproveedor = 'TOTAL';
					$line->saldoTotal = 0;
					$saldoTotal = 0;
					$aux = 0;
					$cpp = 0;
				} else {
					$idArticulo = $line->idArticulo;
					$line->saldo = $line->ing - $line->fac - $line->ne - $line->tr + $aux;
					$line->out = $line->fac + $line->ne + $line->tr;
					$aux = $line->saldo;
					if ($line->ing > 0) {
						$line->saldoTotal = $saldoTotal + ($line->punitario * $line->ing);
						$saldoTotal = $line->saldoTotal;
						$cpp = $aux == 0 ? 0: $saldoTotal / $aux;
						$line->cpp = $cpp;
					} else {
						$line->saldoTotal = $saldoTotal - ($cpp * $line->out);
						$saldoTotal = $line->saldoTotal;
						$cpp = $aux == 0 ? 0: $saldoTotal / $aux;
						$line->cpp = $cpp;
					}


				}
				
			}
			echo json_encode($res);
		
	}
	public function showKardexIndividual()  
	{
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		if($this->input->is_ajax_request())
        {
			$alm=$this->security->xss_clean($this->input->post("alm"));
			$art=$this->input->post("art");
			$lenght = count($art);
			$kardex = [];
			if ($lenght < 3) {
				if ($lenght == 1) {
					$a = $art[0];
					$b = $a;
				} else {
					$a = $art[0];
					$b = $art[1];
				}
				$articulos = $this->Reportes_model->getArticulosID($a, $b)->result();
				foreach ($articulos as $articulo) {
					$arr = [];
					$articuloKardex = $this->Reportes_model->mostrarKardexIndividual($articulo->id,$alm);
					$articuloKardex->next_result();
					$articuloKardex->result();
					array_push($arr, 	$articulo->codigo, 
										$articulo->descrip, 
										$articulo->unidad,
										//$articulo->marca,
										//$articulo->linea,
										$articuloKardex->result_object);
					array_push($kardex, $arr);
				}
				echo json_encode($kardex);
			} else if ($lenght > 2){
				$articulos = [];
				$codigos = $art;
				foreach ($codigos as $codigo) {
					$datos = $this->Reportes_model->getArticulosID($codigo, $codigo)->row();
					array_push($articulos, $datos);  
				}
				foreach ($articulos as $articulo) {
					$arr = [];
					$articuloKardex = $this->Reportes_model->mostrarKardexIndividual($articulo->id,$alm);
					$articuloKardex->next_result();
					$articuloKardex->result();
					array_push($arr, 	$articulo->codigo, 
										$articulo->descrip, 
										$articulo->unidad,
										//$articulo->marca,
										//$articulo->linea,
										$articuloKardex->result_object);
					array_push($kardex, $arr);
				}
				echo json_encode($kardex);
			}
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
		
	}

	public function diarioTraspasos()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Diario de Traspasos";
			$this->datos['titulo']="Diario de Traspasos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			//$this->datos['cabeceras_script'][]=base_url('assets/hergo/egresos.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            //$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");				//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/diarioTraspasos.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function diarioPagos()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Diario de Pagos";
			$this->datos['titulo']="Diario de Pagos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			//$this->datos['cabeceras_script'][]=base_url('assets/hergo/egresos.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            //$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");				//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/diarioPagos.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function resumenProductosUnidades()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Resumen de Productos en Unidades";
			$this->datos['titulo']="Resumen de Productos en Unidades";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			//$this->datos['cabeceras_script'][]=base_url('assets/hergo/egresos.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            //$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");				//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/resumenProductosUnidades.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function libroVentas()
	{
		$this->libacceso->acceso(33);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Libro de Ventas";
			$this->datos['titulo']="Libro de Ventas";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/libroVentas.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            $this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");					//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/libroVentas.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarLibroVentas()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//FECHA FIN
        	$alm=$this->security->xss_clean($this->input->post("a")); //almacen
			$res=$this->Reportes_model->mostrarLibroVentas($ini,$fin,$alm); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function mostrarLibroVentasTotales()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//FECHA FIN
        	$alm=$this->security->xss_clean($this->input->post("a")); //almacen
			$res=$this->Reportes_model->mostrarLibroVentasTotales($ini,$fin,$alm); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function kardexIndividualCliente()
	{
		$this->libacceso->acceso(40);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Kardex Individual Cliente";
			$this->datos['titulo']="Kardex Individual Cliente";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/kardexIndividualCliente.js');
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
			//$this->datos['clientes']=$this->Reportes_model->retornarClientes3();

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/kardexIndividualCliente.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarKardexIndividualCliente()  
	{
		if($this->input->is_ajax_request())
        {
			$almacen=$this->security->xss_clean($this->input->post("almacen"));
			$cliente=$this->security->xss_clean($this->input->post("cliente"));
			$ini=$this->security->xss_clean($this->input->post("ini"));
			$fin=$this->security->xss_clean($this->input->post("fin"));
			$mon=$this->security->xss_clean($this->input->post("mon"));
			$res=$this->Reportes_model->kardexIndividualCliente($cliente,$almacen,$ini,$fin,$mon);
			$res=$res->result();
				$aux = 0;
			foreach ($res as $linea) {
				$linea->total = $aux + floatval($linea->saldoNE) + floatval($linea->saldoTotalFactura) - floatval($linea->saldoTotalPago);
				$aux = $linea->total;
			}
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
		
	}
	public function saldosActualesItems()
	{
		$this->libacceso->acceso(41);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Saldo Actuales Items";
			$this->datos['titulo']="Saldo Actuales Items";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/saldosActualesItems.js'); 				
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
			$this->datos['articulos']=$this->Reportes_model->retornarArticulos();
			$this->datos['linea']=$this->Reportes_model->retornar_tabla("linea");
			/*echo '<pre>';
			print_r($this->Reportes_model->retornarArticulos()->result());
			echo '</pre>';*/

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/saldosActualesItems.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarSaldosActualesItems() 
	{
		if($this->input->is_ajax_request())
        {
			$alm=$this->security->xss_clean($this->input->post("alm"));
			$res=$this->Reportes_model->mostrarSaldosActualesItems($alm);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function ventasClientesItems()
	{
		$this->libacceso->acceso(49);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Reporte Ventas por Items Clientes";
			$this->datos['titulo']="Reporte Ventas por Items Clientes";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/ventasClientesItems.js'); 				
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
			//$this->datos['articulos']=$this->Reportes_model->retornarArticulos();
			$this->datos['linea']=$this->Reportes_model->retornar_tabla("linea");
			/*echo '<pre>';
			print_r($this->Reportes_model->retornarArticulos()->result());
			echo '</pre>';*/

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/ventasClientesItems.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarVentasClientesItems() 
	{
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		if($this->input->is_ajax_request())
        {
			$ini=$this->security->xss_clean($this->input->post("ini"));
			$fin=$this->security->xss_clean($this->input->post("fin"));
			$alm=$this->security->xss_clean($this->input->post("alm")); 
			$res=$this->Reportes_model->mostrarVentasClientesItems($ini, $fin, $alm);
			$res=$res->result_array();
			
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

	public function reporteIngresos()
	{
		$this->libacceso->acceso(51);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Reporte Ingresos";
			$this->datos['titulo']="Reporte Ingresos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/reporteIngresos.js'); 				
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
			//$this->datos['linea']=$this->Reportes_model->retornar_tabla("linea");
			$this->datos['tipoingreso']=$this->Reportes_model->retornar_tablaMovimiento("+");
			/*echo '<pre>';
			print_r($this->Reportes_model->retornarArticulos()->result());
			echo '</pre>';*/

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/reporteIngresos.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarReporteIngreso()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//FECHA FIN
			$alm=$this->security->xss_clean($this->input->post("a")); //almacen
			$ti=$this->security->xss_clean($this->input->post("ti"));//tipo de ingreso
			$res=$this->Reportes_model->mostrarReporteIngreso($ini,$fin,$alm,$ti); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			//$res=$ini.' '.$fin.' '.$alm.' '.$ti;
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
		
	}
	public function reporteEgresos()
	{
		$this->libacceso->acceso(52);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Reporte Egresos";
			$this->datos['titulo']="Reporte Egresos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/reporteEgresos.js'); 				
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");

			$this->datos['tipoingreso']=$this->Reportes_model->retornar_tablaMovimiento("-");

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/reporteEgresos.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarReporteEgresos()
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));
        	$fin=$this->security->xss_clean($this->input->post("f"));
			$alm=$this->security->xss_clean($this->input->post("a"));
			$ti=$this->security->xss_clean($this->input->post("ti"));
			$res=$this->Reportes_model->mostrarReporteEgreso($ini,$fin,$alm,$ti);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
		
	}
	public function reporteFacturas()
	{
		$this->libacceso->acceso(53);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Reporte Facturas";
			$this->datos['titulo']="Reporte Facturas";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/reporteFacturas.js'); 				
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/reporteFacturas.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarReporteFacturas()
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));
        	$fin=$this->security->xss_clean($this->input->post("f"));
			$alm=$this->security->xss_clean($this->input->post("a"));
			$res=$this->Reportes_model->mostrarReporteFacturas($ini,$fin,$alm);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
		
	}

	public function reporteClienteItems()
	{
		$this->libacceso->acceso(50);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Reporte Ventas por Cliente - Items";
			$this->datos['titulo']="Reporte Ventas por Cliente - Items";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/clienteItems.js'); 				
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/clientesItems.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function showClienteItems()
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));
        	$fin=$this->security->xss_clean($this->input->post("f"));
			$alm=$this->security->xss_clean($this->input->post("a"));
			$res=$this->Reportes_model->showClienteItems($ini,$fin,$alm);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
		
	}
	public function ventasTM()
	{
		$this->libacceso->acceso(47);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Reporte Ventas 3M";
			$this->datos['titulo']="Reporte Ventas 3M";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/ventasTM.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            $this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");					//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/ventasTM.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function inventarioTM()
	{
		$this->libacceso->acceso(48);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Reporte Inventario 3M";
			$this->datos['titulo']="Reporte Inventario 3M";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/inventarioTM.js'); 				//*******agregar js********
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
            $this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");					//*******agregar alm********

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/inventarioTM.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function showVentasTM()
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));
        	$fin=$this->security->xss_clean($this->input->post("f"));
			$alm=$this->security->xss_clean($this->input->post("a"));
			$res=$this->Reportes_model->showVentasTM($ini,$fin,$alm);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
		
	}
	public function showInventarioTM()
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));
        	$fin=$this->security->xss_clean($this->input->post("f"));
			$alm=$this->security->xss_clean($this->input->post("a"));
			$res=$this->Reportes_model->showInventarioTM();
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
		
	}
	public function showReportPagos()
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));
        	$end=$this->security->xss_clean($this->input->post("e"));
			$alm=$this->security->xss_clean($this->input->post("a"));
			$res=$this->Reportes_model->reportPagos($ini,$end,$alm);
			$res=$res->result();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
		
	}
	public function reportePagos()
	{
		$this->libacceso->acceso(54);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Reportes";
			$this->datos['opcion']="Reporte Pagos";
			$this->datos['titulo']="Reporte Pagos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/reportes/reportPagos.js'); 				
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('reportes/reportPagoView.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
}