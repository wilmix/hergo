<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Recibo_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
            
        }
        public function Header() {
            $fechaPago = date('d/m/Y',strtotime($this->datos['fechaPago']));
            //TITULO
            $this->SetXY(10,10);
            $this->Image('images/hergo.jpeg', 10, 7, 45 );
            $this->SetFont('Arial','B',9);
            $this->SetXY(15,18);
            $this->Cell(40,6, $this->datos['almacen'] ,0,0,'C');
            $this->Ln(3);
            $this->SetX(15);
            $this->SetFont('Arial','',5);
            $this->Cell(40,6, utf8_decode($this->datos['direccion']),0,0,'C');
            $this->Ln(3);
            $this->SetX(15);
            $this->Cell(40,6, utf8_decode($this->datos['telefonos']),0,0,'C');

            $this->SetXY(10,10);
            $this->SetFont('Arial','B',18);
            $this->Cell(0,8, 'RECIBO',0,1,'C'); 

            $this->SetXY(170,12);
            $this->SetFont('Arial','B',15);
            $this->Cell(40,8, "REC - " .$this->datos['numPago'],1,1,'C');
            $this->SetXY(170,20);
            $this->SetFont('Arial','B',12);
            $this->Cell(40,8, $fechaPago,1,0,'C');
            $this->Ln(10);

            $this->SetXY(10,32);
            $this->SetFont('Arial','',10);
            $this->Cell(10,6, 'Recibimos de: '. utf8_decode($this->datos['nombreCliente']),0,0,'L');
            $this->SetXY(10,38);
            $entera = intval($this->datos['totalPago']);
            $ctvs = round(($this->datos['totalPago'] - $entera) * 100);
            $ctvs = sprintf('%02d',$ctvs);
            $literal = NumeroALetras::convertir($this->datos['totalPago']).$ctvs.'/100 '.'BOLIVIANOS';
            $this->Cell(10,6, 'La suma de: '.$literal,0,0,'L');
            $this->SetXY(10,45);
            $this->Cell(10,6, 'Por concepto de: ',0,0,'L');

           
                //****ENCABEZADO****
                    //ENCABEZADO TABLA
                    $this->SetXY(35,50);
                    $this->Ln(1);
                    $this->SetFillColor(235,235,235);
                    $this->SetFont('Arial','B',8); 
                    $this->Cell(20,6,utf8_decode('Nº'),0,0,'C',1);
                    $this->Cell(20,6,'SUC',0,0,'C',1);
                    $this->Cell(25,6,'Nro FACTURA',0,0,'C',1);
                 //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                    $this->Cell(100,6,'CLIENTE',0,0,'C',1);
                    $this->Cell(30,6,'MONTO',0,0,'R',1);
                    $this->Ln(6);
        }


        public function Footer(){
            $this->SetLineWidth(0.5);
            $this->Line(10,120,206,120);
            $this->SetY(-155);
            $this->SetFont('Arial','',10); 

             if ($this->datos['idTipoPago'] == 3) {
                 $tipo = utf8_decode($this->datos['tipoPago'] . ' Nº ' . $this->datos['cheque']);
             } else if ($this->datos['idTipoPago'] == 2) {
                 $tipo = utf8_decode($this->datos['tipoPago'] . ' DE ' . $this->datos['nomBanco'] . ' Nº '.$this->datos['transferencia']);
             } else{
                $tipo = $this->datos['tipoPago'];
             }
             $this->Cell(15,6,'Tipo de Pago: '.$tipo,0,0,'L',0);
             $this->SetY(-150);
             $this->Cell(15,6,'Observaciones: '.$this->datos['glosa'],0,0,'L',0);

             $this->SetXY(165,-145);
             $this->SetFont('Arial','BI', 9);
             $this->Cell(40,5, 'Recibido por',0,0,'C',0);
             $this->SetXY(165,-140);
             $this->SetFont('Arial','BI', 9);
             $this->Cell(40,5, utf8_decode($this->datos['userName']),0,0,'C',0);
             
                //NUMERO PIED PAGINA
                $this->SetY(-138);
                $this->SetFont('Arial','I', 8);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>