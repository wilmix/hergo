<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class Egresos extends CI_Controller {
  public function index($id=null) {
    //echo $id;
    $this->load->model('Egresos_model');
    $lineas = $this->Egresos_model->mostrarDetalle($id);
    $egreso = $this->Egresos_model->mostrarEgresos($id)->row();
    $idCliente = $egreso->idcliente;
    $saldoDeudor = $this->Egresos_model->saldoDeudorCliente($idCliente)->row();
    $params = array(
        'almacen' => $egreso->almacen,
        'autor' => $egreso->autor,
        'clientePedido' => $egreso->clientePedido ?? '',
        'fechamov' => $egreso->fechamov,
        'numero' => $egreso->n,
        'nombreCliente' => $egreso->nombreCliente,
        'observaciones' => $egreso->obs ?? '',
        'plazoPago' => $egreso->plazopago ?? '',
        'sigla' => $egreso->sigla,
        'tipocambio' => $egreso->tipocambio,
        'tipoMov' => $egreso->tipomov,
        'documento' => $egreso->documento,
        'direccion' => $egreso->direccion ?? '',
        'telefono' => $egreso->telefono ?? '',
        'fax' => $egreso->fax ?? '',
        'email' => $egreso->email ?? '',
        'moneda' => $egreso->moneda,
        'vendedor'=> $egreso->vendedor,
        'nVendedor'=> $egreso->nVendedor,
        'almDes'=>$egreso->almDes,
        'nIng'=>$egreso->nIng,
        'idTipoMov'=>$egreso->idtipomov,
        'userName' => $this->session->userdata['nombre'],
        'almDirec'=>$egreso->almDirec,
        'almFono'=>$egreso->almFono,
        'saldoDeudor'=>$saldoDeudor->saldoDeudor,
        'fechaPrimeraFac'=>$saldoDeudor->fechaFac,

    );
    $year = date('y',strtotime($egreso->fechamov));
    /*echo '<pre>';
    print_r($idCliente);
    echo '</pre>';*/
    
    $this->load->library('Egresos_lib', $params);
        $this->pdf = new Egresos_lib($params);
        //$this->pdf->AddPage('L',array(215,152));
        $this->pdf->AddPage('P','Letter');
        $this->pdf->AliasNbPages();
        $this->pdf->SetTitle($egreso->sigla . ' - ' .$egreso->n . ' - ' . $year);
        $this->pdf->SetAutoPageBreak(true,150);//25
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(10);
        $this->pdf->SetFont('Arial', '', 8);

        if ($egreso->moneda==='1') {
            $n = 1;
            $totalEgreso=0;
            foreach ($lineas->result() as $linea) {
                $totalEgreso += $linea->punitario * $linea->cantidad;
                $this->pdf->SetFillColor(255,255,255);
                    $this->pdf->Cell(5,5,$n++,'',0,'C',0); ///NUMERO DE FILA

                    $this->pdf->Cell(15,5,number_format($linea->cantidad, 2, ".", ","),'',0,'R',0);
                    $this->pdf->Cell(10,5,$linea->Sigla,'',0,'C',0);
                    $this->pdf->Cell(15,5,$linea->CodigoArticulo,'',0,'C',0);
                    if (strlen($linea->Descripcion) > 65) {
                        $this->pdf->MultiCell(110,4,iconv('UTF-8', 'windows-1252', ($linea->Descripcion)),0,'L',0);
                        $this->pdf->SetXY(165,$this->pdf->GetY()-4);
                    } else {
                        $this->pdf->Cell(110,5,utf8_decode($linea->Descripcion),0,0,'L',0);
                    }
                    $this->pdf->Cell(20,5,number_format($linea->punitario, 2, ".", ","),0,0,'R',1);
                    $this->pdf->Cell(20,5,number_format(round($linea->punitario,2) * $linea->cantidad, 2, ".", ","),0,0,'R',1);
                $this->pdf->Ln(5);
            }
            $entera = intval($totalEgreso);
            $ctvs = round(($totalEgreso - $entera) * 100);
            $ctvs = ($ctvs == 0) ? '00' : $ctvs;
                $this->pdf->SetFont('Arial','B',8);
                $this->pdf->SetFillColor(255,255,255);
                $this->pdf->Cell(175,5,'TOTAL Bs.',0,0,'R',1);
                $this->pdf->Cell(20,5,number_format($totalEgreso, 2, ".", ","),0,1,'R',1); 
                $this->pdf->SetFont('Times','B',9);
                $this->pdf->Cell(9,6,'SON: ',0,0,'L',1);
                //$literal = NumeroALetras::convertir($totalEgreso,'BOLIVIANOS','CENTAVOS');
                //$this->pdf->Cell(186,6,$literal,0,0,'l',1);
                $literal = NumeroALetras::convertir($entera).$ctvs.'/100 '.'BOLIVIANOS';
                $this->pdf->Cell(186,6,$literal,0,0,'l',1);


        } elseif ($egreso->moneda==='2') {
            $tipoCambio = floatval($egreso->tipocambio);
            $n = 1;
            $totalEgreso=0;
            foreach ($lineas->result() as $linea) {
                $totalEgreso += $linea->punitario * $linea->cantidad;
                $this->pdf->SetFillColor(255,255,255);
                    $this->pdf->Cell(5,5,$n++,'',0,'C',0); ///NUMERO DE FILA
                    $this->pdf->Cell(15,5,number_format($linea->cantidad, 2, ".", ","),'',0,'R',0);
                    $this->pdf->Cell(10,5,$linea->Sigla,'',0,'C',0);
                    $this->pdf->Cell(15,5,$linea->CodigoArticulo,'',0,'C',0);
                    $this->pdf->Cell(110,5,utf8_decode($linea->Descripcion),0,0,'L',0);
                    $this->pdf->Cell(20,5,number_format($linea->punitario, 2, ".", ","),0,0,'R',1);
                    $this->pdf->Cell(20,5,number_format($linea->punitario * $linea->cantidad, 2, ".", ","),'',0,'R',1);
                $this->pdf->Ln(5);
            }
            $totalBolivianos = $totalEgreso*$tipoCambio;
            $entera = intval( $totalBolivianos);
            $ctvs = round(($totalBolivianos - $entera) * 100);
            $ctvs = ($ctvs == 0) ? '00' : $ctvs;
            $this->pdf->SetFont('Times','B',10);
            $this->pdf->SetFillColor(255,255,255);
            $this->pdf->Cell(175,5,'TOTAL $u$',0,0,'R',1);
            $this->pdf->Cell(20,5,number_format($totalEgreso, 2, ".", ","),0,1,'R',1); 
            $this->pdf->Cell(175,5,'Tipo Cambio:',0,0,'R',1);
            $this->pdf->Cell(20,5,number_format($tipoCambio, 2, ".", ","),0,1,'R',1);
            $this->pdf->Cell(175,5,'TOTAL BOB',0,0,'R',1);
            $this->pdf->Cell(20,5,number_format($totalBolivianos, 2, ".", ","),0,1,'R',1); 
            $this->pdf->SetFont('Courier','B',9);
            $this->pdf->Cell(10,6,'SON: ',0,0,'L',1);
            $entera = intval( $totalBolivianos);
            $literal = NumeroALetras::convertir($entera).$ctvs.'/100 '.'BOLIVIANOS';
            $this->pdf->Cell(186,6,$literal,0,0,'l',1);
            //$literal = NumeroALetras::convertir($totalBolivianos,'BOLIVIANOS','CENTAVOS');
            //$this->pdf->Cell(185,6,$literal,0,0,'l',1);
        } 
        //guardar
      $this->pdf->Output($egreso->sigla . ' - ' . $egreso->n . ' - ' . $year.'.pdf', 'I');
  }
}