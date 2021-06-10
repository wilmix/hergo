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
            $this->SetFont('Arial','B',18);
            $this->SetXY(150,8);
            $titulo = 'PROFORMA ' . str_pad($this->datos['num'], 3, '0', STR_PAD_LEFT) .'/' . date('y',strtotime( $this->datos['fecha'] )) ;
            $this->Cell(60,10, utf8_decode($titulo),$borde,0,'');

            $this->SetFont('Arial','B',10);
            $this->SetXY(10,25);
            $this->Cell(50,5, utf8_decode('NIT: 1000991026'),$borde,0,'');
            $this->Ln(5);
            $this->SetFont('Arial','',7);
            $this->Cell(50,5, utf8_decode('Av. Montes Nº 611 - Casilla 1024'),$borde,0,'');
            $this->Ln(5);
            $this->Cell(50,5, utf8_decode('Telfs: 228 5837 - 228 5854 - Fax 212 6286'),$borde,0,'');
            $this->Ln(5);
            $this->Cell(50,5, utf8_decode('La Paz - Bolivia'),$borde,0,'');

            $this->SetFillColor(20,60,190);
            $this->Rect(60,25,1,20,'F');
            $xCliente = 65;
            $this->SetFont('Arial','B',10);
            $this->SetXY($xCliente,20);
            $this->Cell(60,5, utf8_decode('Para: ' . $this->datos['clienteNombre']),$borde,0,'');
            if (isset($this->datos['clienteTelefono'])) {
                $this->SetXY($xCliente,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(15,5, utf8_decode('Teléfono:'),$borde,0,'');
                $this->SetFont('Arial','',8);
                $this->Cell(45,5, utf8_decode($this->datos['clienteTelefono']),$borde,0,'');
            }
            if (isset($this->datos['clienteEmail'])) {
                $this->SetXY($xCliente,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(10,5, utf8_decode('e-mail:'),$borde,0,'');
                $this->SetFont('Arial','',8);
                $this->Cell(50,5, utf8_decode(strtolower($this->datos['clienteEmail'])),$borde,0,'');
            }
            if (isset($this->datos['clienteDirec'])) {
                $this->SetXY($xCliente,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(60,5, utf8_decode('Dirección:'),$borde,0,'');
                $this->SetXY($xCliente,$this->GetY()+5);
                $this->SetFont('Arial','',8);
                $this->Cell(60,5, utf8_decode(ucwords(strtolower($this->datos['clienteDirec']))),$borde,0,'');
            }

            
            $this->SetFillColor(20,60,190);
            $this->Rect(125,25,1,20,'F');

            $this->SetFont('Arial','B',10);
            $this->SetFillColor(255,255,255);
            $xData = 128;
            $this->SetXY($xData,20);
            $this->Cell(80,5, utf8_decode('Fecha : ' . date('d/m/Y',strtotime($this->datos['fecha'])) ),$borde,0,'',1);
            if (isset($this->datos['condicionesPago'])) {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(25,5, utf8_decode('Forma de Pago: '),$borde,0,'',1);
                $this->SetFont('Arial','',8);
                $this->Cell(55,5, utf8_decode($this->datos['condicionesPago']),$borde,0,'',1);
            }
            if (isset($this->datos['validezOferta'])) {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(25,5, utf8_decode('Validez de Oferta: '),$borde,0,'',1);
                $this->SetFont('Arial','',8);
                $this->Cell(55,5, utf8_decode($this->datos['validezOferta']),$borde,0,'',1);
            }
            if (isset($this->datos['garantia'])) {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(15,5, utf8_decode('Garantia: '),$borde,0,'',1);
                $this->SetFont('Arial','',8);
                $this->Cell(65,5, utf8_decode($this->datos['garantia']),$borde,0,'',1);
            }
            if (isset($this->datos['tiempoEntrega'])) {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(30,5, utf8_decode('Tiempo de Entrega: '),$borde,0,'',1);
                $this->SetFont('Arial','',8);
                $this->Cell(50,5, utf8_decode($this->datos['tiempoEntrega']),$borde,0,'',1);
            }
            if (isset($this->datos['lugarEntrega'])) {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Arial','B',8);
                $this->Cell(25,5, utf8_decode('Lugar de Entrega:'),$borde,0,'');
                $this->SetFont('Arial','',8);
                $this->Cell(55,5, utf8_decode($this->datos['lugarEntrega']),$borde,0,'',1);
            }
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
            $this->Rect(10,230,200,1,'F');
            $this->Rect(145,245,1,30,'F');

           
                //leyendas
                $glosas = explode('<br />',$this->datos['glosa']);
               //print_r($glosas);die();

                $this->SetY(235);
                $this->SetFont('Arial','B',8);
                $this->Cell(0,4, utf8_decode('OBSERVACIÓNES: '),0,0,'L');
                $this->Ln(4);
                $this->SetFont('Arial','',7);
                $yyy = 5;
                foreach ($glosas as $glosa) {
                    $this->MultiCell(130,5,$glosa,0,'L',0);
                    $this->SetXY(10,$this->GetY()-5);
                }
                $this->SetXY(155,260);
                    $this->Cell(50,4, utf8_decode('Elaborado por:'),0,0,'C');
                    $this->Ln(3);
                    $this->SetX(155);
                    $this->Cell(50,4, utf8_decode('Willy Salas'),0,0,'C');
                    $this->Ln(3);
                    $this->SetX(155);
                    $this->Cell(50,4, utf8_decode('willy@hergo.com.bo - 75288681'),0,0,'C');
                //NUMERO PIED PAGINA
                $this->SetY(270);
                $this->SetFont('Arial','I', 6);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }

    }
?>