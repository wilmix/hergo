<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Ingresos_lib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
        }
        public function Header() {
            $tipo = $this->datos['tipo'];
            $numeroIngreso = $this->datos['numeroIngreso'];
            $fechaMovimiento = date('d/m/Y',strtotime($this->datos['fechamov']));
            $almacen = $this->datos['almacen'];
            $moneda = $this->datos['moneda'];
            $proveedor = $this->datos['proveedor'];
            $tipoDoc = $this->datos['tipoDoc'];
            $nfact = $this->datos['nfact'];
            $ordenCompra = $this->datos['ordenCompra'];
            $sigla = $this->datos['sigla'];
            //TITULO
            $this->SetXY(10,10);
            $this->Image('images/hergo.jpeg', 10, 10, 45 );
            $this->SetFont('Arial','B',10);
            $this->SetXY(15,20);
            $this->Cell(40,6, $almacen,0,0,'C');
            $this->SetFont('Arial','B',10);
            $this->SetXY(10,25);
            if ($tipoDoc=='2') {
                $this->Cell(40,6, 'Compra sin Factura - Retencion de Impuestos',0,0,'L');
            } else {
                $this->Cell(40,6, 'Compra con Factura',0,0,'C');
            }
            $this->SetXY(10,10);
            $this->SetFont('Arial','B',18);
            $this->Cell(0,8, 'NOTA DE INGRESOS',0,1,'C'); 
            $this->SetFont('Arial','BU',15);
            $this->Cell(0,8, $tipo,0,0,'C');
            $this->SetXY(220,12);
            $this->SetFont('Arial','B',15);
            $this->Cell(45,8, $sigla . " - " .$numeroIngreso,1,1,'C');
            $this->SetXY(220,20);
            $this->SetFont('Arial','B',12);
            $this->Cell(45,8, $fechaMovimiento,1,0,'C');
            $this->Ln(10);
            
                //****ENCABEZADO****
                $this->SetX(15);
                //proveedor
                $this->SetFont('Arial','B',9);
                $this->Cell(20,10, 'Proveedor: ',0,0,'');
                $this->SetFont('Arial','',9);
                $this->Cell(65, 10, utf8_decode($proveedor), 0,0,'L');
                //factura
                $this->SetFont('Arial','B',9);
                $this->Cell(25,10, 'Orden Compra: ',0,0,'');
                $this->SetFont('Arial','',9);
                $this->Cell(40, 10, $ordenCompra, 0,0,'L');

                // Orden de compra
                $this->SetFont('Arial','B',9);
                $this->Cell(15,10, 'Factura: ',0,0,'');
                $this->SetFont('Arial','',9);
                $this->Cell(50, 10, $nfact=='SF'?' - ':$nfact, 0,1,'L');
                $this->SetX(10);
                    //ENCABEZADO TABLA
                    $this->SetFillColor(255,255,255);
                    $this->SetFont('Arial','B',9); 
                    $this->Cell(173,6,'',0,0,'C',1);
                    $this->SetFillColor(232,232,232);
                    $this->Cell(40,6,'DOCUMENTO',1,0,'C',1);
                    $this->Cell(40,6,'INVENTARIO ',1,0,'C',1);
                    $this->Ln(6);
                    $this->Cell(7,6,'N',1,0,'C',1);
                    $this->Cell(18,6,'CANT',1,0,'c',1);
                    $this->Cell(10,6,'UNID',1,0,'C',1);
                    $this->Cell(18,6,'CODIGO',1,0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
                    $this->Cell(120,6,'DESCRIPCION',1,0,'C',1);
                    $this->Cell(20,6,'P/U',1,0,'R',1);
                    $this->Cell(20,6,'TOTAL',1,0,'R',1);
                    $this->Cell(20,6,'P/U',1,0,'R',1);
                    $this->Cell(20,6,'TOTAL ',1,0,'R',1);
                    $this->Ln(8);
        }

        public function Footer(){
            $observacion = $this->datos['observacion'];
            $this->SetFont('Arial','I', 10);
            $this->SetY(-20);
            $this->Cell(30,10, 'Observaciones: ',0,0,'L');
            $this->Cell(210, 10, $observacion, 0,0,'L');
                //NUMERO PIED PAGINA
                $this->SetY(-12);
                $this->SetFont('Arial','I', 8);
                $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>