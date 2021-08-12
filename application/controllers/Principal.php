<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Principal extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
	
		$this->load->model("Reportes_model");
		$this->load->model("Dashboard_model");
		$this->load->model("Ingresos_model");
			}
	public function index()
	{
		$this->accesoCheck(0);
		$this->titles('Hergo | Inventarios','Dashboard','Versión 1.3');
		$this->datos['foot_script'][]=base_url('assets/hergo/dashboard.js') .'?'.rand();
		$this->datos['almacen']=$this->Reportes_model->retornar_tabla("almacenes");
		$this->setView('dashboard/dashboard_v1');
	}
	public function ventasGestion()
	{
		$this->libacceso->acceso(55);
        	$ini=$this->security->xss_clean($this->input->post("i"));
        	$fin=$this->security->xss_clean($this->input->post("f"));
			$res=$this->Dashboard_model->mostrarVentasGestion($ini,$fin);
			$res=$res->result_array();
			echo json_encode($res);
	}
	public function ventasHoy()
	{
        	$ini=$this->security->xss_clean($this->input->post("i"));
			$res=$this->Dashboard_model->mostrarVentasHoy($ini); 
			$res=$res->result_array();
			echo json_encode($res);
	}
	public function ingresosHoy()
	{
		$ini=$this->security->xss_clean($this->input->post("i"));
		$res=$this->Dashboard_model->mostrarIngresosHoy($ini);
		$res=$res->result_array();
		echo json_encode($res);
	}
	public function infoHoy()
	{
			$res=$this->Dashboard_model->mostrarInfo();
			$res=$res->result_array();
			echo json_encode($res);
	}
	public function notaEntregaHoy() 
	{
		$ini=$this->security->xss_clean($this->input->post("i"));
		$res=$this->Dashboard_model->notaEntregaHoy($ini);
		$res=$res->result_array();
		echo json_encode($res);
	}
	public function ventaCajaHoy() 
	{
		$ini=$this->security->xss_clean($this->input->post("i"));
		$res=$this->Dashboard_model->ventaCajaHoy($ini);
		$res=$res->result_array();
		echo json_encode($res);
	}
	public function cantidadHoy() 
	{
        	$ini=$this->security->xss_clean($this->input->post("i"));
			$res=$this->Dashboard_model->cantidadHoy($ini);
			$res=$res->result_array();
			echo json_encode($res);
	}
	public function negatives() 
	{
		$ges=$this->security->xss_clean($this->input->post("g"));
		$alm=$this->security->xss_clean($this->input->post("a"));
		$res=$this->Dashboard_model->negatives($alm, $ges);
		$res=$res->result_array();
		echo json_encode($res);
	}

	public function subir_imagen()
	{

		//$ruta= dirname(getcwd()) . PHP_EOL; //ruta de la carpeta en el servidor sin hergo
		$ruta= getcwd();
		$ruta=trim($ruta);
		$carpetaAdjunta=$ruta."/assets/imagenes/";
		//die($carpetaAdjunta);
		// Contar envían por el plugin
		$Imagenes =count(isset($_FILES['imagenes']['name'])?$_FILES['imagenes']['name']:0);
		$infoImagenesSubidas = array();
		
		for($i = 0; $i < $Imagenes; $i++) {


		  // El nombre y nombre temporal del archivo que vamos para adjuntar
		  $nombreArchivo=isset($_FILES['imagenes']['name'][$i])?$_FILES['imagenes']['name'][$i]:null;
		  $nombreTemporal=isset($_FILES['imagenes']['tmp_name'][$i])?$_FILES['imagenes']['tmp_name'][$i]:null;
		  
		  $rutaArchivo=$carpetaAdjunta.$nombreArchivo;

		  move_uploaded_file($nombreTemporal,$rutaArchivo);
		  
		  $infoImagenesSubidas[$i]=array("caption"=>"$nombreArchivo","height"=>"120px","url"=>"http://localhost/hergo/up/borrar.php","key"=>$nombreArchivo);
		  $ImagenesSubidas[$i]="<img  height='120px'  src='http://localhost/hergo/imagenes/$rutaArchivo' class='file-preview-image'>";
		  }
		$arr = array("file_id"=>0,"overwriteInitial"=>true,"initialPreviewConfig"=>$infoImagenesSubidas,
		       "initialPreview"=>$ImagenesSubidas);
		//echo json_encode($arr);
	}
	public function verlogin()
	{
		
		?>
		<pre>
		<?php 
			print_r($this->session->userdata);
		?>
		</pre>
		<?php 

	}
}