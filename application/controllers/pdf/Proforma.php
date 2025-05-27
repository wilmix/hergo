<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class Proforma extends MY_Controller {
  public $dist;
  public $imgSize;

  public function generar($id, $dist=14, $imgSize=12,$firma=0) {

    $this->load->model('Proforma_model');
    $this->dist = $dist;
    $this->imgSize = $imgSize;
    $proforma = $this->Proforma_model->getProforma($id);
    $items = $this->Proforma_model->getProformaItems($id);
    $params = get_object_vars($proforma);
    $year = date('y',strtotime($proforma->fecha));
    $mes = date('n',strtotime($proforma->fecha));
    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $params["firma"]=$firma;

    $this->load->library('Proforma_lib', $params);
        $this->pdf = new Proforma_lib($params);
        $this->pdf->AddPage('P','Letter');
        $this->pdf->AliasNbPages();
        $this->pdf->SetAutoPageBreak(true,47); //40
        $this->pdf->SetTitle('PRO' . '-' .$proforma->num. '-' . $year);
        $this->items($items,$proforma);

      $this->pdf->Output('I',$proforma->num . '-' . $year . ' | ' . $proforma->clienteNombre . ' | ' . $proforma->tipo . ' | ' . strtoupper($meses[($mes)-1]) . '.pdf',true);
  }
  public function items($items,$proforma)
  {
        $l = 0;
        $n = 1;
        $total = 0;
        foreach ($items as $item) {
          $total += $item->total;
          $this->pdf->SetFillColor(255,255,255);
          $this->pdf->SetTextColor(0,0,0);
          //$this->pdf->SetFont('Arial','',8); 
          $this->pdf->SetFont('Roboto','',8);
            $this->pdf->Cell(5,5,$n++,$l,0,'C',0); 
            $url_img= $item->img ? 'assets/img_articulos/' . $item->img : 'assets/img_articulos/' . $item->img;
            $url_img_e = 'assets/img_articulos/' . $item->img;
            /* $url_img= $item->img ? 'assets/img_articulos/' . $item->img : 'assets/img_articulos/check_blue.png';
            $this->pdf->Cell(25,10,$this->pdf->Image($url_img, $this->pdf->GetX() + 5, $this->pdf->GetY()+1, 12 ),$l,0,'C',0); */
            if (($item->img  != '')) {
              $this->pdf->Cell(25,10,$this->pdf->Image($url_img_e, $this->pdf->GetX() + 5, $this->pdf->GetY()+1, $this->imgSize ),$l,0,'C',0);
            } else {
              $this->pdf->Cell(25,10,$this->pdf->Image('assets/img_articulos/check_blue.png', $this->pdf->GetX() + 10, $this->pdf->GetY()+2, 5 ),$l,0,'R',0);
            }
            $this->pdf->Cell(15,5,iconv('UTF-8', 'windows-1252//TRANSLIT', $item->codigo),$l,0,'C',0);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
            $this->pdf->MultiCell(45,5,iconv('UTF-8', 'windows-1252//TRANSLIT', ($item->descrip)),$l,'L',0);
            $this->pdf->SetXY(100,$this->pdf->GetY()-5);
            $this->pdf->Cell(15,5,$item->marca,$l,0,'C',0);
            /* $this->pdf->SetXY(100,$this->pdf->GetY()-5);
            $this->pdf->MultiCell(15,5,iconv('UTF-8', 'windows-1252', ($item->marca)),$l,'C',0);
            $this->pdf->SetXY(115,$this->pdf->GetY()-5); */
            $this->pdf->Cell(15,5,$item->industria,$l,0,'C',0);
            $this->pdf->Cell(15,5,$item->tiempoEntrega,$l,0,'C',0);
            $this->pdf->Cell(15,5,number_format($item->cantidad, 2, ".", ","),$l,0,'R',0);
            $this->pdf->Cell(10,5,$item->uni,$l,0,'R',0);
            $this->pdf->Cell(20,5,number_format($item->precioLista, 2, ".", ","),$l,0,'R',0);
            $this->pdf->Cell(20,5,number_format($item->total, 2, ".", ","),$l,0,'R',0);
          $this->pdf->Ln($this->dist);//DISTANCIA ENTRE LINEAS 
          $this->pdf->Line($this->pdf->GetX(),$this->pdf->GetY(),$this->pdf->GetX()+200,$this->pdf->GetY());
          //$this->pdf->SetY($this->pdf->GetY()-5);
        }
        //$this->pdf->SetY($this->pdf->GetY()-5);
        $this->pdf->SetFont('Roboto','B',8);
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
        $literal = NumeroALetras::convertir($entera).$ctvs.'/100 '. $proforma->moneda;
        $this->pdf->Ln(1);
        $this->pdf->Cell(7,6,'SON:',$l,0,'L',1);
        $this->pdf->Cell(156,6,iconv('UTF-8', 'windows-1252//TRANSLIT', $literal),$l,0,'l',1);
        //$this->pdf->Ln(5);
        $this->pdf->SetFillColor(20,60,190);
        //$this->pdf->Rect(10,$this->pdf->GetY()+5,200,1,'F');
        $this->observaciones($proforma->glosa);
        
   
  }
  public function observaciones($glosas)
  {
    if ($glosas) {
      $this->pdf->Ln(6);
      $this->pdf->SetFillColor(20,60,190);
      $this->pdf->SetFont('Roboto','B',8); 
      //$this->pdf->SetFont('Roboto','',8);
      $this->pdf->SetTextColor(255,255,255);
      $this->pdf->Cell(200,5, iconv('UTF-8', 'windows-1252//TRANSLIT', 'OBSERVACIONES: '),1,0,'C',1);
      $this->pdf->Ln(5);
      $this->pdf->SetTextColor(0,0,0);
      //$this->pdf->SetFont('Arial','',8);
      $this->pdf->SetFont('Roboto','',8);
      $glosas = explode('<br />',$glosas);
      foreach ($glosas as $glosa) {
        $this->pdf->MultiCell(200,5,$glosa,0,'L',0);
        $this->pdf->SetXY(10,$this->pdf->GetY()-5);
      }
      $this->pdf->SetDrawColor(20,60,190);
      $this->pdf->SetLineWidth(1);
      $this->pdf->Line(10,$this->pdf->GetY()+6,210,$this->pdf->GetY()+6);

    }
  }
}