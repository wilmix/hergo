
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class OrdenesCompra extends CI_Controller
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
		$this->accesoCheck(61);
		$this->titles('OrdenesCompra','Consulta Orden de Compra','Importaciones');
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
			$config = [
				"upload_path" => "./assets/facComProv/",
				"allowed_types" => "pdf"
			];
			$this->load->library("upload",$config);
				if ($this->upload->do_upload('url')) {
					$pdf = array("upload_data" => $this->upload->data());
					$url = $pdf['upload_data']['file_name'];
				}
				else{
					//echo $this->upload->display_errors();
					$url = '';
				}
				$id = '';
			$asoFac = new stdclass();
			$asoFac->id_orden = $this->security->xss_clean($this->input->post('id_orden'));
			$asoFac->n = strtoupper($this->security->xss_clean($this->input->post('n')));
			$asoFac->fecha = $this->security->xss_clean($this->input->post('fecha'));
			$asoFac->proveedor = $this->security->xss_clean($this->input->post('id_proveedor'));
			$asoFac->monto = $this->security->xss_clean($this->input->post('monto'));
			$asoFac->tiempo_credito = $this->security->xss_clean($this->input->post('tiempo_credito'));
			$asoFac->monto = $this->security->xss_clean($this->input->post('monto'));
			$asoFac->url = $url;
			$asoFac->transporte = strtoupper($this->security->xss_clean($this->input->post('transporte')));
			$asoFac->glosa = strtoupper($this->security->xss_clean($this->input->post('glosa')));
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