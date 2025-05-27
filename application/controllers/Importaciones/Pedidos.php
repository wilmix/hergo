<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pedidos extends MY_Controller
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
		$this->accesoCheck(57);
		$this->titles('ConsultaPedidos','Consulta Pedidos','Importaciones');
		$this->datos['foot_script'][]=base_url('assets/hergo/importaciones/pedidos.js') .'?'.rand();
		$this->setView('importaciones/consultaPedidos');
	}
	public function getPedidos()  
	{
		if($this->input->is_ajax_request())
        {
        	$ini=$this->security->xss_clean($this->input->post("ini"));
        	$fin=$this->security->xss_clean($this->input->post("fin"));
			$condicion= $this->input->post("condicion");
			$res=$this->Pedidos_model->getPedidos($ini, $fin, $condicion); 
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function crearPedido()
	{
		$this->accesoCheck(58);
		$this->titles('FormPedido','Formulario Pedido','Importaciones');
		$this->datos['foot_script'][]=base_url('assets/hergo/importaciones/formPedido.js') .'?'.rand();
		$this->setView('importaciones/formPedido');
	}
	public function store()
	{
		if($this->input->is_ajax_request())
		{
			$id = $this->input->post('id');
			$gestion = date("Y", strtotime($this->input->post('fecha')));
			$pedido = new stdclass();
			$pedido->n = $id ? $this->input->post('n') : $this->Pedidos_model->getNumMov($gestion);
			$pedido->fecha = $this->input->post('fecha');
			$pedido->recepcion = $this->input->post('recepcion');
			$pedido->proveedor = $this->input->post('proveedor');
			$pedido->pedidoPor = strtoupper($this->input->post('pedidoPor'));
			$pedido->cotizacion = strtoupper($this->input->post('cotizacion'));
			$pedido->formaPago = $this->input->post('formaPago');
			$pedido->diasCredito = $this->input->post('diasCredito');
			$pedido->autor = $this->session->userdata('user_id');
			$pedido->glosa = strtoupper($this->input->post('glosa'));
			$pedido->flete = round($this->input->post('flete'),2);
			$pedido->updated_at = $id ? date('Y-m-d H:i:s') : 0;
			$pedido->items = json_decode($this->input->post('items'));

			$id = $this->Pedidos_model->storePedido($id , $pedido);

			if($id)
			{
				$res = new stdclass();
				$res->status = true;
				$res->pedido = $id;
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
	public function aprobar()
	{
		$this->libacceso->acceso(56);
		if($this->input->is_ajax_request())
		{
			$id = $this->input->post('id');
			$aprobar = new stdclass();
			$aprobar->id_user = $this->session->userdata('user_id');
			$aprobar->id_pedido = $id;
			$id = $this->Pedidos_model->aprobar($aprobar);

			if($id)
			{
				$res = new stdclass();
				$res->status = true;
				$res->aprobado = $id;
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
	public function edit($id)
	{
		$this->accesoCheck(59);
		$this->titles('EditarPedido','Editar Pedido','Importaciones');
		$this->datos['foot_script'][]=base_url('assets/hergo/importaciones/formPedido.js') .'?'.rand();
		$this->datos['id']=$id;
		$this->setView('importaciones/formPedido');
	}
	public function getPedido()  
	{
		if($this->input->is_ajax_request())
        {
			$id=$this->security->xss_clean($this->input->post("id"));
			$user =$this->session->userdata('user_id');
			$pedido = new stdclass();
			$pedido->pedido = $this->Pedidos_model->getPedido($id); 
			$pedido->items = $this->Pedidos_model->getPedidoItems($id);
			$pedido->aprobadoPor = $this->Pedidos_model->getAprobadoPor($id);
			$pedido->aprobadoUser = $this->Pedidos_model->getAprobadoUser($id,$user);
			echo json_encode($pedido);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function permisos()  
	{
		if($this->input->is_ajax_request())
        {
			$user =$this->session->userdata('user_id');
			$permisos = $this->Pedidos_model->getPermisos($user);
			echo json_encode($permisos);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}