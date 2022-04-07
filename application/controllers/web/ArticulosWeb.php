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

}