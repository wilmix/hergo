<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Unidad extends CI_Controller {

    

    public function __construct() {
        parent::__construct();

        $this->load->model("Articulo_model");
        $this->load->model("Unidad_model");
        $this->load->model("Ingresos_model");
    }

    public function index() {
        $this->accesoCheck(5);
		$this->titles('Unidad','Unidad','Administracion');
        $this->datos['unidad'] = $this->Articulo_model->retornar_tabla("unidad");
		$this->datos['foot_script'][]=base_url('assets/hergo/unidad.js') .'?'.rand();
		$this->setView('administracion/unidad/unidad');
    }

    public function agregarUnidad() {
        if ($this->input->is_ajax_request()) {
            $uni = $this->security->xss_clean($this->input->post('unidad'));
            $sig = $this->security->xss_clean($this->input->post('sigla'));
            $cod = $this->security->xss_clean($this->input->post('cod'));
            if ($cod == "")
                $this->Unidad_model->agregarUnidad_model($uni, $sig);
            else
                $this->Unidad_model->editarUnidad_model($uni, $sig, $cod);
        }
        echo "{}";
    }
    

}
