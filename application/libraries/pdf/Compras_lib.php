<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Compras_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
            $this->mes = strtoupper($this->datos[0]);
            $this->year = $this->datos[1];
            //var_dump($this->datos[1]);die();
        }
        public function Header() {
            //TITULO
            //$this->SetXY(5,10);
            $this->Image('images/hergo.jpeg', 10, 10, 45 );
            $this->SetFont('Arial','B',9);
            $this->SetY(21);
            $this->Cell(50,4, 'NIT: 1000991026',0,1,'C',0);
            $this->Cell(50,4, 'CASA MATRIZ',0,1,'C');
            $this->Cell(50,4, utf8_decode('AV. MONTES Nº 611'),0,0,'C');

            
            $this->SetXY(5,20);
            $this->SetFont('Arial','B',18);
            $this->Cell(0,8, 'LIBRO DE COMPRAS IVA',0,1,'C'); 
            $this->SetFont('Arial','BU',14);
            $this->Cell(0,8, "PERIODO: $this->mes $this->year",0,0,'C');
            $this->Ln(8);




                    //ENCABEZADO TABLA
                    $this->SetFillColor(255,255,255);
                    $this->SetFont('Arial','B',6); 
                    $this->SetFillColor(232,232,232);
                    $xe = 5;
                    $alto = 5;
                    $this->SetX(5);
                    $this->Cell(5,10,utf8_decode('Nº'),1,0,'C',1);
                    $this->Cell(5,10,utf8_decode('Nº'),1,0,'C',1);
                    $this->MultiCell(15,$alto,utf8_decode('FECHA FACTURA'),1,'C',1);
                    $this->SetXY($xe+=25,$this->GetY()-10);
                    $this->MultiCell(16,$alto,utf8_decode('NIT PROVEEDOR'),1,'C',1);
                    $this->SetXY($xe+=16,$this->GetY()-10);
                    $this->MultiCell(50,$alto*2,utf8_decode('NOMBRE RAZON SOCIAL'),1,'C',1);
                    $this->SetXY($xe+=50,$this->GetY()-10);
                    $this->MultiCell(10,$alto,utf8_decode('Nº DE FACT.'),1,'C',1);
                    $this->SetXY($xe+=10,$this->GetY()-10);
                    $this->MultiCell(20,$alto*2,utf8_decode('Nº DE DUI'),1,'C',1);
                    $this->SetXY($xe+=20,$this->GetY()-10);
                    $this->MultiCell(30,$alto*2,utf8_decode('Nº DE AUTORIZACIÓN'),1,'C',1);
                    $this->SetXY($xe+=30,$this->GetY()-10);

                    $this->MultiCell(15,$alto,utf8_decode('TOTAL COMPRA'),1,'C',1);
                    $this->SetXY($xe+=15,$this->GetY()-10);
                    $this->MultiCell(15,$alto*2,utf8_decode('NO SUJETO'),1,'C',1);
                    $this->SetXY($xe+=15,$this->GetY()-10);
                    $this->MultiCell(15,$alto*2,utf8_decode('SUB TOTAL'),1,'C',1);
                    $this->SetXY($xe+=15,$this->GetY()-10);
                    $this->MultiCell(15,$alto,utf8_decode('DESC. REBAJAS'),1,'C',1);
                    $this->SetXY($xe+=15,$this->GetY()-10);
                    $this->MultiCell(15,$alto,utf8_decode('BASE CRÉDITO'),1,'C',1);
                    $this->SetXY($xe+=15,$this->GetY()-10);
                    $this->MultiCell(15,$alto,utf8_decode('CRÉDITO FISCAL'),1,'C',1);
                    $this->SetXY($xe+=15,$this->GetY()-10);

                    $this->MultiCell(20,$alto,utf8_decode('CÓDIGO CONTROL'),1,'C',1);
                    $this->SetXY($xe+=20,$this->GetY()-10);
                    $this->MultiCell(8,10,utf8_decode('TIPO'),1,'R',1);
                    //$this->Ln(5);
        }

        public function Footer(){
                //NUMERO PIED PAGINA
                $this->SetY(-12);
                $this->SetFont('Arial','I', 8);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>