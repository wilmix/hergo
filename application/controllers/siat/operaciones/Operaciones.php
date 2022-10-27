<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Operaciones extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model('siat/Emitir_model');
		$this->load->model('Egresos_model');
		$this->load->model('Facturacion_model');
	}
    public function eventosSignificativos()
	{
		//$this->accesoCheck(67);
		$this->titles('EventosSignificativos','Eventos Significativos','SIAT');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/operaciones/eventosSignificativos.js') .'?'.rand();
		$this->setView('siat/operaciones/eventosSignificativos');
	}

}