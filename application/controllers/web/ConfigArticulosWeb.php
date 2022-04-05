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
    public function getLevel1()
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
        $nivel->autor = $this->session->userdata('user_id');
        $nivel->img = ($_FILES['img']['name'] == '') ? '' : $this->uploadSpaces($_FILES, 'web/levels/level-1/');

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
				'Key' => $folder.$file['img']['name'],
				'SourceFile' => $file['img']['tmp_name'],
				'ACL' => 'public-read'
		]);	
        //print_r($uploadObject['@metadata']['statusCode']);
		return $file['img']['name'];
	}
}