<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Principal Controller
 * 
 * @property Reportes_model $Reportes_model
 * @property Dashboard_model $Dashboard_model
 * @property Ingresos_model $Ingresos_model
 */
class Principal extends MY_Controller
{
    public $Reportes_model;
    public $Dashboard_model;
    public $Ingresos_model;
	
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
        	$interval=$this->security->xss_clean($this->input->post("interval"));
			$res=$this->Dashboard_model->mostrarVentasGestion($interval);
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
	public function getUsdtRate()
	{
        $url = 'https://api.sas.willysalas.com/api/usdt-rates/latest';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($response === false) {
            echo json_encode([
                'error' => 'Failed to fetch USDT rate',
                'details' => $error,
                'usdt_min_bob' => '0.00',
                'recorded_at' => date('Y-m-d H:i:s')
            ]);
            return;
        }
        
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['usdt_min_bob'])) {
            echo json_encode([
                'error' => 'Invalid response format',
                'usdt_min_bob' => '0.00',
                'recorded_at' => date('Y-m-d H:i:s')
            ]);
            return;
        }
        
        echo $response;
    }
}