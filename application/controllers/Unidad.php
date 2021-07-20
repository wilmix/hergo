<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Unidad extends CI_Controller {

    

    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logeado'))
		redirect('auth', 'refresh');
        /*******/
        $this->load->library('LibAcceso');
        $this->libacceso->acceso(5);
        /*******/
        $this->load->helper('url');
        $this->load->model("Articulo_model");
        $this->load->model("Unidad_model");
        $this->load->model("Ingresos_model");
        $this->cabeceras_css = array(
            base_url('assets/bootstrap/css/bootstrap.min.css'),
            base_url("assets/fa/css/font-awesome.min.css"),
            base_url("assets/dist/css/AdminLTE.min.css"),
            base_url("assets/dist/css/skins/skin-blue.min.css"),
            base_url("assets/hergo/estilos.css"),
            base_url('assets/sweetalert/sweetalert2.min.css'),
        );
        $this->cabecera_script = array(
            base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'),
            base_url('assets/bootstrap/js/bootstrap.min.js'),
            base_url('assets/dist/js/app.min.js'),
            base_url('assets/plugins/validator/bootstrapvalidator.min.js'),
            base_url('assets/plugins/slimscroll/slimscroll.min.js'),
            base_url('assets/sweetalert/sweetalert2.min.js'),
            base_url('assets/plugins/daterangepicker/moment.min.js'),

        );
        $this->datos['nombre_usuario'] = $this->session->userdata('nombre');
		$this->datos['user_id_actual']=$this->session->userdata['user_id'];
        $this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
		$this->datos['id_Almacen_actual']=$this->session->userdata['datosAlmacen']->idalmacen;

        $hoy = date('Y-m-d');
		$tipoCambio = $this->Ingresos_model->getTipoCambio($hoy);
		if ($tipoCambio) {
			$tipoCambio = $tipoCambio->tipocambio;
			$this->datos['tipoCambio'] = $tipoCambio;
		} else {
			$this->datos['tipoCambio'] = 'No se tiene tipo de cambio para la fecha';
		}

        if ($this->session->userdata('foto') == NULL)
            $this->datos['foto'] = base_url('assets/imagenes/ninguno.png');
        else
            $this->datos['foto'] = base_url('assets/imagenes/') . $this->session->userdata('foto');
    }

    public function index() {
        if (!$this->session->userdata('logeado'))
            redirect('auth', 'refresh');
        /*         * ************TITULOS Y LEVEL************** */
        $this->datos['menu'] = "Administracion";
        $this->datos['opcion'] = "Unidad";
        $this->datos['titulo'] = "Unidad";

        $this->datos['cabeceras_css'] = $this->cabeceras_css;
        $this->datos['cabeceras_script'] = $this->cabecera_script;
        /*         * ************FUNCION************** */
        $this->datos['cabeceras_script'][] = base_url('assets/hergo/funciones.js');
        $this->datos['cabeceras_script'][] = base_url('assets/hergo/unidad.js');

        /*         * ***********TABLE************** */
        $this->datos['cabeceras_css'][] = base_url('assets/plugins/table-boot/css/bootstrap-table.css');
        $this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/bootstrap-table.js');
        $this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js');
        $this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/bootstrap-table-export.js');
        $this->datos['cabeceras_script'][] = base_url('assets/plugins/table-boot/js/tableExport.js');
        /*         * *******UPLOAD***************** */
        $this->datos['cabeceras_css'][] = base_url('assets/plugins/FileInput/css/fileinput.min.css');
        $this->datos['cabeceras_script'][] = base_url('assets/plugins/FileInput/js/fileinput.min.js');
        $this->datos['cabeceras_script'][] = base_url('assets/plugins/FileInput/js/locales/es.js');
        /*         * ******************************** */
        $this->datos['unidad'] = $this->Articulo_model->retornar_tabla("unidad");
        $this->load->view('plantilla/head.php', $this->datos);
        $this->load->view('plantilla/header.php', $this->datos);
        $this->load->view('plantilla/menu.php', $this->datos);
        $this->load->view('plantilla/headercontainer.php', $this->datos);
        $this->load->view('administracion/unidad/unidad.php', $this->datos);
        $this->load->view('plantilla/footcontainer.php', $this->datos);
        $this->load->view('plantilla/footer.php', $this->datos);
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
