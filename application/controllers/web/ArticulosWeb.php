<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ArticulosWeb extends CI_Controller
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
		$id =$this->input->post('id');
		$item = [
			'articulo_id' => $this->input->post('articulo_id'),
			'titulo' => $this->input->post('titulo'),
			'descripcion' => $this->input->post('descripcion'),
			'n1_id' => $this->input->post('id_nivel1'),
			'n2_id' => $this->input->post('id_nivel2'),
			'n3_id' => $this->input->post('id_nivel3'),
			'created_by' => $this->session->userdata('user_id'),
			'imagen' => ($_FILES['imagen']['name'] == '') ? '' : $this->uploadSpaces($_FILES, 'web/levels/')
		];
		if ($id == 0) {
			$this->ArticulosWeb_model->storeItem($item);
		} else if ($id > 0) {
			$this->ArticulosWeb_model->updateItem($id, $item);
		}
		echo json_encode($item);
	}

}