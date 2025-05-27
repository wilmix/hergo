<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pagos extends MY_Controller  /////**********nombre controlador
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("Pagos_model");
		$this->load->model("Egresos_model");
		$this->load->model("Facturacion_model");
		$this->load->model("Ingresos_model");
	}
	public function index()
	{
		$this->accesoCheck(23);
		$this->titles('Pagos','Consulta','Pagos');
		$this->datos['foot_script'][]=base_url('assets/hergo/pagos.js') .'?'.rand();
		$this->datos['almacen']=$this->Pagos_model->retornar_tabla("almacenes");
		$this->setView('pagos/consultarPagos');
	}
	public function mostrarPagos()  //******cambiar a funcion del modelo
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//FECHA FIN
        	$alm=$this->security->xss_clean($this->input->post("a")); //almacen
			$res=$this->Pagos_model->mostrarPagos($ini,$fin,$alm); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function RecibirPago()
	{
		$this->accesoCheck(24);
		$this->titles('RecibirPago','Recibir Pago','Pagos');
		$this->datos['foot_script'][]=base_url('assets/hergo/recibirPagos.js') .'?'.rand();
		$this->datos['almacen']=$this->Pagos_model->retornar_tabla("almacenes");
		$this->datos['tipoPago']=$this->Pagos_model->retornar_tabla("tipoPago");
		$this->datos['bancos']=$this->Pagos_model->retornar_tabla("bancos");
		
		$this->setView('pagos/recibirPagos');
	}
	public function editarPago($idPago=0)
	{
		$this->accesoCheck(25);
		$this->titles('EditarPago','Editar Pago','Pagos');
		$this->datos['foot_script'][]=base_url('assets/hergo/recibirPagos.js') .'?'.rand();
		//$this->datos['foot_script'][]=base_url('assets/hergo/editarPago.js') .'?'.rand();

		$this->datos['almacen']=$this->Pagos_model->retornar_tabla("almacenes");
		$this->datos['tipoPago']=$this->Pagos_model->retornar_tabla("tipoPago");
		$this->datos['bancos']=$this->Pagos_model->retornar_tabla("bancos");
		$this->datos['idPago']=$idPago;
		$this->datos['cab']=$this->getPagoCabecera($idPago);
		
		$this->setView('pagos/recibirPagos');
	}
	public function getPagoCabecera($id)
	{
		$res = $this->Pagos_model->retornarEdicion($id);
        if($res->num_rows()>0)
    	{
    		$fila=$res->row();
    		return $fila;
    	}
        else
        {
            return(false);
        }
	}
	public function retornarEdicion() {
		if ($this->input->is_ajax_request()) {
			$idPago=$this->security->xss_clean($this->input->post("idPago"));
			$res = new stdclass();
			$res->cabecera=$this->Pagos_model->retornarEdicion($idPago)->row();
			$res->detalle=$this->Pagos_model->retornarEdicionDetalle($idPago)->result_array();
			//$res=$res->array();
			echo json_encode($res);
		} else {
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function mostrarPendientePago()  //******cambiar a funcion del modelo
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//FECHA FIN
        	$alm=$this->security->xss_clean($this->input->post("a")); //almacen
			$res=$this->Pagos_model->mostrarPendientePago($ini,$fin,$alm); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function guardarPagos()
	{
		if ($this->validate()==TRUE)
		if($this->input->is_ajax_request())
        {
			$data=($this->input->post());
			$data=json_decode(json_encode($data), FALSE);
			
			
			$config = [
				"upload_path" => "./assets/img_pagos/",
				"allowed_types" => "png|jpg|jpeg"
			];
			$this->load->library("upload",$config);
			if ($this->upload->do_upload('img_route')) {
				$img = array("upload_data" => $this->upload->data());
				$img_name = $img['upload_data']['file_name'];
			}
			else{
				//echo $this->upload->display_errors();
				$img_name = '';
			}
			$gestion = $this->Egresos_model->getGestionActual()->gestionActual;
			/* var_dump($data->almacen);
			die(); */
			$numPago=$this->retornarNumPago($data->almacen, $gestion);
			$pago = new stdclass();
			$pago->almacen=$data->almacen;
			$pago->img_route = $img_name;
			$pago->numPago=$numPago;
			$pago->fechaPago=$data->fechaPago;
			$pago->fechaPago = date('Y-m-d',strtotime($pago->fechaPago));
			$pago->gestion= $gestion;
			$pago->moneda=$data->moneda;
			$pago->cliente=$data->cliente;
			$pago->totalPago=$data->totalPago;
			$pago->anulado=$data->anulado;
			$pago->glosa=strtoupper($data->glosa);
			$pago->autor=$this->session->userdata('user_id');
			//$pago->fecha=date('Y-m-d H:i:s');
			$tipocambio = $this->Ingresos_model->getTipoCambio($pago->fechaPago);
			$pago->tipoCambio = $tipocambio->tipocambio;
			$pago->tipoPago=$data->tipoPago;
			$pago->cheque=isset($data->cheque) ? $data->cheque : '';
			$pago->banco=$data->banco ?? 0;
			$pago->transferencia=strtoupper($data->transferencia ?? '');
			$pago->pagos=json_decode($data->porPagar);
			
			$idPago=$this->Pagos_model->storePago($pago);
			if ($idPago == false) {
				$return=new stdClass();
				$return->status='error';
				echo json_encode($return);

			} else {
				$return=new stdClass();
				$return->status=200;
				$return->id=$idPago;
				echo json_encode($return);
			}
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function validate() {
        $json = array();
		$this->form_validation->set_rules('idPago', 'idPago', 'required|numeric');
		$this->form_validation->set_rules('almacen', 'almacen', 'required|numeric');
		$this->form_validation->set_rules('fechaPago', 'fechaPago', 'required');
		$this->form_validation->set_rules('cliente', 'cliente', 'required|numeric');
		$this->form_validation->set_rules('tipoPago', 'tipoPago', 'required|numeric');
		$this->form_validation->set_rules('banco', 'banco', 'numeric');
		$this->form_validation->set_rules('porPagar', 'porPagar', 'required|callback_validate_pago');
		$this->form_validation->set_message('validate_pago','Una Factura esta pagada');

		if ($this->form_validation->run() == FALSE) {
			$json = array(
                'transferencia' => form_error('transferencia'),
                'idPago' => form_error('idPago'),
                'almacen' => form_error('almacen'),
                'fechaPago' => form_error('fechaPago'),
                'cliente' => form_error('cliente'),
                'tipoPago' => form_error('tipoPago'),
                'banco' => form_error('banco'),
                'glosa' => form_error('glosa'), 
                'porPagar' => form_error('porPagar'),
            );
			$json = array_filter($json);
			$return=new stdClass();
			$return->status=400;
			$return->msj=$json;
			echo json_encode($return);
			die();
		} else {
			return TRUE;
		}
    }
	function validate_pago($pagos)
	{
		$pagos =json_decode($pagos);
		$result = [];
		foreach ($pagos as $pago) {
			if ($this->Pagos_model->validate_pago($pago->idFactura)->num_rows()>0) {
				array_push($result, $this->Pagos_model->validate_pago($pago->idFactura)->row());
			}
		}
		if($result === [])
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function retornarNumPago($almacen, $gestion)
	{
		$numPago=$this->Pagos_model->obtenerNumPago($almacen, $gestion);
		return $numPago+1;
	}
	public function retornarIdPago($numPago,$almacen)
	{
		$idPago=$this->Pagos_model->obtenerIdPago($numPago,$almacen);
		return $idPago;
	}
	public function retornarDetallePago()
	{		
		$idPago=$this->security->xss_clean($this->input->post('idPago'));				
		$ret=$this->Pagos_model->retornarDetallePago($idPago);
		echo json_encode($ret);
	}
	public function anularPago() {
		if($this->input->is_ajax_request())
        {

			$idPago=$this->security->xss_clean($this->input->post('idPago'));	
			$msj=strtoupper($this->security->xss_clean($this->input->post('msj')));	
			$this->Pagos_model->anularPago($idPago, $msj);
			$facturas=$this->Pagos_model->retornarIdFacturas($idPago);	
			$return=new stdClass();
			$return->facturas=$facturas->result();
			foreach ($return->facturas as $value) {
				$totalPago=$this->Pagos_model->totalPago($value->idFactura);
				$totalPagado = $totalPago->result()[0]->totalPago;
				if ($totalPagado == 0) {
					$pagada = 0;
					$this->Pagos_model->modificarPagadaFactura($pagada,$value->idFactura);
				} else {
					$pagada = 2;
					$this->Pagos_model->modificarPagadaFactura($pagada,$value->idFactura);
				}
			}
			
			$return->status=200;
			$return->idPago=$idPago;
			$return->msj=$msj;
			echo json_encode($return);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function editarPagos()
	{
		if($this->input->is_ajax_request())
        {
			$data=($this->input->post());
			$data=json_decode(json_encode($data), FALSE);
			
			
			$config = [
				"upload_path" => "./assets/img_pagos/",
				"allowed_types" => "png|jpg"
			];
			$this->load->library("upload",$config);
			if ($this->upload->do_upload('img_route')) {
				$img = array("upload_data" => $this->upload->data());
				$img_name = $img['upload_data']['file_name'];
			}
			else{
				//echo $this->upload->display_errors();
				$img_name = isset($data->img_name) ? $data->img_name: '';
			}

			$idPago=$data->idPago;
			$gestion = date('Y',strtotime($data->fechaPago));
			
			$pago = new stdclass();
			$pago->img_route = $img_name; 
			$pago->fechaPago=$data->fechaPago;
			$pago->fechaPago = date('Y-m-d',strtotime($pago->fechaPago));
			$pago->moneda=$data->moneda;
			$pago->cliente=$data->cliente;
			$pago->totalPago=$data->totalPago;
			$pago->glosa=strtoupper($data->glosa);
			$pago->autor=$this->session->userdata('user_id');
			$tipocambio = $this->Ingresos_model->getTipoCambio($pago->fechaPago);
			$pago->tipoCambio = $tipocambio->tipocambio;
			$pago->tipoPago=$data->tipoPago;
			$pago->cheque=isset($data->cheque) ? $data->cheque : '';
			$pago->banco=$data->banco;
			$pago->transferencia = strtoupper($data->transferencia);
			//$pago->gestion = $gestion;

			$editarPago = $this->Pagos_model->editarPago($idPago,$pago,json_decode($data->porPagar));

			if ($editarPago) {
				$return=new stdClass();
				$return->status=200;
				$return->id = $editarPago;
				echo json_encode($return);
			} else {
				echo json_encode($pago);
				return false;
			}
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}
