<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Pdf extends FPDF {
        public function __construct() {
            parent::__construct();
           
        }

        //CellFitSpace( ) 
        // El encabezado del PDF
      public function Header()
      {
        $ci = & get_instance();
        $ci->load->model("Ingresos_model");
        $lineas = $ci->Ingresos_model->mostrarIngresos();
        $fila=$lineas->row(30);

       // return $fila->idArticulos;
        $this->Image('images/hergo.jpeg', 10, 13, 45 );
        $this->SetFont('Arial','B',18);
        $this->Cell(85);
        $this->Cell(30,10, 'NOTA DE INGRESOS',0,0,'C');
        $this->Ln(8);

             //****TITULO*****
        //TIPO DE INGRESO
        $this->SetFont('Arial','BU',15);
        $this->Cell(85);
        $this->Cell(30,10, 'COMPRAS LOCALES',0,0,'C');
        $this->Ln(5);

        
        //fecha de movimiento
        $this->SetY(8);
        $this->SetX(160);
        $this->Cell(10);
        $this->SetFont('Times','i',9);
        $this->Cell(30, 10, 'Fecha Mov: ', 0);

        //tipo de moneda
        $this->SetY(13);
        $this->SetX(160);
        $this->Cell(10);
        $this->SetFont('Times','i',9);
       "Bolivianos";
        $this->Cell(30, 10, 'Moneda: ', 0);

        // n movimiento
        $this->SetY(18);
        $this->SetX(160);
        $this->Cell(10);
        $this->SetFont('Times','i',9);
        $this->Cell(30, 10, 'Nro Movimiento: ', 0);

        //****ENCABEZADO****
        $this->Ln(10);
        $this->SetX(10);
        $this->SetFont('Arial','',9);
        // almacen
        $this->Cell(30,10, 'Almacen: ',0,0,'');
        $this->Cell(40);
        //proveedor
        $this->Cell(30,10, 'Proveedor: ',0,0,'');
        $this->Cell(50);
        //factura
        $this->Cell(30,10, 'Factura: ',0,0,'');
        $this->Ln(5);
        $this->SetX(10);
        $this->SetFont('Arial','',9);
        //tipo movimiento
        $this->Cell(30,10, 'Tipo Movimiento: ',0,0,'');
        $this->Cell(40);
        // N° ingreso
        $this->Cell(30,10, 'Nro Ingreso: ',0,0,'');
        $this->Cell(50);
        // Orden de compra
        $this->Cell(30,10, 'Orden Compra: ',0,0,'');
        $this->Ln(10);

         //ENCABEZADO TABLA
        $this->SetX(10);
        $this->SetFillColor(232,232,232);
        $this->SetFont('Arial','B',7); 
        $this->Cell(15,5,'CODIGO',0,0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
        $this->Cell(70,5,'ARTICULO',0,0,'C',1);
        $this->Cell(10,5,'UNIDAD',0,0,'C',1);
        $this->Cell(20,5,'CANTIDAD',0,0,'R',1);
        $this->Cell(20,5,'PRECIO $',0,0,'R',1);
        $this->Cell(20,5,'PRECIO Bs',0,0,'R',1);
        $this->Cell(20,5,'TOTAL $',0,0,'R',1);
        $this->Cell(20,5,'TOTAL Bs',0,0,'R',1);
        $this->Ln(5);

        



      










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