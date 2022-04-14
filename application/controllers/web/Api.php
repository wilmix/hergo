<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("ApiModel");
	}
	
	public function index()
	{
		$res = $this->ApiModel->showMenuItems();
		echo json_encode($res);
	}
    public function menu()
	{
		$res = $this->ApiModel->nivel_1();
        $menu = array();

        foreach ($res as $key => $value) {
            $item = array();
            $item['menu'] = $value->name;
            $sub = $this->ApiModel->nivel_2($value->id);
            $item['sub'] = $sub;
            array_push($menu, $item); 
        }
		echo json_encode($menu);
	}
}