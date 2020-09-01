
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class OrdenCompraLib extends FPDF {
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
            $fecha = strtotime($this->datos['fecha']);
            $year = date('y',strtotime($this->datos['fecha']));
            $provedor =$this->datos['provedor'];
            $telefono =$this->datos['telefono'];
            $fax =$this->datos['fax'];
            $atencion =$this->datos['atencion'];
            $direccion =$this->datos['direccion'];
            $referencia =$this->datos['referencia'];

            $this->SetFont('Arial','B',15);
            $this->SetXY(0,35);
            $this->Cell(0,8, utf8_decode("ORDEN DE COMPRA Nº HG.-  $n/$year"),0,0,'C');
            $this->Ln(7);
            $this->SetFont('Arial','B',10);
            $this->Cell(0,8, utf8_decode('Nº de NIT: 1000991026'),0,0,'C');
            $this->Ln(5);
            $this->SetFont('Arial','B',12);
            $this->Cell(0,8, strftime("La Paz, %d de %B de %Y", $fecha),0,0,'C');
            $this->Ln(10);
            $this->SetFont('Arial','B',10);
            $this->Cell(200,8, utf8_decode("Señor(es): $provedor"),'TRL',0,'L');
            $this->Ln(6);
            $this->Cell(200,8, utf8_decode("Atención: $atencion"),'RL',0,'L');
            $this->Ln(6);
            $this->Cell(200,8, utf8_decode("Dirección: $direccion"),'RL',0,'L');
            $this->Ln(6);
            $this->Cell(200,8, utf8_decode("Referencia: $referencia"),'RLB',0,'L');

            $this->SetXY(150,60);
            $this->Cell(0,8, utf8_decode("Telf.: $telefono"),0,0,'L');
            $this->Ln(6);
            $this->SetXY(150,65);
            $this->Cell(0,8, utf8_decode("Fax.: $fax"),0,0,'L');
            $this->Ln(6);

            $this->Ln(45);

        }
        function Footer()
        {
            $condicion =$this->datos['condicion'];
            $formaEnvio =$this->datos['formaEnvio'];
            $formaPago =$this->datos['formaPago'];
            $glosa =$this->datos['glosa'];
            $autor =$this->datos['autor'];

            // Position at 1.5 cm from bottom
            $this->SetXY(135,-51);
            $this->SetFont('Arial','B',10);
            $this->Cell(0,0, utf8_decode("$autor"),'0',0,'C',1);
            $this->SetXY(135,-48);
            $this->Cell(0,5, utf8_decode("Hergo Ltda."),'0',0,'C',1);
            $this->SetXY(135,-43);
            $this->Cell(0,5, utf8_decode("AUTORIZADO"),'0',0,'C',1);


            $this->SetY(-70);
            $this->SetFont('Arial','B',10);
            $this->Cell(120,8, utf8_decode("Condiciones de compra: $condicion"),'TL',0,'L');
            $this->Cell(80,8, utf8_decode(""),'LRT',0,'L');
            $this->Ln(8);
            $this->Cell(120,8, utf8_decode("Forma de Envio: $formaEnvio"),'L',0,'L');
            $this->Cell(80,8, utf8_decode(""),'LR',0,'C');
            $this->Ln(8);
            $this->Cell(120,8, utf8_decode("Termino de pago: $formaPago"),'L',0,'L');
            $this->Cell(80,8, utf8_decode(""),'LR',0,'C');
            $this->Ln(8);
            $this->Cell(120,8, utf8_decode("Observaciones: "),'L',0,'L');
            $this->Cell(80,8, utf8_decode(""),'LR',0,'C');
            $this->Ln(8);
            $this->SetFont('Times','BI',9);
            $this->Cell(200,10, utf8_decode("$glosa"),1,0,'L');
            //$this->Cell(0,8, utf8_decode("$autor"),0,0,'L');

            


        }
        
    }
?>