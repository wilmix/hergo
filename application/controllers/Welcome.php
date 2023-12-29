<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public $Welcome_model;
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
				$dateNegative = null;
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
	public function identificarEgresoTraspaso($articulo): array  {
		$almacen = $almacen = '1,2,3,4,5,6,7,8,9,10';
		$result = $this->Welcome_model->kardex($almacen, $articulo);
		return $result;
		//echo json_encode(print_r($result));
	}
	function identificarCostosEgresoTraspaso($articulo): void {
		$resultLog = [];
		$egresosTraspasos = $this->identificarEgresoTraspaso($articulo);
		//print_r($egresosTraspasos);
		foreach ($egresosTraspasos as $key => $egresoTraspaso) {
			$egreso = $this->Welcome_model->kardex( $egresoTraspaso->almacen_id, $articulo);
			foreach ($egreso as $dato) {
				if ($dato->id == $egresoTraspaso->id) {
					//print_r($dato); return;
					$traspaso = $this->Welcome_model->encontrarTraspaso($dato->id);
					//print_r($traspaso); return;
					$egresoDetalle = $this->Welcome_model->getEgreso($traspaso[0]['idEgreso'], $dato->idArticulo, $dato->cantidad, $dato->cpp);
					$ingresoDetalle = $this->Welcome_model->getIngreso($traspaso[0]['idIngreso'], $dato->idArticulo, $dato->cantidad, $dato->cpp);
					//print_r($egresoDetalle[0]->egreso_id_detalle); return;
					/* var_dump(floatval($egresoDetalle[0]->nuevo_punitario));
					var_dump(floatval($egresoDetalle[0]->punitario)); */
					if (floatval($egresoDetalle[0]->nuevo_punitario) !== floatval($egresoDetalle[0]->punitario)) {
						$this->Welcome_model->actualizarEgresoDetalle($traspaso[0]['idEgreso'], $dato->idArticulo, $dato->cantidad, $dato->cpp);
						$this->Welcome_model->actualizarIngresoDetalle($traspaso[0]['idIngreso'], $dato->idArticulo, $dato->cantidad, $dato->cpp);
						// Registrar la actualización en el log
						$logEntry = [

							'egreso_id_detalle' => $egresoDetalle[0]->egreso_id_detalle,
							'ingreso_id_detalle' => $ingresoDetalle[0]->ingreso_id_detalle,
							'antiguo_punitario' => $dato->punitario,
							'nuevo_punitario' => $dato->cpp
							// Agregar más campos según sea necesario
						];
	
						$resultLog[] = $logEntry;
						
					} else {
						$resultLog[] = 'no se modifico';
					}
				}
			}
			//break;
		}
		print_r($resultLog);
	}
}
