<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Factura_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
            //date_default_timezone_set('America/Bolivia/La_Paz');
            date_default_timezone_set("America/La_Paz");
        }
        public function Header() {
            $almacen = $this->datos['almacen'];
            $direccion = $this->datos['direccion'];
            $Telefonos = $this->datos['Telefonos'];
            $ciudad = ucwords($this->datos['ciudad']);
            $fechaFac = $this->datos['fechaFac'];
            $ClienteFactura = $this->datos['ClienteFactura'];
            $ClienteNit = $this->datos['ClienteNit'];
            $sucursal = $this->datos['sucursal'];
            $anulada = $this->datos['anulada'];
            $cambiovalor = $this->datos['cambiovalor'];
            $nit = $this->datos['nit'];
            $autorizacion = $this->datos['autorizacion'];
            $glosa03 = $this->datos['glosa03'];
            $manual = $this->datos['manual'];
            $nFactura = $this->datos['nFactura'];
            $userName = $this->datos['userName'];
            $year = date('Y',strtotime($fechaFac));
            $dia = date('d',strtotime($fechaFac));
            $mes = date('M',strtotime($fechaFac));


            //TITULO
            $this->SetXY(13,10);
            $this->Image('images/hergo.jpeg', 15, 8, 45 );
            $this->SetFont('Arial','B',24);
            $this->Cell(0,15, 'FACTURA',0,0,'C');
            //SUCURSAL
            $this->SetXY(13,18);
            $this->SetFont('Arial','B',8);
            $this->Cell(60,5,utf8_decode($sucursal),1,1,'C');
            $this->SetX(13);
            $this->SetFont('Arial','',7);
            $this->Cell(60,5,utf8_decode($direccion),1,1,'C');
            $this->SetX(13);
            $this->Cell(60,5,utf8_decode($Telefonos),1,1,'C');


            //n FACTURA DERECHA
            $this->SetXY(150,10);
            $this->SetFont('Arial','B',14);
            $this->Cell(60,7,'NIT: '.$nit,1,1,'C');
            $this->SetXY(150,17);
            $this->SetFont('Arial','B',8);
            $this->Cell(25,8, utf8_decode('FACTURA N°:'),1,0,'C');
            $this->SetFont('Arial','B',11);
            $this->Cell(35,8, utf8_decode($nFactura),1,0,'C');
            $this->SetXY(150,25);
            $this->SetFont('Arial','B',7);
            $this->Cell(25,8, utf8_decode('AUNTORIZACIÓN N°:'),1,0,'C');
            $this->SetFont('Arial','B',9);
            $this->Cell(35,8, utf8_decode($autorizacion),1,0,'C');
            $this->Ln(10);
            
                //****ENCABEZADO****
                $this->SetXY(13,40);
                //cliente
                $this->SetFont('Arial','B',9);
                $this->Cell(25,6, utf8_decode('Lugar y Fecha: '),1,0,'');
                $this->SetFont('Arial','',9);
                $this->Cell(100, 6, utf8_decode($ciudad . ', ' .$dia. ' de '. $mes . ' del '. $year), 1,0,'L');
                $this->SetFont('Arial','B',9);
                $this->Ln(6);
                $this->SetX(13);
                $this->Cell(25,6, utf8_decode('Señor(es): '),1,0,'');
                $this->SetFont('Arial','',9);
                $this->Cell(125, 6, utf8_decode($ClienteFactura), 1,0,'L');
                $this->SetFont('Arial','B',9);
                $this->Cell(15,6, utf8_decode('NIT/CI: '),1,0,'');
                $this->SetFont('Arial','',9);
                $this->Cell(30, 6, utf8_decode($ClienteNit), 1,0,'L');
                $this->Ln(6);
                $this->SetX(13);
                $this->SetFont('Arial','',7);
                $this->Cell(0,5, 'Actividad economica: '. utf8_decode($glosa03),1,0,'C');
                $this->Ln(6);

                

                    //ENCABEZADO TABLA
                    $this->SetX(13);
                    $this->Ln(1);
                    $this->SetFillColor(235,235,235);
                    $this->SetFont('Arial','B',8); 
                    //$this->Cell(5,6,'N',1,0,'C',1);
                    $this->Cell(15,6,'CANT',1,0,'C',1);
                    $this->Cell(10,6,'UNID',1,0,'C',1);
                    $this->Cell(15,6,'CODIGO',1,0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                    $this->Cell(115,6,'ARTICULO',1,0,'C',1);
                    $this->Cell(20,6,'P/U',1,0,'R',1);
                    $this->Cell(20,6,'TOTAL',1,0,'R',1);
                    $this->Ln(6);
        }

        public function Footer(){
            $glosa01 = $this->datos['glosa01'];
            $glosa02 = $this->datos['glosa02'];
            $fechaLimite = $this->datos['fechaLimite'];
            $llaveDosificacion = $this->datos['llaveDosificacion'];
            $codigoControl = $this->datos['codigoControl'];
            $glosa = $this->datos['glosa'];
            $nit = $this->datos['nit'];
            $nFactura = $this->datos['nFactura'];
            $autorizacion = $this->datos['autorizacion'];
            $fechaFac = date('d/m/Y',strtotime($this->datos['fechaFac']));
            $total = $this->datos['total'];
            $baseCreditoFiscal = $total*87/100;
            $ClienteNit = $this->datos['ClienteNit'];

            $qr = $nit.'|'.$nFactura.'|'.$autorizacion.'|'.$fechaFac.'|'.number_format($total, 2, ".","")
            .'|'.$baseCreditoFiscal.'|'.$codigoControl.'|'.$ClienteNit.'|'.'0'.'|'.'0'.'|'.'0'.'|'.'0';


            //codigo qr
            //$this->SetXY(100,-40);
            $this->Image('https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl='.$qr.'&.png',175,148,35);
            $this->SetY(-40);
            $this->SetFont('Arial','',7);
            $this->Cell(10,5, utf8_decode('SON: '),1,0,'');
            $this->Cell(130,5, utf8_decode(''),1,0,'C');
            $this->SetY(-35);
            $this->SetFont('Arial','',7);
            $this->Cell(10,5, utf8_decode('NOTA: '),1,0,'');
            $this->Cell(130,5, utf8_decode($glosa),1,0,'C');
            $this->SetY(-30);
            $this->SetFont('Arial','',7);
            $this->Cell(40,5, utf8_decode('FECHA LÍMITE DE EMISIÓN: '),1,0,'');
            $this->Cell(30,5, utf8_decode($fechaLimite),1,0,'C');
            $this->SetY(-25);
            $this->SetFont('Arial','',7);
            $this->Cell(40,5, utf8_decode('CÓDIGO DE CONTROL: '),1,0,'');
            $this->Cell(30,5, utf8_decode($codigoControl),1,0,'C');
            
                //leyendas
                $this->SetY(-16);
                $this->SetFont('Arial','',7);
                $this->Cell(0,4, utf8_decode($glosa01),1,0,'C');
                $this->SetY(-12);
                $this->SetFont('Arial','',7);
                $this->Cell(0,4, utf8_decode($glosa02),1,0,'C');
                //NUMERO PIED PAGINA
                $this->SetY(-10);
                $this->SetFont('Arial','I', 8);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>