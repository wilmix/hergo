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
    $lineas = $this->Proforma_model->getProformaItems($id);


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
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(10);
        $this->pdf->SetX(10);
        $this->pdf->SetFont('Arial', '', 8);

            
            $this->pdf->Ln(2);


                    
                     //TOTAL DOCUMENTO
                    
        //guardar
      $this->pdf->Output('I','FAC' . '-' .$proforma->num. '-' . $year.'.pdf',true);
  }
}