<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class ReportEgresos_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
        }
        public function Header() {
            $date = date("d") . "/" . date("m") . "/" . date("Y");
            $alm = $this->datos['alm'];
            $ini = date('d/m/Y',strtotime($this->datos['ini']));
            $fin = date('d/m/Y',strtotime($this->datos['fin']));
            $tipo = $this->datos['tipo'];
            
            
            //TITULO

            $this->Image('images/hergo.jpeg', 10, 10, 35 );
            $this->SetFont('Arial','B',10);
            $this->SetXY(10,20);
            $this->SetFont('Times','B',8);
            $this->Cell(35,3, "$alm",0,0,'C');
            $this->SetXY(10,25);

            $this->SetXY(10,10);
            $this->SetFont('Arial','BU',13);
            //$this->Cell(0,8, 'REPORTE DE EGRESOS',0,1,'C'); 
            $this->Cell(0,8, utf8_decode("REPORTE $tipo"),0,1,'C');
            $this->SetFont('Arial','BI',10);
            $this->Cell(0,8, utf8_decode("del $ini al $fin"),0,0,'C');

            $this->Ln(5);
            $this->SetXY(180,10);
            $this->SetFont('Arial','',9);
            $this->Cell(0,8, utf8_decode($date),0,0,'C');
            $this->Ln(15);
                    //ENCABEZADO TABLA
                    $this->SetFont('Arial','B',7); 
                    $this->SetFillColor(250,250,250);
                    $this->Ln(6);
                    //$this->SetX(10);
                    $this->Cell(15,5,'Fecha','B',0,'C',1);//ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                    $this->Cell(10,5,utf8_decode('Núm.'),'B',0,'C',1);
                    $this->Cell(15,5,utf8_decode('Código'),'B',0,'C',1);
                    $this->Cell(80,5,utf8_decode('Descripción'),'B',0,'C',1);
                    $this->Cell(15,5,'Unid.','B',0,'R',1);
                    $this->Cell(10,5,'Mon.','B',0,'R',1);
                    $this->Cell(15,5,'Pre.Unit','B',0,'R',1);
                    $this->Cell(15,5,'Cant.','B',0,'R',1);
                    $this->Cell(20,5,'Importe ','B',0,'R',1);
                    $this->Ln(8);
        }

        public function Footer(){
                //NUMERO PIED PAGINA
                $this->SetY(-12);
                $this->SetFont('Arial','I', 8);
                $this->Cell(0,10, utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>