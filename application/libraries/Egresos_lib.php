<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Egresos_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
        }
        public function Header() {
            $almacen = $this->datos['almacen'];
            $autor = $this->datos['autor'];
            $fechamov = date('d/m/Y',strtotime($this->datos['fechamov']));
            $clientePedido = $this->datos['clientePedido'];
            $numero = $this->datos['numero'];
            $nombreCliente = $this->datos['nombreCliente'];
            $sigla = $this->datos['sigla'];
            $tipoMov = $this->datos['tipoMov'];
            $documento = $this->datos['documento'];
            $direccion = $this->datos['direccion'];
            $telefono = $this->datos['telefono'];
            $fax = $this->datos['fax'];
            $idTipoMov = $this->datos['idTipoMov'];
            $almDes = $this->datos['almDes'];
            $nIng = $this->datos['nIng'];
            $vendedor = $this->datos['nVendedor'];
            
            //TITULO
            $this->SetXY(10,10);
            
            $this->Image('images/hergo.jpeg', 10, 10, 45 );
            $this->SetFont('Arial','B',9);
            $this->SetXY(15,20);
            $this->Cell(40,6, utf8_decode($almacen),0,0,'C');
            $this->Ln(3);
            $this->SetX(15);
            $this->SetFont('Arial','',5);
            $this->Cell(40,6, utf8_decode($this->datos['almDirec']),0,0,'C');
            $this->Ln(3);
            $this->SetX(15);
            $this->Cell(40,6, utf8_decode($this->datos['almFono']),0,0,'C');
            $this->SetXY(10,10);
            $this->SetFont('Arial','B',18);
            $this->Cell(0,8, $tipoMov,0,0,'C');
            //n documento
            $this->SetXY(170,10);
            $this->SetFont('Arial','B',15);
            $this->Cell(35,7, $sigla . " - " .$numero,1,1,'C');
            $this->SetXY(170,17);
            $this->SetFont('Arial','B',12);
            $this->Cell(35,8, $fechamov,1,0,'C');
            $this->Ln(15);
            
                //****ENCABEZADO****
                if ($idTipoMov == '8') {
                    $this->SetXY(80,22);
                    $this->SetFont('Arial','B',12);
                    $this->Cell(20,6, utf8_decode('Origen:'),0,0,'');
                    $this->SetFont('Arial','',12);
                    $this->Cell(150, 6, utf8_decode($almacen), 0,0,'L');
                    $this->SetXY(80,28);
                    $this->SetFont('Arial','B',12);
                    $this->Cell(20,6, utf8_decode('Destino:'),0,0,'');
                    $this->SetFont('Arial','',12);
                    $this->Cell(150, 6, utf8_decode($almDes), 0,0,'L');
                } else {
                    $this->SetFont('Arial','B',9);
                    $this->Cell(20,6, utf8_decode('Señores: '),0,0,'');
                    $this->SetFont('Arial','',9);
                    $this->Cell(100, 6, utf8_decode($nombreCliente), 0,0,'L');
                    $this->SetFont('Arial','B',9);
                    $this->Cell(10,6, utf8_decode('NIT: '),0,0,'');
                    $this->SetFont('Arial','',9);
                    $this->Cell(30, 6, $documento, 0,0,'L');
                    $this->Ln(6);
                    $this->SetFont('Arial','B',9);
                    $this->Cell(20,6, utf8_decode('Dirección: '),0,0,'');
                    $this->SetFont('Arial','',9);
                    $this->Cell(140, 6, utf8_decode($direccion), 0,0,'L');
                    $this->Ln(6);
                    $this->SetFont('Arial','B',9);
                    $this->Cell(20,6, utf8_decode('Pedido Nº: '),0,0,'');
                    $this->SetFont('Arial','',9);
                    $this->Cell(60, 6, $clientePedido, 0,0,'L');
                    $this->SetFont('Arial','B',9);
                    $this->Cell(10,6, utf8_decode(''),0,0,'');
                    $this->SetFont('Arial','',9);
                    $this->Cell(30, 6, '', 0,0,'L');
                    $this->SetFont('Arial','B',9);
                    $this->Cell(20,6, utf8_decode('Teléfono: '),0,0,'');
                    $this->SetFont('Arial','',9);
                    $this->Cell(45, 6, utf8_decode($telefono), 0,0,'L');
                    $this->Ln(6);
                }
                
                    if ($idTipoMov == '8') {
                            $this->SetXY(170,25);
                            $this->SetFont('Arial','B',15);
                            $this->Cell(35,8,utf8_decode('IT - '.$nIng),1,1,'C');
                            $this->Ln(4);
                    } else {
                            //factura n
                            $this->SetXY(170,27);
                            $this->SetFont('Arial','B',12);
                            $this->Cell(35,7,utf8_decode('FACTURA N°'),1,1,'C');
                            $this->SetXY(170,34);
                            $this->SetFont('Arial','B',12);
                            $this->Cell(35,10, '',1,0,'C');
                            $this->Ln(10);
                    }
                    

                    //ENCABEZADO TABLA
                    $this->SetXY(10,50);
                    $this->Ln(1);
                    $this->SetFillColor(235,235,235);
                    $this->SetFont('Arial','B',8); 
                    $this->Cell(5,6,'N',0,0,'C',1);
                    $this->Cell(15,6,'CANT',0,0,'c',1);
                    $this->Cell(10,6,'UNID',0,0,'C',1);
                    $this->Cell(15,6,'CODIGO',0,0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                    $this->Cell(110,6,'DESCRIPCION',0,0,'C',1);
                    $this->Cell(20,6,'P/U',0,0,'R',1);
                    $this->Cell(20,6,'TOTAL',0,0,'R',1);
                    $this->Ln(6);
        }

        public function Footer(){
            $observaciones = $this->datos['observaciones'];
            $plazoPago = date('d/m/Y',strtotime($this->datos['plazoPago']));
            $plazoPago = ($plazoPago == '01/01/1970') ? '' : $plazoPago;
            $userName = $this->datos['userName'];
            $autor = $this->datos['autor'];
            $vendedor = $this->datos['nVendedor'];
            $saldoDeudor = $this->datos['saldoDeudor'] ?? 0;
            $fechaPriFac = $saldoDeudor ? ' - ' . date('d/m/Y',strtotime($this->datos['fechaPrimeraFac'])): '';
            $this->SetLineWidth(0.5);
            $this->Line(10,127,206,127);
            $this->SetY(-150);
            $this->SetFont('Arial','BI', 9);
            $this->Cell(15,5, 'NOTA: ',0,0,'L',1);
            $this->SetFont('Arial','I', 8);
            $this->Cell(110, 5, utf8_decode($observaciones), 0,0,'L',1);
            
            
            $this->Ln(5);
            $this->SetY(-145);
            $this->SetFont('Arial','BI', 9);
            $this->Cell(25,5, 'Emitido por:',0,0,'L',1);
            $this->SetFont('Arial','I', 9);
            $this->Cell(40, 5, utf8_decode($vendedor), 0,0,'L');
            $this->SetFont('Arial','BI', 9);
            $this->Cell(20,5, 'Autorizado:',0,0,'L');
            $this->SetFont('Arial','I', 9);
            $this->Cell(40, 5, '', 0,0,'L');
            $this->Cell(30,5, 'Nombre: ................................................................',0,0,'L');
            $this->Cell(45, 5, '', 0,0,'L');

            $this->SetY(-140);
            $this->SetFont('Arial','BI', 9);
            $this->Cell(25,5, 'Fecha de Pago: ',0,0,'L');
            $this->SetFont('Arial','I', 9);
            $this->Cell(40, 5, $plazoPago, 0,0,'L');
            $this->SetFont('Arial','BI', 9);
            $this->Cell(20,5, 'Nombre:',0,0,'L');
            $this->Cell(40, 5, '', 0,0,'L');
            $this->SetFont('Arial','I', 9);
            $this->Cell(30,5, 'C.I.: .......................................................................',0,0,'L');
            $this->Cell(45, 5, '',0,0,'L');

            $this->SetXY(10,-135);
            $this->SetFont('Arial','BI', 9);
            $this->Cell(30,5, 'Saldo Deudor: ',0,0,'L',1);

            $this->SetXY(35,-135);
            $this->SetFont('Arial','I', 9);
            $this->Cell(30,5, number_format($saldoDeudor, 2, ".", ",") . ' '.  $fechaPriFac,0,0,'L',1);

            $this->SetXY(160,-135);
            $this->SetFont('Arial','BI', 9);
            $this->Cell(30,5, 'Recibi Conforme',0,0,'L',1);


                //NUMERO PIED PAGINA
                $this->SetY(-135);
                $this->SetFont('Arial','I', 8);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>