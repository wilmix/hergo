<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cufd extends MY_Controller
{
    public $Cliente_model;
	public $Almacen_model;
	public $Cufd_model;
    
	public function __construct()
	{	
		parent::__construct();
        $this->load->model("Almacen_model");
        $this->load->model('siat/Cufd_model');
	}
    public function index()
    {
        
        $this->accesoCheck(71);
		$this->titles('CUFD','Código Único de Facturación Diaria','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/codigos/cufd.js') .'?'.rand();
		$this->setView('siat/codigos/cufd');
    }
    public function getAlmacenes()
    {
        $res = $this->datos['stores']=$this->Almacen_model->siatSucursales()->result();
        echo json_encode($res);
    }
    public function store()
    {
        $cufd = [
            'cuis' => $this->input->post('cuis'),
            'codigo' => $this->input->post('codigo'),
            'codigoControl' => $this->input->post('codigoControl'),
            'fechaVigencia' => $this->input->post('fechaVigencia'),
            //'created_at' => date('Y-m-d'),
        ];
        $id = $this->Cufd_model->store($cufd);
        echo json_encode($id);
    }
    public function getCufdList()
    {
        $res = $this->Cufd_model->getCufdList();
        echo json_encode($res);
    }

}
