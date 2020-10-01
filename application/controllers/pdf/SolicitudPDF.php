<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class SolicitudPDF extends CI_Controller {
  public function index($id=null) {
    //CARGAR MODELO
    $this->load->model('Pedidos_model');
    $pedido = $this->Pedidos_model->getPedido($id);
    $year = date('y',strtotime( $pedido->fecha ));

    //print_r($pedido); die();
    //PARAMETROS PARA LA LIBRERIA
    $params = array(
        'n' => $pedido->n,
        'fecha' => $pedido->fecha,
        'recepcion' => $pedido->recepcion,
        'provedor' => $pedido->proveedor,
        'pedidoPor' => $pedido->pedidoPor,
        'cotizacion' => $pedido->cotizacion,
        'direccion' => $pedido->direccion,
        'formaPago' => $pedido->formaPago,
        'diasCredito' => $pedido->diasCredito,
        'glosa' => $pedido->glosa,
        'telefono' => $pedido->telefono,
        'fax' => $pedido->fax,
        'flete' => $pedido->flete,
        );
        $this->load->library('SolicitudLib', $params);
        $this->pdf = new SolicitudLib($params);
        $this->pdf->AddPage('L','Letter');
        $this->pdf->SetTitle("PEDIDO - $pedido->n/$year");
        $this->pdf->AliasNbPages();
        $this->items($id);

        $this->pdf->Output("PED - $pedido->n - $year.pdf"   , 'I');
  }
  public function items($id)
  {
        $items = $this->Pedidos_model->getPedidoItems($id);
        //print_r(($items)); die();
        $l = 0;
        $this->pdf->SetXY(10,50);
        $this->pdf->Ln(1);
        $this->pdf->SetFillColor(235,235,235);
        $this->pdf->SetFont('Arial','B',6); 
                $this->pdf->Cell(5,6,'N',$l,0,'C',1);
                $this->pdf->Cell(15,6,utf8_decode('Código'),$l,0,'L',1);
                $this->pdf->Cell(25,6,utf8_decode('Número Parte'),$l,0,'L',1);
                $this->pdf->Cell(55,6,utf8_decode('Descripción Fabrica'),$l,0,'L',1);
                $this->pdf->Cell(55,6,utf8_decode('Descripción Hergo'),$l,0,'L',1);
                $this->pdf->Cell(15,6,utf8_decode('Unid.'),$l,0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                $this->pdf->Cell(15,6,utf8_decode('Exis.'),$l,0,'R',1);
                $this->pdf->Cell(15,6,utf8_decode('Rot 6M'),$l,0,'R',1);
                $this->pdf->Cell(15,6,utf8_decode('PrecioVenta'),$l,0,'R',1);
                $this->pdf->Cell(15,6,utf8_decode('Cant.'),$l,0,'R',1);
                $this->pdf->Cell(15,6,utf8_decode('PrecioFabrica'),$l,0,'R',1);
                $this->pdf->Cell(15,6,utf8_decode('Total USD'),$l,0,'R',1);
        $this->pdf->Ln(6);
        $n = 1;
        $totalOrden = 0;
        foreach ($items as $item) {
            $totalOrden +=$item->total;
            $this->pdf->SetX(10);
            $this->pdf->SetFillColor(255,255,255);
            $this->pdf->SetFont('Arial','',6); 
                $this->pdf->Cell(5,5,$n++,$l,0,'C',0); 
                $this->pdf->Cell(15,5,$item->codigo,$l,0,'L',0); 
                $this->pdf->Cell(25,5,$item->numParte,$l,0,'L',0); 
                $this->pdf->MultiCell(54,5,iconv('UTF-8', 'windows-1252', ($item->descripFabrica)),$l,'L',0); 
            $this->pdf->SetXY(110,$this->pdf->GetY()-5);
                $this->pdf->MultiCell(54,5,iconv('UTF-8', 'windows-1252', ($item->descripcion)),$l,'L',0); 
            $this->pdf->SetXY(165,$this->pdf->GetY()-5);
            $this->pdf->Cell(15,5,$item->unidad,$l,0,'C',0); 
                $this->pdf->Cell(15,5,number_format($item->saldo, 2, ".", ","),$l,0,'R',0); 
                $this->pdf->Cell(15,5,number_format($item->rotacion, 2, ".", ","),$l,0,'R',0); 
                $this->pdf->Cell(15,5,number_format($item->precio, 2, ".", ","),$l,0,'R',0); 
                $this->pdf->Cell(15,5,number_format($item->cantidad, 2, ".", ","),$l,0,'R',0); 
                $this->pdf->Cell(15,5,number_format($item->precioFabrica, 2, ".", ","),$l,0,'R',0); 
                $this->pdf->Cell(15,5,number_format($item->total, 2, ".", ","),$l,0,'R',0); 
            $this->pdf->Ln(5);
            $this->pdf->Line($this->pdf->GetX(),$this->pdf->GetY(),$this->pdf->GetX()+260,$this->pdf->GetY());
            $this->pdf->SetX(10);
            
        }
        $totalOrden += $item->flete;
        $this->pdf->SetFont('Arial','B',7);
        $this->pdf->Cell(240,5,'FLETE:','T',0,'R',1); 
        $this->pdf->Cell(20,5,number_format($item->flete, 2, ".", ","),'T',0,'R',1); 
        $this->pdf->Ln(5);
        $this->pdf->Cell(240,5,'TOTAL $U$:','0',0,'R',1); 
        $this->pdf->Cell(20,5,number_format($totalOrden, 2, ".", ","),'T',0,'R',1); 
        $this->pdf->Ln(5);
        $this->pdf->Cell(240,5,'T/C:','0',0,'R',1); 
        $this->pdf->Cell(20,5,number_format($item->tc, 2, ".", ","),'T',0,'R',1); 
        $this->pdf->Ln(5);
        $this->pdf->Cell(240,5,'TOTAL BOB:','0',0,'R',1); 
        $this->pdf->Cell(20,5,number_format(($item->tc * $totalOrden), 2, ".", ","),'T',0,'R',1); 
        $this->pdf->Ln(5);
        $this->pdf->SetX(10);
        $entera = intval($totalOrden);
        $ctvs = intval(round(($totalOrden - $entera),2) * 100);
        $ctvs = ($ctvs == 0) ? '00' : $ctvs;
        $literal = NumeroALetras::convertir($entera).sprintf("%02d", $ctvs).'/100 '.'DOLARES';
        //$this->pdf->Cell(260,5,utf8_decode("Son: $literal"),'0',0,'l',0); 
  }
    
}