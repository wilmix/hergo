<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Articulos extends MY_Controller
{
	/**
	 * @var Articulo_model
	 */
	public $Articulo_model;
	/**
	 * @var FileStorage
	 */
	public $filestorage;

	public function __construct()
	{	
		parent::__construct();
		$this->load->model("Articulo_model");
		$this->load->library("FileStorage");
		$this->load->config('storage', TRUE);
	}
	
	public function index()
	{
		$this->accesoCheck(2);
		$this->titles('Articulos','Articulos','Administracion',);
		$this->datos['foot_script'][]=base_url('assets/hergo/fileutils.js').'?'.rand();
		$this->datos['foot_script'][]=base_url('assets/hergo/articulo.js').'?'.rand();

		$this->datos['unidad']=$this->Articulo_model->retornar_tabla("unidad");	
		$this->datos['marca']=$this->Articulo_model->retornar_tabla("marca");
		$this->datos['linea']=$this->Articulo_model->retornar_tabla("linea");
		$this->datos['requisito']=$this->Articulo_model->retornar_tabla("requisito");
		$this->datos['codigosCaeb']= $this->Articulo_model->retornar_tabla("siat_sincro_actividades")->result_array();
					
		$this->setView('administracion/articulo/articulo');
	}
	/**
	 * Agrega o actualiza un artículo
	 * 
	 * Maneja la creación y actualización de artículos incluyendo la gestión de imágenes
	 * con soporte para subir nuevas imágenes o eliminar existentes.
	 */
	public function addItem()
	{
		// Obtener el ID para saber si es una creación o actualización
		$id = $this->input->post('id_articulo');
		$isUpdate = ($id > 0);
		
		// Crear objeto con datos del artículo
		$item = $this->_prepareArticuloData();
		
		// Manejar la imagen (subir nueva, eliminar o mantener)
		$this->_handleArticuloImage($item, $id);
		
		// Guardar en la base de datos
		if ($isUpdate) {
			$result = $this->Articulo_model->update($id, $item);
		} else {
			$result = $this->Articulo_model->store($item);
		}

		// Preparar respuesta
		$res = new stdClass();
		$res->item = $item;
		$res->status = $result;
		$res->msg = $isUpdate ? 'modificado' : 'guardado';
		
		echo json_encode($res);
	}
	
	/**
	 * Prepara los datos del artículo desde el formulario
	 * 
	 * @return stdClass Objeto con los datos del artículo
	 */
	private function _prepareArticuloData()
	{
		$item = new stdClass();
		$item->CodigoArticulo = strtoupper($this->input->post('codigo'));
		$item->Descripcion = strtoupper($this->input->post('descripcion'));
		$item->NumParte = strtoupper($this->input->post('parte'));
		$item->idUnidad = $this->input->post('unidad');
		$item->idMarca = $this->input->post('marca');
		$item->idLinea = $this->input->post('linea');
		$item->PosicionArancelaria = $this->input->post('posicion');
		$item->idRequisito = $this->input->post('autoriza');
		$item->ProductoServicio = $this->input->post('proser');
		$item->EnUso = $this->input->post('uso');
		$item->detalleLargo = strtoupper($this->input->post('descripcionFabrica'));
		$item->web_catalogo = $this->input->post('web');
		$item->Autor = $this->session->userdata('user_id');
		$item->codigoCaeb = $this->input->post('codigoActividadSiat');
		$item->codigoProducto = $this->input->post('codigoSiatSelect');
		
		return $item;
	}
	
	/**
	 * Maneja la imagen del artículo (subir nueva, eliminar o mantener)
	 * 
	 * @param stdClass $item Objeto de datos del artículo
	 * @param int $id ID del artículo (0 si es nuevo)
	 */
	private function _handleArticuloImage(&$item, $id)
	{
		$imagenEliminada = $this->input->post('imagenEliminada');
		$isUpdate = ($id > 0);
		
		// Caso 1: Se ha subido una nueva imagen
		if (!empty($_FILES['imagenes']['name'])) {
			// La configuración ya se ha cargado en el constructor
			
			// Usar la biblioteca FileStorage para subir el archivo
			$result = $this->filestorage->uploadToSpaces('articulos', $_FILES, 'imagenes');
			
			if ($result['success']) {
				// Para retrocompatibilidad, guardamos solo el nombre del archivo en Imagen
				$item->Imagen = pathinfo($result['path'], PATHINFO_BASENAME);
				// Y la ruta completa relativa en ImagenUrl
				$item->ImagenUrl = $result['path'];
			} else {
				log_message('error', 'Error al subir imagen de artículo: ' . $result['message']);
			}
		}
		// Caso 2: Se ha eliminado la imagen existente
		else if ($imagenEliminada == '1') {
			// Si hay una actualización y tenemos una imagen anterior, eliminarla de Spaces
			if ($isUpdate) {
				$articuloActual = $this->Articulo_model->getById($id);
				if (!empty($articuloActual->ImagenUrl)) {
					$this->filestorage->deleteFromSpaces($articuloActual->ImagenUrl);
				}
			}
			
			$item->Imagen = "";
			$item->ImagenUrl = "";
		}
		// Caso 3: No se ha cambiado la imagen
		else {
			if ($isUpdate) {
				// En actualización, no incluimos estos campos para no modificarlos
				if (property_exists($item, 'Imagen')) unset($item->Imagen);
				if (property_exists($item, 'ImagenUrl')) unset($item->ImagenUrl);
			} else {
				// En creación, establecemos valores vacíos
				$item->Imagen = "";
				$item->ImagenUrl = "";
			}
		}
	}
	public function mostrarArticulos()
	{
		if($this->input->is_ajax_request())
        {
			$uso = $this->input->post("uso");
			$res = $this->Articulo_model->mostrarArticulos($uso);
			$articulos = $res->result_array();
			
			// Agregar URL completa para todas las imágenes
			$cdnUrl = $this->config->item('spaces_cdn_url', 'storage');
			foreach ($articulos as $key => $articulo) {
				// Si existe ImagenUrl, usar esa para la URL completa
				if (!empty($articulo['ImagenUrl'])) {
					$articulos[$key]['ImagenUrlCompleta'] = $cdnUrl . $articulo['ImagenUrl'];
				} 
				// Si no hay ImagenUrl pero hay Imagen (caso legacy), usar ruta local
				else if (!empty($articulo['Imagen'])) {
					// Mantener compatibilidad con imágenes antiguas
					$articulos[$key]['ImagenUrlCompleta'] = base_url('assets/img_articulos/' . $articulo['Imagen']);
				} else {
					$articulos[$key]['ImagenUrlCompleta'] = '';
				}
			}
			
			echo json_encode($articulos);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function getCodigosSiat()
	{
		if($this->input->is_ajax_request())
        {

			$codigoActividad = $this->input->post("codigoActividad");
			$res=$this->Articulo_model->getCodigosSiat($codigoActividad);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	/**
	 * Obtiene la ruta de la imagen para un artículo
	 * 
	 * @param string $imagenUrl URL relativa de la imagen
	 * @return string URL completa de la imagen o cadena vacía si no hay imagen
	 */
	private function obtenerUrlImagen($imagenUrl)
	{
		if(empty($imagenUrl)) {
			return "";
		}
		
		// Cargar la configuración para obtener la URL base del CDN
		$this->config->load('storage', TRUE);
		$cdnUrl = $this->config->item('spaces_cdn_url', 'storage');
		
		return $cdnUrl . $imagenUrl;
	}
	/**
	 * Obtiene los detalles de un artículo con URLs completas para imágenes
	 * 
	 * @param int $id ID del artículo
	 * @return objeto Artículo con información completa
	 */
	public function getArticuloDetails()
	{
		if($this->input->is_ajax_request())
        {
			$id = $this->input->post("id");
			$articulo = $this->Articulo_model->getArticuloById($id);
			
			// Convertir a array para manipular
			$articuloArray = (array)$articulo;
			
			// Agregar URL completa de la imagen si existe
			if (!empty($articuloArray['ImagenUrl'])) {
				$cdnUrl = $this->config->item('spaces_cdn_url', 'storage');
				$articuloArray['ImagenUrlCompleta'] = $cdnUrl . $articuloArray['ImagenUrl'];
			} else {
				$articuloArray['ImagenUrlCompleta'] = '';
			}
			
			echo json_encode($articuloArray);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}
