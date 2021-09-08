<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class VentasNotario extends CI_Controller {
  public function index($alm,$month,$year) {
    //CARGAR MODELO
    $this->load->model('pdf/ReportePDF_model');
    $ventas = $this->ReportePDF_model->ventasNotario($alm,$month,$year);
    $alm = $this->ReportePDF_model->getAlmacen($alm);
    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $mes = $meses[($month)-1];

    $params = [$alm,$mes,$year];

    $this->load->library('pdf/VentasNotario_lib', $params);

      $this->pdf = new VentasNotario_lib($params);
      $this->pdf->AddPage('L','Letter');
      $this->pdf->AliasNbPages();

      $this->pdf->SetTitle('VentasNotario');
      $this->pdf->SetLeftMargin(5);
      $this->pdf->SetRightMargin(5);
      $this->pdf->SetFont('Arial', '', 6);
      $this->pdf->SetAutoPageBreak(true,10);
      
      $n = 1;
      $c = 0;
      $b = 0;
      $alto = 3;
      $total=0;
      $totalDebito=0;
        $subTotal=0;
        $subDebito =0;

        foreach ($ventas as $venta) {
        $this->pdf->SetX(5);
        $this->pdf->SetFillColor(255,255,255);
            $c++;
            $total+=$venta->totalVenta;
            $totalDebito+=$venta->debito;
              $subTotal+= $venta->totalVenta;
              $subDebito += $venta->debito;
            $this->pdf->SetFont('Arial','',5.8);
            $this->pdf->SetFillColor(255,255,255);

            $this->pdf->Cell(5,$alto,'3',$b,0,'C',0);
            $this->pdf->Cell(5,$alto,$n++,$b,0,'C',0);
            $this->pdf->Cell(15,$alto, date('d/m/Y',strtotime($venta->fecha)),$b,0,'R',1);
            $this->pdf->Cell(10,$alto, utf8_decode($venta->nFac),$b,0,'R',1);
            $this->pdf->Cell(22,$alto, utf8_decode($venta->nAut),$b,0,'R',0);
            $this->pdf->Cell(5,$alto, utf8_decode($venta->estado),$b,0,'L',0);

            $this->pdf->Cell(15,$alto, utf8_decode($venta->nit),$b,0,'L',0);
            $this->pdf->Cell(50,$alto, utf8_decode($venta->nombre),$b,0,'L',0);

            $this->pdf->Cell(15,$alto,number_format($venta->totalVenta, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format($venta->totalVenta, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format($venta->totalVenta, 2, ".", ","),$b,0,'R',1);
            $this->pdf->Cell(15,$alto,number_format($venta->debito, 2, ".", ","),$b,0,'R',1);

            //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
            if ($c%52 == 0) {
                $this->subTotales($venta,$alto,$b,$subTotal,$subDebito);
                $subTotal=0;
                $subDebito =0;
            } else if($venta === end($ventas)){
                $this->subTotales($venta,$alto,$b,$subTotal,$subDebito);
                $subTotal=0;
                $subDebito =0;
            } else {
                $this->pdf->Cell(20,$alto, utf8_decode($venta->codigoControl),$b,1,'R',0);
            }
        }
      // TOTALES
      $this->pdf->Cell(100,$alto, utf8_decode(''),$b,0,'c','0');
      $this->pdf->SetFont('Arial','B',6);
      $this->pdf->SetFillColor(232,232,232);
      $this->pdf->Cell(27,$alto, utf8_decode('TOTAL GENERAL:'),'TB',0,'R','1');
      $this->pdf->Cell(15,$alto,number_format($total, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format($total, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format($total, 2, ".", ","),'TB',0,'R',1);
      $this->pdf->Cell(15,$alto,number_format($totalDebito, 2, ".", ","),'TB',1,'R',1);
      //guardar
      $this->pdf->Output("VentasNotario-$alm->ciudad-$mes-$year", 'I');
  }
  public function subTotales($venta,$alto,$b,$subTotal,$subDebito)
  {
    $this->pdf->Cell(20,$alto, utf8_decode($venta->codigoControl),$b,1,'R',0);

    $this->pdf->Cell(112,$alto, utf8_decode(''),$b,0,'c','0');
    $this->pdf->SetFont('Arial','B',6);
    $this->pdf->Cell(15,$alto, utf8_decode('Subtotal:'),'B',0,'R','0');
    $this->pdf->Cell(15,$alto,number_format($subTotal, 2, ".", ","),'B',0,'R',1); 
    $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),'B',0,'R',1); 
    $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),'B',0,'R',1); 
    $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),'B',0,'R',1); 
    $this->pdf->Cell(15,$alto,number_format($subTotal, 2, ".", ","),'B',0,'R',1); 
    $this->pdf->Cell(15,$alto,number_format(0, 2, ".", ","),'B',0,'R',1); 
    $this->pdf->Cell(15,$alto,number_format($subTotal, 2, ".", ","),'B',0,'R',1); 
    $this->pdf->Cell(15,$alto,number_format($subDebito, 2, ".", ","),'B',1,'R',1);

  }
}