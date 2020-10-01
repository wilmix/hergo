
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class SolicitudLib extends FPDF {
        private $datos = array();
        public function __construct($params){
            parent::__construct();
            setlocale(LC_TIME, 'es_RB.UTF-8');
            date_default_timezone_set("America/La_Paz");
            $this->datos = $params;
            
        }
        function Header()
        {
            setlocale(LC_TIME, 'es_RB.UTF-8');
            $n = $this->datos['n'];
            $fecha = date('d/m/Y',strtotime($this->datos['fecha']));
            $year = date('y',strtotime($this->datos['fecha']));
            $provedor =$this->datos['provedor'];
            $recepcion =date('d/m/Y',strtotime($this->datos['recepcion']));
            $formaPago =$this->datos['formaPago'];
            $diasCredito = $this->datos['diasCredito'];
            $formaPago =  $diasCredito > 0 ?"$formaPago $diasCredito DÌAS" : $formaPago;
            $pedidoPor =  $this->datos['pedidoPor'];
            $cotizacion = $this->datos['cotizacion'];

            $this->SetXY(10,9);
            $this->Image('images/hergo.jpeg', 15, 8, 45 );
            $this->SetFont('Arial','B',18);
            $this->SetXY(0,15);
            $this->Cell(0,8, utf8_decode("PEDIDO Nº $n/$year"),0,0,'C');
            $this->Ln(10);
            $this->SetFont('Arial','B',10);
            $this->Cell(200,8, utf8_decode("PEDIDO POR: $pedidoPor"),'0',0,'L');
            $this->Ln(6);
            $this->Cell(200,8, utf8_decode("N° COTIZACIÓN: $cotizacion"),'0',0,'L');
            $this->Ln(6);
            $this->Cell(200,8, utf8_decode("PROVEEDOR: $provedor"),'0',0,'L');
            $this->Ln(6);

                    $this->SetXY(200,25);
                    $this->Cell(0,8, utf8_decode("FECHA DE PEDIDO.: $fecha"),0,0,'L');
                    $this->SetXY(200,31);
                    $this->Cell(0,8, utf8_decode("FECHA DE RECEPCIÓN.: $recepcion"),0,0,'L');
                    $this->SetXY(200,37);
                    $this->Cell(0,8, utf8_decode("FORMA DE PAGO: $formaPago"),0,0,'L');

        }
        function Footer()
        {
            $glosa =$this->datos['glosa'];
            $this->SetY(-25);
            $this->SetFont('Arial','B',8);
            $this->Cell(260,10, utf8_decode("JUSTIFICACIÓN: $glosa"),0,0,'L');

        }
        
    }
?>