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
            $numero = $this->datos['n'];
            $nombreCliente = $this->datos['nombreCliente'];
            $sigla = $this->datos['sigla'];
            $tipoMov = $this->datos['tipomov'];
            $documento = $this->datos['documento'];
            $direccion = $this->datos['direccion'];
            $telefono = $this->datos['telefono'];
            $email = strtolower($this->datos['email']);
            $idTipoMov = $this->datos['idtipomov'];
            $almDes = $this->datos['almDes'];
            $nIng = $this->datos['nIng'];
            $vendedor = $this->datos['nVendedor'];
            $l = 0;
            
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
                    $this->Cell(20,6, utf8_decode('Señores: '),$l,0,'');
                    $this->SetFont('Arial','',9);
                    if (strlen($nombreCliente) > 51) {
                        $this->MultiCell(100, 4, mb_convert_encoding($nombreCliente, "ISO-8859-1"), $l, 'L', 0);
                        $this->SetXY(130, $this->GetY() -8);
                    } else {
                        $this->Cell(100, 6, utf8_decode($nombreCliente), $l,0,'L');
                    }
                    $this->SetFont('Arial','B',9);
                    $this->Cell(10,6, utf8_decode('NIT: '),$l,0,'');
                    $this->SetFont('Arial','',9);
                    $this->Cell(30, 6, $documento, $l,0,'L');
                    $this->Ln(6);
                    $this->SetFont('Arial','B',9);
                    $this->Cell(20,6, utf8_decode('Dirección: '),$l,0,'');
                    if (!empty($direccion) && !empty($email)) {
                        $direccion_email = "$direccion | $email";
                    } elseif (!empty($direccion)) {
                        $direccion_email = $direccion;
                    } elseif (!empty($email)) {
                        $direccion_email = $email;
                    } else {
                        $direccion_email = '';
                    }
                    $this->SetFont('Arial','',8);
                    if (strlen($direccion_email) > 80) {
                        $this->MultiCell(140, 4, mb_convert_encoding($direccion_email, "ISO-8859-1"), $l, 'L', 0);
                        $this->SetXY(10, $this->GetY() -6);
                    } else {
                        $this->Cell(140, 6, utf8_decode($direccion_email), $l,0,'L');
                    }
                    $this->Ln(6);
                    $this->SetFont('Arial','B',9);
                    $this->Cell(20,6, utf8_decode('Pedido Nº: '),$l,0,'');
                    $this->SetFont('Arial','',9);
                    $this->Cell(60, 6, utf8_decode($clientePedido), $l,0,'L');
                    $this->SetFont('Arial','B',9);
                    $this->Cell(10,6, utf8_decode(''),$l,0,'');
                    $this->SetFont('Arial','',9);
                    $this->Cell(30, 6, '', $l,0,'L');
                    $this->SetFont('Arial','B',9);
                    $this->Cell(20,6, utf8_decode('Teléfono: '),$l,0,'');
                    $this->SetFont('Arial','',9);
                    $this->Cell(45, 6, utf8_decode($telefono), $l,0,'L');
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
                    
                    if ($this->datos['almacen_destino_id'] <> '9') {
                        $this->Cell(110,6,'DESCRIPCION',0,0,'C',1);
                        $this->Cell(20,6,'P/U',0,0,'R',1);
                        $this->Cell(20,6,'TOTAL',0,0,'R',1);
                    } else {
                        $this->Cell(150,6,'DESCRIPCION',0,0,'C',1);
                    }
                    $this->Ln(6);
        }

        public function Footer(){
            $observaciones = $this->datos['obs'];
            $plazoPago = date('d/m/Y',strtotime($this->datos['plazopago']));
            $plazoPago = ($plazoPago == '01/01/1970') ? '' : $plazoPago;
            $userName = $this->datos['userName'];
            $autor = $this->datos['autor'];
            $vendedor = $this->datos['nVendedor'];
            $saldoDeudorVencidas = $this->datos['saldoDeudorVencidas'] ?? 0;
            $fechaPriFacVencidas = $saldoDeudorVencidas ? ' - ' . date('d/m/Y',strtotime($this->datos['fechaPrimeraFacVencidas'])): '';
            $saldoDeudorTotal = $this->datos['saldoDeudorTotal'] ?? 0;
            $fechaPriFacTotal = $saldoDeudorTotal ? ' - ' . date('d/m/Y',strtotime($this->datos['fechaPrimeraFacTotal'])): '';
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

            $this->SetXY(9,-135);
            $this->SetFont('Arial','BI', 9);
            $this->Cell(30,5, 'Fact. Vencidas: ',0,0,'L',1);

            $this->SetXY(35,-135);
            $this->SetFont('Arial','I', 9);
            $this->Cell(30,5, number_format($saldoDeudorVencidas, 2, ".", ",") . ' '.  $fechaPriFacVencidas,0,0,'L',1);

            $this->SetXY(160,-135);
            $this->SetFont('Arial','BI', 9);
            $this->Cell(30,5, 'Recibi Conforme',0,0,'L',1);

            $this->SetXY(9,-130);
            $this->SetFont('Arial','BI', 9);
            $this->Cell(30,5, 'Saldo Deudor: ',0,0,'L',1);

            $this->SetXY(35,-130);
            $this->SetFont('Arial','I', 9);
            $this->Cell(30,5, number_format($saldoDeudorTotal, 2, ".", ",") . ' '.  $fechaPriFacTotal,0,0,'L',1);



                //NUMERO PIED PAGINA
                $this->SetY(-130);
                $this->SetFont('Arial','I', 8);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>