<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Recibo_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
        }
        public function Header() {
            //TITULO
            $this->SetXY(10,10);
            
            $this->Image('images/hergo.jpeg', 10, 10, 45 );
            $this->SetFont('Arial','B',9);
            $this->SetXY(15,20);
            $this->Cell(40,6, 'ALMACEN',0,0,'C');

            $this->SetXY(10,10);
            $this->SetFont('Arial','B',18);
            $this->Cell(0,8, 'RECIBO',0,1,'C'); 

            $this->SetXY(170,12);
            $this->SetFont('Arial','B',15);
            $this->Cell(40,8, '1000',1,1,'C');
            $this->SetXY(170,20);
            $this->SetFont('Arial','B',12);
            $this->Cell(40,8, '10/08/2018',1,0,'C');
            $this->Ln(10);

            $this->SetXY(10,40);
            $this->SetFont('Arial','',12);
            $this->Cell(10,6, 'Recibimos del Sr. '. 'NOMBRE DEL CLIENTE',0,0,'L');
            $this->SetXY(10,47);
            $this->Cell(10,6, 'La suma de: '.' TOTAL LITERAL'.' Bolivianos',0,0,'L');
            $this->SetXY(10,54);
            $this->Cell(10,6, 'Por concepto de: ',0,0,'L');


            
                //****ENCABEZADO****

                

                    //ENCABEZADO TABLA
                    $this->SetXY(15,60);
                    $this->Ln(1);
                    $this->SetFillColor(235,235,235);
                    $this->SetFont('Arial','B',8); 
                    $this->Cell(15,6,'LOTE',0,0,'C',1);
                    $this->Cell(25,6,'Nro FACTURA',0,0,'C',1);
                 //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                    $this->Cell(120,6,'CLIENTE',0,0,'C',1);
                    $this->Cell(30,6,'MONTO',0,0,'R',1);
                    $this->Ln(6);
        }


        public function Footer(){
            $this->SetLineWidth(0.5);
            //$this->Line(10,120,206,120);
            $this->SetY(-30);
            $this->SetFont('Arial','',12); 
//$this->Cell(15,6,'Tipo de Pago: '.' EFECTIVO',0,0,'L',0);
             //$this->Cell(15,6,'Tipo de Pago: '.' TRANSFERENCIA '.' de '. ' BNB-1 '.'Nro VAUCHER',0,0,'L',0);
             $this->Cell(15,6,'Tipo de Pago: '.' CHEQUE '. ' Nro CHEQUE',0,0,'L',0);
             $this->SetY(-25);
             $this->Cell(15,6,'Observaciones: '.'GLOSA DEL PAGO',0,0,'L',0);

             //$this->SetY(-15);
             $this->SetXY(170,-20);
             $this->SetFont('Arial','BI', 9);
             $this->Cell(40,5, 'Recibido por',0,0,'C',0);
             $this->SetXY(170,-15);
             $this->SetFont('Arial','BI', 9);
             $this->Cell(40,5, 'Willy Salas',0,0,'C',0);




                //NUMERO PIED PAGINA
                $this->SetY(-12);
                $this->SetFont('Arial','I', 8);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>