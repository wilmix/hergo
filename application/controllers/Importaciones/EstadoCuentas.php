
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class EstadoCuentas extends CI_Controller
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
		$this->accesoCheck(63);
		$this->titles('EstadoCuentas','Estado de Cuentas','Importaciones');
		$this->datos['foot_script'][]=base_url('assets/hergo/importaciones/estadoCuentas.js') .'?'.rand();
		$this->setView('importaciones/estadoCuentas');
	}
	public function getEstadoCuentas()  
	{
		if($this->input->is_ajax_request())
        {
			$pedServ=$this->security->xss_clean($this->input->post("pedServ"));
			$signo = $pedServ == 'servicios' ? '=' : '>';
			$condicion=$this->security->xss_clean($this->input->post("condicion"));

			$res=$this->Pedidos_model->getEstadoCuentas($condicion, $signo); 
			//$res = $condicion . ' - ' . $signo;
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}