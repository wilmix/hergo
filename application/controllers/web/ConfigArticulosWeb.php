<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ConfigArticulosWeb extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("ArticulosWeb_model");
	}
	
	public function index()
	{
		//$this->accesoCheck(2);
		$this->titles('ArticulosWebConfig','Configurar Articulos Web','Administracion',);
		$this->datos['foot_script'][]=base_url('assets/hergo/articulosWeb/config.js').'?'.rand();
					
		$this->setView('administracion/webArticulos/config.php');
	}
    public function getLevels()
    {
        $table = $this->input->post('table');
        $res = $this->ArticulosWeb_model->show($table);
        echo json_encode($res);
    }
    public function addLevel()
    {

        $id = $this->input->post('id');
        $table = $this->input->post('table');
        $nivel = new stdclass();
        $nivel->name = $this->input->post('name');
        $nivel->description = $this->input->post('description');
        $nivel->is_active = $this->input->post('isActive');
        $nivel->url = $this->get_slug($nivel->name);
        if ($table == 'web_nivel1') {
            $nivel->is_service = $this->input->post('isService');
            $nivel->img = $this->get_slug(($_FILES['img']['name'] == '') ? '' : $this->uploadSpaces($_FILES, 'web/levels/'));
        }
        if ($table == 'web_nivel2') {
            $nivel->id_nivel1 = $this->input->post('id_nivel1');
            $nivel->img = $this->get_slug(($_FILES['img']['name'] == '') ? '' : $this->uploadSpaces($_FILES, 'web/levels/n2/'));
        }
        if ($table == 'web_nivel3') {
            $nivel->id_nivel2 = $this->input->post('id_nivel2');
        }
        $nivel->autor = $this->session->userdata('user_id');
        

        if ($id > 0) {
            if ( $nivel->img == '' ) {
				unset($nivel->img);
			}
            $this->ArticulosWeb_model->update($table, $id, $nivel);
        } else if($id == 0){
            $this->ArticulosWeb_model->store($nivel, $table);
        }

        echo json_encode($nivel); 
    }
    public function uploadSpaces($file, $folder)
	{
		$client = new Aws\S3\S3Client($this->config->item('credentialsSpacesDO'));
		$uploadObject = $client->putObject([
				'Bucket' => 'hergo-space',
				'Key' => $folder.$this->get_slug($file['img']['name']),
				'SourceFile' => $file['img']['tmp_name'],
				'ACL' => 'public-read'
		]);	
        //print_r($uploadObject['@metadata']['statusCode']);
		return $file['img']['name'];
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
}