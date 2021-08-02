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
				strtoupper($posicion),$autoriza,$proser,$uso,$nom_imagen,$precio,strtoupper($descripcionFabrica));
        	}
        	else
        	{
				$this->libacceso->accesoInt(69);
        		$nom_imagen=$this->subir_imagen($id,$_FILES);
				$this->Articulo_model->editarArticulo_model($id,strtoupper($codigo),strtoupper($descripcion),$unidad,$marca,$linea,strtoupper($parte),
				strtoupper($posicion),$autoriza,$proser,$uso,$nom_imagen,$precio,strtoupper($descripcionFabrica));
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
}