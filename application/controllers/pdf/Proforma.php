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
    $params = array(
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
    );
    $year = date('y',strtotime($proforma->fecha));

    
    $this->load->library('Proforma_lib', $params);
        $this->pdf = new Proforma_lib($params);
        $this->pdf->AddPage('P','Letter');
        $this->pdf->AliasNbPages();
        $this->pdf->SetAutoPageBreak(true,40); //40
        $this->pdf->SetTitle('PRO' . '-' .$proforma->num. '-' . $year);
        $this->items($items,$proforma);

      $this->pdf->Output('I','FAC' . '-' .$proforma->num. '-' . $year.'.pdf',true);
  }
  public function items($items,$proforma)
  {
        
        //print_r(($items)); die();
        $l = 0;
        $this->pdf->SetXY(10,50);
        $this->pdf->Ln(1);
        $this->pdf->SetFillColor(235,235,235);
        $this->pdf->SetFont('Arial','B',6); 
                //$this->pdf->Cell(5,6,'N',$l,0,'C',1);
                $this->pdf->SetFillColor(20,60,190);
                    $this->pdf->SetFont('Arial','B',8); 
                    $this->pdf->SetTextColor(255,255,255);
                    $this->pdf->Cell(5,6,utf8_decode('Nº'),0,0,'C',1);
                    $this->pdf->Cell(25,6,'Imagen',0,0,'C',1);
                    $this->pdf->Cell(15,6,utf8_decode('Código'),0,0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                    $this->pdf->Cell(45,6,utf8_decode('Descripción'),0,0,'C',1);
                    $this->pdf->Cell(15,6,'Marca',0,0,'C',1);
                    $this->pdf->Cell(15,6,'Industria',0,0,'C',1);
                    $this->pdf->Cell(15,6,'Entrega',0,0,'C',1);
                    $this->pdf->Cell(15,6,'Cant.',0,0,'R',1);
                    $this->pdf->Cell(10,6,'Uni.',0,0,'C',1);
                    $this->pdf->Cell(20,6,'P/U',0,0,'R',1);
                    $this->pdf->Cell(20,6,'Total',0,0,'R',1);

        $this->pdf->Ln(6);
        $n = 1;
        $total = 0;
        foreach ($items as $item) {
          $total += $item->total;
          $this->pdf->SetFillColor(255,255,255);
          $this->pdf->SetTextColor(0,0,0);
          $this->pdf->SetFont('Arial','',8); 
          $this->pdf->Cell(5,5,$n++,$l,0,'C',0); 
          $url_img= $item->img ? 'assets/img_articulos/' . $item->img : 'assets/img_articulos/hergo.jpg';
          $this->pdf->Cell(25,5,$this->pdf->Image($url_img, $this->pdf->GetX() + 5, $this->pdf->GetY() + 1, 15 ),$l,0,'C',0);
          $this->pdf->Cell(15,5,utf8_decode($item->codigo),$l,0,'C',0);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
          $this->pdf->MultiCell(45,5,iconv('UTF-8', 'windows-1252', ($item->descripcion)),$l,'L',0);
          $this->pdf->SetXY(100,$this->pdf->GetY()-5);
          $this->pdf->Cell(15,5,$item->marca,$l,0,'C',1);
          $this->pdf->Cell(15,5,$item->industria,$l,0,'C',1);
          $this->pdf->Cell(15,5,$item->tiempoEntrega,$l,0,'C',1);
          $this->pdf->Cell(15,5,number_format($item->cantidad, 2, ".", ","),$l,0,'R',0);
          $this->pdf->Cell(10,5,$item->uni,$l,0,'C',1);
          $this->pdf->Cell(20,5,number_format($item->precio, 2, ".", ","),$l,0,'R',0);
          $this->pdf->Cell(20,5,number_format($item->total, 2, ".", ","),$l,0,'R',0);
          $this->pdf->Ln(12);
          $this->pdf->Line($this->pdf->GetX(),$this->pdf->GetY(),$this->pdf->GetX()+200,$this->pdf->GetY());
        }
        $this->pdf->SetFont('Arial','B',8);
        $this->pdf->Cell(180,5,'Total:','T',0,'R',1); 
        $this->pdf->Cell(20,5,number_format($total, 2, ".", ","),'T',0,'R',1); 
        $this->pdf->Ln(5);
        if ($proforma->porcentajeDescuento) {
          $sub = 'Descuento ' . $proforma->porcentajeDescuento . '%';
          $this->pdf->Cell(180,5,$sub,'0',0,'R',1); 
          $this->pdf->Cell(20,5,number_format($proforma->descuento, 2, ".", ","),'T',0,'R',1); 
          $this->pdf->Ln(5);
          $this->pdf->Cell(180,5,'Total Final','0',0,'R',1); 
          $this->pdf->Cell(20,5,number_format($total - $proforma->descuento, 2, ".", ","),'T',0,'R',1); 
        }




      
  }
}