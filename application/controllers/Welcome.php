<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Welcome_model");
	}

	public function cambiarFechaKardex()
	{
		$result = $this->Welcome_model->kardexByCode(1,0,'2021-01-01','2021-12-31');
		$dateNegative = null;
		$data =[];
		$n = 0;
		foreach ($result as $value) {
			if ($value->idDetalle == '') {
				continue;
			}
			if ($value->operacion == '+' && $value->tipo == 'II') {
				continue;
			}
			if($value->operacion == '-' && $value->cantidadSaldo < 0 && isset($dateNegative)){
				continue;
			}
			if($value->operacion == '-' && $value->cantidadSaldo < 0 ){
				$dateNegative = $value->fechakardex;
				continue;
			}
			else if ($value->operacion == '+'  && isset($dateNegative)) {
				$value->newDate = $dateNegative;
				$n = $n +1;
				$data = array(
					'fechaIngreso' => $value->newDate
				);
				$this->Welcome_model->update_date($value->id, $data);
				echo "$n - $value->codigo -  AL ID: $value->id CAMBIAR LA FECHA DE $value->fechakardex A $value->newDate";
				echo'<br>';
			}
			$dateNegative = null;
			$data =[];
		}
	}
}
