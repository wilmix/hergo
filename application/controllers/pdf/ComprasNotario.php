<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class ComprasNotario extends CI_Controller {
  public function index($month, $year) {
    //CARGAR MODELO
    $this->load->model('pdf/ReportePDF_model');
    $compras = $this->ReportePDF_model->comprasPdf($month, $year);
    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $mes = $meses[($month)-1];
     //PARAMETROS PARA LA LIBRERIA
    $params = array($mes,$year);
    $this->load->library('pdf/Compras_lib', $params);

      $this->pdf = new Compras_lib($params);
      $this->pdf->AddPage('L','Letter');
      $this->pdf->AliasNbPages();
      $this->pdf->SetTitle('ComprasNotario');
      $this->pdf->SetLeftMargin(5);
      $this->pdf->SetRightMargin(5);
      $this->pdf->SetFont('Arial', '', 6);
      $this->pdf->SetAutoPageBreak(true,10);
      
      $n = 1;
      $c = 0;
      $b = 0;
      $alto = 3;
      $total=0;
      $totalNoSujeto=0;
      $totalSubTotal=0;
      $totalDescuento=0;
      $totalBase=0;
      $totalCredito=0;
        $subTotal=0;
        $subNoSujeto =0;
        $subSubtotal = 0;
        $subDescuento = 0;
        $subBase = 0;
        $subCredito =0;
      //var_dump(end($compras));
      //die();
      foreach ($compras as $compra) {
        $this->pdf->SetX(5);
        $this->pdf->SetFillColor(255,255,255);
            $c++;
            $total+=$compra->total;
            $totalNoSujeto+=$compra->noSujetoCF;
            $totalSubTotal+=$compra->subtotal;
            $totalDescuento+=$compra->descuento;
            $totalBase+=$compra->base;
            $totalCredito+=$compra->credito;
              $subTotal+= $compra->total;
              $subNoSujeto += $compra->noSujetoCF;
              $subSubtotal += $compra->subtotal;
              $subDescuento += $compra->descuento;
              $subBase += $compra->base;
              $subCredito += $compra->credito;
            $this->pdf->SetFont('Arial','',6);
            $this->pdf->SetFillColor(255,255,255);
            $this->pdf->Cell(5,$alto,'1',$b,0,'C',0);
            $this->pdf->Cell(5,$alto,$n++,$b,0,'C',0);
            $this->pdf->Cell(15,$alto, date('d/m/Y',strtotime($compra->fecha)),$b,0,'R',1);
            $this->pdf->Cell(16,$alto, utf8_decode($compra->nit),$b,0,'L',0);
            $this->pdf->Cell(50,$alto, utf8_decode($compra->razonSocial),$b,0,'L',0);
            $this->pdf->Cell(10,$alto, utf8_decode($compra->nFactura),$b,0,'R',1);
            $this->pdf->Cell(20,$alto, utf8_decode($compra->nDUI),$b,0,'R',1);
            $this->pdf->Cell(30,$alto, utf8_decode($compra->nAut),$b,0,'L',0);
            $this->pdf->Cell(15,$alto,number_format($compra->total, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format($compra->noSujetoCF, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format($compra->subtotal, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format($compra->descuento, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format($compra->base, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format($compra->credito, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(20,$alto, utf8_decode($compra->codigoControl),$b,0,'R',0);
            //$this->pdf->Cell(8,$alto, utf8_decode($compra->tipo),$b,1,'c','0');
            //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
            if ($c%52 == 0) {
              $this->subTotales($compra,$alto,$b,$subTotal,$subNoSujeto,$subSubtotal,$subDescuento,$subBase,$subCredito);
              $subTotal=0;
              $subNoSujeto =0;
              $subSubtotal = 0;
              $subDescuento = 0;
              $subBase = 0;
              $subCredito =0;
            } else if($compra === end($compras)){
              $this->subTotales($compra,$alto,$b,$subTotal,$subNoSujeto,$subSubtotal,$subDescuento,$subBase,$subCredito);
              $subTotal=0;
              $subNoSujeto =0;
              $subSubtotal = 0;
              $subDescuento = 0;
              $subBase = 0;
              $subCredito =0;
            }
            else {
              $this->pdf->Cell(8,$alto, utf8_decode($compra->tipo),$b,1,'C','0');
              
            }
      }
      // TOTALES
      $this->pdf->Cell(121,$alto, utf8_decode(''),$b,0,'c','0');
      $this->pdf->SetFont('Arial','B',6);
      $this->pdf->SetFillColor(232,232,232);
      $this->pdf->Cell(30,$alto, utf8_decode('TOTAL GENERAL:'),'TB',0,'R','1');
      $this->pdf->Cell(15,$alto,number_format($total, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format($totalNoSujeto, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format($totalSubTotal, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format($totalDescuento, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format($totalBase, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format($totalCredito, 2, ".", ","),'TB',1,'R',1);
      //guardar
      $this->pdf->Output("ComprasNotario $mes-$year" . ".pdf", 'I');
  }
  public function subTotales($compra,$alto,$b,$subTotal,$subNoSujeto,$subSubtotal,$subDescuento,$subBase,$subCredito)
  {
    $this->pdf->Cell(8,$alto, utf8_decode($compra->tipo),$b,1,'C','0');
    $this->pdf->Cell(121,$alto, utf8_decode(''),$b,0,'c','0');
    $this->pdf->SetFont('Arial','B',6);
    $this->pdf->Cell(30,$alto, utf8_decode('Subtotal:'),'B',0,'R','0');
    $this->pdf->Cell(15,$alto,number_format($subTotal, 2, ".", ","),'B',0,'R',1);
    $this->pdf->Cell(15,$alto,number_format($subNoSujeto, 2, ".", ","),'B',0,'R',1);
    $this->pdf->Cell(15,$alto,number_format($subSubtotal, 2, ".", ","),'B',0,'R',1);
    $this->pdf->Cell(15,$alto,number_format($subDescuento, 2, ".", ","),'B',0,'R',1);
    $this->pdf->Cell(15,$alto,number_format($subBase, 2, ".", ","),'B',0,'R',1);
    $this->pdf->Cell(15,$alto,number_format($subCredito, 2, ".", ","),'B',1,'R',1);
  }
}