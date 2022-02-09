<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Articulos extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("Articulo_model");
	}
	
	public function index()
	{
		$this->accesoCheck(2);
		$this->titles('Articulos','Articulos','Administracion',);
		$this->datos['foot_script'][]=base_url('assets/hergo/articulo.js').'?'.rand();

		$this->datos['unidad']=$this->Articulo_model->retornar_tabla("unidad");	
		$this->datos['marca']=$this->Articulo_model->retornar_tabla("marca");
		$this->datos['linea']=$this->Articulo_model->retornar_tabla("linea");
		$this->datos['requisito']=$this->Articulo_model->retornar_tabla("requisito");
		//echo json_encode($this->datos['articulos']->result());die();		
					
		$this->setView('administracion/articulo/articulo');
	}
	public function agregarArticulo()
	{
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
				$this->libacceso->accesoInt(46);
        		$nom_imagen=$this->subir_imagen($id,$_FILES);
				$this->Articulo_model->agregarArticulo_model($id,strtoupper($codigo) ,strtoupper($descripcion),$unidad,$marca,$linea,strtoupper($parte),
				strtoupper($posicion),$autoriza,$proser,$uso,$nom_imagen,strtoupper($descripcionFabrica));
        	}
        	else
        	{
				$this->libacceso->accesoInt(69);
        		$nom_imagen=$this->subir_imagen($id,$_FILES);
				$this->Articulo_model->editarArticulo_model($id,strtoupper($codigo),strtoupper($descripcion),$unidad,$marca,$linea,strtoupper($parte),
				strtoupper($posicion),$autoriza,$proser,$uso,$nom_imagen,strtoupper($descripcionFabrica));
        	}
        }
        $res = new stdclass();
		$res->status = true;
		$res->msg = 'Guardado Exitosamente';

		echo json_encode($res);	      
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
		//$Imagenes =count(isset($archivo_img['imagenes']['name'])?$archivo_img['imagenes']['name']:0);
		$infoImagenesSubidas = array();
		$nombreArchivo="";
	

		if(($archivo_img['imagenes']['name'])!="")
		{
			//echo $archivo_img['imagenes']['name'];
		  // El nombre y nombre temporal del archivo que vamos para adjuntar
		  $nombreArchivo=isset($archivo_img['imagenes']['name'])?time().$archivo_img['imagenes']['name']:null;
		  $nombreTemporal=isset($archivo_img['imagenes']['tmp_name'])?$archivo_img['imagenes']['tmp_name']:null;
		  
		  $rutaArchivo=$carpetaAdjunta.$nombreArchivo;

		  move_uploaded_file($nombreTemporal,$rutaArchivo);
		}
		
		//return ($nombreArchivo=="")?"ninguno.jpg":$nombreArchivo;
		  return($nombreArchivo);
	}
	/* public function cambiarPrecio()
	{
		$data = [
			['CodigoArticulo'=>'TM3100','precio'=>1.4],
			['CodigoArticulo'=>'TM3201','precio'=>1.8],
			['CodigoArticulo'=>'TM3080','precio'=>2],
			['CodigoArticulo'=>'TM3050','precio'=>2.8],
			['CodigoArticulo'=>'TM3213','precio'=>3],
			['CodigoArticulo'=>'TM3200','precio'=>3.5],
			['CodigoArticulo'=>'TM3131','precio'=>4],
			['CodigoArticulo'=>'TM3190','precio'=>4.8],
			['CodigoArticulo'=>'TM1114','precio'=>5],
			['CodigoArticulo'=>'TM3210','precio'=>5.29],
			['CodigoArticulo'=>'TM1112','precio'=>6],
			['CodigoArticulo'=>'TM3130','precio'=>7],
			['CodigoArticulo'=>'TM1115','precio'=>7.2],
			['CodigoArticulo'=>'TM9901','precio'=>9],
			['CodigoArticulo'=>'TM1113','precio'=>9.2],
			['CodigoArticulo'=>'TM1100','precio'=>9.2],
			['CodigoArticulo'=>'TM1116','precio'=>9.4],
			['CodigoArticulo'=>'TM2420','precio'=>10],
			['CodigoArticulo'=>'TM3170','precio'=>10.45],
			['CodigoArticulo'=>'TM8100','precio'=>11.6],
			['CodigoArticulo'=>'TM3180','precio'=>12.8],
			['CodigoArticulo'=>'TM5206','precio'=>14.4],
			['CodigoArticulo'=>'TM5204','precio'=>14.4],
			['CodigoArticulo'=>'TM3225','precio'=>14.8],
			['CodigoArticulo'=>'TM5189','precio'=>15],
			['CodigoArticulo'=>'TM5188','precio'=>15],
			['CodigoArticulo'=>'TM4270','precio'=>15.8],
			['CodigoArticulo'=>'TM8301','precio'=>16.1],
			['CodigoArticulo'=>'TM1111','precio'=>16.6],
			['CodigoArticulo'=>'TM2410','precio'=>17],
			['CodigoArticulo'=>'TM9900','precio'=>20.5],
			['CodigoArticulo'=>'TM1190','precio'=>21.5],
			['CodigoArticulo'=>'TM5202','precio'=>23.3],
			['CodigoArticulo'=>'TM5201','precio'=>23.3],
			['CodigoArticulo'=>'TM5185','precio'=>25],
			['CodigoArticulo'=>'TM5184','precio'=>25],
			['CodigoArticulo'=>'TM5183','precio'=>25],
			['CodigoArticulo'=>'TM5148','precio'=>25.5],
			['CodigoArticulo'=>'TM5147','precio'=>25.5],
			['CodigoArticulo'=>'TM5350','precio'=>29.2],
			['CodigoArticulo'=>'TM5020','precio'=>31.35],
			['CodigoArticulo'=>'TM2430','precio'=>32.2],
			['CodigoArticulo'=>'TM9899','precio'=>33],
			['CodigoArticulo'=>'TM1155','precio'=>38.7],
			['CodigoArticulo'=>'TM5152','precio'=>39],
			['CodigoArticulo'=>'TM5151','precio'=>39],
			['CodigoArticulo'=>'TM5150','precio'=>39],
			['CodigoArticulo'=>'TM9920','precio'=>45],
			['CodigoArticulo'=>'TM1507','precio'=>45],
			['CodigoArticulo'=>'TM5224','precio'=>50],
			['CodigoArticulo'=>'TM2350','precio'=>53.1],
			['CodigoArticulo'=>'TM4310','precio'=>54],
			['CodigoArticulo'=>'TM5222','precio'=>55],
			['CodigoArticulo'=>'TM2315','precio'=>56.5],
			['CodigoArticulo'=>'TM5342','precio'=>66.5],
			['CodigoArticulo'=>'TM5341','precio'=>66.5],
			['CodigoArticulo'=>'TM5340','precio'=>66.5],
			['CodigoArticulo'=>'TM5196','precio'=>68.7],
			['CodigoArticulo'=>'TM4238','precio'=>70],
			['CodigoArticulo'=>'TM1506','precio'=>70],
			['CodigoArticulo'=>'TM9978','precio'=>80],
			['CodigoArticulo'=>'TM1508','precio'=>80],
			['CodigoArticulo'=>'TM2310','precio'=>83.4],
			['CodigoArticulo'=>'TM4242','precio'=>84.8],
			['CodigoArticulo'=>'TM9685','precio'=>85],
			['CodigoArticulo'=>'TM9910','precio'=>90],
			['CodigoArticulo'=>'TM9883','precio'=>90],
			['CodigoArticulo'=>'TM9882','precio'=>90],
			['CodigoArticulo'=>'TM9881','precio'=>90],
			['CodigoArticulo'=>'TM9898','precio'=>97],
			['CodigoArticulo'=>'TM4237','precio'=>97.5],
			['CodigoArticulo'=>'TM9502','precio'=>98],
			['CodigoArticulo'=>'TM2319','precio'=>98],
			['CodigoArticulo'=>'TM2320','precio'=>99],
			['CodigoArticulo'=>'TM5155','precio'=>102],
			['CodigoArticulo'=>'TM5344','precio'=>107.1],
			['CodigoArticulo'=>'TM5343','precio'=>107.1],
			['CodigoArticulo'=>'TM4300','precio'=>112.2],
			['CodigoArticulo'=>'TM5345','precio'=>114],
			['CodigoArticulo'=>'TM9782','precio'=>120],
			['CodigoArticulo'=>'TM2110','precio'=>120.4],
			['CodigoArticulo'=>'TM2321','precio'=>123],
			['CodigoArticulo'=>'TM2130','precio'=>123.3],
			['CodigoArticulo'=>'TM1505','precio'=>130.6],
			['CodigoArticulo'=>'TM1706','precio'=>135],
			['CodigoArticulo'=>'TM5028','precio'=>143],
			['CodigoArticulo'=>'TM4235','precio'=>146.6],
			['CodigoArticulo'=>'TM9954','precio'=>150],
			['CodigoArticulo'=>'TM9897','precio'=>150],
			['CodigoArticulo'=>'TM9895','precio'=>150],
			['CodigoArticulo'=>'TM3360','precio'=>151],
			['CodigoArticulo'=>'TM9907','precio'=>155],
			['CodigoArticulo'=>'TM9904','precio'=>155],
			['CodigoArticulo'=>'TM4239','precio'=>158.6],
			['CodigoArticulo'=>'TM1600','precio'=>163.7],
			['CodigoArticulo'=>'TM1704','precio'=>170],
			['CodigoArticulo'=>'TM2152','precio'=>179],
			['CodigoArticulo'=>'TM9892','precio'=>180],
			['CodigoArticulo'=>'TM9891','precio'=>180],
			['CodigoArticulo'=>'TM8055','precio'=>180],
			['CodigoArticulo'=>'TM8050','precio'=>180],
			['CodigoArticulo'=>'TM5325','precio'=>180],
			['CodigoArticulo'=>'TM9952','precio'=>200],
			['CodigoArticulo'=>'TM9951','precio'=>200],
			['CodigoArticulo'=>'TM9950','precio'=>200],
			['CodigoArticulo'=>'TM3500','precio'=>200],
			['CodigoArticulo'=>'TM3308','precio'=>200],
			['CodigoArticulo'=>'TM4330','precio'=>221.8],
			['CodigoArticulo'=>'TM2150','precio'=>228.1],
			['CodigoArticulo'=>'TM9906','precio'=>240],
			['CodigoArticulo'=>'TM3340','precio'=>242.3],
			['CodigoArticulo'=>'TM3320','precio'=>244.7],
			['CodigoArticulo'=>'TM9159','precio'=>250],
			['CodigoArticulo'=>'TM9158','precio'=>250],
			['CodigoArticulo'=>'TM4350','precio'=>250],
			['CodigoArticulo'=>'TM3310','precio'=>250.4],
			['CodigoArticulo'=>'TM1501','precio'=>252.7],
			['CodigoArticulo'=>'TM1500','precio'=>252.7],
			['CodigoArticulo'=>'TM3330','precio'=>255.6],
			['CodigoArticulo'=>'TM2155','precio'=>283.9],
			['CodigoArticulo'=>'TM9953','precio'=>300],
			['CodigoArticulo'=>'TM9110','precio'=>300],
			['CodigoArticulo'=>'TM4320','precio'=>300],
			['CodigoArticulo'=>'TM2160','precio'=>318.5],
			['CodigoArticulo'=>'TM3313','precio'=>331.9],
			['CodigoArticulo'=>'TM3312','precio'=>331.9],
			['CodigoArticulo'=>'TM9632','precio'=>338],
			['CodigoArticulo'=>'TM9687','precio'=>350],
			['CodigoArticulo'=>'TM96801','precio'=>355],
			['CodigoArticulo'=>'TM9677','precio'=>355],
			['CodigoArticulo'=>'TM9688','precio'=>380],
			['CodigoArticulo'=>'TM9151','precio'=>415],
			['CodigoArticulo'=>'TM9603','precio'=>419],
			['CodigoArticulo'=>'TM9804','precio'=>420],
			['CodigoArticulo'=>'TM3314','precio'=>425.3],
			['CodigoArticulo'=>'TM1707','precio'=>450],
			['CodigoArticulo'=>'TM3350','precio'=>458.8],
			['CodigoArticulo'=>'TM9905','precio'=>480],
			['CodigoArticulo'=>'TM9676','precio'=>495],
			['CodigoArticulo'=>'TM9160','precio'=>515],
			['CodigoArticulo'=>'TM9620','precio'=>517],
			['CodigoArticulo'=>'TM9744','precio'=>570],
			['CodigoArticulo'=>'TM8060','precio'=>600],
			['CodigoArticulo'=>'TM9628','precio'=>667],
			['CodigoArticulo'=>'TM9856','precio'=>715],
			['CodigoArticulo'=>'TM9636','precio'=>740],
			['CodigoArticulo'=>'TM95452','precio'=>750],
			['CodigoArticulo'=>'TM8330','precio'=>750],
			['CodigoArticulo'=>'TM9658','precio'=>780],
			['CodigoArticulo'=>'TM9146','precio'=>828],
			['CodigoArticulo'=>'TM9145','precio'=>828],
			['CodigoArticulo'=>'TM9144','precio'=>828],
			['CodigoArticulo'=>'TM9142','precio'=>828],
			['CodigoArticulo'=>'TM9105','precio'=>828],
			['CodigoArticulo'=>'TM9141','precio'=>900],
			['CodigoArticulo'=>'TM9140','precio'=>900],
			['CodigoArticulo'=>'TM4315','precio'=>980],
			['CodigoArticulo'=>'TM9154','precio'=>1000],
			['CodigoArticulo'=>'TM9153','precio'=>1000],
			['CodigoArticulo'=>'TM9152','precio'=>1000],
			['CodigoArticulo'=>'TM9909','precio'=>1040],
			['CodigoArticulo'=>'TM9629','precio'=>1100],
			['CodigoArticulo'=>'TM9655','precio'=>1170],
			['CodigoArticulo'=>'TM9740','precio'=>1200],
			['CodigoArticulo'=>'TM9747','precio'=>1300],
			['CodigoArticulo'=>'TM9600','precio'=>1400],
			['CodigoArticulo'=>'TM9122','precio'=>1500],
			['CodigoArticulo'=>'TM9121','precio'=>1500],
			['CodigoArticulo'=>'TM1700','precio'=>1515.7],
			['CodigoArticulo'=>'TM9137','precio'=>1648],
			['CodigoArticulo'=>'TM9547','precio'=>2100],
			['CodigoArticulo'=>'TM9139','precio'=>2200],
			['CodigoArticulo'=>'TM9138','precio'=>2200],
			['CodigoArticulo'=>'TM9654','precio'=>2480],
			['CodigoArticulo'=>'TM8310','precio'=>2490],
			['CodigoArticulo'=>'TM8160','precio'=>2500],
			['CodigoArticulo'=>'TM9810','precio'=>2530],
			['CodigoArticulo'=>'TM1701','precio'=>2530],
			['CodigoArticulo'=>'TM9854','precio'=>3650],
			['CodigoArticulo'=>'TM9818','precio'=>3700],
			['CodigoArticulo'=>'TM9649','precio'=>3700],
			['CodigoArticulo'=>'TM9753','precio'=>3955],
			['CodigoArticulo'=>'TM9001','precio'=>4410],
			['CodigoArticulo'=>'TM9783','precio'=>4600],
			['CodigoArticulo'=>'TM9626','precio'=>4600],
			['CodigoArticulo'=>'TM9805','precio'=>4700],
			['CodigoArticulo'=>'TM9646','precio'=>4800],
			['CodigoArticulo'=>'TM9719','precio'=>4900],
			['CodigoArticulo'=>'TM9849','precio'=>5000],
			['CodigoArticulo'=>'TM9020','precio'=>5000],
			['CodigoArticulo'=>'TM9010','precio'=>5164],
			['CodigoArticulo'=>'TM9728','precio'=>5500],
			['CodigoArticulo'=>'TM9233','precio'=>6300],
			['CodigoArticulo'=>'TM9232','precio'=>6300],
			['CodigoArticulo'=>'TM9231','precio'=>6300],
			['CodigoArticulo'=>'TM9230','precio'=>6300],
			['CodigoArticulo'=>'TM9190','precio'=>6640],
			['CodigoArticulo'=>'TM9186','precio'=>6640],
			['CodigoArticulo'=>'TM9809','precio'=>7300],
			['CodigoArticulo'=>'TM9750','precio'=>7500],
			['CodigoArticulo'=>'TM9267','precio'=>8550],
			['CodigoArticulo'=>'TM9266','precio'=>8550],
			['CodigoArticulo'=>'TM9238','precio'=>9460],
			['CodigoArticulo'=>'TM9235','precio'=>9460],
			['CodigoArticulo'=>'TM9754','precio'=>10116],
			['CodigoArticulo'=>'TM96502','precio'=>11300],
			['CodigoArticulo'=>'TM9850','precio'=>12000],
			['CodigoArticulo'=>'TM9546','precio'=>12500],
			['CodigoArticulo'=>'TM9207','precio'=>13500],
			['CodigoArticulo'=>'TM9206','precio'=>13500],
			['CodigoArticulo'=>'TM9193','precio'=>13500],
			['CodigoArticulo'=>'TM9174','precio'=>13520],
			['CodigoArticulo'=>'TM9173','precio'=>13520],
			['CodigoArticulo'=>'TM9170','precio'=>13520],
			['CodigoArticulo'=>'TM9172','precio'=>14600],
			['CodigoArticulo'=>'TM9171','precio'=>16030],
			['CodigoArticulo'=>'TM9853','precio'=>17000],
			['CodigoArticulo'=>'TM9276','precio'=>17100],
			['CodigoArticulo'=>'TM9272','precio'=>17100],
			['CodigoArticulo'=>'TM9179','precio'=>27040],
			['CodigoArticulo'=>'TM9177','precio'=>27040],
			['CodigoArticulo'=>'TM9178','precio'=>29215],
		];
		foreach ($data as $value) {
			$this->db->where('CodigoArticulo', $value['CodigoArticulo']);
			$this->db->update('articulos', $value);
		}
		foreach ($data as $value) {
			print_r($value['CodigoArticulo']);
			echo '</br>';
			print_r($value['precio']);
			echo '</br>';
		}
		
	} */
}