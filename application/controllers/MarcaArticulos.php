<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MarcaArticulos extends CI_Controller 
{

    private $datos;

    public function __construct() {
        parent::__construct();
        /*******/
        $this->load->library('LibAcceso');
        $this->libacceso->acceso(3);
        /*******/
        $this->load->helper('url');
        $this->load->model("Articulo_model");
        $this->load->model("Ingresos_model");
        $this->load->model("MarcaArticulo_model");
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
            base_url('assets/plugins/daterangepicker/moment.min.js'),
        );
        $this->datos['nombre_usuario'] = $this->session->userdata('nombre');
        $this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
        $hoy = date('Y-m-d');
		$tipoCambio = $this->Ingresos_model->getTipoCambio($hoy);
		$tipoCambio = $tipoCambio->tipocambio;
		$this->datos['tipoCambio'] = $tipoCambio;
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
        $this->datos['opcion'] = "Marca Articulos";
        $this->datos['titulo'] = "Marca Articulos";

        $this->datos['cabeceras_css'] = $this->cabeceras_css;
        $this->datos['cabeceras_script'] = $this->cabecera_script;
        /*         * ************FUNCION************** */
        $this->datos['cabeceras_script'][] = base_url('assets/hergo/funciones.js');
        $this->datos['cabeceras_script'][] = base_url('assets/hergo/marcaArticulo.js');

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
        //$this->datos['unidad'] = $this->Articulo_model->retornar_tabla("unidad");
        $this->datos['marca'] = $this->Articulo_model->retornar_tabla("marca");
        //$this->datos['linea'] = $this->Articulo_model->retornar_tabla("linea");
        //$this->datos['requisito'] = $this->Articulo_model->retornar_tabla("requisito");
        //$this->datos['articulos'] = $this->Articulo_model->retornar_tabla("marca");


        $this->load->view('plantilla/head.php', $this->datos);
        $this->load->view('plantilla/header.php', $this->datos);
        $this->load->view('plantilla/menu.php', $this->datos);
        $this->load->view('plantilla/headercontainer.php', $this->datos);
        $this->load->view('administracion/marca/marca.php', $this->datos);
        $this->load->view('plantilla/footcontainer.php', $this->datos);
        $this->load->view('plantilla/footer.php', $this->datos);
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
