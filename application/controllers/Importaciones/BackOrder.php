
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BackOrder extends MY_Controller
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
		$this->accesoCheck(64);
		$this->titles('BackOrders','BackOrders','Importaciones');
		$this->datos['editarBack']= $this->libacceso->accesoSubMenu(65);
		$this->datos['foot_script'][]=base_url('assets/hergo/importaciones/backOrder.js') .'?'.rand();
		$this->setView('importaciones/backOrder');
	}
	public function getBackOrderList()  
	{
		if($this->input->is_ajax_request())
        {
			$ini=$this->security->xss_clean($this->input->post("ini"));
        	$fin=$this->security->xss_clean($this->input->post("fin"));
			$filter=$this->security->xss_clean($this->input->post("filtro"));
			$res=$this->Pedidos_model->getBackOrderList($filter, $ini, $fin); 
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function saveStatus()
	{
		if($this->input->is_ajax_request())
		{
			$id = $this->input->post('id');
			$idPedido = $this->input->post('idPedido');
			$pedidoItem = $this->input->post('pedidoItem');
			$status = new stdclass();
			$status->estado = strtoupper($this->input->post('estado'));
			$status->recepcion = $this->input->post('fecha');
			$status->recepcion = $status->recepcion == 'Invalid date' ? null : $status->recepcion;
			$status->embarque = strtoupper($this->input->post('embarque'));
			$status->status = $this->input->post('status');

			if ($pedidoItem == 'pedido') {
				$trans = $this->Pedidos_model->updateStatusPedido($idPedido, $status);
				$status->trans = $trans;
				echo json_encode($status);
				
			} else if ($pedidoItem == 'item') {
				$trans = $this->Pedidos_model->updateStatusPedidoItem($id, $status);
				$status->trans = $trans;
				echo json_encode($status);
			} 
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}