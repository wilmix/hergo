<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/phpqrcode/qrlib.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class FacturaSiatLib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
        }
        public function Header() {
             //TITULO
            $l = '0';
            $this->SetXY(10,9);
            $this->Image('images/hergo.jpeg', 15, 8, 45 );
            $this->SetFont('Arial','B',24);
            $this->SetTextColor(0,0,200);
            $this->Cell(0,15, '',0,0,'C');
            $this->Ln(10);
            $this->SetFont('Arial','B',12);
            $this->Cell(0,10, '',0,0,'C');

            //SUCURSAL
            $this->SetXY(10,18);
            //$this->SetTextColor(0,0,0);
            /* if ($this->datos['sucursal'] == 'Casa Matriz - 0') {
                $this->Ln(2);
            } */
            $this->Ln(2);

            $this->SetFont('Arial','B',7);
           
            /* if ($this->datos['sucursal'] != 'Casa Matriz') {
                $this->SetFont('Arial','B',7);
                $this->Cell(60,4,utf8_decode($this->datos['sucursal']),0,1,'C');
                $this->SetFont('Arial','',5);
            } */
            $this->SetFont('Arial','B',7);
            $this->Cell(60,4,utf8_decode($this->datos['sucursal']),0,1,'C');
            $this->SetFont('Arial','',6);
            $this->Cell(60,3,utf8_decode('No. Punto de Venta: ' . $this->datos['codigoPuntoVenta']),0,1,'C');
            $this->Cell(60,3,utf8_decode($this->datos['direccion']),0,1,'C');
            $this->Cell(60,3,utf8_decode('Teléfono: ' . $this->datos['telefono']),0,1,'C');
            $this->Cell(60,3,utf8_decode($this->datos['ciudad']),0,1,'C');


            //n FACTURA DERECHA
            $this->SetXY(138,10);
            $this->SetFont('Arial','B',14);
            $this->SetTextColor(0,0,200);
            $this->Cell(70,7,'NIT: '.'1000991026',0,1,'C');
            $this->SetXY(138,17);
            $this->SetFont('Arial','B',8);
            $this->SetTextColor(0,0,0);
            $this->Cell(32,8, utf8_decode('FACTURA N°:'),0,0,'R');
            $this->SetFont('Arial','B',14);
            $this->Cell(35,8, utf8_decode($this->datos['numeroFactura']),0,0,'C');
            $this->SetXY(138,25);
            $this->SetFont('Arial','B',7);
            $this->Cell(32,8, utf8_decode('CÓD. AUTORIZACIÓN:'),0,0,'R');
            $this->SetFont('Arial','B',7);
            //$this->Cell(35,8, utf8_decode($this->datos['cuf']),1,0,'C');
            $this->MultiCell(35,4,utf8_decode($this->datos['cuf']),0,'L',0);
            //$this->SetXY(168,$this->pdf->GetY()-5);
            $this->SetXY(138,33);
            $this->SetFont('Arial','I',7);
            //$this->MultiCell(70,5, utf8_decode($glosa03),0,'C',false);

            
                //****ENCABEZADO****
                $this->SetXY(10,42);
                $fontSize = 9;
                //cliente
                $this->SetFont('Arial','B',$fontSize);
                $this->Cell(20,6, utf8_decode('Fecha: '),$l,0,'');
                $this->SetFont('Arial','',$fontSize);
                $this->Cell(40, 6, utf8_decode($this->datos['fechaEmision']),$l,0,'L');
                $this->SetFont('Arial','B',$fontSize);
                if ($this->datos['pedido'] ==! '') {
                    
                    $this->Cell(15,6, utf8_decode('Pedido: '),$l,0,'');
                    $this->SetFont('Arial','',$fontSize);
                    /* if ($this->datos['complemento']) {
                    $this->Cell(55, 6, utf8_decode($this->datos['pedido']),$l,0,'L');
                    } else { */
                        $this->Cell(68, 6, utf8_decode($this->datos['pedido']),$l,0,'L');
                    /* } */
                } else {
                   /*  if ($this->datos['complemento']) {
                    $this->Cell(15,6, '',$l,0,'');
                    $this->Cell(55,6, '',$l,0,'');
                    } else { */
                        $this->Cell(15,6, '',$l,0,'');
                        $this->Cell(68,6, '',$l,0,'');
                    /* } */

                }
                $this->SetFont('Arial','B',$fontSize);
                $this->Cell(20,6, utf8_decode('NIT/CI/CEX:'),$l,0,'');
                $this->SetFont('Arial','',$fontSize);
                
                if ($this->datos['complemento']) {
                    /* $this->SetFont('Arial','B',$fontSize);
                    $this->Cell(10,6, utf8_decode('-'),$l,0,''); */
                    $this->SetFont('Arial','',$fontSize);
                    $this->Cell(35, 6, utf8_decode($this->datos['documentoNumero'] . ' - ' . $this->datos['complemento']), $l,0,'R');
                    //$this->Cell(6, 6, utf8_decode(' - ' . $this->datos['complemento'] ), $l,0,'R');
                } else {
                    $this->Cell(35, 6, utf8_decode($this->datos['documentoNumero']), $l,0,'R');
                }
                $this->Ln(6);

                $this->SetX(10);
                $this->SetFont('Arial','B',$fontSize);
                $this->Cell(35,6, utf8_decode('Nombre/Razon Social: '),$l,0,'');
                $this->SetFont('Arial','',$fontSize);
                $this->Cell(108, 6, utf8_decode($this->datos['nombreRazonSocial']),$l,0,'L');
                $this->SetFont('Arial','B',$fontSize);
                $this->Cell(20,6, utf8_decode('Cod. Cliente:'),$l,0,'');
                $this->SetFont('Arial','',$fontSize);
                $this->Cell(35, 6, utf8_decode($this->datos['codigoCliente']),$l,0,'R');
                
                $this->Ln(8);

                

                

                    //ENCABEZADO TABLA
                    $this->SetX(15);
                    $this->Ln(1);
                    $this->SetFillColor(235,235,235);
                    $f = '0';
                    $x = 25;
                    $this->SetLineWidth(0.5);
                    $this->Line(10,57,208,57);

                    $this->SetFont('Arial','B',6); 
                    $this->MultiCell(15,3,utf8_decode('CÓDIGO PRODUCTO / SERVICIO'),$l,'C',$f);
                    $this->SetXY($x,$this->GetY()-5);
                    $this->MultiCell(15,3,utf8_decode('CANTIDAD'),$l,'C',$f);
                    $this->SetXY($x+=15,$this->GetY()-5);
                    $this->MultiCell(15,3,utf8_decode('UNIDAD DE MEDIDA'),$l,'C',$f);
                    $this->SetXY($x+=15,$this->GetY()-5);
                    $this->MultiCell(93,3,utf8_decode('DESCRIPCIÓN'),$l,'C',$f);
                    $this->SetXY($x+=93,$this->GetY()-5);
                    $this->MultiCell(20,3,utf8_decode('PRECIO UNITARIO'),$l,'R',$f);
                    $this->SetXY($x+=20,$this->GetY()-5);
                    $this->MultiCell(20,3,utf8_decode('DESCUENTO'),$l,'R',$f);
                    $this->SetXY($x+=20,$this->GetY()-3);
                    $this->MultiCell(20,3,utf8_decode('SUBTOTAL'),$l,'R',$f);

                    $this->Line(10,66,208,66);
                    $this->Ln(4);
        }
        public function Footer(){
            $l = '0';
            $y = -110;
            $this->SetLineWidth(0.5);
            $this->Line(10,158,208,158); //ok
                //leyendas
                $this->SetFont('Arial','',7);
                if ($this->datos['glosa'] !== '') {
                    $this->SetY($y);
                    $this->SetFont('Arial','B',7);
                    $this->Cell(15,4, utf8_decode('GLOSA:'),$l,0,'L');
                    $this->SetFont('Arial','',7);
                    $this->Cell(145,4, utf8_decode($this->datos['glosa']),$l,0,'L');
                }
                $this->SetY($y+4);
                $this->Cell(160,4, utf8_decode('ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY'),$l,0,'C');
                $this->SetY($y+8);
                $this->MultiCell(160,3,utf8_decode($this->datos['leyenda']),$l,'C',0);
                $this->SetXY(10,$this->GetY());
                if ($this->datos['codigoEmision'] == '2') {
                    $this->MultiCell(160,4,utf8_decode('Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido fuera de línea, verifique su envío con su proveedor o en la página web www.impuestos.gob.bo'),$l,'C',0);
                } else {
                    $this->Cell(160,4, utf8_decode('Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea'),$l,0,'C');
                }
                

                //NUMERO PIED PAGINA
                $this->SetY($y+18);
                $this->SetFont('Arial','I', 7);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
                //QR
                $this->SetXY(170, $y);
                $this->SetFont('Arial','',7);
                $nit = '1000991026';
                $cuf = $this->datos['cuf'];
                $numeroFactura = $this->datos['numeroFactura'];
                $QR = "https://siat.impuestos.gob.bo/consulta/QR?nit=1000991026&cuf=$cuf&numero=$numeroFactura&t=2";
                $nameQR ='qr/' . $this->datos['cuf'] . '.png';
                QRcode::png($QR, $nameQR );
                $this->Image($nameQR, 176, 160, 30,0, 'png');
        }


    }
?>