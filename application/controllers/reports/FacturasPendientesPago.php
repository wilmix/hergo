<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FacturasPendientesPago extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Reportes_model");
		$this->load->model("Ingresos_model");
		$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
	}

    public function index()
    {
        $this->accesoCheck(28);
        $this->titles('PendientesPago','Facturas Pendientes de Pago','Reportes');
        
        $this->datos['foot_script'][]=base_url('assets/hergo/reportes/facturasPendientesPagoNew.js') .'?'.rand();
        $this->setView('reportes/facturasPendietesPagoNew');
    }
    public function show()
	{
			$alm=$this->security->xss_clean($this->input->post('alm')); 
			$ini=$this->security->xss_clean($this->input->post('ini')); 
			$fin=$this->security->xss_clean($this->input->post('fin')); 
			$res=$this->Reportes_model->facturasPendientesPagoNew($alm, $ini, $fin); 
			echo json_encode($res);
	}

}