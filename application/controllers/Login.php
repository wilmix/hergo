<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends MY_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('url');	
		$this->load->library('LibAcceso');
		//$this->libacceso->acceso(1);
		

		$this->cabeceras_css=array(
				'http://fonts.googleapis.com/css?family=Roboto:400,100,300,500',
				base_url('assets/bootstrap/css/bootstrap.min.css'),
				base_url("assets/fa/css/font-awesome.min.css"),
				
			);
		$this->cabecera_script=array(
				base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'),
				base_url('assets/bootstrap/js/bootstrap.min.js'),
				base_url('assets/plugins/slimscroll/slimscroll.min.js'),
				
			);
	}
	public function index()
	{
		$datos['cabeceras_css']= $this->cabeceras_css;
		$datos['cabeceras_script']= $this->cabecera_script;
		$datos['cabeceras_css'][]=base_url('assets/login/css/form-elements.css');
		$datos['cabeceras_css'][]=base_url('assets/login/css/style.css');
		$datos['cabeceras_script'][]=base_url('assets/login/js/jquery.backstretch.min.js');
		$datos['cabeceras_script'][]=base_url('assets/login/js/scripts.js');

		$this->datos['titulo']="Iniciar Sesion";
		$this->load->view('plantilla/head.php',$datos);		
		$this->load->view('login/login.php',$datos);
		$this->load->view('login/footerlogin.php',$datos);
	}
	public function verLogin()
	{
		//print_r($_SESSION['datosAlmacen']); 
		//print_r($_SESSION['accesoMenu']); 
		$aux=$this->retornarSubMenus($_SESSION['accesoMenu']);
		var_dump($aux);
		
		if(in_array("122", $aux))
		{
			echo "existe";
		}
		else
		{
			echo "No existe";
		}
	}
	public function retornarSubMenus($submenues)
	{
		$submenu = array();
		foreach ($submenues as $row ) {
			array_push($submenu,$row['subMenu']);
		}
		return $submenu;
	}
}
