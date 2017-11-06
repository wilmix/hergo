<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Linea extends CI_Controller {

    private $datos;

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model("Articulo_model");
        $this->load->model("Linea_model");
        $this->cabeceras_css = array(
            base_url('assets/bootstrap/css/bootstrap.min.css'),
            base_url("assets/fa/css/font-awesome.min.css"),
            base_url("assets/dist/css/AdminLTE.min.css"),
            base_url("assets/dist/css/skins/skin-blue.min.css"),
            base_url("assets/hergo/estilos.css"),
        );
        $this->cabecera_script = array(
            base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'),
            base_url('assets/bootstrap/js/bootstrap.min.js'),
            base_url('assets/dist/js/app.min.js'),
            base_url('assets/plugins/validator/bootstrapvalidator.min.js'),
            base_url('assets/plugins/slimscroll/slimscroll.min.js'),
        );
        $this->datos['nombre_usuario'] = $this->session->userdata('nombre');
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
        $this->datos['opcion'] = "Linea";
        $this->datos['titulo'] = "Linea";

        $this->datos['cabeceras_css'] = $this->cabeceras_css;
        $this->datos['cabeceras_script'] = $this->cabecera_script;
        /*         * ************FUNCION************** */
        $this->datos['cabeceras_script'][] = base_url('assets/hergo/funciones.js');
        $this->datos['cabeceras_script'][] = base_url('assets/hergo/linea.js');

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
        //$this->datos['unidad'] = $this->articulo_model->retornar_tabla("unidad");
        //$this->datos['marca'] = $this->articulo_model->retornar_tabla("marca");
        $this->datos['linea'] = $this->Articulo_model->retornar_tabla("linea");
        //$this->datos['requisito'] = $this->articulo_model->retornar_tabla("requisito");
        //$this->datos['articulos'] = $this->articulo_model->retornar_tabla("linea");


        $this->load->view('plantilla/head.php', $this->datos);
        $this->load->view('plantilla/header.php', $this->datos);
        $this->load->view('plantilla/menu.php', $this->datos);
        $this->load->view('plantilla/headercontainer.php', $this->datos);
        $this->load->view('administracion/linea/linea.php', $this->datos);
        $this->load->view('plantilla/footcontainer.php', $this->datos);
        $this->load->view('plantilla/footer.php', $this->datos);
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
