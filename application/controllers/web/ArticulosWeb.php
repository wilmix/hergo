<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property ArticulosWeb_model $ArticulosWeb_model
 */
class ArticulosWeb extends MY_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("ArticulosWeb_model");
	}
	
	public function index()
	{
		//$this->accesoCheck(2);
		$this->titles('ArticulosWeb','Articulos Web','Administracion',);
		$this->datos['foot_script'][]=base_url('assets/hergo/articulosWeb/articulosWeb.js').'?'.rand();
					
		$this->setView('administracion/webArticulos/articulosWeb.php');
	}
	public function getItems()
	{
		$res = $this->ArticulosWeb_model->showItems();
		echo json_encode($res);
	}
	public function getData()
	{
		$res = new stdClass;
		$res->n1 = $this->ArticulosWeb_model->getDataLevels('web_nivel1');
		$res->n2 = $this->ArticulosWeb_model->getDataLevels('web_nivel2');
		$res->n3 = $this->ArticulosWeb_model->getDataLevels('web_nivel3');
		echo json_encode($res);
	}
	public function getItem()
	{
		$nivel1 = $this->input->post('n1');
		$nivel2 = $this->input->post('n2');

		$res = new stdClass;
		$res->n2 = $this->ArticulosWeb_model->getDataLevelsItem('web_nivel2','n.id_nivel1', $nivel1);
		$res->n3 = $this->ArticulosWeb_model->getDataLevelsItem('web_nivel3','n.id_nivel2', $nivel2);

		echo json_encode($res);
	}
	public function getLevel()
	{
		$level = $this->input->post('level');
		$table = $this->input->post('table');
		$where = $this->input->post('where');
        $res = $this->ArticulosWeb_model->getLevel($level, $table, $where);
        echo json_encode($res);
	}
	public function addItemWeb()
	{
		$id = $this->input->post('id');
		$n1_id = (int) $this->input->post('id_nivel1');
		$n2_id = (int) $this->input->post('id_nivel2');
		$n3_id = (int) $this->input->post('id_nivel3');

		if ($n1_id <= 0) {
			http_response_code(400);
			echo json_encode(['error' => 'Debe seleccionar al menos el Nivel 1.']);
			return;
		}

		$item = [
			'articulo_id' => $this->input->post('articulo_id'),
			'titulo' => $this->input->post('titulo'),
			'descripcion' => $this->input->post('descripcion'),
			'n1_id' => $n1_id,
			'n2_id' => ($n2_id > 0) ? $n2_id : null,
			'n3_id' => ($n3_id > 0) ? $n3_id : null,
		];

		// Manejo de archivos solo si realmente se subió uno
		if (isset($_FILES['imagen']) && is_uploaded_file($_FILES['imagen']['tmp_name'])) {
			$item['imagen'] = $this->uploadSpaces($_FILES, 'web/items/', 'imagen', 'image/jpg');
		}
		if (isset($_FILES['pdf']) && is_uploaded_file($_FILES['pdf']['tmp_name'])) {
			$item['fichaTecnica'] = $this->uploadSpaces($_FILES, 'web/pdf/', 'pdf', 'application/pdf');
		}
		if (isset($_FILES['video']) && is_uploaded_file($_FILES['video']['tmp_name'])) {
			$item['video'] = $this->uploadSpaces($_FILES, 'web/videos/', 'video', 'video/mp4');
		}

		$eliminarPdf = $this->input->post('eliminarPdf');
		$eliminarVideo = $this->input->post('eliminarVideo');
		$eliminarImagen = $this->input->post('eliminarImagen');

		if ($eliminarPdf) {
			$item['fichaTecnica'] = null;
		}
		if ($eliminarVideo) {
			$item['video'] = null;
		}
		if ($eliminarImagen) {
			$item['imagen'] = null;
		}

		if ($id == 0) {
			$item['created_by'] = $this->session->userdata('user_id');
			$this->ArticulosWeb_model->storeItem($item);
		} else if ($id > 0) {
			$item['updated_by'] = $this->session->userdata('user_id');
			$item['updated_at'] = date('Y-m-d H:i:s');
			$this->ArticulosWeb_model->updateItem($id, $item);
		}

		echo json_encode(['success' => true]);
	}
	public function uploadSpaces($file, $folder, $field, $type)
	{
		// @intelephense-ignore-next-line
		$client = new Aws\S3\S3Client($this->config->item('credentialsSpacesDO'));
		$uploadObject = $client->putObject([
				'Bucket' => 'hergo-space',
				'Key' => $folder.$this->get_slug($file[$field]['name']),
				'SourceFile' => $file[$field]['tmp_name'],
				'ACL' => 'public-read',
				'ContentType' => $type
		]);	
        //print_r($uploadObject['@metadata']['statusCode']);
		return $this->get_slug($file[$field]['name']);
	}
    public function getDataLevels()
    {
        $table = $this->input->post('table');
        $res = $this->ArticulosWeb_model->getDataLevels($table);
        echo json_encode($res);
    }
	function get_slug($string_in){
        $string_output=mb_strtolower($string_in, 'UTF-8');
        //caracteres inválidos en una url
        $find=array('¥','µ','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','\'','"');
        //traduccion caracteres válidos
        $repl=array('-','-','a','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','ny','o','o','o','o','o','o','u','u','u','u','y','y','','' );
        $string_output=str_replace($find, $repl, $string_output);
        //más caracteres inválidos en una url que sustituiremos por guión
        $find=array(' ', '&','%','$','·','!','(',')','?','¿','¡',':','+','*','\n','\r\n', '\\', '´', '`', '¨', ']', '[');
        $string_output=str_replace($find, '-', $string_output);
        $string_output=str_replace('--', '', $string_output);
        return $string_output;
    }
	public function getPromos()
	{
		$res = $this->ArticulosWeb_model->showPromos();
		echo json_encode($res);
	}
	public function addItemPromo()
	{
		$id =$this->input->post('id');
		$item = [
			'titulo' => $this->input->post('titulo'),
			'descripcion' => ($this->input->post('descripcion')),
			'created_by' => $this->session->userdata('user_id'),
			'is_active' => ($this->input->post('isActive')),
			'imagen' => ($_FILES['imagen']['name'] == '') ? '' : $this->uploadSpaces($_FILES, 'web/promos/','imagen','image/jpg'),
		];
		if ($id == 0) {
			$this->ArticulosWeb_model->storeItemPromo($item);
		} else if ($id > 0) {
			if ( $item['imagen'] == '' ) {
				unset($item['imagen']);
			}
			$this->ArticulosWeb_model->updateItemPromos($id, $item);
		}
		echo json_encode($item);
	}

}