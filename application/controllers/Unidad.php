<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Unidad extends MY_Controller {

    

    public function __construct() {
        parent::__construct();

        $this->load->model("Articulo_model");
        $this->load->model("Unidad_model");
        $this->load->model("Ingresos_model");
    }

    public function index() {
        $this->accesoCheck(5);
		$this->titles('Unidad','Unidad','Administracion');
        $this->datos['unidad'] = $this->Unidad_model->unidadesSiat();
        $this->datos['unidadMedidaSiat'] = $this->Unidad_model->unidadesMedidaSiat();
		$this->datos['foot_script'][]=base_url('assets/hergo/unidad.js') .'?'.rand();
		$this->setView('administracion/unidad/unidad');
    }
    public function addOrUpdate() {
        try {
            $id = $this->input->post('cod');
            $data = array(
                'Unidad' => strtoupper($this->security->xss_clean($this->input->post('unidad'))),
                'Sigla' => strtoupper($this->security->xss_clean($this->input->post('sigla'))),
                'siat_codigo' => $this->input->post('unidadSiat'),
                'user_id' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            if ($id === ''){
                $res = $this->Unidad_model->store($data);
            }
            else if($id > 0){
                $res = $this->Unidad_model->update($id, $data);
            }
            echo json_encode($res);
        } catch (Exception $exception) {
            $error = $exception->getMessage();
            echo json_encode($error);
        }
    }
}
