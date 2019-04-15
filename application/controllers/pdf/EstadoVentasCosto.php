<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class EstadoVentasCosto extends CI_Controller {
  public function index($id=1) {
        $this->load->model('Reportes_model');
        $lineas = $this->Reportes_model->mostrarEstadoVentasCosto($id)->result();
        $gestion = $this->Reportes_model->showGestionActual()->row()->gestionActual;
    $params = array(
      'gestion' => $gestion,
      'alm' => $lineas[0]->almacen
    );
    //print_r($lineas[0]->almacen);

   // //die();
    $this->load->library('EstadoVentasCosto_lib', $params);

      $this->pdf = new EstadoVentasCosto_lib($params);
      $this->pdf->AddPage('L','Letter');
      //$this->pdf->SetX(10);
      $this->pdf->AliasNbPages();
      $this->pdf->SetAutoPageBreak(true,10); 
      $year = date('y',strtotime('$ingreso->fechamov'));
      $this->pdf->SetTitle('EstadoCostoVentas');
      $this->pdf->SetLeftMargin(10);
      $this->pdf->SetRightMargin(10);
      $this->pdf->SetFont('Arial', '', 6);
      
      $n = NULL;

      foreach ($lineas as $linea) {
        $this->pdf->SetX(10);
        $this->pdf->SetFillColor(255,255,255);
        if ($linea->codigo == NULL && $linea->siglaLinea == NULL) {
          $linea->saldoCantidad = 'TOTAL GENERAL:';
        } elseif ($linea->codigo == NULL) {
          $linea->saldoCantidad = 'TOTAL:';
        } else {
          $linea->saldoCantidad = number_format($linea->saldoCantidad, 2, ".", ",");
        }
        
        $linea->descrip = $linea->codigo == NULL ? '' : $linea->descrip;
        $linea->uni = $linea->codigo == NULL ? '' : $linea->uni;
        $linea->cpp = $linea->codigo == NULL ? '' : number_format($linea->cpp, 2, ".", ",");
        $linea->precioVenta = $linea->codigo == NULL ? '' : number_format($linea->precioVenta, 2, ".", ",");
        $linea->cantVendida = $linea->codigo == NULL ? '' : number_format($linea->cantVendida, 2, ".", ",");
        $top = $linea->codigo == NULL ? 'T' : '0';

        if ($n == NULL && $linea->siglaLinea==NULL) {
          $this->pdf->SetFont('Arial', 'B', 6);
          $this->filas($linea,$top);

        } elseif ($n == NULL) {
            $this->pdf->SetFont('Arial', 'B', 6);
            $this->pdf->Cell(15,5,utf8_decode($linea->siglaLinea),'0',0,'C',1);
            $this->pdf->Cell(80,5,utf8_decode($linea->linea),'0',0,'L',1);
            $this->pdf->Ln(5);
            $this->filas($linea,$top);
        } else {
            $this->filas($linea,$top);
        }
        $n = $linea->codigo;
        $this->pdf->Ln(5);
      }


      $this->pdf->Ln(2);
     

      //guardar
      $this->pdf->Output('EstadoVentasCosto.pdf', 'I');
  }
  public function filas($linea,$top){
          if ($linea->codigo == NULL) {
            $this->pdf->SetFont('Arial', 'B', 6);
          } else {
            $this->pdf->SetFont('Arial', '', 6);
          }
          $this->pdf->Cell(15,5,utf8_decode($linea->codigo),'0',0,'C',1);
          $this->pdf->Cell(80,5,utf8_decode($linea->descrip),'0',0,'L',1);
          $this->pdf->Cell(10,5,$linea->uni,'0',0,'C',1);
          $this->pdf->Cell(18,5,$linea->cpp,'0',0,'R',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
          $this->pdf->Cell(20,5,$linea->precioVenta,'0',0,'R',1);
          $this->pdf->Cell(20,5,$linea->saldoCantidad,'0',0,'R',1);
          $this->pdf->Cell(20,5,number_format($linea->saldoValorado, 2, ".", ","),$top,0,'R',1);
          $this->pdf->Cell(20,5,$linea->cantVendida,'0',0,'R',1);
          $this->pdf->Cell(20,5,number_format($linea->totalCosto, 2, ".", ","),$top,0,'R',1);
          $this->pdf->Cell(20,5,number_format($linea->totalVenta, 2, ".", ","),$top,0,'R',1);
          $this->pdf->Cell(20,5,number_format($linea->utilidad, 2, ".", ","),$top,0,'R',1);
  }

}