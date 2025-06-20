<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Articulos extends MY_Controller
{
	/**
	 * @property Articulo_model $Articulo_model
	 * @property FileStorage $filestorage
	 */

	public function __construct()
	{	
		parent::__construct();
		$this->load->model("Articulo_model");
		$this->load->library("FileStorage");
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
		$uploadedImage = $this->subir_imagen($id, $_FILES);
		$imagenEliminada = $this->input->post('imagenEliminada');
		$isUpdate = ($id > 0);
		
		// Caso 1: Se ha subido una nueva imagen
		if ($uploadedImage && $uploadedImage !== "") {
			$item->Imagen = $uploadedImage;
			$item->ImagenUrl = "hg/articulos/{$uploadedImage}";
		}
		// Caso 2: Se ha eliminado la imagen existente
		else if ($imagenEliminada == '1') {
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
			$res=$this->Articulo_model->mostrarArticulos($uso);
			$res=$res->result_array();
			echo json_encode($res);
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
	 * Sube una imagen del artículo a DigitalOcean Spaces
	 * 
	 * @param int $id ID del artículo
	 * @param array $archivo_img Array $_FILES con la imagen
	 * @return string Nombre del archivo subido o cadena vacía si no hay imagen
	 */
	private function subir_imagen($id, $archivo_img)
	{
		if(empty($archivo_img['imagenes']['name'])) {
			return "";
		}
		
		// Usar la biblioteca FileStorage para subir el archivo
		$result = $this->filestorage->uploadToSpaces('articulos', $archivo_img, 'imagenes');
		
		if($result['success']) {
			// Devolvemos solo el nombre del archivo, sin la ruta
			return pathinfo($result['path'], PATHINFO_BASENAME);
		}
		
		log_message('error', 'Error al subir imagen de artículo: ' . $result['message']);
		return "";
	}
}
