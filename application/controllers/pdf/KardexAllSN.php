<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class KardexAllSN extends CI_Controller {
  public function index($id=1) {
    //CARGAR MODELO
    $this->load->model('Reportes_model');
    $lineas = $this->Reportes_model->showKardexAllModelSN($id);
    $gestion = $this->Reportes_model->showGestionActual()->row()->gestionActual;

    //PARAMETROS PARA LA LIBRERIA
    $params = array(
      'alm' => $lineas[0]->alm,
      'gestion' => $gestion
    );
    $this->load->library('KardexAllSN_lib', $params);

      $this->pdf = new KardexAllSN_lib($params);
      $this->pdf->AddPage('P','Letter');
      //$this->pdf->SetX(10);
      $this->pdf->AliasNbPages();
      
      $this->pdf->SetTitle("Kardex Valorado");
      $this->pdf->SetLeftMargin(20);
      $this->pdf->SetRightMargin(10);
      $this->pdf->SetFont('Arial', '', 7);
      $aux = NULL;
      
      foreach ($lineas as $linea) {
        if ($linea->tipo == 'II' ) {
          $fechaMov = '';
        } else if ($linea->id) {
          $fechaMov = date('d/m/Y',strtotime($linea->fechakardex));
        } else {
          $fechaMov = '';
        }
        $pu = $linea->id ? number_format($linea->punitario, 2, ".", ",") : '';
        $valorado = $linea->id ? number_format($linea->saldoTotal, 2, ".", ",") : '';
        $neg = $linea->id ? '' : 'B';
        $bor = $linea->id ? 0 : 'B';
        $id = $linea->id;

        if ($aux == NULL) {
          $this->pdf->Ln(1);
          $this->pdf->SetX(20);
          $this->pdf->SetFillColor(255,255,255);
          $this->pdf->SetFont('Arial', 'B' , 7);
          $this->pdf->Cell(1,6,utf8_decode("$linea->codigo      $linea->uni - $linea->descp"),$bor,0,'L',1);
          $this->pdf->Ln(5);

              $this->pdf->SetFont('Arial', $neg, 7);
              $this->pdf->Cell(12,6,$fechaMov,$bor,0,'C',1);
              $this->pdf->Cell(8,6,$linea->numMov,$bor,0,'C',1);
              $this->pdf->Cell(70,6,utf8_decode($linea->nombreproveedor),$bor,0,'L',1);
              $this->pdf->Cell(10,6,$pu,0,0,'R',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
              //$this->pdf->Cell(7,6,$linea->almacen,0,0,'C',1);
              $this->pdf->Cell(15,6,$this->formatoNumVacio($linea->ing, $id),$bor,0,'R',1);
              $this->pdf->Cell(15,6,$this->formatoNumVacio($linea->fac, $id),$bor,0,'R',1);
              //$this->pdf->Cell(15,6,$this->formatoNumVacio($linea->ne, $id),$bor,0,'R',1);
              $this->pdf->Cell(15,6,$this->formatoNumVacio($linea->tr, $id),$bor,0,'R',1);
              $this->pdf->Cell(15,6,number_format($linea->saldo, 2, ".", ","),$bor,0,'R',1);
              $this->pdf->Cell(20,6,$valorado,$bor,0,'R',1);
              $this->pdf->Ln(5);

        } else {
          $this->pdf->SetX(20);
          $this->pdf->SetFont('Arial', $neg, 7);
          $this->pdf->SetFillColor(255,255,255);
          //$fechaMov =  $linea->id ? date('d/m/Y',strtotime($linea->fechakardex)) : '';
              $this->pdf->Cell(12,6,$fechaMov,$bor,0,'C',1);
              $this->pdf->Cell(8,6,$linea->numMov,$bor,0,'C',1);
              $this->pdf->Cell(70,6,utf8_decode($linea->nombreproveedor),$bor,0,'L',1);
              $this->pdf->Cell(10,6,$pu,$bor,0,'R',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
              //$this->pdf->Cell(7,6,$linea->almacen,'B',0,'C',1);
              $this->pdf->Cell(15,6,$this->formatoNumVacio($linea->ing, $id),$bor,0,'R',1);
              $this->pdf->Cell(15,6,$this->formatoNumVacio($linea->fac, $id),$bor,0,'R',1);
              //$this->pdf->Cell(15,6,$this->formatoNumVacio($linea->ne, $id),$bor,0,'R',1);
              $this->pdf->Cell(15,6,$this->formatoNumVacio($linea->tr, $id),$bor,0,'R',1);
              $this->pdf->Cell(15,6,number_format($linea->saldo, 2, ".", ","),$bor,0,'R',1);
              $this->pdf->Cell(20,6,$valorado,$bor,0,'R',1);
  
          $this->pdf->Ln(5);
          $this->pdf->SetX(10);
        }
        $aux = $linea->id;
        
      }
      $this->pdf->Ln(2);
     
      //guardar
      $this->pdf->Output('Kardex Individual Valorado', 'I');
  }
  function formatoNumVacio($num, $id){
    if ($id == NULL) {
      return number_format($num, 2, ".", ",");
    } elseif ($num == '0') {
      return '';
    } else {
      return number_format($num, 2, ".", ",");
    }
  }
}