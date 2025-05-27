
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
	}
	public function index()
	{
		$this->accesoCheck(62);
		$this->titles('PagoProveedores','Pago Proveedores','Importaciones');
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
			$config = [
				"upload_path" => "./assets/pagoProveedores/",
				"allowed_types" => "pdf"
			];
			$this->load->library("upload",$config);
				if ($this->upload->do_upload('url_pago')) {
					$pdf = array("upload_data" => $this->upload->data());
					$url = $pdf['upload_data']['file_name'];
				}
				else{
					//echo $this->upload->display_errors();
					$url = '';
				}
			$pago = new stdclass();
			$pago->fecha = $this->input->post('fechaPago');
			$pago->url = $url;
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
}