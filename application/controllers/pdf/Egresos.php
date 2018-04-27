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
    $params = array(
        'almacen' => $egreso->almacen,
        'autor' => $egreso->autor,
        'clientePedido' => $egreso->clientePedido,
        'fechamov' => $egreso->fechamov,
        'numero' => $egreso->n,
        'nombreCliente' => $egreso->nombreCliente,
        'observaciones' => $egreso->obs,
        'plazoPago' => $egreso->plazopago,
        'sigla' => $egreso->sigla,
        'tipocambio' => $egreso->tipocambio,
        'tipoMov' => $egreso->tipomov,
        'documento' => $egreso->documento,
        'direccion' => $egreso->direccion,
        'telefono' => $egreso->telefono,
        'fax' => $egreso->fax,
        'userName' => $this->session->userdata['nombre']
    );
    $year = date('y',strtotime($egreso->fechamov));
    /*echo '<pre>';
    print_r($this->session->userdata['nombre']);
    echo '</pre>';*/
    
    $this->load->library('Egresos_lib', $params);
        $this->pdf = new Egresos_lib($params);
        $this->pdf->AddPage('L',array(215,152));
        $this->pdf->AliasNbPages();
        $this->pdf->SetTitle($egreso->tipomov . ' - ' .$egreso->n . ' - ' . $year);
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(10);
        $this->pdf->SetFont('Arial', '', 8);
            $n = 1;
            $totalEgreso=0;
            foreach ($lineas->result() as $linea) {
                $totalEgreso += $linea->total;
                $this->pdf->SetFillColor(255,255,255);
                    $this->pdf->Cell(5,5,$n++,'',0,'C',0); ///NUMERO DE FILA
                    $this->pdf->Cell(15,5,number_format($linea->cantidad, 2, ".", ","),'',0,'R',0);
                    $this->pdf->Cell(10,5,$linea->Sigla,'',0,'C',0);
                    $this->pdf->Cell(15,5,$linea->CodigoArticulo,'',0,'C',0);
                    $this->pdf->Cell(110,5,utf8_decode($linea->Descripcion),0,0,'L',0);
                    $this->pdf->Cell(20,5,number_format($linea->punitario, 2, ".", ","),0,0,'R',1);
                    $this->pdf->Cell(20,5,number_format($linea->total, 2, ".", ","),'',0,'R',1);
                $this->pdf->Ln(5);
            }
            $this->pdf->Ln(2);


        //guardar
      $this->pdf->Output($egreso->sigla . ' - ' . $egreso->n . ' - ' . $year, 'I');
  }
}