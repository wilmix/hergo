<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class Siat extends CI_Controller {
  public function factura($id=null) {
    //echo $id;
    $this->load->model('siat/Emitir_model');
    $factura = $this->Emitir_model->getFactura($id);
    $lineas = $this->Emitir_model->getDetalleFactura($id);
    $params = (array) $factura;
    //$year = date('y',strtotime($factura->fechaEmision));
    
    /* echo '<pre>';
     print_r($lineas);
     print_r($factura);
    echo '</pre>'; */
    
    $this->load->library('pdf/FacturaSiatLib', $params);
        $this->pdf = new FacturaSiatLib($params);
        //print_r(( $params));
        
        //$this->pdf->AddPage('P',array(195,216));
        $this->pdf->AddPage('P','Letter');
        $this->pdf->AliasNbPages();
        $this->pdf->SetAutoPageBreak(true,130); //40
        $this->pdf->SetTitle('FAC' . '-' .$factura->numeroFactura. '-' . $factura->gestion);
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(10);
        $this->pdf->SetX(10);
        $this->pdf->SetFont('Arial', '', 7);
        $this->pdf->SetLineWidth(0.2);
            
        $l = '0';
            $totalFactura=0;
            foreach ($lineas as $linea) {
                $totalFactura += $linea->subTotal;
                $this->pdf->SetFillColor(255,255,255);
                    // cell(w , h, 'dato', border, ln, align, fill)
                    $this->pdf->Cell(15,5,$linea->codigo,$l,0,'C',0);
                    $this->pdf->Cell(15,5,number_format($linea->cantidad, 2, ".", ","),$l,0,'C',0);
                    $this->pdf->Cell(15,5,$linea->unidad,$l,0,'C',0); 
                    $this->pdf->MultiCell(93,5,utf8_decode($linea->descripcion),$l,'L',0);
                    $this->pdf->SetXY(148,$this->pdf->GetY()-5);
                    $this->pdf->Cell(20,5,number_format($linea->precioUnitario, 2, ".", ","),$l,0,'R',0);
                    $this->pdf->Cell(20,5,number_format($linea->descuento, 2, ".", ","),$l,0,'R',0);
                    $this->pdf->Cell(20,5,number_format(($linea->subTotal), 2, ".", ","),$l,0,'R',0);
                $this->pdf->Ln(5);

                $this->pdf->Line(10,$this->pdf->GetY(),208,$this->pdf->GetY());
            }
           
            $this->totales($totalFactura, $this->pdf->GetY(), $factura->montoTotalMoneda, $factura->moneda);
            $this->literal($factura->total,$this->pdf->GetY()-15);

        //guardar
       $this->pdf->Output('I','FAC' . '-' .$factura->numeroFactura. '-' . $factura->gestion .'.pdf',true);
  }
  public function literal($total,$y)
  {
    $this->pdf->SetY($y);
    $l =0;
    $entera = intval(round($total,2));
    $ctvs = round((round($total,2) - $entera) * 100);
    $ctvs = sprintf('%02d',$ctvs);
    $ctvs = ($ctvs == 0) ? '00' : $ctvs;
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->Cell(10,5,'SON: ',$l,0,'L',1);
    $literal = NumeroALetras::convertir($entera).$ctvs.'/100 '.'BOLIVIANOS';
    //$this->pdf->Cell(113,6,$literal,$l,0,'l',1);
    $this->pdf->MultiCell(113,5,utf8_decode($literal),$l,'L',0);
  }
  public function totales($totalFactura, $y, $montoTotalMoneda, $moneda)
  {
    $this->pdf->SetY($y);
    $this->pdf->SetFont('Arial','',8);
    $this->pdf->SetFillColor(255,255,255);
    $this->pdf->SetX(133);
    $this->pdf->Cell(55,5,utf8_decode('SUBTOTAL Bs'),'TL',0,'R',1);
    $this->pdf->Cell(20,5,number_format($totalFactura, 2, ".", ","),'T',1,'R',1);
    $this->pdf->SetX(133);
    $this->pdf->Cell(55,5,utf8_decode('DESCUENTO Bs'),'TL',0,'R',1);
    $this->pdf->Cell(20,5,number_format(0, 2, ".", ","),'T',1,'R',1); 
    $this->pdf->SetX(133);
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Cell(55,5,'MONTO A PAGAR Bs','TL',0,'R',1);
    $this->pdf->Cell(20,5,number_format($totalFactura, 2, ".", ","),'T',1,'R',1);
    if ($moneda == 2) {
      $this->pdf->SetX(133);
      $this->pdf->Cell(55,5,utf8_decode('MONTO A PAGAR (DOLAR)'),'TLB',0,'R',1);
      $this->pdf->Cell(20,5,number_format($montoTotalMoneda, 2, ".", ","),'TB',1,'R',1);  
    }
    $this->pdf->SetX(133);
    $this->pdf->Cell(55,5,utf8_decode('IMPORTE BASE CRÉDITO FISCAL Bs'),'TLB',0,'R',1);
    $this->pdf->Cell(20,5,number_format($totalFactura, 2, ".", ","),'TB',1,'R',1);  
    
  }
}