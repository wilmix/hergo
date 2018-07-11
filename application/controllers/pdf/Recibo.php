<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class Recibo extends CI_Controller {
  public function index($id=null) {
    //echo $id;
    $this->load->model('Pagos_model');
    $lineas = $this->Pagos_model->retornarDetallePago($id);
    $params = array();
    //$year = date('y',strtotime($egreso->fechamov));
    /*echo '<pre>';
    print_r($egreso);
    echo '</pre>';*/
    
    $this->load->library('Recibo_lib', $params);
        $this->pdf = new Recibo_lib($params);
        $this->pdf->AddPage('L',array(215,152));
        $this->pdf->AliasNbPages();
       // $this->pdf->SetTitle($egreso->sigla . ' - ' .$egreso->n . ' - ' . $year);
        $this->pdf->SetAutoPageBreak(true,25);
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(10);
        $this->pdf->SetFont('Arial', '', 8);
        
    
        //guardar
      $this->pdf->Output(' - ' , 'I');
  }
}