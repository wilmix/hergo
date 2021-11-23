<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class ReportKardexCliente extends CI_Controller {
  public function index($cliente,$alm,$ini,$fin,$mon) {
      //CARGAR MODELO
        $alm = $alm == 0 ? '' : $alm;
      $this->load->model('Reportes_model');
      $lineas = $this->Reportes_model->kardexIndividualCliente($cliente,$alm,$ini,$fin,$mon)->result();

      $cliente = $lineas ? utf8_decode(strtoupper($lineas[0]->nombreCliente)) : '';
      $alm = $lineas ? utf8_decode(strtoupper($lineas[0]->almacen)) : '';

     
    //PARAMETROS PARA LA LIBRERIA
    $params = array(
      'alm' => $alm,
      'ini' => $ini,
      'fin' => $fin,
      'cliente' => $cliente,
    );
    $this->load->library('ReportKardexCliente_lib', $params);

      $this->pdf = new ReportKardexCliente_lib($params);
      $this->pdf->AddPage('P','Letter');
      $this->pdf->AliasNbPages();
      $this->pdf->SetAutoPageBreak(true,10); 
      $this->pdf->SetTitle("KARDEX $cliente");
      $this->pdf->SetLeftMargin(10);
      $this->pdf->SetRightMargin(10);
      $this->pdf->SetFont('Arial', '', 7);
      
      $aux = 0;
      $auxNota = 0;
      $auxFac = 0;
      $auxPago = 0;
      foreach ($lineas as $linea) {
          $linea->total = $aux + floatval($linea->saldoNE) + floatval($linea->saldoTotalFactura) - floatval($linea->saldoTotalPago);
          $auxNota = $auxNota + floatval($linea->saldoNE);
          $auxFac = $auxFac + floatval($linea->saldoTotalFactura);
          $auxPago = $auxFac + floatval($linea->saldoTotalPago);

          $this->pdf->Ln(1);
          $this->pdf->SetX(10);
          $this->pdf->SetFillColor(255,255,255);
          $this->pdf->SetFont('Arial', '' , 7);

          $this->filas($linea);
          $aux = $linea->total;
      }
        $this->pdf->SetFont('Arial', 'B' , 7);
        $this->pdf->Ln(2);
        $this->pdf->Cell(110,5,'','BT',0,'R',1);
        $this->pdf->Cell(20,5,number_format($auxNota, 2, ".", ","),'BT',0,'R',1);
        $this->pdf->Cell(20,5,number_format($auxFac, 2, ".", ",") ,'BT',0,'R',1);
        $this->pdf->Cell(20,5,number_format($auxPago, 2, ".", ",") ,'BT',0,'R',1);
        $this->pdf->Cell(20,5,number_format($linea->total, 2, ".", ","),'BT',0,'R',1);
      //guardar
      $this->pdf->Output("KARDEX $cliente.pdf", 'I');
  }
  public function filas($linea){
    $fecha = $linea->fecha ? date('d/m/Y',strtotime($linea->fecha)) : '';
    $notaEntrega = $linea->saldoNE ? number_format($linea->saldoNE, 2, ".", ","): '';
    $factura = $linea->saldoTotalFactura ? number_format($linea->saldoTotalFactura, 2, ".", ","): '';
    $pago = $linea->saldoTotalPago ? number_format($linea->saldoTotalPago, 2, ".", ","): '';
    $total = number_format($linea->total, 2, ".", ",");

    $this->pdf->Cell(15,5,$fecha,'0',0,'C',1);//ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
    $this->pdf->Cell(10,5,$linea->numDocumento,0,0,'C',1);
    //$this->pdf->Cell(10,5,$linea->idalmacen,0,0,'C',1);
    $this->pdf->Cell(85,5,utf8_decode($linea->detalle),0,0,'L',1);
    $this->pdf->Cell(20,5,$notaEntrega,0,0,'R',1);
    $this->pdf->Cell(20,5,$factura,0,0,'R',1);
    $this->pdf->Cell(20,5,$pago,0,0,'R',1);
    $this->pdf->Cell(20,5,$total,0,0,'R',1);
    $this->pdf->Ln(5);
  }
}