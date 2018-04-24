<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";

 
class ComprasLocales_pdf extends CI_Controller {
 
  public function index($id=null) {

        $this->load->model('Ingresos_model');    //********cambiar nombre de modelo
        $this->load->library('ingresopdf/pdf');

        $lineas = $this->Ingresos_model->mostrarDetalle($id); //********cambiar nombre de class de modelo
        $ingreso = $this->Ingresos_model->mostrarIngresos($id)->row();

        $this->pdf = new Pdf();
        /*echo '<pre>';
        print_r ($lineas);
        echo '</pre>';*/
        
        // Agregamos una página
        $this->pdf->AddPage('L','Letter');
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
    

        $this->pdf->SetTitle($ingreso->tipomov . " - ". $ingreso->n);
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);

    

        $this->pdf->SetX(10);
        $this->pdf->SetFont('Arial', '', 8);
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        $totalIngreso=0;
        foreach ($lineas->result() as $linea) {
        $totalIngreso += $linea->total;
        $this->pdf->SetX(10);
        $this->pdf->SetFillColor(232,232,232);
        

                    $this->pdf->Cell(7,5,$x++,'',0,'C',0); ///NUMERO DE FILA
                    $this->pdf->Cell(20,5,number_format($linea->cantidad, 2, ".", ","),'',0,'R',0);
                    $this->pdf->Cell(10,5,'UNI','',0,'C',0);
                    $this->pdf->Cell(20,5,$linea->CodigoArticulo,'',0,'C',0);
                    $this->pdf->Cell(80,5,utf8_decode($linea->Descripcion),'',0,'L',0);
                    $this->pdf->Cell(30,5,number_format($linea->punitario, 2, ".", ","),'',0,'R',0);
                    $this->pdf->Cell(30,5,number_format($linea->totaldoc, 2, ".", ","),'',0,'R',0);
                    $this->pdf->Cell(30,5,number_format($linea->punitario, 2, ".", ","),'',0,'R',0);
                    $this->pdf->Cell(30,5,number_format($linea->total, 2, ".", ","),'',0,'R',0);   //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
        //Se agrega un salto de linea
        $this->pdf->Ln(5);
        }
        $this->pdf->Ln(5);
        // TOTALES
        $this->pdf->SetFillColor(232,232,232);
        $this->pdf->SetFont('Arial','B',9); 
        $this->pdf->Cell(15,7,'Total: ','1',0,'L',0);
        $literal = NumeroALetras::convertir($totalIngreso, 'BOLIVIANOS', 'CENTAVOS');
        $this->pdf->Cell(200,7,$literal,'1',0,'l',0);
        ;  
        $this->pdf->Cell(30,7,number_format($totalIngreso, 2, ".", ","),'1',0,'R',0);

        //guardar
        $this->pdf->Output($ingreso->tipomov . " - ". $ingreso->n, 'I');
  }

}