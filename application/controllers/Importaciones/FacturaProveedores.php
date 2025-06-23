<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class FacturaProveedores extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Ingresos_model");
		$this->load->model("Egresos_model");
		$this->load->model("Cliente_model");
		$this->load->model("Pedidos_model");
		$this->load->library("FileStorage");
		$this->load->config('storage', TRUE);
	}
	public function index()
	{
		$this->accesoCheck(62);
		$this->titles('PagoProveedores','Pago Proveedores','Importaciones');
		$this->datos['foot_script'][]=base_url('assets/hergo/fileutils.js') .'?'.rand();
		$this->datos['foot_script'][]=base_url('assets/hergo/importaciones/facturaProveedores.js') .'?'.rand();
		$this->setView('importaciones/FacturaProveedores');
	}
	public function getFacturaProveedores()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("ini"));
			$fin=$this->security->xss_clean($this->input->post("fin"));
			$filtro=$this->security->xss_clean($this->input->post("filtro"));			
			$res=$this->Pedidos_model->getFacturaProveedores($ini, $fin,$filtro); 
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function storePago()
	{
		if($this->input->is_ajax_request())
		{
			// Utilizar FileStorage para subir el archivo a Spaces
			if (!empty($_FILES['url_pago']['name'])) {
				$uploadResult = $this->filestorage->uploadToSpaces('facturaComercial', $_FILES, 'url_pago');
				$url_pdf = $uploadResult['success'] ? $uploadResult['path'] : '';
				$url = $uploadResult['success'] ? $_FILES['url_pago']['name'] : ''; // Guardamos nombre original en url para compatibilidad
			} else {
				$url_pdf = '';
				$url = '';
			}
			
			$pago = new stdclass();
			$pago->fecha = $this->input->post('fechaPago');
			$pago->url = $url; // Mantiene la columna url para compatibilidad
			$pago->url_pdf = $url_pdf; // Nueva columna para la ruta en Spaces
			$pago->total = $this->input->post('total');
			$pago->created_by = $this->session->userdata('user_id');
			$pago->pagos = json_decode($this->input->post('pagos'));
			//echo json_encode($pago);die();
			$id = $this->Pedidos_model->storePago($pago);
			if($id)
			{
				$res = new stdclass();
				$res->status = true;
				$res->id = $id;
				$res->orden = $pago;
				echo json_encode($res);
			} else {
				echo json_encode($id);
			}

		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function storeAsociarFactura()
	{
		if($this->input->is_ajax_request())
		{
			// Utilizar FileStorage para subir el archivo a Spaces
			if (!empty($_FILES['url']['name'])) {
				$uploadResult = $this->filestorage->uploadToSpaces('facturaComercial', $_FILES, 'url');
				$url_pdf = $uploadResult['success'] ? $uploadResult['path'] : '';
				$url = $uploadResult['success'] ? $_FILES['url']['name'] : ''; // Guardamos nombre original en url para compatibilidad
			} else {
				$url_pdf = '';
				$url = '';
			}
			
			$factServ = new stdclass();
			$factServ->fecha = $this->input->post('fecha');
			$factServ->n = $this->input->post('n');
			$factServ->tiempo_credito = $this->input->post('tiempo_credito');
			$factServ->monto = $this->input->post('monto');
			$factServ->transporte = $this->input->post('transporte');
			$factServ->glosa = $this->input->post('glosa');
			$factServ->url = $url; // Mantiene la columna url para compatibilidad
			$factServ->url_pdf = $url_pdf; // Nueva columna para la ruta en Spaces
			$factServ->id_orden = $this->input->post('id_orden');
			$factServ->id_proveedor = $this->input->post('id_proveedor');
			$factServ->created_by = $this->session->userdata('user_id');
			
			$id = $this->Pedidos_model->storeFactServ($factServ);
			if($id)
			{
				$res = new stdclass();
				$res->status = true;
				$res->id = $id;
				$res->factServ = $factServ;
				echo json_encode($res);
			} else {
				echo json_encode(false);
			}
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}