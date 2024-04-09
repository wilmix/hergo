<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reportes extends CI_Controller
{
	public $Reportes_model;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Reportes_model");
		$this->load->model("Ingresos_model");
		$this->datos['almacen']=$this->Reportes_model->retornar_almacenes();
		$this->datos['sucursales']=$this->Reportes_model->retornar_sucursales();
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
		
		$spreadsheet->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArray);
		$sheet->setCellValue('A1', 'ID');
		$sheet->setCellValue('B1', 'CODIGO');
		$sheet->setCellValue('C1', 'DESCRIPCIÓN');
		$sheet->setCellValue('D1', 'UNIDAD');
		$sheet->setCellValue('E1', 'CPP');
		$sheet->setCellValue('F1', 'LA PAZ');
		$sheet->setCellValue('G1', 'EL ALTO');
		$sheet->setCellValue('H1', 'POTOSI');
		$sheet->setCellValue('I1', 'SANTA CRUZ');
		$sheet->setCellValue('J1', 'TOTAL');
		$sheet->setCellValue('K1', 'BACKORDER');
		$sheet->setCellValue('L1', 'RECEPCION');
		$sheet->setCellValue('M1', 'ESTADO');
		$sheet->setCellValue('N1', 'IMAGEN');

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
		$spreadsheet->getActiveSheet()->getColumnDimension('L')->setVisible(false);
		$spreadsheet->getActiveSheet()->getColumnDimension('M')->setVisible(false);
		$spreadsheet->getActiveSheet()->getColumnDimension('N')->setVisible(false);
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
        header('Content-Disposition: attachment;filename="'. $filename . ' ' . $fecha .'.xls"'); 
        header('Cache-Control: max-age=0');
        
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
		$writer->save('php://output');  // download file 
 
    }
	public function listaPrecios(){
		$this->accesoCheck(26);
		$this->titles('ListaPrecios','Lista de Precios','Reportes');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/listaPrecios.js') .'?'.rand();
		$this->setView('reportes/listaPrecios');

	}
	public function mostrarListaPrecios()
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
		$this->accesoCheck(27);
		$this->titles('SaldosResumen','Saldos Resumen','Reportes');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/saldosActuales.js') .'?'.rand();
		$this->setView('reportes/saldosActuales');
	}
	public function mostrarSaldos()
	{
		if($this->input->is_ajax_request())
        {
			$res=$this->Reportes_model->mostrarSaldos();
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
		$this->accesoCheck(31);
		$this->titles('CostoItem','Reportes','Estado de Ventas y Costos por Item');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/estadoVentasCosto.js') .'?'.rand();
		////$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
		$this->setView('reportes/estadoVentasCostoItem');
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
		$this->accesoCheck(42);
		$this->titles('CostoItemNew','Reportes','Estado de Ventas y Costos por Item Nuevo');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/estadoVentasCostoNew.js') .'?'.rand();
		////$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
		$this->setView('reportes/estadoVentasCostoItemNew');
	}
	public function pruebaKardex()
	{
		$this->accesoCheck(42);
		$this->titles('PruebaKardex','Reportes','Prueba Kardex');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/pruebaKardex.js') .'?'.rand();
		$this->setView('reportes/pruebaKardex');
	}
	public function mostrarEstadoVentasCostoNew()  
	{
		if($this->input->is_ajax_request())
        {
			$alm=$this->security->xss_clean($this->input->post("alm"));
        	$ini=$this->security->xss_clean($this->input->post("ini")); 
			$fin=$this->security->xss_clean($this->input->post("fin")); 
			$mon=$this->security->xss_clean($this->input->post("mon")); 
			if(empty($alm)) {
				$alm = '1,2,3,4,5,6,7,8,9,10';
			}
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
		$this->accesoCheck(34);
		$this->titles('KardexItems','Reportes','Kardex Individual Itemes Valorado');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/kardexValorado.js') .'?'.rand();
		//$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
		$this->datos['articulos']=$this->Reportes_model->retornarArticulos();
		$this->setView('reportes/kardexIndividualValorado');
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
		$this->accesoCheck(29);
		$this->titles('ResumenLineaMes','Reportes','Resumen de Ventas por Linea y Mes');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/resumenVentasLineaMes.js') .'?'.rand();
		$this->setView('reportes/resumenVentasLineaMes');
	}
	public function mostrarVentasLineaMes()
	{
		if($this->input->is_ajax_request())
        {
        	$inicio=$this->security->xss_clean($this->input->post("inicio"));
        	$fin=$this->security->xss_clean($this->input->post("fin"));
        	$sucursal=$this->security->xss_clean($this->input->post("sucursal"));
			$res=$this->Reportes_model->mostrarVentasLineaMes($inicio, $fin, $sucursal);
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function facturasPendietesPago()
	{
		$this->accesoCheck(28);
		$this->titles('PendientesPago','Facturas Pendientes de Pago','Reportes');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/facturasPendientesPago.js') .'?'.rand();
		//$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");	
		$this->setView('reportes/facturasPendietesPago');
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
		$this->accesoCheck(30);
		$this->titles('NEporFacturar','Reportes','Notas de Entrega por Facturar');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/notasEntregaPorFacturar.js') .'?'.rand();
        //$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
		$this->setView('reportes/notasEntregaPorFacturar');
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
            //$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");				//*******agregar alm********

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
        	$ini=$this->security->xss_clean($this->input->post("ini"));
        	$fin=$this->security->xss_clean($this->input->post("fin"));
			$alm=$this->security->xss_clean($this->input->post("alm"));
			$tipoNota=$this->security->xss_clean($this->input->post("tipoNota"));
			$alm = $alm == 'all' ? '' : $alm;
			$idCliente=$this->security->xss_clean($this->input->post("c"));
			$res=$this->Reportes_model->mostrarNEporFac($ini,$fin,$alm,$idCliente,$tipoNota); 
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
		$this->accesoCheck(31);
		$this->titles('FacturaciónClientes','Reportes','Facturación Clientes');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/facturacionClientes.js') .'?'.rand();
		//$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");	
		$this->setView('reportes/facturacionClientes');
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
		$this->accesoCheck(31);
		$this->titles('DiarioIngresos','Reportes','Diario de Ingresos');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/diarioIngresos.js') .'?'.rand();
		//$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
		$this->datos['tipoingreso']=$this->Reportes_model->retornar_tablaMovimiento("+");
		$this->datos['tipoPrefer']="2";
		$this->setView('reportes/diarioIngresos');
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
			//$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
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
										$articulo->id,
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
										$articulo->id,
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
		$this->accesoCheck(33);
		$this->titles('LibroVentas','Reportes','Libro de Ventas');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/libroVentas.js') .'?'.rand();
		//$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
		$this->setView('reportes/libroVentas');
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
		$this->accesoCheck(40);
		$this->titles('KardexCliente','Reportes','Kardex Individual Cliente');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/kardexIndividualCliente.js') .'?'.rand();
		//$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
		$this->setView('reportes/kardexIndividualCliente');
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
		$this->accesoCheck(41);
		$this->titles('SaldoActualesItems','Saldo Actuales Items','Reportes');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/saldosActualesItems.js') .'?'.rand();
		////$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
		$this->datos['articulos']=$this->Reportes_model->retornarArticulos();
		$this->datos['linea']=$this->Reportes_model->retornar_tabla("linea");
		$this->setView('reportes/saldosActualesItems');
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
		$this->accesoCheck(49);
		$this->titles('ItemClientes','Reportes','Reporte Ventas por Items Clientes');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/ventasClientesItems.js') .'?'.rand();
		$this->datos['linea']=$this->Reportes_model->retornar_tabla("linea");
		$this->setView('reportes/ventasClientesItems');
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
		$this->accesoCheck(51);
		$this->titles('ReporteIngresos','Reporte Ingresos','Reporte Ingresos');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/reporteIngresos.js') .'?'.rand();
		$this->datos['tipoingreso']=$this->Reportes_model->retornar_tablaMovimiento("+");
		$this->datos['tipoPrefer']="2";
		$this->setView('reportes/reporteIngresos');
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
		$this->accesoCheck(52);
		$this->titles('ReporteEgresos','Reporte Egresos','Reporte Egresos');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/reporteEgresos.js') .'?'.rand();
		$this->datos['tipoingreso']=$this->Reportes_model->retornar_tablaMovimiento("-");
		$this->datos['tipoPrefer']="7";
		$this->setView('reportes/reporteEgresos');
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
		$this->accesoCheck(53);
		$this->titles('ReporteFacturas','Reporte Facturas','Reporte Facturas');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/reporteFacturas.js') .'?'.rand();
		$this->setView('reportes/reporteFacturas');
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
		$this->accesoCheck(50);
		$this->titles('ClienteItems','Reportes','Reporte Ventas por Cliente - Items');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/clienteItems.js') .'?'.rand();
		//$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
		$this->setView('reportes/clientesItems');
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
		$this->accesoCheck(47);
		$this->titles('Ventas3M','Reporte Ventas 3M','Reportes');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/ventasTM.js') .'?'.rand();
		$this->setView('reportes/ventasTM');
	}
	public function inventarioTM()
	{
		$this->accesoCheck(48);
		$this->titles('Inventario3M','Reporte Inventario 3M','Reportes');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/inventarioTM.js') .'?'.rand();
		$this->setView('reportes/inventarioTM');
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
        	/* $ini=$this->security->xss_clean($this->input->post("i"));
        	$fin=$this->security->xss_clean($this->input->post("f"));
			$alm=$this->security->xss_clean($this->input->post("a")); */
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
		$this->accesoCheck(54);
		$this->titles('ReportePagos','Reporte Pagos','Reporte Pagos');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/reportPagos.js') .'?'.rand();
		$this->setView('reportes/reportPagoView');
	}
}