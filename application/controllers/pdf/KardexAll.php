<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class KardexAll extends CI_Controller {
  public function index($id=null) {
    //CARGAR MODELO
    $this->load->model('Ingresos_model');
    $lineas = $this->Ingresos_model->mostrarDetalle($id);
    $ingreso = $this->Ingresos_model->mostrarIngresos($id)->row();
    //PARAMETROS PARA LA LIBRERIA
    $params = array('tipo' => $ingreso->tipomov, 
                    'numeroIngreso' => $ingreso->n, 
                    'sigla'=>$ingreso->sigla, 
                    'fechamov'=>$ingreso->fechamov, 
                    'almacen'=>$ingreso->almacen, 
                    'moneda'=>$ingreso->monedasigla,
                    'proveedor' =>$ingreso->nombreproveedor,
                    'tipoDoc' =>$ingreso->tipoDoc,
                    'nfact' =>$ingreso->nfact,
                    'ordenCompra' =>$ingreso->ordcomp, 
                    'observacion' =>$ingreso->obs);
    $this->load->library('Ingresos_lib', $params);

      $this->pdf = new Ingresos_lib($params);
      $this->pdf->AddPage('P','Letter');
      //$this->pdf->SetX(10);
      $this->pdf->AliasNbPages();
      
      $year = date('y',strtotime($ingreso->fechamov));
      $this->pdf->SetTitle("kardexAll");
      $this->pdf->SetLeftMargin(10);
      $this->pdf->SetRightMargin(10);
      $this->pdf->SetFont('Arial', '', 8);
      
      $n = 1;
      $totalInventario=0;
      $totalDocumento=0;
      foreach ($lineas->result() as $linea) {
        if ($ingreso->moneda == 2) {
          $totalInventario += $linea->total/$linea->tipocambio;
          $totalDocumento += $linea->totaldoc/$linea->tipocambio;
          $costoUniDoc= $linea->totaldoc/$linea->cantidad/$linea->tipocambio;
          $linea->totaldoc = $linea->totaldoc/$linea->tipocambio;
          $linea->punitario = $linea->punitario/$linea->tipocambio;
          $linea->total = $linea->total/$linea->tipocambio;

        } else {
          $totalInventario += $linea->total;
          $totalDocumento += $linea->totaldoc;
          $costoUniDoc= $linea->totaldoc/$linea->cantidad;
        }
        

        $this->pdf->SetX(10);
        $this->pdf->SetFillColor(255,255,255);
            $this->pdf->Cell(7,5,$n++,'',0,'C',0); ///NUMERO DE FILA
            $this->pdf->Cell(18,5,number_format($linea->cantidad, 2, ".", ","),'',0,'R',0);
            $this->pdf->Cell(10,5,$linea->Unidad,'',0,'C',0);
            $this->pdf->Cell(18,5,$linea->CodigoArticulo,'',0,'C',0);
            $this->pdf->Cell(120,5,utf8_decode($linea->Descripcion),0,0,'L',0);
            $this->pdf->Cell(20,5,number_format($costoUniDoc, 2, ".", ","),0,0,'R',1);
            $this->pdf->Cell(20,5,number_format($linea->totaldoc, 2, ".", ","),'',0,'R',1);
            $this->pdf->SetFillColor(245,245,245);
            $this->pdf->Cell(20,5,number_format($linea->punitario, 2, ".", ","),'',0,'R',1);
            $this->pdf->Cell(20,5,number_format($linea->total, 2, ".", ","),'',0,'R',1);   //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
        $this->pdf->Ln(5);
        $this->pdf->SetX(10);
      }
      $this->pdf->Ln(2);
     
      // TOTALES
      $entera = intval($totalInventario);
      $ctvs = intval(($totalInventario - $entera) * 100);
      $ctvs = ($ctvs == 0) ? '00' : $ctvs;
      $this->pdf->SetFont('Times','BI',10);
      $this->pdf->SetFillColor(232,232,232); 
      $this->pdf->Cell(13,7,'Total: ','1',0,'L',1);
      $this->pdf->SetFont('Times','I',10);
      if ($ingreso->moneda == 2) {
        $literal = NumeroALetras::convertir($totalInventario).$ctvs.'/100 '.'DOLARES';
      } else {
        $literal = NumeroALetras::convertir($totalInventario).$ctvs.'/100 '.'BOLIVIANOS';
      }
      $this->pdf->Cell(160,7,$literal,'1',0,'l',1);
      $this->pdf->SetFont('Arial','B',9);
      $this->pdf->Cell(40,7,number_format($totalDocumento, 2, ".", ","),'1',0,'R',1); //TOTAL DOCUMENTO
      $this->pdf->Cell(40,7,number_format($totalInventario, 2, ".", ","),'1',0,'R',1); //TOTAL INVENTARIO
      //guardar
      $this->pdf->Output($ingreso->sigla . " - ". $ingreso->n . " - " . $year, 'I');
  }
}