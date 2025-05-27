<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Articulos_pdf extends MY_Controller {
 
  public function index() {
        // Se carga el modelo alumno
        $this->load->model('Articulo_model');    //********cambiar nombre de modelo
        // Se carga la libreria fpdf
        $this->load->library('pdf');
        // Se obtienen los alumnos de la base de datos
        $lineas = $this->Articulo_model->mostrarArticulos(); //********cambiar nombre de class de modelo
        // Creacion del PDF
        /*
        * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
        * heredó todos las variables y métodos de fpdf
        */
        $this->pdf = new Pdf();
        // Agregamos una página
        $this->pdf->AddPage('L','Legal');
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
    
        /* Se define el titulo, márgenes izquierdo, derecho y
        * el color de relleno predeterminado
        */
        $this->pdf->SetTitle("Lista de Articulos");
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200,200,200);
    
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetX(10);
        $this->pdf->SetFont('Arial', '', 6);
        /*
        * TITULOS DE COLUMNAS
        *
        * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
        */
        //print_r ($lineas);
       
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        foreach ($lineas->result() as $linea) {
        $this->pdf->SetX(10);
        // se imprime el numero actual y despues se incrementa el valor de $x en uno
        $this->pdf->Cell(10,5,$x++,'',0,'C',0); ///NUMERO DE FILA
        // Se imprimen los datos de cada alumno
        //$this->pdf->utf8_decode($str);
        
        $this->pdf->Cell(20,5,$linea->CodigoArticulo,'',0,'C',0);
        utf8_decode($this->pdf->Cell(190,5,$linea->Descripcion,'',0,'L',0));
        $this->pdf->Cell(15,5,$linea->Unidad,'',0,'C',0);
        utf8_decode($this->pdf->Cell(35,5,$linea->Marca,'',0,'C',0));
        utf8_decode($this->pdf->Cell(35,5,$linea->Linea,'',0,'C',0));
        $this->pdf->Cell(30,5,$linea->NumParte,'',0,'L',0);
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