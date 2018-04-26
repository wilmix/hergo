<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Pdf extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            $this->datos = $params;
        }
        //CellFitSpace( ) 
        // El encabezado del PDF
        public function Header() {
            $tipo = $this->datos['tipo'];
            $numeroIngreso = $this->datos['numeroIngreso'];
            $fechaMovimiento = date('d/m/Y',strtotime($this->datos['fechamov']));
            $almacen = $this->datos['almacen'];
            $moneda = $this->datos['moneda'];
            $proveedor = $this->datos['proveedor'];
            $nfact = $this->datos['nfact'];
            $nIngreso = $this->datos['nIngreso'];
            $ordenCompra = $this->datos['ordenCompra'];
            $sigla = $this->datos['sigla'];

            $this->SetY(10);
            $this->SetX(10);
            $this->Image('images/hergo.jpeg', 10, 10, 45 );
            $this->SetFont('Arial','B',10);
            $this->SetXY(15,20);
            $this->Cell(40,6, $almacen,0,0,'C');
            
            $this->SetFont('Arial','B',10);
            $this->SetXY(10,10);
            $this->SetFont('Arial','B',18);
            $this->Cell(0,8, 'NOTA DE INGRESOS',0,1,'C'); //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
            /*$this->SetFont('Arial','B',13);
            $this->Cell(0,6, $almacen,0,1,'C');*/
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
            $this->SetFont('Arial','',10);
            //proveedor
            $this->Cell(20,10, 'Proveedor: ',0,0,'');
            $this->Cell(85, 10, $proveedor, 0,0,'L');
            //factura
            $this->Cell(20,10, 'Factura: ',0,0,'');
            $this->Cell(20, 10, $nfact, 0,0,'L');
            // N° ingreso
            $this->Cell(25,10, 'Nro Ingreso: ',0,0,'');
            $this->Cell(20, 10, $nIngreso, 0,0,'L');
            // Orden de compra
            $this->Cell(25,10, 'Orden Compra: ',0,0,'');
            $this->Cell(20, 10, $ordenCompra, 0,1,'L');
        // $this->Ln(10);
            //ENCABEZADO TABLA
            $this->SetX(10);
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

       // El pie del pdf
        public function Footer(){
            $observacion = $this->datos['observacion'];
            //posicion a 1.5 cm del final
            $this->SetFont('Arial','I', 10);
            $this->SetY(-20);
            $this->Cell(30,10, 'Observaciones: ',0,0,'L');
            $this->Cell(210, 10, $observacion, 0,0,'L');
            $this->SetY(-12);
            //fuente
            $this->SetFont('Arial','I', 8);
            //numero de pagina
            $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        }
    }
?>