<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class ComprasLocales_pdf extends CI_Controller {
 
  public function index() {
        // Se carga el modelo alumno
        $this->load->model('Ingresos_model');    //********cambiar nombre de modelo
        // Se carga la libreria fpdf
        $this->load->library('ingresopdf/pdf');
        // Se obtienen los alumnos de la base de datos
        $lineas = $this->Ingresos_model->mostrarDetalle(10092); //********cambiar nombre de class de modelo
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
    
        /* Se define el titulo, márgenes izquierdo, derecho y
        * el color de relleno predeterminado
        */
        $this->pdf->SetTitle("Compras LocalesH");
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200,200,200);
    
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetX(10);
        $this->pdf->SetFont('Arial', '', 10);
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        foreach ($lineas->result() as $linea) {
        $this->pdf->SetX(10);

                    $this->pdf->Cell(7,5,$x++,'',0,'C',0); ///NUMERO DE FILA
                    $this->pdf->Cell(20,5,$linea->cantidad,'',0,'R',0);
                    $this->pdf->Cell(10,5,'UNI','',0,'C',0);
                    $this->pdf->Cell(20,5,$linea->CodigoArticulo,'',0,'C',0);
        utf8_decode($this->pdf->Cell(80,5,$linea->Descripcion,'',0,'L',0));
                    
        utf8_decode($this->pdf->Cell(30,5,$linea->punitario,'',0,'R',0));
        utf8_decode($this->pdf->Cell(30,5,$linea->totaldoc,'',0,'R',0));
                    $this->pdf->Cell(30,5,$linea->punitario,'',0,'R',0);
                    $this->pdf->Cell(30,5,$linea->total,'',0,'R',0);   //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
        //$this->pdf->Cell(27,5,$linea->PosicionArancelaria,'',0,'R',0);
        //Se agrega un salto de linea
        $this->pdf->Ln(5);
        }
        /*
        * Se manda el pdf al navegador
        *
        * $this->pdf->Output(nombredelarchivo, destino);
        *
        * I = Muestra el pdf en el navegador
        * D = Envia el pdf para descarga
        *
        */
        $this->pdf->Output("Articulos.pdf", 'I');
  }
}