<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Configuracion extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
        $this->load->model("Ingresos_model");
		$this->load->model("Configuracion_model");
	}

	public function DatosFactura(){

		$this->accesoCheck(9);
		$this->titles('DatosFactura','Datos Factura','Configuración');
		$this->datos['almacenes']=$this->Configuracion_model->retornar_tabla("almacenes");

		$this->datos['foot_script'][]=base_url('assets/hergo/configuracion/datosFactura.js') .'?'.rand();
		$this->setView('administracion/configuracion/datosFactura');

	}
	public function mostrarDatosFactura()//******cambiar a funcion del modelo
	{
		if($this->input->is_ajax_request())
        {
			$res=$this->Configuracion_model->mostrarDatosFactura(); //*******************cambiar a nombre modelo -> funcion modelo (variable de js para filtrar)
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function agregarDatosFactura(){
		if($this->input->is_ajax_request()){
				$id_lote = addslashes($this->security->xss_clean($this->input->post('id_lote')));
				$almacen = addslashes($this->security->xss_clean($this->input->post('almacen')));
				$autorizacion = addslashes($this->security->xss_clean($this->input->post('autorizacion')));
				$desde = addslashes($this->security->xss_clean($this->input->post('desde')));
				$hasta = addslashes($this->security->xss_clean($this->input->post('hasta')));
				$fechaLimite = addslashes($this->security->xss_clean($this->input->post('fechaLimite')));
				$tipo = addslashes($this->security->xss_clean($this->input->post('tipo')));
				$llave = addslashes($this->input->post('llave'));
				$leyenda1 = addslashes($this->security->xss_clean($this->input->post('leyenda1')));
				$leyenda2 = addslashes($this->security->xss_clean($this->input->post('leyenda2')));
				$leyenda3 = addslashes($this->security->xss_clean($this->input->post('leyenda3')));
				$uso = addslashes($this->security->xss_clean($this->input->post('uso')));
				
				if($id_lote==""){
					$this->Configuracion_model->agregarDatosFactura_model($id_lote,$almacen,$autorizacion,$desde,$hasta,$fechaLimite,$tipo,$llave,$leyenda1,$leyenda2,$leyenda3,$uso);
				} else {
					$this->Configuracion_model->editarDatosFactura_model($id_lote,$almacen,$autorizacion,$desde,$hasta,$fechaLimite,$tipo,$llave,$leyenda1,$leyenda2,$leyenda3,$uso);
				}
		}
        echo "{}";       
	}
	
	public function TipoCambio()
	{

		$this->accesoCheck(10);
		$this->titles('TipoCambio','Tipo de Cambio','Configuración');
		$this->datos['foot_script'][]=base_url('assets/hergo/configuracion/tipoCambio.js') .'?'.rand();
		$this->setView('administracion/configuracion/tipoCambio');
	}
	public function agregarTipoCambio(){
		if($this->input->is_ajax_request()){
			$tipocambio = addslashes($this->security->xss_clean($this->input->post('tipoCambio')));
			$this->Configuracion_model->agregarTipoCambio_model($tipocambio);
		}
	}
	public function mostrarTipoCambio()
	{
		if($this->input->is_ajax_request()){
			$res=$this->Configuracion_model->mostrarTipoCambio();
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function updateTipoCambio()
	{
		if($this->input->is_ajax_request()){
			$id = addslashes($this->security->xss_clean($this->input->post('id')));
			$fecha = addslashes($this->security->xss_clean($this->input->post('fechaCambio')));
			$tipocambio = addslashes($this->security->xss_clean($this->input->post('tipocambio')));
			
			if ($id == '') {
				$res = $this->Configuracion_model->agregarTipoCambio_model($tipocambio, $fecha);
			} else if($id == 'egreso') {
				$fecha = date('Y-m-d',strtotime($fecha));
				$res = $this->Configuracion_model->agregarTipoCambio_model($tipocambio, $fecha);
			}
			else {
				$res=$this->Configuracion_model->updateTipoCambio($id, $fecha, $tipocambio);
			}
			
			$res = new stdclass();
			$res->id =$id;
			$res->fecha = $fecha;
			$res->TipoCambio = $tipocambio;
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

	
}