<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FacturasPendientesPago extends CI_Controller
{
	public $FacturasPendientesPago_model;
	public $Reportes_model;
	public function __construct()
	{
		parent::__construct();
		$this->load->model("reports/FacturasPendientesPago_model");
		$this->load->model("Ingresos_model");
		$this->load->model("Reportes_model");
		$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
	}

    public function index()
    {
        $this->accesoCheck(28);
        $this->titles('PendientesPago','Facturas Pendientes de Pago','Reportes');
		//$this->datos['cabeceras_css'][]='https://cdn.datatables.net/fixedheader/3.2.0/css/fixedHeader.dataTables.min.css';
        
        $this->datos['foot_script'][]=base_url('assets/hergo/reportes/facturasPendientesPagoNew.js') .'?'.rand();
		//$this->datos['foot_script'][]='https://cdn.datatables.net/fixedheader/3.2.0/js/dataTables.fixedHeader.min.js';
        $this->setView('reportes/facturasPendietesPagoNew');
    }
    public function show()
	{
			$alm=$this->security->xss_clean($this->input->post('alm')); 
			$ini=$this->security->xss_clean($this->input->post('ini')); 
			$fin=$this->security->xss_clean($this->input->post('fin')); 
			$tipoEgreso=$this->security->xss_clean($this->input->post('tipoEgreso')); 
			$res=$this->FacturasPendientesPago_model->getFacturasPendientesPago($alm, $ini, $fin,$tipoEgreso); 
			$aux = 0;
			$auxD = 0;
			foreach ($res as $line) {
					if ($line->idFactura == NULL && $line->cliente == NULL) {
						$line->lote = '';
						$line->nFactura = '';
						$line->egreso ='';
						$line->fechaFac = '';
						$line->vendedor = '';
						$line->almacen = '';
						$line->fechaVencimiento = '';
						$line->estado = '';
						$line->diasCredito='';
						$line->cliente = 'TOTAL GENERAL';
						$line->saldo = $line->total - $line->montoPagado;
						//$line->saldoDol = $line->totalFacDol - $line->montoPagoDol;
					} elseif ($line->idFactura == NULL) {
						$line->lote = '';
						$line->nFactura = '';
						$line->fechaFac = '';
						$line->vendedor = '';
						$line->egreso ='';
						$line->almacen = '';
						$line->cliente =  $line->cliente;
						$line->saldo = $line->total - $line->montoPagado;
						//$line->saldoDol = $line->totalFacDol - $line->montoPagoDol;
						$line->fechaVencimiento = '';
						$line->estado = '';
					} else {
						$line->cliente = $line->cliente;
						$line->saldo = $aux + $line->total - $line->montoPagado;
						//$line->saldoDol = $auxD + $line->totalFacDol - $line->montoPagoDol;
					}
					$aux = $line->idFactura == NULL ? 0 : $aux + $line->total - $line->montoPagado;
					//$auxD = $line->idFactura == NULL ? 0 : $auxD + $line->totalFacDol - $line->montoPagoDol;
			}
			echo json_encode($res);
	}

}