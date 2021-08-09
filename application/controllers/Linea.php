<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Linea extends CI_Controller {

    

    public function __construct() {
        parent::__construct();
        $this->load->model("Articulo_model");
        $this->load->model("Linea_model");
        $this->load->model("Ingresos_model");
     }

    public function index() {

        $this->accesoCheck(4);
		$this->titles('Linea','Linea','Administracion');
        $this->datos['linea'] = $this->Articulo_model->retornar_tabla("linea");
		$this->datos['foot_script'][]=base_url('assets/hergo/linea.js') .'?'.rand();
		$this->setView('administracion/linea/linea');
    }

    public function agregarLinea() {
        if ($this->input->is_ajax_request()) {
            $mar = $this->security->xss_clean($this->input->post('linea'));
            $sig = $this->security->xss_clean($this->input->post('sigla'));
            $cod = $this->security->xss_clean($this->input->post('cod'));
            $enu = $this->security->xss_clean($this->input->post('enuso'));
            if ($cod == "")
                $this->Linea_model->agregarLinea_model($mar, $sig);
            else
                $this->Linea_model->editarLinea_model($mar, $sig, $cod);
        }
        echo "{}";
    }
    

}
