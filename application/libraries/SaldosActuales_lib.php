<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class SaldosActuales_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
        }
        public function Header() {
            $gestion = $this->datos['gestion'];
            $date = date("d") . "/" . date("m") . "/" . date("Y");
            $alm = $this->datos['alm'];
            //TITULO
            $this->SetXY(10,10);
            $this->Image('images/hergo.jpeg', 10, 10, 45 );
            $this->SetFont('Arial','B',10);
            $this->SetXY(15,20);
            $this->Cell(40,6, $alm,0,0,'C');
            $this->SetFont('Arial','B',10);
            $this->SetXY(10,25);
            $this->SetXY(10,10);
            $this->SetFont('Arial','B',18);
            $this->Cell(0,8, 'SALDOS ACTUALES ITEMS',0,1,'C'); 
            $this->SetFont('Arial','BU',15);
            //$this->Cell(0,8, utf8_decode("Gestión $gestion"),0,0,'C');


            $this->Ln(5);
            $this->SetXY(190,10);
            $this->SetFont('Arial','',8);
            $this->Cell(0,8, utf8_decode($date),0,0,'C');
            $this->Ln(12);
            
                    //ENCABEZADO TABLA
                    $this->SetFillColor(255,255,255);
                    $this->SetFont('Arial','B',8); 
                    $this->Ln(6);
                    $this->Cell(15,5,utf8_decode('Código'),'B',0,'C',1);
                    $this->Cell(80,5,utf8_decode('Descripción'),'B',0,'L',1);
                    $this->Cell(8,5,'Uni.','B',0,'C',1);
                    $this->Cell(15,5,'Costo Uni.','B',0,'R',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                    $this->Cell(20,5,'Saldo','B',0,'R',1);
                    $this->Cell(20,5,utf8_decode('Remisión'),'B',0,'R',1);
                    $this->Cell(20,5,'Saldo Alm.','B',0,'R',1);
                    $this->Cell(20,5,'Total','B',0,'R',1);
                    $this->Ln(6);
        }

        public function Footer(){
            
                //NUMERO PIED PAGINA
                $this->SetY(-13);
                $this->SetFont('Arial','I', 8);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>