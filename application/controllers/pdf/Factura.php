<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class Factura extends CI_Controller {
  public function index($id=null) {
    //echo $id;
    $this->load->model('Facturacion_model');
    $factura = $this->Facturacion_model->obtenerFacturaPDF($id);
    $lineas = $this->Facturacion_model->obtenerDetalleFacturaPDF($id);

    $params = array(
        'almacen' => $factura->almacen,
        'direccion' => $factura->direccion,
        'Telefonos' => $factura->Telefonos,
        'ciudad' => $factura->ciudad,
        'fechaFac' => $factura->fechaFac,
        'glosa'=> $factura->glosa,
        'codigoControl' => $factura->codigoControl,
        'ClienteFactura' => $factura->ClienteFactura,
        'ClienteNit' => $factura->ClienteNit,
        'sucursal' => $factura->sucursal,
        'moneda' => $factura->moneda,
        'anulada' => $factura->anulada,
        'cambiovalor' => $factura->cambiovalor,
        'nit' => $factura->nit,
        'autorizacion' => $factura->autorizacion,
        'fechaLimite' => $factura->fechaLimite,
        'llaveDosificacion' => $factura->llaveDosificacion,
        'glosa01' => $factura->glosa01,
        'glosa02' => $factura->glosa02,
        'glosa03' => $factura->glosa03,
        'manual' => $factura->manual,
        'nFactura' => $factura->nFactura,
        'total'=>$factura->total,
        'userName' => $this->session->userdata['nombre']
        

    );
    $year = date('y',strtotime($factura->fechaFac));
    /*echo $id;
    echo '<pre>';
     print_r($lineas->result());
     print_r($factura);
    echo '</pre>';*/
    
    $this->load->library('Factura_lib', $params);
        $this->pdf = new Factura_lib($params);
        $this->pdf->AddPage('L',array(215,195));
        $this->pdf->AliasNbPages();
        $this->pdf->SetTitle('FAC' . '-' .$factura->nFactura. '-' . $year);
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(10);
        $this->pdf->SetX(10);
        $this->pdf->SetFont('Arial', '', 8);
            $totalFactura=0;
            foreach ($lineas->result() as $linea) {
                $totalFactura += ($linea->facturaCantidad*$linea->facturaPUnitario);
                $this->pdf->SetFillColor(255,255,255);
                    $this->pdf->Cell(15,5,number_format($linea->facturaCantidad, 2, ".", ","),'',0,'R',0);
                    $this->pdf->Cell(10,5,$linea->Sigla,'',0,'C',0);
                    $this->pdf->Cell(15,5,$linea->ArticuloCodigo,'',0,'C',0);
                    $this->pdf->Cell(115,5,utf8_decode($linea->ArticuloNombre),0,0,'L',0);
                    $this->pdf->Cell(20,5,number_format($linea->facturaPUnitario, 2, ".", ","),0,0,'R',1);
                    $this->pdf->Cell(20,5,number_format(($linea->facturaCantidad*$linea->facturaPUnitario), 2, ".", ","),'',0,'R',1);
                $this->pdf->Ln(5);
            }
            $this->pdf->Ln(2);
                    // TOTALES
                    $this->pdf->SetFont('Times','BI',10);
                    $this->pdf->SetFillColor(232,232,232); 
                    $this->pdf->Cell(15,6,'Total: ','1',0,'L',1);
                    $this->pdf->SetFont('Times','I',10);
                    $literal = NumeroALetras::convertir($totalFactura, 'BOLIVIANOS', 'CENTAVOS');
                    $this->pdf->Cell(160,6,$literal,'1',0,'l',1);
                    $this->pdf->SetFont('Arial','B',9);
                    $this->pdf->Cell(20,6,number_format($totalFactura, 2, ".", ","),'1',0,'R',1); //TOTAL DOCUMENTO
                    
        //guardar
      $this->pdf->Output('I','FAC' . '-' .$factura->nFactura. '-' . $year.'.pdf',true);
  }
}