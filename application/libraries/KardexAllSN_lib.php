<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class KardexAllSN_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
        }
        public function Header() {
            $date = date("d") . "/" . date("m") . "/" . date("Y");
            $alm = $this->datos['alm'];
            $gestion = $this->datos['gestion'];
            
            //TITULO
            $this->SetXY(20,10);
            $this->Image('images/hergo.jpeg', 20, 10, 35 );
            $this->SetFont('Arial','B',10);
            $this->SetXY(20,20);
            $this->SetFont('Arial','B',8);
            $this->Cell(35,3, "$alm",0,0,'C');
            $this->SetXY(10,25);

            $this->SetXY(20,10);
            $this->SetFont('Arial','BU',13);
            $this->Cell(0,8, 'KARDEX INDIVIDUAL ITEMES VALORADO',0,1,'C'); 
            $this->Cell(0,8, utf8_decode("Gestión $gestion"),0,0,'C');

            $this->Ln(5);
            $this->SetXY(180,10);
            $this->SetFont('Arial','',9);
            $this->Cell(0,8, utf8_decode($date),0,0,'C');

            $this->Ln(15);
                    //ENCABEZADO TABLA
                    $this->SetFont('Arial','B',7); 
                    $this->SetFillColor(250,250,250);
                    $this->Ln(6);
                    $this->SetX(20);
                    $this->Cell(12,5,'Fecha','B',0,'C',1);
                    $this->Cell(8,5,utf8_decode('Núm.'),'B',0,'C',1);
                    $this->Cell(70,5,'Cliente/Proveedor/Almacen','B',0,'C',1);
                    $this->Cell(10,5,'P.Unit','B',0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                    //$this->Cell(7,5,'Alm.','B',0,'C',1);
                    $this->Cell(15,5,'Ingreso','B',0,'R',1);
                    $this->Cell(15,5,'Factura','B',0,'R',1);
                    //$this->Cell(15,5,'N.E.','B',0,'R',1);
                    $this->Cell(15,5,'Traspaso','B',0,'R',1);
                    $this->Cell(15,5,'Cantidad','B',0,'R',1);
                    $this->Cell(20,5,'Valorado ','B',0,'R',1);
                    $this->Ln(8);
        }

        public function Footer(){
                //NUMERO PIED PAGINA
                $this->SetY(-14);
                $this->SetFont('Arial','I', 8);
                $this->Cell(0,10, utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>