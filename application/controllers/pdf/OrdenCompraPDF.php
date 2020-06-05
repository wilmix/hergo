<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class OrdenCompraPDF extends FPDF {

    function Header()
    {
        $this->SetFont('Arial','B',15);
        $this->SetXY(0,20);
        $this->Cell(0,8, utf8_decode('ORDEN DE COMPRA Nº HG.- 097/19'),0,0,'C');
        $this->Ln(7);
        $this->SetFont('Arial','B',10);
        $this->Cell(0,8, utf8_decode('Nº de NIT: 1000991026'),0,0,'C');
        $this->Ln(5);
        $this->SetFont('Arial','B',12);
        $this->Cell(0,8, utf8_decode('La Paz, 22 de agosto de 2019 '),0,0,'C');
        $this->Ln(10);
        $this->SetFont('Arial','B',11);
        $this->Cell(0,8, utf8_decode('Señor(es): '),0,0,'L');
        $this->Ln(5);
        $this->Cell(0,8, utf8_decode('Atención: '),0,0,'L');
        $this->Ln(5);
        $this->Cell(0,8, utf8_decode('Dirección: '),0,0,'L');
        $this->Ln(5);
        $this->Cell(0,8, utf8_decode('Referencia: '),0,0,'L');

        $this->SetXY(150,40);
        $this->Cell(0,8, utf8_decode('Telf.: '),0,0,'L');
        $this->Ln(5);
        $this->SetXY(150,45);
        $this->Cell(0,8, utf8_decode('Fax.: '),0,0,'L');
        $this->Ln(5);


        $this->Ln(25);

    }
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-40);
        $this->SetFont('Arial','B',10);
        $this->Cell(0,8, utf8_decode('Condiciones de compra: '),0,0,'L');
        $this->SetX(55);
        $this->Cell(0,8, utf8_decode('EXWW '),0,0,'L');
        $this->Ln(5);
        $this->Cell(0,8, utf8_decode('Forma de Envio: '),0,0,'L');
        $this->SetX(55);
        $this->Cell(0,8, utf8_decode('Terrestre '),0,0,'L');
        $this->Ln(5);
        $this->Cell(0,8, utf8_decode('Termino de pago: '),0,0,'L');
        $this->SetX(55);
        $this->Cell(0,8, utf8_decode('Crédito 45 dias '),0,0,'L');
        $this->Ln(5);
        $this->Cell(0,8, utf8_decode('Observaciones: '),0,0,'L');
        $this->SetX(55);
        $this->Cell(0,8, utf8_decode('Por favor colocar la condicion de credito en la factura comercial'),0,0,'L');
        $this->Ln(5);
        $this->Ln(5);
    }
    public function index($id=null)
    {
        $this->pdf = new OrdenCompraPDF();
        $this->pdf->AddPage('P','Letter');
        $this->pdf->SetFont('Arial', '', 18);
        $this->pdf->Cell(0,10,'id de orden '.$id,0,0,1);
        $this->pdf->Output();
    }

}