<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class Proforma extends CI_Controller {
  public function index($id=null) {
    //echo $id;die();
    $this->load->model('Proforma_model');
    $proforma = $this->Proforma_model->getProforma($id);
    $items = $this->Proforma_model->getProformaItems($id);
    $params = get_object_vars($proforma);
    //print_r($params);die();
    /* $params = array(
        'almacen' => $proforma->almacen,
        'fecha' => $proforma->fecha,
        'num' => $proforma->num,
        'nombreCliente' => $proforma->nombreCliente,
        'sigla' => $proforma->sigla,
        'condicionesPago' => $proforma->condicionesPago,
        'validezOferta' => $proforma->validezOferta,
        'lugarEntrega' => $proforma->lugarEntrega,
        'porcentajeDescuento' => $proforma->porcentajeDescuento,
        'autor' => $proforma->autor,
        'email' => $proforma->email,
    ); */
    $year = date('y',strtotime($proforma->fecha));

    
    $this->load->library('Proforma_lib', $params);
        $this->pdf = new Proforma_lib($params);
        $this->pdf->AddPage('P','Letter');
        $this->pdf->AliasNbPages();
        $this->pdf->SetAutoPageBreak(true,55); //40
        $this->pdf->SetTitle('PRO' . '-' .$proforma->num. '-' . $year);
        $this->items($items,$proforma);

      $this->pdf->Output('I','FAC' . '-' .$proforma->num. '-' . $year.'.pdf',true);
  }
  public function items($items,$proforma)
  {
        $l = 0;
 
        //$this->pdf->Ln(6);
        $n = 1;
        $total = 0;
        foreach ($items as $item) {
          $total += $item->total;
          $this->pdf->SetFillColor(255,255,255);
          $this->pdf->SetTextColor(0,0,0);
          $this->pdf->SetFont('Arial','',8); 
            $this->pdf->Cell(5,5,$n++,$l,0,'C',0); 
            $url_img= $item->img ? 'assets/img_articulos/' . $item->img : 'assets/img_articulos/hergo.jpg';
            $this->pdf->Cell(25,5,$this->pdf->Image($url_img, $this->pdf->GetX() + 5, $this->pdf->GetY(), 12 ),$l,0,'C',0);
            $this->pdf->Cell(15,5,utf8_decode($item->codigo),$l,0,'C',0);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
            $this->pdf->MultiCell(45,5,iconv('UTF-8', 'windows-1252', ($item->descripcion)),$l,'L',0);
            $this->pdf->SetXY(100,$this->pdf->GetY()-5);
            $this->pdf->Cell(15,5,$item->marca,$l,0,'C',1);
            $this->pdf->Cell(15,5,$item->industria,$l,0,'C',1);
            $this->pdf->Cell(15,5,$item->tiempoEntrega,$l,0,'C',1);
            $this->pdf->Cell(15,5,number_format($item->cantidad, 2, ".", ","),$l,0,'R',0);
            $this->pdf->Cell(10,5,$item->uni,$l,0,'R',1);
            $this->pdf->Cell(20,5,number_format($item->precio, 2, ".", ","),$l,0,'R',0);
            $this->pdf->Cell(20,5,number_format($item->total, 2, ".", ","),$l,0,'R',0);
          $this->pdf->Ln(12);
          $this->pdf->Line($this->pdf->GetX(),$this->pdf->GetY()-5,$this->pdf->GetX()+200,$this->pdf->GetY()-5);
        }
        $this->pdf->SetY($this->pdf->GetY()-5);
        $this->pdf->SetFont('Arial','B',8);
        $this->pdf->Cell(180,5,'Total:','T',0,'R',1); 
        $this->pdf->Cell(20,5,number_format($total, 2, ".", ","),'T',0,'R',1); 
        $this->pdf->Ln(5);
        if ($proforma->porcentajeDescuento) {
          $sub = 'Descuento ' . $proforma->porcentajeDescuento . '%';
          $this->pdf->Cell(180,5,$sub,'0',0,'R',1); 
          $this->pdf->Cell(20,5,number_format($proforma->descuento, 2, ".", ","),'0',0,'R',1); 
          $this->pdf->Ln(5);
          $this->pdf->Cell(180,5,'Total Final','0',0,'R',1); 
          $this->pdf->Cell(20,5,number_format($total - $proforma->descuento, 2, ".", ","),'TB',0,'R',1); 
        }
        $entera = intval($proforma->total);
        $ctvs = round(($proforma->total - $entera) * 100);
        $ctvs = sprintf('%02d',$ctvs);
        $ctvs = ($ctvs == 0) ? '00' : $ctvs;
        $literal = NumeroALetras::convertir($entera).$ctvs.'/100 '.'BOLIVIANOS';
        $this->pdf->Ln(6);
        $this->pdf->Cell(9,6,'SON: ',0,0,'L',1);
        $this->pdf->Cell(188,6,$literal,0,0,'l',1);
        //$this->pdf->Ln(5);
        $this->pdf->SetFillColor(20,60,190);
        $this->pdf->Rect(10,$this->pdf->GetY()+5,200,1,'F');
        $this->pdf->Ln(6);
        $glosas = explode('<br />',$proforma->glosa);
        /* foreach ($glosas as $glosa) {
          echo($glosa);
        } */

        //$this->observaciones($proforma->glosa);
        
  }
  public function observaciones($glosas)
  {
    foreach ($glosas as $glosa) {
      $this->pdf->MultiCell(130,5,$glosa,0,'L',0);
      $this->pdf->SetXY(10,$this->pdf->GetY()-5);
  }
  }
}