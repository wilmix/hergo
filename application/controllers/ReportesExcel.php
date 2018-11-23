<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportesExcel extends CI_Controller
{
	private $datos;
	public function __construct()
	{
		parent::__construct();
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
    function array_push_assoc(array &$arrayDatos, array $values){
        $arrayDatos = array_merge($arrayDatos, $values);
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
    public function estadoVentasCostoItem($alm, $tc)
    {
		$alm = ($alm == 'NN') ? '' : $alm;
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('EstadoCostoVentas');
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
			/*'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
				'rotation' => 90,
				'startColor' => [
					'argb' => 'FFA0A0A0',
				],
				'endColor' => [
					'argb' => 'FFFFFFFF',
				],
			],*/
		];
		
        $spreadsheet->getActiveSheet()->getStyle('A4:N4')->applyFromArray($styleArray);
        $sheet->setCellValue('A4', 'id');
        $sheet->setCellValue('B4', 'SIGLA');
		$sheet->setCellValue('C4', 'LINEA');
		$sheet->setCellValue('D4', 'CODIGO');
		$sheet->setCellValue('E4', 'DESCRIPCIÓN');
		$sheet->setCellValue('F4', 'UNIDAD');
		$sheet->setCellValue('G4', 'C/U');
		$sheet->setCellValue('H4', 'PPV');
		$sheet->setCellValue('I4', 'SALDO');
		$sheet->setCellValue('J4', 'SALDO VALORADO');
        $sheet->setCellValue('K4', 'CANTIDAD VENDIDA');
		$sheet->setCellValue('L4', 'TOTAL COSTO');
		$sheet->setCellValue('M4', 'TOTAL VENTAS');
		$sheet->setCellValue('N4', 'UTILIDAD');
        

		$res=$this->Reportes_model->mostrarEstadoVentasCostoXLS($alm);
        $res=$res->result_array();
        $dataExcel = [];
        foreach ($res as $linea) {
            $x = [];
            if ($linea['codigo'] == '' && $linea['sigla'] == '') {
                $this->array_push_assoc($x, array('idArticulo'=> ''));
                $this->array_push_assoc($x, array('sigla'=> ''));
                $this->array_push_assoc($x, array('linea'=> ''));
                $this->array_push_assoc($x, array('codigo'=> ''));
                $this->array_push_assoc($x, array('descrip'=> 'TOTAL GENERAL'));
                $this->array_push_assoc($x, array('unidad'=> ''));
                $this->array_push_assoc($x, array('costo'=> ''));
                $this->array_push_assoc($x, array('ppVenta'=> ''));
                $this->array_push_assoc($x, array('saldo'=> ''));
                $this->array_push_assoc($x, array('saldoValorado'=> $linea['saldoValorado']));
                $this->array_push_assoc($x, array('cantidadVendida'=> ''));
                $this->array_push_assoc($x, array('totalCosto'=> $linea['totalCosto']));
                $this->array_push_assoc($x, array('totalVentas'=> $linea['totalVentas']));
                $this->array_push_assoc($x, array('utilidad'=> $linea['utilidad']));
            } else if ($linea['codigo'] == '')  {
                $this->array_push_assoc($x, array('idArticulo'=> ''));
                $this->array_push_assoc($x, array('sigla'=> $linea['sigla']));
                $this->array_push_assoc($x, array('linea'=> $linea['linea']));
                $this->array_push_assoc($x, array('codigo'=> ''));
                $this->array_push_assoc($x, array('descrip'=> 'TOTAL '.$linea['linea']));
                $this->array_push_assoc($x, array('unidad'=> ''));
                $this->array_push_assoc($x, array('costo'=> ''));
                $this->array_push_assoc($x, array('ppVenta'=> ''));
                $this->array_push_assoc($x, array('saldo'=> ''));
                $this->array_push_assoc($x, array('saldoValorado'=> $linea['saldoValorado']));
                $this->array_push_assoc($x, array('cantidadVendida'=> ''));
                $this->array_push_assoc($x, array('totalCosto'=> $linea['totalCosto']));
                $this->array_push_assoc($x, array('totalVentas'=> $linea['totalVentas']));
                $this->array_push_assoc($x, array('utilidad'=> $linea['utilidad']));
            } else {
				if ($tc == 'BOB') {
					$this->array_push_assoc($x, array('idArticulo'=> $linea['idArticulo']));
					$this->array_push_assoc($x, array('sigla'=> $linea['sigla']));
					$this->array_push_assoc($x, array('linea'=> $linea['linea']));
					$this->array_push_assoc($x, array('codigo'=> $linea['codigo']));
					$this->array_push_assoc($x, array('descrip'=> $linea['descrip']));
					$this->array_push_assoc($x, array('unidad'=> $linea['unidad']));
					$this->array_push_assoc($x, array('costo'=> $linea['costo']));
					$this->array_push_assoc($x, array('ppVenta'=> $linea['ppVenta']));
					$this->array_push_assoc($x, array('saldo'=> $linea['saldo']));
					$this->array_push_assoc($x, array('saldoValorado'=> $linea['saldoValorado']));
					$this->array_push_assoc($x, array('cantidadVendida'=> $linea['cantidadVendida']));
					$this->array_push_assoc($x, array('totalCosto'=> $linea['totalCosto']));
					$this->array_push_assoc($x, array('totalVentas'=> $linea['totalVentas']));
					$this->array_push_assoc($x, array('utilidad'=> $linea['utilidad']));
				} else {
					$this->array_push_assoc($x, array('idArticulo'=> $linea['idArticulo']));
					$this->array_push_assoc($x, array('sigla'=> $linea['sigla']));
					$this->array_push_assoc($x, array('linea'=> $linea['linea']));
					$this->array_push_assoc($x, array('codigo'=> $linea['codigo']));
					$this->array_push_assoc($x, array('descrip'=> $linea['descrip']));
					$this->array_push_assoc($x, array('unidad'=> $linea['unidad']));
					$this->array_push_assoc($x, array('costo'=> $linea['costo']/$tc));
					$this->array_push_assoc($x, array('ppVenta'=> $linea['ppVenta'] / $tc));
					$this->array_push_assoc($x, array('saldo'=> $linea['saldo'] / $tc));
					$this->array_push_assoc($x, array('saldoValorado'=> $linea['saldoValorado'] / $tc));
					$this->array_push_assoc($x, array('cantidadVendida'=> $linea['cantidadVendida'] / $tc));
					$this->array_push_assoc($x, array('totalCosto'=> $linea['totalCosto'] / $tc));
					$this->array_push_assoc($x, array('totalVentas'=> $linea['totalVentas'] / $tc));
					$this->array_push_assoc($x, array('utilidad'=> $linea['utilidad'] / $tc));
				}
            }
            array_push($dataExcel, $x);
        }

        if($alm == 1){
            $alm = 'CENTRAL HERGO';
        } else if ($alm == 2) {
            $alm = 'DEPOSITO ALTO';
        } else if ($alm == 3) {
            $alm = 'POTOSI';
        } else if ($alm == 4) {
            $alm = 'SANTA CRUZ';
        } else if ($alm == '') {
            $alm = 'TODOS';
        } 
        
        //echo '<pre>'; print_r($dataExcel); echo '</pre>';
        //return false;
		$spreadsheet->getActiveSheet()
		->fromArray(
			$dataExcel,  // The data to set
			NULL,        // Array values with this value will not be set
			'A5'         // Top left coordinate of the worksheet range where
						//    we want to set these values (default is A1)
		);
        
		$writer = new Xlsx($spreadsheet);
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setVisible(false);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setVisible(false);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(60);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);

        foreach(range('G','N') as $columnID)
        {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setWidth(12);
        }
		//$spreadsheet->getActiveSheet()->getColumnDimension('G:N')->setWidth(20);
		$filename = 'estadoVentasCostoItem';
        $fecha = date('d-m-Y');
		$spreadsheet->getActiveSheet()->getStyle('G1:N5000')->getNumberFormat()->setFormatCode('#,##0.00');
        $spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(40);
        $spreadsheet->getActiveSheet()->mergeCells('A1:M1');
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'ESTADO DE VENTAS Y COSTO POR ITEM');
        $spreadsheet->getActiveSheet()->mergeCells('A2:M2');
        $spreadsheet->getActiveSheet()->setCellValue('A2', $alm);
		$spreadsheet->getActiveSheet()->mergeCells('A3:M3');
		$moneda = ($tc == 'BOB') ? 'BOLIVIANOS' : 'DOLARES';
		$spreadsheet->getActiveSheet()->setCellValue('A3', $moneda);
		$spreadsheet->getActiveSheet()->setCellValue('N3', $fecha);
		$spreadsheet->getActiveSheet()->setCellValue('N2', 'TC: ' . $tc);
        $spreadsheet->getActiveSheet()->getStyle('A1:C3')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('B4:N4')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('B4:N4')
        ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1');
        $spreadsheet->getActiveSheet()->freezePane('B5');
		$spreadsheet->getActiveSheet()->setAutoFilter('A4:N4');

        
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename . ' ' . $alm .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
 
    }

}