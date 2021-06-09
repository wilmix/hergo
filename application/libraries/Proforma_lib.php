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
            //$this->setlocale(LC_ALL,"es_ES");
            $almacen = $this->datos['almacen'];
            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $fecha = date('d/m/Y',strtotime($this->datos['fecha']));


             //TITULO
            $this->Image('images/hergo.jpeg', 10, 8, 55 );
            $this->SetFont('Arial','B',20);
            $this->SetXY(143,10);
            $titulo = 'PROFORMA ' . str_pad($this->datos['num'], 3, '0', STR_PAD_LEFT) .'/' . date('y',strtotime( $this->datos['fecha'] )) ;
            $this->Cell(70,5, utf8_decode($titulo),0,0,'');

            $this->SetFont('Arial','B',12);
            $this->SetXY(10,25);
            $this->Cell(70,5, utf8_decode('NIT: 1000991026'),0,0,'');
            $this->Ln(5);
            $this->SetFont('Arial','',10);
            $this->Cell(70,5, utf8_decode('Av. Montes Nº 611 - Casilla 1024'),0,0,'');
            $this->Ln(5);
            $this->Cell(70,5, utf8_decode('Telfs: 228 5837 - 228 5854 - Fax 212 6286'),0,0,'');
            $this->Ln(5);
            $this->Cell(70,5, utf8_decode('La Paz - Bolivia'),0,0,'');

            $this->SetFillColor(20,60,190);
            $this->Rect(80,20,2,30,'F');
            
            $this->SetFont('Arial','B',12);
            $this->SetXY(85,25);
            $this->Cell(70,5, utf8_decode('PARA: WILLY SALAS'),0,0,'');
            $this->SetFont('Arial','',10);
            $this->SetXY(85,30);
            $this->Cell(70,5, utf8_decode('DIRECCION: LA CALLE DE MI CASA'),0,0,'');
            $this->SetXY(85,35);
            $this->Cell(70,5, utf8_decode('TELEFONO: 70572005'),0,0,'');
            $this->SetXY(85,40);
            $this->Cell(70,5, utf8_decode('EMAIL: wmx.seo@gmail.com'),0,0,'');

            $this->SetFillColor(20,60,190);
            $this->Rect(150,20,2,30,'F');

            $this->SetFont('Arial','B',12);
            $this->SetXY(155,25);
            $this->Cell(70,5, utf8_decode('Fecha : 20/12/2021'),0,0,'');
            $this->SetFont('Arial','',9);
            $this->SetXY(155,30);
            $this->Cell(70,5, utf8_decode('FORMA DE PAGO: CREDITO'),0,0,'');
            $this->SetXY(155,35);
            $this->Cell(70,5, utf8_decode('VALIDEZ DE LA OFERTA: 30 DIAS'),0,0,'');
            $this->SetXY(155,40);
            $this->Cell(70,5, utf8_decode('LUGAR DE ENTREGA:'),0,0,'');
            $this->SetXY(155,45);
            $this->Cell(70,5, utf8_decode(' RECOJO DE TODAS SUCURSALES'),0,0,'');

            
            
            $this->Cell(0,15, '',0,0,'C');
            $this->Ln(10);
            $this->SetFont('Arial','B',12);
            $this->Cell(0,10, '',0,0,'C');
            
        }

        public function Footer(){

            $this->SetLineWidth(0.5);
            $this->Line(10,260,208,260); //ok
            
                //leyendas
                $this->SetY(260);
                $this->SetFont('Arial','',7);
                $this->Cell(0,4, utf8_decode('glosa01'),0,0,'C');
                //NUMERO PIED PAGINA
                $this->SetY(270);
                $this->SetFont('Arial','I', 9);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }

    }
?>