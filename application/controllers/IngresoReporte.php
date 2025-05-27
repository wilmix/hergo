<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class IngresoReporte extends MY_Controller {
 
  public function index()
  {
    $this->load->model('Ingresos_model');    //********cambiar nombre de modelo
    // Se carga la libreria fpdf
    $this->load->library('ingresopdf/pdf');
 
    // Se obtienen los alumnos de la base de datos
    $lineas = $this->Ingresos_model->mostrarIngresos();
 
    // Creacion del PDF
 
    /*
     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
     * heredó todos las variables y métodos de fpdf
     */
    $this->pdf = new Pdf();
    // Agregamos una página
    $this->pdf->AddPage('L','Letter');
    // Define el alias para el número de página que se imprimirá en el pie
    $this->pdf->AliasNbPages();

   /* echo "<pre>";
    print_r($lineas->result_array());
    echo "</pre>";
    die();*/

      ///contenido de tabla detalle
      foreach ($lineas->result_array() as $fila) {
        $this->pdf->SetX(10);
        $this->pdf->SetFont('Arial','',7);
        $this->pdf->Cell(15,5,'TM0001',0,0,'C',0);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
        $this->pdf->Cell(70,5,'NOMBRE DE ARTICULO',0,0,'C',0);
        $this->pdf->Cell(10,5,'PZA',0,0,'C',0);
        $this->pdf->Cell(20,5,'10',0,0,'R',0);
        $this->pdf->Cell(20,5,'10.00',0,0,'R',0);
        $this->pdf->Cell(20,5,'69.60',0,0,'R',0);
        $this->pdf->Cell(20,5,'100.00',0,0,'R',0);
        $this->pdf->Cell(20,5,'696.00',0,0,'R',0);
        $this->pdf->Ln(5);
      }




      //TOTALES
      $this->pdf->SetFont('Arial','B',7);
      $this->pdf->Cell(155,5,'TOTALES:',0,0,'R',0);
      //total dolares
      $this->pdf->Cell(20,5,'1000.00',0,0,'R',0);
      //total bolivianos
      $this->pdf->Cell(20,5,'6960.00',0,0,'R',0);
      $this->pdf->Ln(10);

      $this->pdf->SetFont('Arial','B',8);
      $this->pdf->Cell(27,5,'Observaciones: ',0,0,'L',0);
      $this->pdf->SetFont('Arial','',8);
      //OBSERVACION
      $this->pdf->Cell(0,5,'Esta es una observacion si la existiese',0,0,'L',0);





 
    $this->pdf->Output("Ingreso.pdf", 'I');
  }
}