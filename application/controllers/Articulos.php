<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Articulos extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		if(!$this->session->userdata('logeado'))
		redirect('auth', 'refresh');
		/*******/
		$this->load->library('LibAcceso');
		$this->libacceso->acceso(2);
		/*******/
		$this->load->helper('url');	
		$this->load->model("Articulo_model");
		$this->load->model("Ingresos_model");
		$this->cabeceras_css=array(
				base_url('assets/bootstrap/css/bootstrap.min.css'),
				base_url("assets/fa/css/font-awesome.min.css"),
				base_url("assets/dist/css/AdminLTE.min.css"),
				base_url("assets/dist/css/skins/skin-blue.min.css"),
				base_url("assets/hergo/estilos.css"),
				base_url('assets/sweetalert/sweetalert2.min.css'),
			);
		$this->cabecera_script=array(
				base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'),
				base_url('assets/bootstrap/js/bootstrap.min.js'),
				base_url('assets/dist/js/app.min.js'),
				base_url('assets/plugins/validator/bootstrapvalidator.min.js'),
				base_url('assets/plugins/slimscroll/slimscroll.min.js'),
				base_url('assets/sweetalert/sweetalert2.min.js'),
			);
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
		$this->datos['user_id_actual']=$this->session->userdata['user_id'];
		$this->datos['id_Almacen_actual']=$this->session->userdata['datosAlmacen']->idalmacen;

		$hoy = date('Y-m-d');
		$tipoCambio = $this->Ingresos_model->getTipoCambio($hoy);
		if ($tipoCambio) {
			$tipoCambio = $tipoCambio->tipocambio;
			$this->datos['tipoCambio'] = $tipoCambio;
		} else {
			$this->datos['tipoCambio'] = 'No se tiene tipo de cambio para la fecha';
		}
			if($this->session->userdata('foto')==NULL)
				$this->datos['foto']=base_url('assets/imagenes/ninguno.png');
			else
				$this->datos['foto']=base_url('assets/imagenes/').$this->session->userdata('foto');	
	}
	public function index()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');
		
			$this->datos['menu']="Administracion";
			$this->datos['opcion']="Articulos";
			$this->datos['titulo']="Articulos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			//$this->datos['cabeceras_script']= $this->cabecera_script;
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/articulo.js');
			
			/*************TABLE***************/
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/css/bootstrap-table.css'); 
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-export.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/tableExport.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/js/bootstrap-table-filter-control.js');
			/*********UPLOAD******************/
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/FileInput/css/fileinput.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/FileInput/js/fileinput.min.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/FileInput/js/locales/es.js');
			/****************MOMENT*******************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/moment.min.js');
			$this->datos['unidad']=$this->Articulo_model->retornar_tabla("unidad");			
			$this->datos['marca']=$this->Articulo_model->retornar_tabla("marca");
			$this->datos['linea']=$this->Articulo_model->retornar_tabla("linea");
			$this->datos['requisito']=$this->Articulo_model->retornar_tabla("requisito");
			$this->datos['articulos']=$this->Articulo_model->mostrarArticulos();
					
			
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('administracion/articulo/articulo.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);						
	}
	public function agregarArticulo()
	{
		$this->libacceso->acceso(46);
		if($this->input->is_ajax_request())
        {
        	$id = addslashes($this->security->xss_clean($this->input->post('id_articulo')));
        	$codigo = addslashes($this->security->xss_clean($this->input->post('codigo')));
			$descripcion = addslashes($this->security->xss_clean($this->input->post('descripcion')));
			$descripcionFabrica = addslashes($this->security->xss_clean($this->input->post('descripcionFabrica')));
        	$unidad = addslashes($this->security->xss_clean($this->input->post('unidad')));
        	$marca = addslashes($this->security->xss_clean($this->input->post('marca')));           	
        	$linea = addslashes($this->security->xss_clean($this->input->post('linea')));
        	$parte = addslashes($this->security->xss_clean($this->input->post('parte')));
        	$posicion = addslashes($this->security->xss_clean($this->input->post('posicion')));
        	$autoriza = addslashes($this->security->xss_clean($this->input->post('autoriza')));   
        	$proser = addslashes($this->security->xss_clean($this->input->post('proser')));
			$uso = addslashes($this->security->xss_clean($this->input->post('uso')));
			$precio = addslashes($this->security->xss_clean($this->input->post('precio')));
        	
        	if($id=="")
        	{
        		$nom_imagen=$this->subir_imagen($id,$_FILES);
				$this->Articulo_model->agregarArticulo_model($id,strtoupper($codigo) ,strtoupper($descripcion),$unidad,$marca,$linea,strtoupper($parte),
				strtoupper($posicion),$autoriza,$proser,$uso,$nom_imagen,$precio,strtoupper($descripcionFabrica));
        	}
        	else
        	{
        		$nom_imagen=$this->subir_imagen($id,$_FILES);
				$this->Articulo_model->editarArticulo_model($id,strtoupper($codigo),strtoupper($descripcion),$unidad,$marca,$linea,strtoupper($parte),
				strtoupper($posicion),$autoriza,$proser,$uso,$nom_imagen,$precio,strtoupper($descripcionFabrica));
        	}
        }
        echo "{}";       
	}
	public function mostrarArticulos()
	{
		if($this->input->is_ajax_request())
        {
			$res=$this->Articulo_model->mostrarArticulos();
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	private function subir_imagen($id,$archivo_img)
	{

		//$ruta= dirname(getcwd()) . PHP_EOL; //ruta de la carpeta en el servidor
		//$carpetaAdjunta="assets/\imagenes//\\";
		$ruta= getcwd();
		$ruta=trim($ruta);
		$carpetaAdjunta=$ruta."/assets/img_articulos/";
		// Contar envían por el plugin
		$Imagenes =count(isset($archivo_img['imagenes']['name'])?$archivo_img['imagenes']['name']:0);
		$infoImagenesSubidas = array();
		$nombreArchivo="";
	

		if(($archivo_img['imagenes']['name'])!="")
		{
			echo $archivo_img['imagenes']['name'];
		  // El nombre y nombre temporal del archivo que vamos para adjuntar
		  $nombreArchivo=isset($archivo_img['imagenes']['name'])?time().$archivo_img['imagenes']['name']:null;
		  $nombreTemporal=isset($archivo_img['imagenes']['tmp_name'])?$archivo_img['imagenes']['tmp_name']:null;
		  
		  $rutaArchivo=$carpetaAdjunta.$nombreArchivo;

		  move_uploaded_file($nombreTemporal,$rutaArchivo);
		}
		
		//return ($nombreArchivo=="")?"ninguno.jpg":$nombreArchivo;
		  return($nombreArchivo);
	}
}