<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class VentasNotario_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
            $this->datos['mes'] = strtoupper($params[1]);

        }
        public function Header() {
            //TITULO
            $this->SetXY(5,10);
            $this->Image('images/hergo.jpeg',5, 5, 45 );
            $this->SetFont('Arial','B',9);
            $this->SetXY(5,16);
            $this->Cell(50,4, 'NIT: 1000991026',0,1,'C',0);
            $this->SetX(5);
            $this->Cell(50,4, utf8_decode($this->datos[0]->sucursal) ,0,1,'C');
            $this->SetFont('Arial','B',7);
            $this->SetX(5);
            $this->MultiCell(55,3,utf8_decode($this->datos[0]->address),0,'C',0);
            $this->SetX(5);
            $this->Cell(50,4, utf8_decode($this->datos[0]->ciudad . '- BOLIVIA') ,0,1,'C');
            
            
            $this->SetXY(5,20);
            $this->SetFont('Arial','B',18);
            $this->Cell(0,8, 'LIBRO DE VENTAS IVA',0,1,'C'); 
            $this->SetFont('Arial','BU',14);
            $this->Cell(0,8, "PERIODO: " . utf8_decode($this->datos['mes']),0,1,'C');

                //ENCABEZADO TABLA
                $this->SetFillColor(255,255,255);
                $this->SetFont('Arial','B',5); 
                $this->SetFillColor(232,232,232);
                $xe = 5;
                $alto = 5;
                $this->SetX(5);
                $this->Cell(5,10,utf8_decode('Nº'),1,0,'C',1);
                $this->Cell(5,10,utf8_decode('Nº'),1,0,'C',1);
                $this->MultiCell(15,$alto,utf8_decode('FECHA FACTURA'),1,'C',1);
                $this->SetXY($xe+=25,$this->GetY()-10);
                $this->MultiCell(10,$alto,utf8_decode('Nº DE FACT.'),1,'C',1);
                $this->SetXY($xe+=10,$this->GetY()-10);
                $this->MultiCell(22,$alto*2,utf8_decode('Nº DE AUTORIZACIÓN'),1,'C',1);
                $this->SetXY($xe+=22,$this->GetY()-10);
                
                $this->Cell(5,10,utf8_decode('EST'),1,0,'C',1);
                $this->MultiCell(20,$alto*2,utf8_decode('NIT CLIENTE'),1,'L',1);
                $this->SetXY($xe+=20,$this->GetY()-10);

                $this->MultiCell(50,$alto*2,utf8_decode('NOMBRE RAZON SOCIAL'),1,'C',1);
                $this->SetXY($xe+=50,$this->GetY()-10);

                $this->MultiCell(15,$alto*2,utf8_decode('TOTAL VENTA'),1,'C',1);
                $this->SetXY($xe+=15,$this->GetY()-10);
                $this->MultiCell(15,$alto*2,utf8_decode('ICE / IEHD'),1,'C',1);
                $this->SetXY($xe+=15,$this->GetY()-10);
                $this->MultiCell(15,$alto,utf8_decode('EXPORT EXENTAS'),1,'C',1);
                $this->SetXY($xe+=15,$this->GetY()-10);
                $this->MultiCell(15,$alto,utf8_decode('GRAVADAS TASA 0'),1,'C',1);
                $this->SetXY($xe+=15,$this->GetY()-10);
                $this->MultiCell(15,$alto*2,utf8_decode('SUB TOTAL'),1,'C',1);
                $this->SetXY($xe+=15,$this->GetY()-10);
                $this->MultiCell(15,$alto,utf8_decode('DESC. REBAJAS'),1,'C',1);
                $this->SetXY($xe+=15,$this->GetY()-10);
                $this->MultiCell(15,$alto,utf8_decode('BASE CREDITO F.'),1,'C',1);
                $this->SetXY($xe+=15,$this->GetY()-10);
                $this->MultiCell(15,$alto,utf8_decode('DÉBITO FISCAL'),1,'C',1);
                $this->SetXY($xe+=15,$this->GetY()-10);
                $this->MultiCell(20,$alto*2,utf8_decode('CÓDIGO CONTROL'),1,'C',1);
        }

        public function Footer(){
            //NUMERO PIED PAGINA
            /* $this->SetY(-12);
            $this->SetFont('Arial','I', 8);
            $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' ); */
        }
    }
?>