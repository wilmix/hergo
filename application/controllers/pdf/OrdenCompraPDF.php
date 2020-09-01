<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class OrdenCompraPDF extends CI_Controller {
  public function index($id=null) {
    //CARGAR MODELO
    $this->load->model('Pedidos_model');
    $orden = $this->Pedidos_model->getOrden($id);
    
    //print_r($orden); die();
    //PARAMETROS PARA LA LIBRERIA
    $params = array(
        'n' => $orden->n,
        'fecha' => $orden->fecha,
        'provedor' => $orden->nombreproveedor,
        'telefono' => $orden->telefono,
        'fax' => $orden->fax,
        'atencion' => $orden->atencion,
        'direccion' => $orden->direccion,
        'referencia' => $orden->referencia,
        'condicion' => $orden->condicion,
        'formaEnvio' => $orden->formaEnvio,
        'formaPago' => $orden->formaPago,
        'glosa' => $orden->glosa,
        'autor' => $orden->autor,       
        );
    $this->load->library('OrdenCompraLib', $params);

      $this->pdf = new OrdenCompraLib($params);
      $this->pdf->AddPage('P','Letter');
      //$this->pdf->SetX(10);
      $this->pdf->AliasNbPages();
      $this->items($id);

      


     
     
      //guardar
      $this->pdf->Output('$ingreso->sigla' . " - ". '$ingreso->n' . " - " . '$year', 'I');
  }
  public function items($id)
  {
        $items = $this->Pedidos_model->getOrdenItems($id);
        //print_r($items); die();
        //$this->pdf->Cell(0,10,'id de orden '.$id,0,0,1);
        $this->pdf->SetXY(10,83);
        $this->pdf->Ln(1);
        $this->pdf->SetFillColor(235,235,235);
        $this->pdf->SetFont('Arial','B',7); 
                $this->pdf->Cell(5,6,'N',0,0,'C',1);
                $this->pdf->Cell(15,6,utf8_decode('Código HG'),0,0,'C',1);
                $this->pdf->Cell(10,6,utf8_decode('Cant.'),0,0,'R',1);
                $this->pdf->Cell(10,6,utf8_decode('Unid.'),0,0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                $this->pdf->Cell(100,6,utf8_decode('Descripción'),0,0,'L',1);
                $this->pdf->Cell(20,6,utf8_decode('Nº Parte'),0,0,'R',1);
                $this->pdf->Cell(20,6,utf8_decode('ValorUnitario'),0,0,'R',1);
                $this->pdf->Cell(20,6,utf8_decode('Total USD'),0,0,'R',1);
        $this->pdf->Ln(6);
        $n = 1;
        $totalOrden = 0;
        foreach ($items as $item) {
            $totalOrden +=$item->total;
           // print_r($item); die();
            $this->pdf->SetX(10);
            $this->pdf->SetFillColor(255,255,255);
            $this->pdf->SetFont('Arial','',7); 
                $this->pdf->Cell(5,5,$n++,'',0,'C',1); 
                $this->pdf->Cell(15,5,$item->codigo,'',0,'L',1); 
                $this->pdf->Cell(10,5,number_format($item->cantidad, 2, ".", ","),'',0,'R',1); 
                $this->pdf->Cell(10,5,$item->unidad,'',0,'C',1); 
                $this->pdf->Cell(100,5,utf8_decode($item->descripcion),'',0,'l',0); 
                $this->pdf->Cell(20,5,utf8_decode($item->numParte),'',0,'R',1); 
                $this->pdf->Cell(20,5,number_format($item->precioFabrica, 2, ".", ","),'',0,'R',1); 
                $this->pdf->Cell(20,5,number_format($item->total, 2, ".", ","),'',0,'R',1); 
            $this->pdf->Ln(5);
            $this->pdf->SetX(10);
            
        }
        $this->pdf->SetFont('Arial','B',7);
        $this->pdf->Cell(180,5,'TOTAL $U$:','T',0,'R',1); 
        $this->pdf->Cell(20,5,number_format($totalOrden, 2, ".", ","),'T',0,'R',1); 
        $this->pdf->Ln(5);
        $this->pdf->SetX(10);
            
        $entera = intval($totalOrden);
        $ctvs = intval(($totalOrden - $entera) * 100);
        $ctvs = ($ctvs == 0) ? '00' : $ctvs;
        $literal = NumeroALetras::convertir($entera).$ctvs.'/100 '.'DOLARES';
        $this->pdf->Cell(100,5,utf8_decode("Son: $literal"),'',0,'l',0); 
      
  }
    
}