<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class Recibo extends CI_Controller {
  public function index($id=null) {
    //echo $id;
    $this->load->model('Pagos_model');
    $pago = $this->Pagos_model->retornarEdicion($id)->row();
    $lineas = $this->Pagos_model->retornarEdicionDetalle($id)->result();
    //var_dump($pago);
    $params = array(
      'almacen' => $pago->nomAlmacen,
      'autor' => $pago->autor,
      'fechaPago' => $pago->fechaPago,
      'numPago' => $pago->numPago,
      'nombreCliente' => $pago->nombreCliente,
      'glosa' => $pago->glosa,
      'tipocambio' => $pago->tipoCambio,
      'moneda' => $pago->moneda,
      'userName' => $pago->userName,
      'totalPago' => $pago->totalPago,
      'tipoPago' => $pago->tipoPago,
      'idTipoPago' => $pago->idTipoPago,
      'nomBanco' => $pago->nomBanco,
      'cheque' => $pago->cheque,
      'direccion'=> $pago->direccion,
      'telefonos'=> $pago->Telefonos,
      'transferencia'=> $pago->transferencia,
      //$this->session->userdata['nombre']
  );
        $year = date('y',strtotime($pago->fechaPago));
    /*print_r($lineas);
    return false;*/
        $this->load->library('Recibo_lib', $params);
        $this->pdf = new Recibo_lib($params);
        //$this->pdf->AddPage('L',array(215,152));
        $this->pdf->AddPage('P','Letter');
        $this->pdf->AliasNbPages();
       $this->pdf->SetTitle('REC' . ' - ' .$pago->numPago . ' - ' . $year);
        $this->pdf->SetAutoPageBreak(true,160);
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(10);
        $this->pdf->SetFont('Arial', '', 8);

            $n = 1;
            $totalPago=0;
            foreach ($lineas as $linea) {
                $totalPago += $linea->pagar;
                $this->pdf->SetFillColor(255,255,255);
                    $this->pdf->Cell(20,5,$n++,'',0,'C',0); ///NUMERO DE FILA
                    $this->pdf->Cell(20,5,$linea->siglAlm,'',0,'C',0);
                    $this->pdf->Cell(25,5,$linea->nFactura,'',0,'C',0);
                    $this->pdf->Cell(100,5,utf8_decode($linea->nombreCliente),0,0,'L',0);
                    $this->pdf->Cell(30,5,number_format($linea->pagar, 2, ".", ","),0,0,'R',1);
                $this->pdf->Ln(5);
            }
            $this->pdf->SetFont('Arial','B',8);
                $this->pdf->SetFillColor(255,255,255);
                $this->pdf->Cell(175,5,'TOTAL Bs.',0,0,'R',1);
                $this->pdf->Cell(20,5,number_format($totalPago, 2, ".", ","),0,1,'R',1); 
                $this->pdf->SetFont('Courier','B',9);
    
        //guardar
      $this->pdf->Output('REC'. ' - ' . $pago->numPago . ' - ' . $year . ' - ' . $pago->nombreCliente .  '.pdf', 'I');
  }
}