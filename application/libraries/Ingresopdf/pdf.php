<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Pdf extends FPDF {
        public function __construct() {
            parent::__construct();
           
        }

        //CellFitSpace( ) 
        // El encabezado del PDF
        public function Header() {
        $ci = & get_instance();
        $ci->load->model("Ingresos_model");
        $ingreso = $ci->Ingresos_model->mostrarIngresos(10092)->row();
        $fechaMovimiento = date('d/m/Y',strtotime($ingreso->fechamov));
        //echo date_create($fechaMovimiento);
        //echo date_format($fechaMovimiento, 'd/m/y');

        $this->Image('images/hergo.jpeg', 10, 13, 45 );
        $this->SetFont('Arial','B',10);
        $this->Cell(50, 33, $ingreso->almacen, 1,0,'C');
        $this->SetFont('Arial','B',18);
        $this->Cell(35);
        $this->Cell(80,10, 'NOTA DE INGRESOS',1,0,'C'); //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
        $this->Ln(8);
             //****TITULO*****
        //TIPO DE INGRESO
        $this->SetFont('Arial','BU',15);
        $this->Cell(85);
        $this->Cell(80,10, $ingreso->tipomov,0,0,'C');
        $this->Ln(5);


        //fecha de movimiento
        $this->SetY(8);
        $this->SetX(200);
        $this->Cell(10);
        $this->SetFont('Times','i',12);
        $this->Cell(25, 10, 'Nro Movimiento: ', 0,0,'R');
        $this->Cell(25, 10, $ingreso->n, 0,0,'L');
        //tipo de moneda
        $this->SetY(13);
        $this->SetX(200);
        $this->Cell(10);
        $this->SetFont('Times','i',11);
        $this->Cell(25, 10, 'Fecha Mov: ', 0,0,'R');
        $this->Cell(25, 10, $fechaMovimiento, 0,0,'L');

        
        // n movimiento
        $this->SetY(18);
        $this->SetX(200);
        $this->Cell(10);
        $this->SetFont('Times','i',11);
        $this->Cell(25, 10, 'Moneda: ', 0,0,'R');
        $this->Cell(25, 10, $ingreso->monedasigla, 0,0,'L');
        

        //****ENCABEZADO****
        $this->Ln(10);
        $this->SetX(15);
        $this->SetFont('Arial','',9);
        // almacen
        $this->Cell(30,10, 'Almacen: ',0,0,'');
        $this->Cell(30, 10, $ingreso->almacen, 0,0,'L');
        $this->Cell(30);
        //proveedor
        $this->Cell(30,10, 'Proveedor: ',0,0,'');
        $this->Cell(50, 10, $ingreso->nombreproveedor, 0,0,'L');
        $this->Cell(25);
        //factura
        $this->Cell(30,10, 'Factura: ',0,0,'');
        $this->Cell(20, 10, $ingreso->nfact, 0,0,'R');
        $this->Ln(5);
        $this->SetX(15);
        $this->SetFont('Arial','',9);
        //tipo movimiento
        $this->Cell(30,10, 'Tipo Movimiento: ',0,0,'');
        $this->Cell(30, 10, $ingreso->tipomov, 0,0,'L');
        $this->Cell(30);
        // N° ingreso
        $this->Cell(30,10, 'Nro Ingreso: ',0,0,'');
        $this->Cell(50, 10, $ingreso->ningalm, 0,0,'L');
        $this->Cell(25);
        // Orden de compra
        $this->Cell(30,10, 'Orden Compra: ',0,0,'');
        $this->Cell(20, 10, $ingreso->ordcomp, 0,1,'R');
       // $this->Ln(10);

         //ENCABEZADO TABLA
        $this->SetX(10);
        $this->SetFillColor(232,232,232);
        $this->SetFont('Arial','B',9); 

        $this->Cell(7,7,'N',1,0,'C',1);
        $this->Cell(20,7,'CANTIDAD',1,0,'R',1);
        $this->Cell(10,7,'UNID',1,0,'C',1);
        $this->Cell(20,7,'CODIGO',1,0,'C',1);  //ANCHO,ALTO,TEXTO,BORDE,SALTO DE LINEA, CENTREADO, RELLENO
        $this->Cell(80,7,'DESCRIPCION',1,0,'C',1);
        $this->Cell(30,7,'P/U',1,0,'R',1);
        $this->Cell(30,7,'TOTAL DOC',1,0,'R',1);
        $this->Cell(30,7,'P/U',1,0,'R',1);
        $this->Cell(30,7,'TOTAL ',1,0,'R',1);
        $this->Ln(8);
       }
       // El pie del pdf
    public function Footer(){
        $ci = & get_instance();
        $ci->load->model("Ingresos_model");
        $ingreso = $ci->Ingresos_model->mostrarIngresos(10092)->row();
        
        //posicion a 1.5 cm del final
        $this->SetFont('Arial','I', 10);
        $this->SetY(-20);
        $this->Cell(30,10, 'Observaciones: ',1,0,'L');
        $this->Cell(220, 10, $ingreso->obs, 1,0,'L');

        $this->SetY(-12);
        //fuente
        $this->SetFont('Arial','I', 8);
        //numero de pagina
        $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',1,0,'C' );
    }
    }
?>