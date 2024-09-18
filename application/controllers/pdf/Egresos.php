<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
    require_once APPPATH."/libraries/utils.php";
class Egresos extends MY_Controller {
    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */


    protected $egreso;
  
    protected $egreso_items = 0;
  
    protected $totalEgreso = 0;

    public function __construct()
	{	
        parent::__construct();
        $this->load->model('Egresos_model');
    }

  public function index($id=0) {
    $this->egreso = $this->Egresos_model->mostrarEgresos($id)->row();
    $this->egreso_items = $this->Egresos_model->mostrarDetalle($id);
    $saldoDeudorVencidas = $this->Egresos_model->saldoDeudorVencidas($this->egreso->idcliente)->row();
    $saldoDeudorTotal = $this->Egresos_model->saldoDeudorTotal($this->egreso->idcliente)->row();
    $this->egreso->saldoDeudorVencidas = $saldoDeudorVencidas->saldoDeudor;
    $this->egreso->fechaPrimeraFacVencidas = $saldoDeudorVencidas->fechaFac;
    $this->egreso->saldoDeudorTotal = $saldoDeudorTotal->saldoDeudor;
    $this->egreso->fechaPrimeraFacTotal = $saldoDeudorTotal->fechaFac;
    $this->egreso->userName = $this->session->userdata['nombre'];

    $params = json_decode(json_encode($this->egreso), true);
    $year = date('y',strtotime($this->egreso->fechamov));

    /* echo '<pre>';
    $paramsDirecto = json_decode(json_encode($this->egreso), true);
    var_dump($paramsDirecto);
    echo '</pre>';
    return false; */
    
    $this->load->library('Egresos_lib', $params);
        $this->pdf = new Egresos_lib($params);
        //$this->pdf->AddPage('L',array(215,152));
        $this->pdf->AddPage('P','Letter');
        $this->pdf->AliasNbPages();
        $this->pdf->SetTitle($this->egreso->sigla . ' - ' .$this->egreso->n . ' - ' . $year);
        $this->pdf->SetAutoPageBreak(true,150);//25
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(10);
        $this->pdf->SetFont('Arial', '', 8);

        $this->detalleCompleto($this->egreso_items,  $this->totalEgreso);

        if ($this->egreso->moneda==='1') {
            $this->totalesBolivianos();
        } elseif ($this->egreso->moneda==='2') {
            $this->totalesDolares();
        } 
        //guardar
      $this->pdf->Output($this->egreso->sigla . ' - ' . $this->egreso->n . ' - ' . $year.'.pdf', 'I');
  }
  function detalleCompleto($lineas) : void {
    $n = 1;
    foreach ($lineas->result() as $linea) {
        $this->totalEgreso += $linea->punitario * $linea->cantidad;
        $this->pdf->SetFillColor(255,255,255);
            $this->pdf->Cell(5,5,$n++,'',0,'C',0);
            $this->pdf->Cell(15,5,number_format($linea->cantidad, 2, ".", ","),'',0,'R',0);
            $this->pdf->Cell(10,5,$linea->Sigla,'',0,'C',0);
            $this->pdf->Cell(15,5,$linea->CodigoArticulo,'',0,'C',0);
            
                if ($this->egreso->almacen_destino_id <> '9') {
                    if (strlen($linea->Descripcion) > 65) {
                        $this->pdf->MultiCell(110,4,convertToISO($linea->Descripcion),0,'L',0);
                        $this->pdf->SetXY(165,$this->pdf->GetY()-4);
                    } else {
                        $this->pdf->Cell(110,5,convertToISO($linea->Descripcion),0,0,'L',0);
                    }
                    $this->pdf->Cell(20,5,number_format($linea->punitario, 2, ".", ","),0,0,'R',1);
                    $this->pdf->Cell(20,5,number_format(round($linea->punitario,2) * $linea->cantidad, 2, ".", ","),0,0,'R',1);
                } else {
                    $this->pdf->Cell(150,5,convertToISO($linea->Descripcion),0,0,'L',0);
                }

            $this->pdf->Ln(5);
    }
  }
  function totalesBolivianos() : void {
    if ($this->egreso->almacen_destino_id <> '9') {
        $entera = intval($this->totalEgreso);
        $ctvs = round(($this->totalEgreso - $entera) * 100);
        $ctvs = ($ctvs == 0) ? '00' : $ctvs;
            $this->pdf->SetFont('Arial','B',8);
            $this->pdf->SetFillColor(255,255,255);
            $this->pdf->Cell(175,5,'TOTAL Bs.',0,0,'R',1);
            $this->pdf->Cell(20,5,number_format($this->totalEgreso, 2, ".", ","),0,1,'R',1); 
            $this->pdf->SetFont('Times','B',9);
            $this->pdf->Cell(9,6,'SON: ',0,0,'L',1);
            //$literal = NumeroALetras::convertir($this->totalEgreso,'BOLIVIANOS','CENTAVOS');
            //$this->pdf->Cell(186,6,$literal,0,0,'l',1);
            $literal = NumeroALetras::convertir($entera).$ctvs.'/100 '.'BOLIVIANOS';
            $this->pdf->Cell(186,6,$literal,0,0,'l',1);
    }
  }
  function totalesDolares() : void {
    if ($this->egreso->almacen_destino_id <> '9') {
        $tipoCambio = floatval($this->egreso->tipocambio);
        $totalBolivianos = $this->totalEgreso*$tipoCambio;
        $entera = intval( $totalBolivianos);
        $ctvs = round(($totalBolivianos - $entera) * 100);
        $ctvs = ($ctvs == 0) ? '00' : $ctvs;
        $this->pdf->SetFont('Times','B',10);
        $this->pdf->SetFillColor(255,255,255);
        $this->pdf->Cell(175,5,'TOTAL $u$',0,0,'R',1);
        $this->pdf->Cell(20,5,number_format($this->totalEgreso, 2, ".", ","),0,1,'R',1); 
        $this->pdf->Cell(175,5,'Tipo Cambio:',0,0,'R',1);
        $this->pdf->Cell(20,5,number_format($tipoCambio, 2, ".", ","),0,1,'R',1);
        $this->pdf->Cell(175,5,'TOTAL BOB',0,0,'R',1);
        $this->pdf->Cell(20,5,number_format($totalBolivianos, 2, ".", ","),0,1,'R',1); 
        $this->pdf->SetFont('Courier','B',9);
        $this->pdf->Cell(10,6,'SON: ',0,0,'L',1);
        $entera = intval( $totalBolivianos);
        $literal = NumeroALetras::convertir($entera).$ctvs.'/100 '.'BOLIVIANOS';
        $this->pdf->Cell(186,6,$literal,0,0,'l',1);
    }
  }
}