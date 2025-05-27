<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MarcaArticulos extends MY_Controller 
{

    

    public function __construct() {
        parent::__construct();
        
        $this->load->model("Articulo_model");
        $this->load->model("Ingresos_model");
        $this->load->model("MarcaArticulo_model");
       
    }

    public function index() {
        $this->accesoCheck(3);
		$this->titles('MarcaArticulos','Marca Articulos','Administracion');
        $this->datos['marca'] = $this->Articulo_model->retornar_tabla("marca");
		$this->datos['foot_script'][]=base_url('assets/hergo/marcaArticulo.js') .'?'.rand();
		$this->setView('administracion/marca/marca');
    }

    public function agregarMarca() {
        if ($this->input->is_ajax_request()) {
            $marca = $this->security->xss_clean($this->input->post('marca'));
            $sig = $this->security->xss_clean($this->input->post('sigla'));
            $cod = $this->security->xss_clean($this->input->post('cod'));
            if ($cod == "")
                $res = $this->MarcaArticulo_model->agregarMarca_model($marca, $sig);
            else
                $res = $this->MarcaArticulo_model->editarMarca_model($marca, $sig, $cod);
        }
        echo $res;
    }

}
