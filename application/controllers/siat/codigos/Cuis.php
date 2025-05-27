<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cuis extends MY_Controller
{
	public function __construct()
	{	
		parent::__construct();
        $this->load->model("Almacen_model");
        $this->load->model('siat/Cuis_model');

	}
    public function index()
    {
        
        //$this->accesoCheck(74);
		$this->titles('CUIS','Código Único de Inicio de Sistemas','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/codigos/cuis.js') .'?'.rand();
		$this->setView('siat/codigos/cuis');
    }
    public function getAlmacenes()
    {
        $res = $this->datos['stores']=$this->Almacen_model->siatSucursales()->result();
        echo json_encode($res);
    }
    public function store()
    {
        $cuis = [
            'sucursal' => $this->input->post('sucursal'),
            'cuis' => $this->input->post('cuis'),
            'fechaVigencia' => $this->input->post('fechaVigencia'),
            'codigoPuntoVenta' => $this->input->post('codigoPuntoVenta'),
            'almacen_id' => $this->input->post('almacen_id')
        ];
        $res = $this->Cuis_model->search($this->input->post('cuis'));
        if($res == null){
            $this->Cuis_model->store($cuis);
        };
        echo json_encode($cuis);
    }
    public function editEstadoCuis()
    {
        $row = $this->input->post('row');
        $res = $this->Cuis_model->editEstadoCuis($row['id']);
        echo json_encode($res);
    }
    public function registrarPuntoventa()
    {
        $data = $this->input->post('cliente');
        echo json_encode($data);
    }
    public function cierrePuntoVenta()
    {
        $cuis = $this->input->post('cuis');
        $codigoSucursal = $this->input->post('codigoSucursal');
        $codigoPuntoVenta = $this->input->post('codigoPuntoVenta');
        $res = $this->Cuis_model->cierrePuntoVenta($cuis, $codigoSucursal, $codigoPuntoVenta);
        echo json_encode($res);
    }
    public function buscarCuis($cuis)
    {
        $this->Cuis_model->search($cuis);
    }

}
