<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class SaldosActuales extends CI_Controller {
  public function index($id=1) {
        $this->load->model('Reportes_model');
        $lineas = $this->Reportes_model->mostrarSaldosActualesItems($id)->result();
        $gestion = $this->Reportes_model->showGestionActual()->row()->gestionActual;
    $params = array(
      'gestion' => $gestion,
      'alm' => $lineas[0]->almacen
    );
    //print_r($lineas);
  // die();
    $this->load->library('SaldosActuales_lib', $params);

      $this->pdf = new SaldosActuales_lib($params);
      $this->pdf->AddPage('P','Letter');
      //$this->pdf->SetX(10);
      $this->pdf->AliasNbPages();
      $this->pdf->SetAutoPageBreak(true,10); 
      $this->pdf->SetTitle('SaldosActuales');
      $this->pdf->SetLeftMargin(10);
      $this->pdf->SetRightMargin(10);
      $this->pdf->SetFont('Arial', '', 6);
      
      $n = '';

      foreach ($lineas as $linea) {
        $linea->costo = $linea->costo == 0 ? '' : number_format($linea->costo, 2, ".", ",");
        $linea->saldo = $linea->saldo == 0 ? '' : number_format($linea->saldo, 2, ".", ",");
        $linea->remision = $linea->remision == 0 ? '' : number_format($linea->remision, 2, ".", ",");
        $linea->saldoAlm = $linea->saldoAlm == 0 ? '' : number_format($linea->saldoAlm, 2, ".", ",");
        if ($linea->codigo == '' && $linea->sigla == NULL) {
            $linea->descripcion = 'TOTAL GENERAL ';
        } elseif ($linea->codigo == '') {
            $linea->descripcion = "TOTAL $linea->linea";
        } else {
            $linea->descripcion =  $linea->descripcion;
        }
        
        $linea->vTotal = number_format($linea->vTotal, 2, ".", ",");
        $top = $linea->codigo == '' ? 'BT' : '0';


        $this->pdf->SetX(10);
        $this->pdf->SetFillColor(255,255,255);

        //$this->filas($linea, $top);
        if ($n == '' && $linea->sigla==NULL) {
            $this->pdf->SetFont('Arial', 'B', 6);
            $this->filas($linea,$top);
  
          } elseif ($n == '') {
              $this->pdf->SetFont('Arial', 'B', 6);
              $this->pdf->Cell(15,5,utf8_decode($linea->sigla),'0',0,'C',1);
              $this->pdf->Cell(80,5,utf8_decode($linea->linea),'0',0,'L',1);
              $this->pdf->Ln(5);
              $this->filas($linea,$top);
          } else {
              $this->filas($linea,$top);
          }

        $n = $linea->codigo;
        $this->pdf->Ln(6);
      }


      $this->pdf->Ln(2);
     

      //guardar
      $this->pdf->Output('SaldosActualesItems.pdf', 'I');
  }
  public function filas($linea, $top){
          if ($linea->codigo == NULL) {
            $this->pdf->SetFont('Arial', 'B', 6);
          } else {
            $this->pdf->SetFont('Arial', '', 6);
          }
          $this->pdf->Cell(15,5,utf8_decode($linea->codigo),'0',0,'C',1);
          $this->pdf->Cell(80,5,utf8_decode($linea->descripcion),$top,0,'L',1);
          $this->pdf->Cell(8,5,$linea->unidad,$top,0,'C',1);
          $this->pdf->Cell(15,5,$linea->costo,$top,0,'R',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
          $this->pdf->Cell(20,5,$linea->saldo,$top,0,'R',1);
          $this->pdf->Cell(20,5,$linea->remision,$top,0,'R',1);
          $this->pdf->Cell(20,5,$linea->saldoAlm,$top,0,'R',1);
          $this->pdf->Cell(20,5,$linea->vTotal,$top,0,'R',1);

          //$this->pdf->Cell(20,5,number_format($linea->utilidad, 2, ".", ","),$top,0,'R',1);
  }

}