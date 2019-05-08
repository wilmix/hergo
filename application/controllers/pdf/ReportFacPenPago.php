<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class ReportFacPenPago extends CI_Controller {
  public function index($alm,$ini,$fin) {
      //CARGAR MODELO
        $alm = $alm == 0 ? '' : $alm;
      $this->load->model('Reportes_model');
      $lineas = $this->Reportes_model->facturasPendientesPago($alm, $ini, $fin);
      $alm = $lineas ? $lineas[0]->almacen : '';
      //print_r($lineas);
      //die();
      
    //PARAMETROS PARA LA LIBRERIA
    $params = array(
      'alm' => $alm,
      'ini' => $ini,
      'fin' => $fin,
    );
    $this->load->library('ReportFacPenPago_lib', $params);

      $this->pdf = new ReportFacPenPago_lib($params);
      $this->pdf->AddPage('P','Letter');
      $this->pdf->AliasNbPages();
      $this->pdf->SetAutoPageBreak(true,10); 
      $this->pdf->SetTitle("FACTURAS PENDIENTES PAGO");
      $this->pdf->SetLeftMargin(10);
      $this->pdf->SetRightMargin(10);
      $this->pdf->SetFont('Arial', '', 7);
      
      $aux = NULL;
      foreach ($lineas as $linea) {
        if ($aux == NULL) {
          $this->pdf->Ln(1);
          $this->pdf->SetX(10);
          $this->pdf->SetFillColor(255,255,255);
          $this->pdf->SetFont('Arial', 'B' , 7);
          $this->pdf->Cell(80,5,utf8_decode($linea->cliente),'0',1,'L',1);
          $this->filas($linea);
        } else {
          $this->filas($linea);
        }
        $aux = $linea->id;
      }
    
      //guardar
      $this->pdf->Output('FACTURAS PENDIENTES PAGO', 'I');
  }

  public function filas($linea){
    $total = number_format($linea->total, 2, ".", ",");
    $montoPagado = number_format($linea->montoPagado, 2, ".", ",");
    $saldo = number_format($linea->saldo, 2, ".", ",");
    $b = 0;
    if ($linea->id == NULL) {
      $this->pdf->SetFont('Arial', 'B', 6);
      $b = 'TB';

    } else {
      $this->pdf->SetFont('Arial', '', 6);
    }
    $this->pdf->Cell(15,5,$linea->fechaFac,$b,0,'C',1);//ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
    $this->pdf->Cell(10,5,$linea->nFactura,$b,0,'C',1);
    //$this->Cell(15,5,utf8_decode('Lote'),0,0,'C',1);
    $this->pdf->Cell(85,5,$linea->cliente,$b,0,'L',1);
    $this->pdf->Cell(20,5,$total,$b,0,'R',1);
    $this->pdf->Cell(20,5,$montoPagado,$b,0,'R',1);
    $this->pdf->Cell(20,5,$saldo,$b,0,'R',1);
    $this->pdf->Cell(25,5,$linea->vendedor,$b,0,'L',1);
    $this->pdf->Ln(7);
  }
}