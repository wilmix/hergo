<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Incluimos el archivo fpdf
require_once APPPATH . "/third_party/fpdf/fpdf.php";
require_once APPPATH . "/third_party/phpqrcode/qrlib.php";
require_once APPPATH . "/third_party/multicell/PDF_MC_Table.php";
//Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
class FacturaSiatLib extends FPDF
{
    private $datos = array();
    public function __construct($params)
    {
        parent::__construct();
        $this->datos = $params;
    }
    public function Header()
    {
        /*** imagen ***/
        $l = '0';
        $this->SetXY(10, 9);
        $this->Image('images/hergo.jpeg', 15, 8, 45);
        /*** titulo ***/
        $this->SetFont('Arial', 'B', 24);
        $this->SetTextColor(0, 0, 200);
            $this->Cell(0, 15, '', 0, 0, 'C');
        $this->Ln(10);
        /*** subtitulo ***/
        $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, '', 0, 0, 'C');

        /*** izquierda***/
        $this->SetXY(10, 18);
        $this->Ln(2);
        $this->SetFont('Arial', 'B', 7);
            $this->Cell(60, 4, mb_convert_encoding($this->datos['sucursal'], "ISO-8859-1"), 0, 1, 'C');
        $this->SetFont('Arial', '', 6);
            $this->Cell(60, 3, mb_convert_encoding('No. Punto de Venta: ' . $this->datos['codigoPuntoVenta'], "ISO-8859-1"), 0, 1, 'C');
            $this->Cell(60, 3, mb_convert_encoding($this->datos['direccion'], "ISO-8859-1"), 0, 1, 'C');
            $this->Cell(60, 3, mb_convert_encoding('Teléfono: ' . $this->datos['telefono'], "ISO-8859-1"), 0, 1, 'C');
            $this->Cell(60, 3, mb_convert_encoding($this->datos['ciudad'], "ISO-8859-1"), 0, 1, 'C');


