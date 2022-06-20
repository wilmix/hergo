<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class WebInfo extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("ArticulosWeb_model");
	}
	
	public function index()
	{
		//$this->accesoCheck(2);
		$this->titles('ArticulosWeb','Articulos Web','AdministracionWeb',);
		//$this->datos['foot_script'][]=base_url('assets/hergo/articulosWeb/articulosWeb.js').'?'.rand();
					
		//$this->setView('administracion/webArticulos/home.php');
	}
    public function home()
	{
		//$this->accesoCheck(2);
		$this->titles('ArticulosWeb','Articulos Web','AdministracionWeb',);
		$this->datos['foot_script'][]=base_url('assets/hergo/articulosWeb/home.js').'?'.rand();
					
		$this->setView('administracion/webArticulos/home.php');
	}
}