<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class FacturaSiatLib extends FPDF
{
    var $angle = 0;
    private $datos = array();

    function Rotate($angle, $x=-1, $y=-1)
    {
        if($x==-1)
            $x=$this->x;
        if($y==-1)
            $y=$this->y;
        if($this->angle!=0)
            $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0)
        {
            $angle*=@M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function _endpage()
    {
        if($this->angle!=0)
        {
            $this->angle=0;
            $this->_out('Q');
        }
        parent::_endpage();
    }
    public function __construct($params)
    {
        parent::__construct();
        $this->datos = $params;
    }
    public function Header()
    {
        // Marca de agua ANULADA
        if (isset($this->datos['anulada']) && $this->datos['anulada'] == 1) {
            $this->SetFont('Arial', 'B', 80);
            $this->SetTextColor(255, 180, 180); // Color más suave
            $this->Rotate(45, 55, 160); // Posición más arriba
            $this->Text(55, 160, 'ANULADA');
            $this->Rotate(0);
            $this->SetTextColor(0,0,0); // Reset text color
        }

        /*** imagen ***/
        $l = '0';
        $this->SetXY(10, 9);
        $this->Image('images/hergo.jpeg', 15, 8, 45);

        // Mostrar título y subtítulo solo si showHeader es true
        if (isset($this->datos['showHeader']) && $this->datos['showHeader'] === true) {
            /*** titulo ***/
            $this->SetFont('Arial', 'B', 24);
            $this->SetTextColor(0, 0, 200);
                $this->Cell(0, 15, 'FACTURA', 0, 0, 'C');
            $this->Ln(8);
            /*** subtitulo ***/
            $this->SetFont('Arial', '', 9);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(0, 10, convertToISO('(Con Derecho a Crédito Fiscal)'), 0, 0, 'C');
        }

        /*** izquierda***/
        $this->SetXY(10, 18);
        $this->Ln(2);
        $this->SetFont('Arial', 'B', 7);
            $this->Cell(60, 4, convertToISO($this->datos['sucursal']), 0, 1, 'C');
        $this->SetFont('Arial', '', 6);
            $this->Cell(60, 3, convertToISO('No. Punto de Venta: ' . $this->datos['codigoPuntoVenta']), 0, 1, 'C');
            $this->Cell(60, 3, convertToISO($this->datos['direccion']), 0, 1, 'C');
            $this->Cell(60, 3, convertToISO('Teléfono: ' . $this->datos['telefono']), 0, 1, 'C');
            $this->Cell(60, 3, convertToISO($this->datos['ciudad']), 0, 1, 'C');


        /*** derecha***/
        $this->SetXY(138, 10);
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0, 0, 200);
            $this->Cell(70, 7, 'NIT: ' . '1000991026', 0, 1, 'C');
        $this->SetXY(138, 17);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(0, 0, 0);
            $this->Cell(32, 8, convertToISO('FACTURA N°:'), 0, 0, 'R');
        $this->SetFont('Arial', 'B', 14);
            $this->Cell(35, 8, convertToISO($this->datos['numeroFactura']), 0, 0, 'C');
        $this->SetXY(138, 25);
        $this->SetFont('Arial', 'B', 7);
            $this->Cell(32, 8, convertToISO('CÓD. AUTORIZACIÓN:'), 0, 0, 'R');
        $this->SetFont('Arial', 'B', 7);
            $this->MultiCell(35, 4, convertToISO($this->datos['cuf']), 0, 'L', 0);



        /*** datos ***/
        $this->SetXY(10, 42);
        $fontSize = 9;
        $this->SetFont('Arial', 'B', $fontSize);
            $this->Cell(20, 6, convertToISO('Fecha: '), $l, 0, '');
        $this->SetFont('Arial', '', $fontSize);
            $this->Cell(40, 6, convertToISO($this->datos['fechaEmision']), $l, 0, 'L');
        $this->SetFont('Arial', 'B', $fontSize);
        if ($this->datos['pedido'] == !'') {
                $this->Cell(15, 6, convertToISO('Pedido: '), $l, 0, '');
            $this->SetFont('Arial', '', $fontSize);
                $this->Cell(68, 6, convertToISO($this->datos['pedido']), $l, 0, 'L');
        } else {
            $this->Cell(15, 6, '', $l, 0, '');
            $this->Cell(68, 6, '', $l, 0, '');
        }
        $this->SetFont('Arial', 'B', $fontSize);
            $this->Cell(20, 6, convertToISO('NIT/CI/CEX:'), $l, 0, '');
        $this->SetFont('Arial', '', $fontSize);

        if ($this->datos['complemento']) {
            $this->SetFont('Arial', '', $fontSize);
                $this->Cell(35, 6, convertToISO($this->datos['documentoNumero'] . ' - ' . $this->datos['complemento']), $l, 0, 'R');
        } else {
            $this->Cell(35, 6, convertToISO($this->datos['documentoNumero']), $l, 0, 'R');
        }
        $this->Ln(6);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', $fontSize);
            $this->Cell(35, 6, convertToISO('Nombre/Razon Social: '), $l, 0, '');
        $this->SetFont('Arial', '', $fontSize);
        if (strlen($this->datos['nombreRazonSocial']) > 55) {
            $this->MultiCell(108, 4, convertToISO($this->datos['nombreRazonSocial']), $l, 'L', 0);
            $this->SetXY(153, $this->GetY() - 8);
        } else {
            $this->MultiCell(108, 6, convertToISO($this->datos['nombreRazonSocial']), $l, 'L', 0);
            $this->SetXY(153, $this->GetY() - 6);
        }
        $this->SetFont('Arial', 'B', $fontSize);
            $this->Cell(20, 6, convertToISO('Cod. Cliente:'), $l, 0, '');
        $this->SetFont('Arial', '', $fontSize);
            $this->Cell(35, 6, convertToISO($this->datos['codigoCliente']), $l, 0, 'R');
        $this->Ln(8);


        /*** cabezera tabla ***/
        $this->SetX(15);
        $this->Ln(1);
        $this->SetFillColor(235, 235, 235);
        $f = '0';
        $x = 25;
        $this->SetLineWidth(0.5);
        $this->Line(10, 57, 208, 57);

        $this->SetFont('Arial', 'B', 6);
            $this->MultiCell(15, 3, convertToISO('CÓDIGO PRODUCTO / SERVICIO', "ISO-8859-1"), $l, 'C', $f);
        $this->SetXY($x, $this->GetY() - 5);
            $this->MultiCell(15, 3, convertToISO('CANTIDAD', "ISO-8859-1"), $l, 'C', $f);
        $this->SetXY($x += 15, $this->GetY() - 5);
            $this->MultiCell(20, 3, convertToISO('UNIDAD DE MEDIDA', "ISO-8859-1"), $l, 'C', $f);
        $this->SetXY($x += 20, $this->GetY() - 5);
            $this->MultiCell(88, 3, convertToISO('DESCRIPCIÓN', "ISO-8859-1"), $l, 'C', $f);
        $this->SetXY($x += 88, $this->GetY() - 5);
            $this->MultiCell(20, 3, convertToISO('PRECIO UNITARIO', "ISO-8859-1"), $l, 'R', $f);
        $this->SetXY($x += 20, $this->GetY() - 5);
            $this->MultiCell(20, 3, convertToISO('DESCUENTO', "ISO-8859-1"), $l, 'R', $f);
        $this->SetXY($x += 20, $this->GetY() - 3);
            $this->MultiCell(20, 3, convertToISO('SUBTOTAL', "ISO-8859-1"), $l, 'R', $f);

        $this->Line(10, 66, 208, 66);
        $this->Ln(4);
    }
    public function Footer()
    {
        $l = '0';
        $y = -110;
        $this->SetLineWidth(0.5);
        $this->Line(10, 158, 208, 158); //ok
        /*** glosa ***/
        $this->SetFont('Arial', '', 7);
        if ($this->datos['glosa'] !== '') {
            $this->SetY($y);
            $this->SetFont('Arial', 'B', 7);
                $this->Cell(15, 4, convertToISO('GLOSA:'), $l, 0, 'L');
            $this->SetFont('Arial', '', 7);
                $this->Cell(145, 4, convertToISO($this->datos['glosa']), $l, 0, 'L');
        }
        /*** leyendas ***/
        $this->SetY($y + 4);
            $this->Cell(160, 4, convertToISO('ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY'), $l, 0, 'C');
        $this->SetY($y + 8);
            $this->MultiCell(160, 3, convertToISO($this->datos['leyenda']), $l, 'C', 0);
        $this->SetXY(10, $this->GetY());
        if ($this->datos['codigoEmision'] == '2') {
            $this->MultiCell(160, 4, convertToISO('Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido fuera de línea, verifique su envío con su proveedor o en la página web www.impuestos.gob.bo'), $l, 'C', 0);
        } else {
            $this->Cell(160, 4, convertToISO('Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea'), $l, 0, 'C');
        }


        /*** número de página ***/
        $this->SetY($y + 18);
        $this->SetFont('Arial', 'I', 7);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        /*** qr ***/
        $this->SetXY(170, $y);
        $this->SetFont('Arial', '', 7);
        $cuf = $this->datos['cuf'];
        $numeroFactura = $this->datos['numeroFactura'];
        $QR = "https://siat.impuestos.gob.bo/consulta/QR?nit=1000991026&cuf=$cuf&numero=$numeroFactura&t=2";
        $nameQR = 'qr/' . $this->datos['cuf'] . '.png';
        QRcode::png($QR, $nameQR);
        $this->Image($nameQR, 176, 160, 30, 0, 'png');
    }
}
