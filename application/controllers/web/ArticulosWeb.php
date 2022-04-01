<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ArticulosWeb extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		//$this->load->model("Articulo_model");
	}
	
	public function index()
	{
		//$this->accesoCheck(2);
		$this->titles('ArticulosWebConfig','Configurar Articulos Web','Administracion',);
		$this->datos['foot_script'][]=base_url('assets/hergo/articulosWeb/config.js').'?'.rand();
					
		$this->setView('administracion/webArticulos/config.php');
	}

}