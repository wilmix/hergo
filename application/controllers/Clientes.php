<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Clientes extends CI_Controller
{
	public function __construct()
	{	
		parent::__construct();

		$this->load->model("Cliente_model");
        //$this->load->model("Ingresos_model");

	}
	public function index()
	{
		$this->accesoCheck(6);
		$this->titles('Clientes','Clientes','Administracion',);
		
		$this->datos['foot_script'][]=base_url('assets/hergo/clientes.js') .'?'.rand();
		$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
		$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");
		
		$this->setView('administracion/clientes/clientes');
	}
	public function mostrarclientes()
	{
		if(!$this->input->is_ajax_request())
		die("PAGINA NO ENCONTRADA");

			$res=$this->Cliente_model->mostrarclientes_model();
			$res=$res->result_array();
			echo json_encode($res);

	}
	public function store()
	{
		if(!$this->input->is_ajax_request())
		die("PAGINA NO ENCONTRADA");

			$id = $this->input->post('id_cliente');
			$cliente = new stdclass();
			$cliente->documento = (trim($this->input->post('carnet')));
			$cliente->nombreCliente= strtoupper(trim($this->input->post('nombre_cliente')));
			$cliente->direccion = strtoupper(trim($this->input->post('direccion')));
			$cliente->email = $this->input->post('email');
			$cliente->web = strtoupper(trim($this->input->post('website')));
			$cliente->telefono = strtoupper(trim($this->input->post('phone')));
			$cliente->fax = strtoupper(trim($this->input->post('fax')));
			$cliente->idDocumentoTipo = $this->input->post('tipo_doc');
			$cliente->idClientetipo = $this->input->post('clientetipo');
			$cliente->autor = $this->session->userdata('user_id');
			$cliente->fecha = date('Y-m-d H:i:s');

			if ($id==0) {
				$checkCliente = $this->chekSaveCliente($cliente->documento,$cliente->nombreCliente);
			} else {
				$checkCliente = $this->chekUpdateCliente($cliente->documento,$cliente->nombreCliente);
			}
			
			if ($checkCliente == false) {
				$id = $this->Cliente_model->storeCliente($id ,$cliente);
				$res = new stdclass();
				$res->status = true;
				$res->id = $id;
				$res->cliente = $cliente;
				echo json_encode($res);
			} else {
				$res = new stdclass();
				$res->status = false;
				$res->id = $id;
				$res->cliente = $checkCliente->row();
				echo json_encode($res);
			}			

	}
	public function chekSaveCliente($nit,$nombe)
	{
		$clienteNit =  $this->Cliente_model->getClientByDoc($nit);
		$clienteNitName = $this->Cliente_model->getClientByDocName($nit,$nombe);
		if($clienteNit->num_rows()>0)
        {
			if ($nit == '99001' || $nit == '0') {
				if ($clienteNitName->num_rows()>0) {
					return $clienteNitName;
				}
				else {
					return false;
				}
			} else {
				if ($clienteNit->num_rows()>0) {
					return $clienteNit;
				}
				else {
					return false;
				}
			}
        }
        else
        {
            return false;
        }

	}
	public function chekUpdateCliente($nit,$nombe)
	{
		$clienteNitName = $this->Cliente_model->getClientByDocName($nit,$nombe);
		if ($clienteNitName->num_rows()>0) {
			return $clienteNitName;
		}
		else {
			return false;
		}
	}
}