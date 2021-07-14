<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/phpqrcode/qrlib.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Proforma_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
            
        }
        public function Header() {
             //TITULO
             $borde = 0;
            $this->Image('images/hergo.jpeg', 10, 8, 55 );
            $this->SetFont('Arial','B',16);
            $this->SetXY(150,12);
            $titulo = 'PROFORMA ' . str_pad($this->datos['num'], 3, '0', STR_PAD_LEFT) .'/' . date('y',strtotime( $this->datos['fecha'] )) ;
            $this->Cell(60,8, utf8_decode($titulo),$borde,0,'C');
            $this->SetXY(150,20);
            $this->SetFont('Arial','B',12);
            $this->Cell(60,8, utf8_decode('Fecha : ' . date('d/m/Y',strtotime($this->datos['fecha'])) ),$borde,0,'C',0);
            $this->SetXY(150,28);
            $this->SetFont('Arial','B',10);
            //$this->Cell(60,8, utf8_decode('Validez: ' . $this->datos['validezOferta'] ),$borde,0,'C',0);
            $this->Cell(60,8, utf8_decode( 'Moneda: ' . $this->datos['moneda'] ),$borde,0,'C',0);
            $this->SetXY(150,36);
            $this->MultiCell(60,5,iconv('UTF-8', 'windows-1252', ('Validez: ' . $this->datos['validezOferta'])),$borde,'C',0);
            


            $this->SetFont('Arial','B',12);
            $this->SetXY(10,22);
            $this->Cell(55,5, utf8_decode('NIT: 1000991026'),$borde,0,'C');
            $this->Ln(5);
            $this->SetFont('Arial','',7.5);
            $this->Cell(55,5, utf8_decode('Av. Montes Nº 611 - Casilla 1024'),$borde,0,'');
            $this->Ln(5);
            $this->Cell(55,5, utf8_decode('Telfs: 228 5837 - 228 5854 - Fax 212 6286'),$borde,0,'');
            $this->Ln(5);
            $this->Cell(55,5, utf8_decode('La Paz - Bolivia'),$borde,0,'');

            $this->SetFillColor(20,60,190);
            $this->Rect(64,20,1,30,'F');

            $xCliente = 65;
            $this->SetFont('Arial','B',12);
            $this->SetXY($xCliente,20);
            //$this->Cell(85,5, utf8_decode('Para: ' . $this->datos['clienteNombre']),$borde,0,'C');
            $this->MultiCell(85,5,iconv('UTF-8', 'windows-1252', ('Para: ' . $this->datos['clienteNombre'])),$borde,'C',0);
            //if (($this->datos['clienteTelefono'])) {
                $this->SetXY($xCliente,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(15,5, utf8_decode('Teléfono:'),$borde,0,'');
                $this->SetFont('Arial','',8);
                $this->Cell(70,5, utf8_decode($this->datos['clienteTelefono']),$borde,0,'');
            //} 
        
            //if (isset($this->datos['clienteEmail'])) {
                $this->SetXY($xCliente,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(10,5, utf8_decode('e-mail:'),$borde,0,'');
                $this->SetFont('Arial','',8);
                $this->Cell(75,5, utf8_decode(strtolower($this->datos['clienteEmail'])),$borde,0,'');
            //} 
            //if (isset($this->datos['clienteDirec'])) {
                $this->SetXY($xCliente,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(15,5, utf8_decode('Dirección:'),$borde,0,'');
                //$this->SetXY($xCliente,$this->GetY()+5);
                $this->SetFont('Arial','',8);
                $this->MultiCell(70,5,iconv('UTF-8', 'windows-1252', ($this->datos['clienteDirec'])),$borde,'L',0);
            //} 
            $this->SetFillColor(20,60,190);
            $this->Rect(150,12,1,33,'F');


   
            $this->SetFont('Arial','B',10);
            $this->SetFillColor(255,255,255);
            
            
            
            $l = 0;
            $this->Ln(5);
            $this->SetFillColor(20,60,190);
                $this->SetFont('Arial','B',8); 
                $this->SetTextColor(255,255,255);
                  $this->Cell(5,6,utf8_decode('Nº'),$l,0,'C',1);
                  $this->Cell(25,6,'Imagen',$l,0,'C',1);
                  $this->Cell(15,6,utf8_decode('Código'),$l,0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                  $this->Cell(45,6,utf8_decode('Descripción'),$l,0,'C',1);
                  $this->Cell(15,6,'Marca',$l,0,'C',1);
                  $this->Cell(15,6,'Industria',$l,0,'C',1);
                  $this->Cell(15,6,'Entrega',$l,0,'C',1);
                  $this->Cell(15,6,'Cant.',$l,0,'R',1);
                  $this->Cell(10,6,'Uni.',$l,0,'R',1);
                  $this->Cell(20,6,'P/U',$l,0,'R',1);
                  $this->Cell(20,6,'Total',$l,0,'R',1);
                  $this->Ln(6);
        }

        public function Footer(){
            $borde = 0;
            $this->SetDrawColor(20,60,190);
            $this->SetLineWidth(1);
            $this->Line(10,240,210,240);

            $this->SetXY(10,245);
            $this->SetFillColor(255,255,255);
            $xData = 10;
            $this->SetXY($xData,$this->GetY()-9);
            
            if (isset($this->datos['condicionesPago']) && $this->datos['condicionesPago'] ==! '') {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(25,5, utf8_decode('Forma de Pago: '),$borde,0,'',1);
                $this->SetFont('Arial','',8);
                $this->Cell(85,5, utf8_decode($this->datos['condicionesPago']),'R',0,'',1);
            }
            if (isset($this->datos['validezOferta']) && $this->datos['validezOferta'] ==! '') {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(25,5, utf8_decode('Validez de Oferta: '),$borde,0,'',1);
                $this->SetFont('Arial','',8);
                $this->Cell(85,5, utf8_decode($this->datos['validezOferta']),'R',0,'',1);
            }
            if (isset($this->datos['garantia']) && $this->datos['garantia'] ==! '') {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(15,5, utf8_decode('Garantia: '),$borde,0,'',1);
                $this->SetFont('Arial','',8);
                $this->Cell(95,5, utf8_decode($this->datos['garantia']),'R',0,'',1);
            }
            if (isset($this->datos['tiempoEntregaC']) && $this->datos['tiempoEntregaC'] ==! '') {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(30,5, utf8_decode('Tiempo de Entrega: '),$borde,0,'',1);
                $this->SetFont('Arial','',8);
                $this->Cell(80,5, utf8_decode($this->datos['tiempoEntregaC']),'R',0,'',1);
            }
            if (isset($this->datos['lugarEntrega']) && $this->datos['lugarEntrega'] ==! '') {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(25,5, utf8_decode('Lugar de Entrega:'),$borde,0,'');
                $this->SetFont('Arial','',8);
                $this->Cell(85,5, utf8_decode($this->datos['lugarEntrega']),'R',0,'',1);
            }

                
                    $this->SetXY(140,260);
                    $this->Cell(50,4, utf8_decode('Elaborado por:'),0,0,'C');
                    $this->Ln(3);
                    $this->SetX(140);
                    $this->Cell(50,4, utf8_decode($this->datos['autorNombre']),0,0,'C');
                    $this->Ln(3);
                    $this->SetX(140);
                    $autorPie = "{$this->datos['autorEmail']} - {$this->datos['autorPhone']}";
                    $this->Cell(50,4, utf8_decode($autorPie),0,0,'C');
                //NUMERO PIED PAGINA
                $this->SetY(270);
                $this->SetFont('Arial','I', 6);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }

    }
?>