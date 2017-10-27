<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Pdfing extends FPDF {
        public function __construct() {
            parent::__construct();
        }

        //CellFitSpace( ) 
        // El encabezado del PDF
        public function Header(){
      $this->Image('images/hergo.jpeg', 10, 8, 40 );
      $this->Cell(300);
      $this->SetFont('Times','i',9);
      $this->Cell(30, 10, 'Fecha: '.date('d-m-Y').'', 0); //fecha actual
      $this->Ln(5);
      $this->SetFont('Arial','BU',15);
      $this->Cell(150);
      $this->Cell(30,10, 'Lista de Articulos',0,0,'C');
      $this->Ln(12);
      //ENCABEZADO TABLA
      $this->SetX(10);
      $this->SetFillColor(232,232,232);
      $this->SetFont('Arial','B',8); 
      $this->Cell(10,5,'NUM',0,0,'C',1);
      $this->Cell(20,5,'CODIGO',0,0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
      $this->Cell(190,5,'DESCRIPCION',0,0,'C',1);
      $this->Cell(15,5,'UNIDAD',0,0,'C',1);
      $this->Cell(35,5,'MARCA',0,0,'C',1);
      $this->Cell(35,5,'LINEA',0,0,'C',1);
      $this->Cell(30,5,'N PARTE',0,0,'C',1);
      //$this->Cell(27,5,'N ARANCEL',0,0,'C',1);
      $this->Ln(6);

       }
       // El pie del pdf
       public function Footer(){
      //posicion a 1.5 cm del final
      $this->SetY(-15);
      //fuente
      $this->SetFont('Arial','I', 8);
      //numero de pagina
      $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
      }
    }
?>