<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Proforma extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Proforma_model");
		$this->load->model("General_model");
	}
	public function index()
	{
			$this->accesoCheck(67);
			$this->titles('Proformas','Consulta Proformas','Proformas');
			
			$this->datos['foot_script'][]=base_url('assets/hergo/proforma/proformas.js') .'?'.rand();
			$this->setView('proforma/consultaProformas');
	}
	public function formProforma($id='crear')
	{
		$this->accesoCheck(67);
		$this->datos['id'] = ($id =='crear') ? 0 : $id;
		$this->titles('CrearProforma','Crear Proforma','Proformas',);
		if ($this->datos['id']>0) {
			$this->titles('EditarProforma','Editar Proforma','Proformas',);
		}
		$this->datos['foot_script'][]=base_url('assets/hergo/proforma/formProforma.js') .'?'.rand();
		$this->setView('proforma/formProforma');
	}

	public function getProformas()  
	{
		if(!$this->input->is_ajax_request())
		die("PAGINA NO ENCONTRADA");
 
		$ini=$this->security->xss_clean($this->input->post("ini"));
		$fin=$this->security->xss_clean($this->input->post("fin"));
		$alm= $this->input->post("alm");
		$res=$this->Proforma_model->getProformas($ini, $fin, $alm); 
		echo json_encode($res);
	}
	public function store()
	{
		$id = $this->input->post('id');
		$gestion = $this->General_model->getGestionActual()->gestionActual;
		$proforma = new stdclass();
		$proforma->almacen = $this->input->post('almacen');
		$proforma->num = $id ? $this->input->post('n') : $this->Proforma_model->getNumMov($gestion,$proforma->almacen);
		$proforma->fecha = $this->input->post('fecha');

		$proforma->clienteDatos = strtoupper(trim($this->input->post('clienteDato')));
		$proforma->complemento =  strtoupper(trim($this->input->post('complemento')));
		$proforma->cliente = 1;

		$proforma->moneda = $this->input->post('moneda');
		$proforma->condicionesPago = strtoupper($this->input->post('condicionesPago'));
		$proforma->porcentajeDescuento = $this->input->post('porcentajeDescuento');
		$proforma->descuento = round($this->input->post('descuento'),2);
		$proforma->total = round($this->input->post('totalFin'),2);
		$proforma->validezOferta = strtoupper($this->input->post('validez'));
		$proforma->lugarEntrega = strtoupper($this->input->post('lugarEntrega'));
		$proforma->tiempoEntrega = strtoupper($this->input->post('tiempoEntregaC'));
		$proforma->garantia = strtoupper($this->input->post('garantia'));
		$proforma->marca = strtoupper($this->input->post('marca'));
		$proforma->tipo = strtoupper($this->input->post('tipo'));
		$proforma->glosa = nl2br(strtoupper($this->input->post('glosa')));
		$proforma->gestion = $gestion;
		$proforma->autor = $this->session->userdata('user_id');
		$proforma->updated_at = $id ? date('Y-m-d H:i:s') : 0;
		$proforma->items = json_decode($this->input->post('items'));

		$id = $this->Proforma_model->storeProforma($id , $proforma);


		if($id)
		{
			$res = new stdclass();
			$res->status = true;
			$res->id = $id;
			$res->proforma = $proforma;
			echo json_encode($res);
		} else {
			echo json_encode($id);
		}
	}
	public function searchItem()
    {
        if($this->input->is_ajax_request() && $this->input->post('item'))
        {
			$item = $this->security->xss_clean($this->input->post('item'));
			$alm = $this->security->xss_clean($this->input->post('alm'));
        	$dato=$this->Proforma_model->searchItem($item,$alm);        	
			echo json_encode($dato->result_array());
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function searchCliente()
    {
        if($this->input->is_ajax_request() && $this->input->post('search'))
        {
			$search = $this->security->xss_clean($this->input->post('search'));
        	$dato=$this->Proforma_model->searchClientes($search)->result_array();        	
			echo json_encode($dato);
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
	public function getInfoProformaForm()  
	{
		if($this->input->is_ajax_request())
        {
			$datosProforma = new stdclass();
			$datosProforma->tipos = $this->Proforma_model->getTipos(); 
			$datosProforma->almacenes = $this->Proforma_model->getAlmacenes(); 
			$datosProforma->monedas = $this->Proforma_model->getMonedas(); 
			$datosProforma->articulos = $this->Proforma_model->getArticulos(); 

			echo json_encode($datosProforma);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function getArticulo()
    {
        if($this->input->is_ajax_request() && $this->input->post('id'))
        {
			$id = $this->security->xss_clean($this->input->post('id'));
			$alm = $this->security->xss_clean($this->input->post('alm'));
        	$dato=$this->Proforma_model->getArticulo($id,$alm);        	
			echo json_encode($dato);
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function getProforma(){
		if($this->input->is_ajax_request())
        {
			$id=$this->security->xss_clean($this->input->post("id"));
			$user =$this->session->userdata('user_id');
			$proforma = new stdclass();
			$proforma->proforma = $this->Proforma_model->getProforma($id); 
			$proforma->items = $this->Proforma_model->getProformaItems($id);
			echo json_encode($proforma);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}