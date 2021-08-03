
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class FacturaTipoPago extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Ingresos_model");
		$this->load->model("Egresos_model");
		$this->load->model("Cliente_model");
		$this->load->model("Pedidos_model");
	}
	
	public function index()
	{
		$this->titles('FacturaTipoPago','Factura Tipo Pago','Reporte');
		$this->datos['foot_script'][]=base_url('assets/hergo/reportes/facturaTipoPago.js') .'?'.rand();
		$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
		$this->setView('reportes/facturaTipoPago');
    }
    function getFacturaTipoPago ()
    {
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("ini"));
            $fin=$this->security->xss_clean($this->input->post("fin"));
        	$alm=$this->security->xss_clean($this->input->post("alm"));
            //$res=$this->Pedidos_model->getPedidos($ini, $fin); 
            
			echo json_encode($ini . '-' . $fin  . '-' . $alm);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }

}