        /*** derecha***/
        $this->SetXY(138, 10);
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0, 0, 200);
            $this->Cell(70, 7, 'NIT: ' . '1000991026', 0, 1, 'C');
        $this->SetXY(138, 17);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(0, 0, 0);
            $this->Cell(32, 8, mb_convert_encoding('FACTURA N°:', "ISO-8859-1"), 0, 0, 'R');
        $this->SetFont('Arial', 'B', 14);
            $this->Cell(35, 8, mb_convert_encoding($this->datos['numeroFactura'], "ISO-8859-1"), 0, 0, 'C');
        $this->SetXY(138, 25);
        $this->SetFont('Arial', 'B', 7);
            $this->Cell(32, 8, mb_convert_encoding('CÓD. AUTORIZACIÓN:', "ISO-8859-1"), 0, 0, 'R');
        $this->SetFont('Arial', 'B', 7);
            $this->MultiCell(35, 4, mb_convert_encoding($this->datos['cuf'], "ISO-8859-1"), 0, 'L', 0);



        /*** datos ***/
        $this->SetXY(10, 42);
        $fontSize = 9;
        $this->SetFont('Arial', 'B', $fontSize);
            $this->Cell(20, 6, mb_convert_encoding('Fecha: ', "ISO-8859-1"), $l, 0, '');
        $this->SetFont('Arial', '', $fontSize);
            $this->Cell(40, 6, mb_convert_encoding($this->datos['fechaEmision'], "ISO-8859-1"), $l, 0, 'L');
        $this->SetFont('Arial', 'B', $fontSize);
        if ($this->datos['pedido'] == !'') {
                $this->Cell(15, 6, mb_convert_encoding('Pedido: ', "ISO-8859-1"), $l, 0, '');
            $this->SetFont('Arial', '', $fontSize);
                $this->Cell(68, 6, mb_convert_encoding($this->datos['pedido'], "ISO-8859-1"), $l, 0, 'L');
        } else {
            $this->Cell(15, 6, '', $l, 0, '');
            $this->Cell(68, 6, '', $l, 0, '');
        }
        $this->SetFont('Arial', 'B', $fontSize);
            $this->Cell(20, 6, mb_convert_encoding('NIT/CI/CEX:', "ISO-8859-1"), $l, 0, '');
        $this->SetFont('Arial', '', $fontSize);

        if ($this->datos['complemento']) {
            $this->SetFont('Arial', '', $fontSize);
                $this->Cell(35, 6, mb_convert_encoding($this->datos['documentoNumero'] . ' - ' . $this->datos['complemento'], "ISO-8859-1"), $l, 0, 'R');
        } else {
            $this->Cell(35, 6, mb_convert_encoding($this->datos['documentoNumero'], "ISO-8859-1"), $l, 0, 'R');
        }
        $this->Ln(6);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', $fontSize);
            $this->Cell(35, 6, mb_convert_encoding('Nombre/Razon Social: ', "ISO-8859-1"), $l, 0, '');
        $this->SetFont('Arial', '', $fontSize);
        if (strlen($this->datos['nombreRazonSocial']) > 55) {
            $this->MultiCell(108, 4, mb_convert_encoding($this->datos['nombreRazonSocial'], "ISO-8859-1"), $l, 'L', 0);
            $this->SetXY(153, $this->GetY() - 8);
        } else {
            $this->MultiCell(108, 6, mb_convert_encoding($this->datos['nombreRazonSocial'], "ISO-8859-1"), $l, 'L', 0);
            $this->SetXY(153, $this->GetY() - 6);
        }
        $this->SetFont('Arial', 'B', $fontSize);
            $this->Cell(20, 6, mb_convert_encoding('Cod. Cliente:', "ISO-8859-1"), $l, 0, '');
        $this->SetFont('Arial', '', $fontSize);
            $this->Cell(35, 6, mb_convert_encoding($this->datos['codigoCliente'], "ISO-8859-1"), $l, 0, 'R');
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
            $this->MultiCell(15, 3, mb_convert_encoding('CÓDIGO PRODUCTO / SERVICIO', "ISO-8859-1"), $l, 'C', $f);
        $this->SetXY($x, $this->GetY() - 5);
            $this->MultiCell(15, 3, mb_convert_encoding('CANTIDAD', "ISO-8859-1"), $l, 'C', $f);
        $this->SetXY($x += 15, $this->GetY() - 5);
            $this->MultiCell(20, 3, mb_convert_encoding('UNIDAD DE MEDIDA', "ISO-8859-1"), $l, 'C', $f);
        $this->SetXY($x += 20, $this->GetY() - 5);
            $this->MultiCell(88, 3, mb_convert_encoding('DESCRIPCIÓN', "ISO-8859-1"), $l, 'C', $f);
        $this->SetXY($x += 88, $this->GetY() - 5);
            $this->MultiCell(20, 3, mb_convert_encoding('PRECIO UNITARIO', "ISO-8859-1"), $l, 'R', $f);
        $this->SetXY($x += 20, $this->GetY() - 5);
            $this->MultiCell(20, 3, mb_convert_encoding('DESCUENTO', "ISO-8859-1"), $l, 'R', $f);
        $this->SetXY($x += 20, $this->GetY() - 3);
            $this->MultiCell(20, 3, mb_convert_encoding('SUBTOTAL', "ISO-8859-1"), $l, 'R', $f);

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
                $this->Cell(15, 4, mb_convert_encoding('GLOSA:', "ISO-8859-1"), $l, 0, 'L');
            $this->SetFont('Arial', '', 7);
                $this->Cell(145, 4, mb_convert_encoding($this->datos['glosa'], "ISO-8859-1"), $l, 0, 'L');
        }
        /*** leyendas ***/
        $this->SetY($y + 4);
            $this->Cell(160, 4, mb_convert_encoding('ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY', "ISO-8859-1"), $l, 0, 'C');
        $this->SetY($y + 8);
            $this->MultiCell(160, 3, mb_convert_encoding($this->datos['leyenda'], "ISO-8859-1"), $l, 'C', 0);
        $this->SetXY(10, $this->GetY());
        if ($this->datos['codigoEmision'] == '2') {
            $this->MultiCell(160, 4, mb_convert_encoding('Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido fuera de línea, verifique su envío con su proveedor o en la página web www.impuestos.gob.bo', "ISO-8859-1"), $l, 'C', 0);
        } else {
            $this->Cell(160, 4, mb_convert_encoding('Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea', "ISO-8859-1"), $l, 0, 'C');
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
