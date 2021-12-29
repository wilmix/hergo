<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Welcome_model");
	}

	public function index()
	{
		$result = $this->Welcome_model->kardexByCode(1,0,'2021-01-01','2021-12-31');
		$dateNegative = null;
		$n = 0;
		foreach ($result as $value) {
			//echo $value->codigo.'<br>';
			if($value->operacion == '-' && $value->cantidadSaldo < 0 && isset($dateNegative)){
				continue;
			}
			if($value->operacion == '-' && $value->cantidadSaldo < 0 ){
				$dateNegative = $value->fechakardex;
				//print_r($value->id);
				//echo'<br>';
				continue;
			}
			else if ($value->operacion == '+' && $value->cantidadSaldo>=0 && isset($dateNegative)) {
				$value->newDate = $dateNegative;
				//print_r($value);
				$n = $n +1;
				echo "$n - AL ID: $value->id CAMBIAR LA FECHA DE $value->fechakardex A $value->newDate";
				echo'<br>';

			}
			$dateNegative = null;
			
			
		}

	}
}
