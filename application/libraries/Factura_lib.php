<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    //require_once APPPATH."/third_party/phpqrcode/qrlib.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Factura_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
        }
        public function Header() {
            //$this->setlocale(LC_ALL,"es_ES");
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
            $mes = date('n',strtotime($fechaFac));
            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $fechaFacQR = date('d/m/Y',strtotime($this->datos['fechaFac']));
            $total = $this->datos['total'];
            $baseCreditoFiscal = $total;
            $ClienteNit = $this->datos['ClienteNit'];
            $codigoControl = $this->datos['codigoControl'];
            $idAlmacen = $this->datos['idAlmacen'];
            $pedido = $this->datos['pedido'];


            $qr = $nit.'|'.$nFactura.'|'.$autorizacion.'|'.$fechaFacQR.'|'.number_format($total, 2, ".","")
            .'|'.number_format($total, 2, ".","").'|'.$codigoControl.'|'.$ClienteNit.'|'.'0'.'|'.'0'.'|'.'0'.'|'.'0';

            //QRcode::png($qr,"qr.png",QR_ECLEVEL_L,3,2);

            //var_dump($manual);
            /* if ($manual==='0') {
                $this->SetY(-136);
                $this->SetX(177);
                $this->Image('qr.png');
            }*/
            if ($manual==='0') {
                $this->SetY(-136);
                $this->SetX(177);
                //$this->Image('http://api.qrserver.com/v1/create-qr-code/?data='.$qr.'&size=100x100'.'.png', 178, 143, 30);
                $this->Image('https://www.qrcoder.co.uk/api/v1/?text='.$qr, 178, 143, 30,0,'PNG');
            }

            //TITULO
            $this->SetXY(10,9);
            $this->Image('images/hergo.jpeg', 15, 8, 45 );
            $this->SetFont('Arial','B',24);
            $this->SetTextColor(0,0,200);
            $this->Cell(0,15, '',0,0,'C');
            $this->Ln(10);
            $this->SetFont('Arial','B',12);
            $this->Cell(0,10, '',0,0,'C');

            /*$this->SetFont('Arial','B',10);
            $this->Cell(0,10, 'COPIA CONTABILIDAD',0,0,'C');
            $this->Ln(5);
            $this->Cell(0,10, utf8_decode('Sin Derecho a Crédito Fiscal'),0,0,'C');*/

            //SUCURSAL
            $this->SetXY(10,18);
            //$this->SetTextColor(0,0,0);
            if ($idAlmacen == 1) {
                $this->Ln(2);
            }
            if ($idAlmacen != 1) {
                $this->SetFont('Arial','B',7);
                $this->Cell(60,4,utf8_decode($this->datos['matrizSucursal']),0,1,'C');
                $this->SetFont('Arial','',5);
                $this->Cell(60,4,utf8_decode($this->datos['matrizDireccion']),0,1,'C');
                 $this->Cell(60,4,utf8_decode($this->datos['matrizTelefonos']),0,1,'C');
            }
            $this->SetFont('Arial','B',7);
            $this->Cell(60,4,utf8_decode($sucursal),0,1,'C');
            $this->SetFont('Arial','',5);
            $this->Cell(60,4,utf8_decode($direccion),0,1,'C');
            $this->Cell(60,4,utf8_decode($Telefonos),0,1,'C');


            //n FACTURA DERECHA
            $this->SetXY(138,10);
            $this->SetFont('Arial','B',14);
            $this->SetTextColor(0,0,200);
            $this->Cell(70,7,'NIT: '.$nit,0,1,'C');
            $this->SetXY(138,17);
            $this->SetFont('Arial','B',8);
            $this->SetTextColor(0,0,0);
            $this->Cell(32,8, utf8_decode('FACTURA N°:'),0,0,'R');
            $this->SetFont('Arial','B',14);
            $this->Cell(35,8, utf8_decode($nFactura),0,0,'C');
            $this->SetXY(138,25);
            $this->SetFont('Arial','B',7);
            $this->Cell(32,8, utf8_decode('AUNTORIZACIÓN N°:'),0,0,'R');
            $this->SetFont('Arial','B',11);
            $this->Cell(35,8, utf8_decode($autorizacion),0,0,'C');
            $this->SetXY(138,33);
            $this->SetFont('Arial','I',7);
            $this->MultiCell(70,5, utf8_decode($glosa03),0,'C',false);

            
                //****ENCABEZADO****
                $this->SetXY(13,42);
                //cliente
                $this->SetFont('Arial','B',9);
                $this->Cell(25,6, utf8_decode('Lugar y Fecha: '),0,0,'');
                $this->SetFont('Arial','',9);
                $this->Cell(100, 6, utf8_decode($ciudad . ', ' .$dia. ' de '. $meses[($mes)-1] . ' del '. $year), 0,0,'L');
                $this->SetFont('Arial','B',9);
                $this->Ln(6);
                $this->SetX(13);
                $this->Cell(25,6, utf8_decode('Señor(es): '),0,0,'');
                $this->SetFont('Arial','',9);
                $this->Cell(125, 6, utf8_decode($ClienteFactura), 0,0,'L');
                $this->SetFont('Arial','B',9);
                $this->Cell(15,6, utf8_decode('NIT/CI: '),0,0,'');
                $this->SetFont('Arial','',9);
                $this->Cell(30, 6, utf8_decode($ClienteNit), 0,0,'L');
                
                
                if ($pedido) {
                    $this->Ln(6);
                    $this->SetX(13);
                    $this->SetFont('Arial','B',9);
                    $this->Cell(25,6, utf8_decode('OC/PedidoNº: '),0,0,'');
                    $this->SetFont('Arial','',9);
                    $this->Cell(125, 6, utf8_decode($pedido), 0,0,'L');
                    $this->Ln(8);
                } else {
                    $this->Ln(8);
                }
                

                

                

                    //ENCABEZADO TABLA
                    $this->SetX(15);
                    $this->Ln(1);
                    $this->SetFillColor(235,235,235);
                    $this->SetFont('Arial','B',8); 
                    //$this->Cell(5,6,'N',1,0,'C',1);
                    $this->Cell(15,6,'CANT',0,0,'C',1);
                    $this->Cell(10,6,'UNID',0,0,'C',1);
                    $this->Cell(15,6,'CODIGO',0,0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                    $this->Cell(118,6,'ARTICULO',0,0,'C',1);
                    $this->Cell(20,6,'P/U',0,0,'R',1);
                    $this->Cell(20,6,'TOTAL',0,0,'R',1);
                    $this->Ln(6);
        }

        public function Footer(){
            $glosa01 = $this->datos['glosa01'];
            $glosa02 = $this->datos['glosa02'];
            $fechaLimite = date('d/m/Y',strtotime($this->datos['fechaLimite']));
            $codigoControl = $this->datos['codigoControl'];
            $glosa = $this->datos['glosa'];
            $manual = $this->datos['manual'];
            $this->SetLineWidth(0.5);
            $this->Line(10,140,208,140); //ok


            if ($manual==='0') {
                $this->SetY(-130);
                $this->SetFont('Arial','B',7);
                $this->Cell(10,5, utf8_decode('NOTA: '),0,0,'');
                $this->Cell(160,5, utf8_decode($glosa),0,0,'L');
                /*$this->SetY(-35);
                $this->SetFont('Arial','',7);
                $this->Cell(170,5, utf8_decode(''),0,0,'');*/
            } else {
                $this->SetY(-130);
                $this->SetFont('Arial','',7);
                $this->Cell(10,5, utf8_decode('NOTA: '),0,0,'');
                $this->Cell(160,5, utf8_decode($glosa. 'Factura Manual'),0,0,'L');
                /*$this->SetY(-35);
                $this->SetFont('Arial','',7);
                $this->Cell(170,5, utf8_decode(''),0,0,'');*/
            }
            

            if ($manual === '0') {
                $this->SetY(-120);
                $this->SetFont('Arial','B',7);
                $this->Cell(40,5, utf8_decode('FECHA LÍMITE DE EMISIÓN: '),0,0,'');
                $this->SetFont('Arial','',8);
                $this->Cell(30,5, utf8_decode($fechaLimite),0,0,'C');
                $this->SetY(-115);
                $this->SetFont('Arial','B',7);
                $this->Cell(40,5, utf8_decode('CÓDIGO DE CONTROL: '),0,0,'');
                $this->SetFont('Arial','',8);
                $this->Cell(30,5, utf8_decode($codigoControl),0,0,'C');
            }
            
            
                //leyendas
                $this->SetY(-103);
                $this->SetFont('Arial','',7);
                $this->Cell(0,4, utf8_decode($glosa01),0,0,'C');
                $this->SetY(-99);
                $this->SetFont('Arial','',7);
                $this->Cell(0,4, utf8_decode($glosa02),0,0,'C');
                //NUMERO PIED PAGINA
                $this->SetY(-97);
                $this->SetFont('Arial','I', 9);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }

    }
?>