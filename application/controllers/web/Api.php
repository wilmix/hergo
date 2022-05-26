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
	public function lineProducts()
	{
		$res = $this->ApiModel->lineProducts();
		echo json_encode($res);
	}
	public function services()
	{
		$res = $this->ApiModel->services();
		echo json_encode($res);
	}
	public function item($id)
	{
		$res = $this->ApiModel->item($id);
		echo json_encode($res);
	}
	public function list_items($n2) //5 17
	{
		$subList = $this->getSubList($n2);
		$list = [];
		foreach ($subList as $value) {
			$item['n3_name'] = $value->n3_name;
			$item['n2_name'] = $value->n2_name;
			$sub = $this->ApiModel->list_items($value->n3_id);
			$item['items'] = $sub;
			array_push($list,$item);
		}
		echo json_encode($list); 
	}
	public function getSubList($n2) //5 17
	{
		$res = $this->ApiModel->getSubList($n2);
		return $res;
	}
}