
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class OrdenesCompra extends MY_Controller
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
		$this->accesoCheck(61);
		$this->titles('OrdenesCompra','Consulta Orden de Compra','Importaciones');
		$this->datos['foot_script'][]=base_url('assets/hergo/fileutils.js') .'?'.rand();
		$this->datos['foot_script'][]=base_url('assets/hergo/importaciones/ordenesCompra.js') .'?'.rand();
		$this->setView('importaciones/OrdenesCompra');
	}
	public function getPedidos()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("ini"));
        	$fin=$this->security->xss_clean($this->input->post("fin"));
			$res=$this->Pedidos_model->getPedidos($ini, $fin); 
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function crearOrden()
	{
		$this->accesoCheck(60);
		$this->titles('FormOrdenCompra','Formulario Orden de Compra','Importaciones');
		$this->datos['foot_script'][]=base_url('assets/hergo/importaciones/formOrden.js') .'?'.rand();
		$this->setView('importaciones/formOrden');
	}
	public function editOrden($id)
	{
		$this->accesoCheck(58);
		$this->titles('EditarOrdenCompra','Editar Orden de Compra','Importaciones');
		$this->datos['foot_script'][]=base_url('assets/hergo/importaciones/formOrden.js') .'?'.rand();
		$this->datos['id']=$id;
		$this->setView('importaciones/formOrden');
	}
	public function store()
	{
		if($this->input->is_ajax_request())
		{
			$id = $this->input->post('id');
			$gestion = date("Y", strtotime($this->input->post('fecha')));
			$orden = new stdclass();
			$orden->id_pedido = $this->input->post('id_pedido');
			$orden->n = $id ? $this->input->post('n') : $this->Pedidos_model->getNumMovOrden($gestion);
			$orden->fecha = $this->input->post('fecha');
			$orden->atencion = strtoupper($this->input->post('atencion'));
			$orden->diasCredito = strtoupper($this->input->post('diasCredito'));
			$orden->referencia = strtoupper($this->input->post('referencia'));
			$orden->condicion = strtoupper($this->input->post('condicion'));
			$orden->formaEnvio = strtoupper($this->input->post('formaEnvio'));
			$orden->glosa = strtoupper($this->input->post('glosa'));
			$orden->created_by = $this->session->userdata('user_id');
			/* echo json_encode($id);
			die(); */
			$id = $this->Pedidos_model->storeOrden($id , $orden);

			if($id)
			{
				$res = new stdclass();
				$res->status = true;
				$res->id = $id;
				$res->orden = $orden;
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
	public function getOrden()  
	{
		if($this->input->is_ajax_request())
        {
			$id=$this->security->xss_clean($this->input->post("id"));
			$orden = new stdclass();
			$orden->orden = $this->Pedidos_model->getOrden($id); 
			$orden->items = $this->Pedidos_model->getOrdenItems($id);
			echo json_encode($orden);
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
				$id = '';
			$asoFac = new stdclass();
			
			// Validamos cada campo por separado para evitar valores nulos
			$id_orden = $this->input->post('id_orden');
			$n = $this->input->post('n');
			$fecha = $this->input->post('fecha');
			$id_proveedor = $this->input->post('id_proveedor');
			$monto = $this->input->post('monto');
			$tiempo_credito = $this->input->post('tiempo_credito');
			
			$asoFac->id_orden = $id_orden ? $this->security->xss_clean($id_orden) : 0;
			$asoFac->n = $n ? strtoupper($this->security->xss_clean($n)) : '';
			$asoFac->fecha = $fecha ? $this->security->xss_clean($fecha) : date('Y-m-d');
			$asoFac->proveedor = $id_proveedor ? $this->security->xss_clean($id_proveedor) : 0;
			$asoFac->monto = $monto ? $this->security->xss_clean($monto) : 0;
			$asoFac->tiempo_credito = $tiempo_credito ? $this->security->xss_clean($tiempo_credito) : 0;
			$asoFac->url = $url; // Mantener compatibilidad
			$asoFac->url_pdf = $url_pdf; // Nueva columna para la ruta en Spaces
			
			// Prevenir valores nulos en transporte y glosa
			$transporte = $this->input->post('transporte');
			$glosa = $this->input->post('glosa');
			
			$asoFac->transporte = $transporte ? strtoupper($this->security->xss_clean($transporte)) : '';
			$asoFac->glosa = $glosa ? strtoupper($this->security->xss_clean($glosa)) : '';
			$asoFac->created_by = $this->session->userdata('user_id');

			
			
			$id = $this->Pedidos_model->storeAsociarFactura($id, $asoFac);

			if($id)
        	{
				echo json_encode($id);
        	}
			else
			{				
				echo json_encode(false);
			}
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}