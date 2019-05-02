<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class ReportEgresos extends CI_Controller {
  public function index($ini,$fin,$alm,$tin) {
      //CARGAR MODELO
        $alm = $alm == 0 ? '' : $alm;
        $tin = $tin == 0 ? '' : $tin;
      $this->load->model('Reportes_model');
      $lineas = $this->Reportes_model->mostrarReporteEgreso($ini,$fin,$alm,$tin)->result();

      $tmov = $lineas ? strtoupper($lineas[0]->siglaMov) : '';
      $alm = $lineas ? $lineas[0]->nombreAlmacen : '';
      
    //PARAMETROS PARA LA LIBRERIA
    $params = array(
      'alm' => $alm,
      'ini' => $ini,
      'fin' => $fin,
      'tipo' => $tmov,
    );
    $this->load->library('ReportEgresos_lib', $params);

      $this->pdf = new ReportEgresos_lib($params);
      $this->pdf->AddPage('P','Letter');
      $this->pdf->AliasNbPages();
      $this->pdf->SetAutoPageBreak(true,10); 
      $this->pdf->SetTitle("REPORTE $tmov");
      $this->pdf->SetLeftMargin(10);
      $this->pdf->SetRightMargin(10);
      $this->pdf->SetFont('Arial', '', 7);
      
      $aux = NULL;
      foreach ($lineas as $linea) {
        if ($aux == NULL) {
          $this->pdf->Ln(1);
          $this->pdf->SetX(10);
          $this->pdf->SetFillColor(255,255,255);
          $this->pdf->SetFont('Arial', 'B' , 7);
          $this->pdf->Cell(80,5,utf8_decode($linea->cliente),'0',1,'L',1);
          $this->filas($linea);
        } else {
          $this->filas($linea);
        }
        $aux = $linea->id;
      }
    
      //guardar
      $this->pdf->Output('Reporte Egresos', 'I');
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
  public function filas($linea){
    $tmov = strtoupper($linea->siglaMov);
    if ($linea->id == NULL && $linea->nmov == NULL) {
        $linea->descripcion = "TOTAL $tmov";
    }  else {
        $linea->descripcion = $linea->id == NULL ? utf8_decode("") : utf8_decode($linea->descripcion);
    }
    $fechaMov = $linea->id ? date('d/m/Y',strtotime($linea->fechamov)) : '';
    $nmov = $linea->id ? $linea->nmov : '';
    $codigo = $linea->id ? $linea->codigo : '';
    $uni = $linea->id ? $linea->uni : '';
    $mon = $linea->id ? $linea->mon : '';
    $pu = $linea->id ? number_format($linea->punitario, 2, ".", ",") : '';
    $cantidad = number_format($linea->cantidad, 2, ".", ",");
    $total = number_format($linea->total, 2, ".", ",");
    $neg = $linea->id ? '' : 'B';
    $bor = $linea->id ? 0 : 'B';
    $id = $linea->id;
    $b = $linea->id == NULL ? 'TB' : '0';
    
    if ($linea->id == NULL) {
      $this->pdf->SetFont('Arial', 'B', 6);
    } else {
      $this->pdf->SetFont('Arial', '', 6);
    }
    $this->pdf->Cell(15,5,$fechaMov,$b,0,'C',1);//ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
    $this->pdf->Cell(10,5,utf8_decode($nmov),$b,0,'C',1);
    $this->pdf->Cell(15,5,utf8_decode($codigo),$b,0,'L',1);
    $this->pdf->Cell(80,5,($linea->descripcion),$b,0,'L',1);
    $this->pdf->Cell(15,5,$uni,$b,0,'R',1);
    $this->pdf->Cell(10,5,$mon,$b,0,'R',1);
    $this->pdf->Cell(15,5,$pu,$b,0,'R',1);
    $this->pdf->Cell(15,5,$cantidad,$b,0,'R',1);
    $this->pdf->Cell(20,5,$total,$b,1,'R',1);
  }
}