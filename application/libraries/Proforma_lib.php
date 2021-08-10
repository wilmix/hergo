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
            $borde = 0;
            /* imagen marca */
            $this->Image('images/hergo.jpeg', 10, 8, 55 );
            $this->AddFont('Roboto','','Roboto-Regular.php');
            $this->AddFont('Roboto','B','Roboto-Bold.php');
            $this->SetFont('Roboto','B',16); 
            
            /* Almacen */
            $this->SetFont('Roboto','B',12);
            $this->SetXY(10,22);
            $this->Cell(55,5, utf8_decode('NIT: 1000991026'),$borde,0,'C');
            $this->Ln(5);
            $this->SetFont('Roboto','',7.5);
            $this->MultiCell(55,4,iconv('UTF-8', 'windows-1252', ($this->datos['address'])),$borde,'C',0);
            $this->SetXY(55,$this->GetY()-5);
            $this->Ln(5);
            $this->Cell(55,5, utf8_decode('Telf: ' . $this->datos['phone'] . ' - ' . 'www.hergo.com.bo'),$borde,'','C');
            $this->Ln(5);
            $this->Cell(55,5, utf8_decode($this->datos['ciudad'] . ' - BOLIVIA'),$borde,'','C');
            $this->SetFillColor(20,60,190);
            $this->Rect(64,20,1,30,'F');

            /* Clientes */
            $xCliente = 65;
            $this->SetFont('Roboto','B',12);
            $this->SetXY($xCliente,20);
            $this->MultiCell(85,5,iconv('UTF-8', 'windows-1252', ('Para: ' . $this->datos['clienteDatos'])),$borde,'C',0);
            $this->SetXY(85,$this->GetY()-5);
            $this->SetXY($xCliente,$this->GetY()+5);
            $this->SetFont('Roboto','',10);
            $comp = explode('<br />',$this->datos['complemento']);
            foreach ($comp as $ln) {
                $this->MultiCell(85,5,iconv('UTF-8', 'windows-1252', ($ln)),$borde,'L',0);
                $this->SetXY(65,$this->GetY());
            }
            //$this->MultiCell(85,5,iconv('UTF-8', 'windows-1252', ('complemento'.$this->datos['complemento'])),$borde,'C',0);
           /*  $this->SetXY(85,$this->GetY()-5);
            $this->SetXY($xCliente,$this->GetY()+5);
            $this->MultiCell(85,5,iconv('UTF-8', 'windows-1252', ('Validez: ' . $this->datos['validezOferta'])),$borde,'C',0); */

            /* titulo proforma */
            $this->SetXY(150,12);
            $this->SetFont('Roboto','B',14);
            $titulo = 'PROFORMA ' . str_pad($this->datos['num'], 3, '0', STR_PAD_LEFT) .'/' . date('y',strtotime( $this->datos['fecha'] )) ;
            $this->Cell(60,8, utf8_decode($titulo),$borde,0,'C');
            $this->SetXY(150,20);
            $this->SetFont('Roboto','',12); 
            $this->Cell(60,8, utf8_decode('Fecha : ' . date('d/m/Y',strtotime($this->datos['fecha'])) ),$borde,0,'C',0);
            $this->SetXY(150,28);
            $this->SetFont('Roboto','',10);
            $this->Cell(60,8, utf8_decode( 'Moneda: ' . $this->datos['moneda'] ),$borde,0,'C',0);
            $this->Ln(13);
            $this->SetXY(150,36);
            $this->MultiCell(60,5,iconv('UTF-8', 'windows-1252', ('Validez: ' . $this->datos['validezOferta'])),$borde,'C',0);


            /* titulo tabla */
            $this->SetFillColor(20,60,190);
            $this->Rect(150,12,1,33,'F');
            $this->SetFont('Roboto','',10);
            $this->SetFillColor(255,255,255);

            $l = 0;
            $this->Ln(5);
            $this->SetFillColor(20,60,190);
                //$this->SetFont('Roboto','B',8);
                $this->SetFont('Roboto','',9); 
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
                $this->SetFont('Roboto','B',8);
                $this->Cell(26,5, utf8_decode('Forma de Pago: '),$borde,0,'',1);
                $this->SetFont('Roboto','',8);
                $this->Cell(85,5, utf8_decode($this->datos['condicionesPago']),'R',0,'',1);
            }
            if (isset($this->datos['validezOferta']) && $this->datos['validezOferta'] ==! '') {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Roboto','B',8);
                $this->Cell(26,5, utf8_decode('Validez de Oferta: '),$borde,0,'',1);
                $this->SetFont('Roboto','',8);
                $this->Cell(85,5, utf8_decode($this->datos['validezOferta']),'R',0,'',1);
            }
            if (isset($this->datos['garantia']) && $this->datos['garantia'] ==! '') {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Roboto','B',8);
                $this->Cell(26,5, utf8_decode('Garantia: '),$borde,0,'',1);
                $this->SetFont('Roboto','',8);
                $this->Cell(85,5, utf8_decode($this->datos['garantia']),'R',0,'',1);
            }
            if (isset($this->datos['tiempoEntregaC']) && $this->datos['tiempoEntregaC'] ==! '') {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Roboto','B',8);
                $this->Cell(26,5, utf8_decode('Tiempo de Entrega: '),$borde,0,'',1);
                $this->SetFont('Roboto','',8);
                $this->Cell(85,5, utf8_decode($this->datos['tiempoEntregaC']),'R',0,'',1);
            }
            if (isset($this->datos['lugarEntrega']) && $this->datos['lugarEntrega'] ==! '') {
                $this->SetXY($xData,$this->GetY()+5);
                $this->SetFont('Roboto','B',8);
                $this->Cell(26,5, utf8_decode('Lugar de Entrega:'),$borde,0,'');
                $this->SetFont('Roboto','',8);
                $this->Cell(85,5, utf8_decode($this->datos['lugarEntrega']),'R',0,'',1);
            }

                    if ($this->datos['firma']==1 && isset($this->datos['firmaAutor']) && empty(!$this->datos['firmaAutor'])) {
                        $this->Image('images/firmas/' . $this->datos['firmaAutor'] , 147, 248, 45 );
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
                $this->SetFont('Roboto','', 6);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>