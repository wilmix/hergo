<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cuis extends CI_Controller
{
	public function __construct()
	{	
		parent::__construct();
        $this->load->model("Almacen_model");

	}
    public function index()
    {
        
        //$this->accesoCheck(57);
		$this->titles('CUIS','Código Único de Inicio de Sistemas','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/codigos/cuis.js') .'?'.rand();
		$this->setView('siat/codigos/cuis');
    }
    public function getAlmacenes()
    {
        $res = $this->datos['stores']=$this->Almacen_model->retornar_tabla('almacenes')->result();
        echo json_encode($res);
    }

}
