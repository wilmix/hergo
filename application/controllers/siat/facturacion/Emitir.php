<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Emitir extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		
		$this->load->model('siat/Emitir_model');
		$this->load->model('Egresos_model');
		$this->load->model('Facturacion_model');

	}
	public function pendientesFacturar()
	{
		$almacen  = $this->input->post('almacen');
		$res = $this->Emitir_model->pendientes($almacen);
		echo json_encode($res);
	}
	public function pendientesFacturarDetalle()
	{
		$data  = $this->input->post('row');
		$id = $data['id'];
		$res = $this->Emitir_model->pendientesDetalle($id);
		echo json_encode($res);
	}
	public function monedas_siat()
	{
		$res = $this->Emitir_model->monedas_siat();
		echo json_encode($res);
	}
	public function metodos_pago_siat()
	{
		$res = $this->Emitir_model->metodos_pago_siat();
		echo json_encode($res);
	}
	public function getCodigos()
	{
		/* echo json_encode($this->session->userdata['user_id']);
		return false; */
		$sucursal  = $this->session->datosAlmacen->siat_sucursal; //$this->input->post('sucursal');
		$codigoPuntoVenta  = $this->input->post('codigoPuntoVenta');

		$res = $this->Emitir_model->getCodigos($sucursal, $codigoPuntoVenta);
		echo json_encode($res);
	}
	public function infoFactura()
	{
		$user_id = $this->session->userdata['user_id'];
		$res = $res = [
			'leyenda' =>  $this->Emitir_model->getLeyenda(),
			'user' => $this->Emitir_model->getUser($user_id),
			'monedas_siat' => $this->Emitir_model->monedas_siat(),
			'metodos_pago_siat' => $this->Emitir_model->metodos_pago_siat()
		];
		echo json_encode($res);
	}
	public function storeFacturaSiat()
	{
		if($this->input->is_ajax_request())
        {
			$errores = [];
			$cabeceraSiat = $this->input->post('facturaSiat')['cabecera'];
			$cabecera = $this->input->post('cabecera');
			$detalle = $this->input->post('detalle');
			$items = [];
			foreach ($detalle as $item) {
				array_push($items, [
					'idFactura' => '',
					'articulo' => $item['articulo_id'],
					'moneda' => '',
					'facturaCantidad' => $item['cantidad'],
					'facturaPUnitario' => $item['precioUnitario'],
					'ArticuloNombre' => $item['descripcion'],
					'ArticuloCodigo' => $item['codigoProducto'],
					'idEgresoDetalle' => $item['detalle_egreso_id'],
					'egreso_id' => $item['egreso_id']
				]);
			}

			$factura = new stdclass();
			$factura->lote = '0';
			$factura->almacen =  $this->getAlmacen($cabeceraSiat['codigoSucursal']);
			$factura->nFactura = $cabeceraSiat['numeroFactura'];
			$factura->fechaFac = $cabeceraSiat['fechaEmision'];
			$factura->cliente = $cabeceraSiat['codigoCliente'];
			$factura->moneda = $cabeceraSiat['codigoMoneda'];
			$factura->total = $cabeceraSiat['montoTotal'];
			$factura->glosa = $this->input->post('glosa');
			$factura->tipoPago = $cabeceraSiat['codigoMetodoPago'];
			$factura->codigoControl = '';
			$factura->autor=$this->session->userdata('user_id');
			$factura->tipoCambio=$this->Egresos_model->retornarTipoCambio();
			$factura->ClienteFactura=$cabeceraSiat['nombreRazonSocial'];
			$factura->ClienteNit=$cabeceraSiat['numeroDocumento'];
			$factura->articulos = $items;

			$facturaSiat = new stdclass();
			$facturaSiat->cuf = $cabeceraSiat['cuf'];
			$facturaSiat->cufd = $cabeceraSiat['cufd'];
			$facturaSiat->codigoSucursal = $cabeceraSiat['codigoSucursal'];
			$facturaSiat->codigoPuntoVenta = $cabeceraSiat['codigoPuntoVenta'];
			$facturaSiat->fechaEmision = $cabeceraSiat['fechaEmision'];
			$facturaSiat->leyenda = $cabeceraSiat['leyenda'];
			$facturaSiat->pedido = $cabecera['clientePedido'];
			$facturaSiat->codigoRecepcion = $cabeceraSiat['codigoRecepcion'];

			//echo json_encode($facturaSiat); return false; 

			$idFactura = $this->Emitir_model->storeFacturaSiat($factura, $facturaSiat);

			echo json_encode($idFactura); return false; 
			
			/* if ($errores == []) {
				$idFactura = $this->Emitir_model->storeFacturaSiat($factura);
				$res = new stdclass();
				$res->status = 'ok';
				$res->idFactura = $idFactura;
				echo json_encode($res); 
			} else {
				$res = new stdclass();
				$res->status = 'error';
				$res->errors = $errores;
				echo json_encode($res); 
			} */
        }
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function getAlmacen($sucursal)
	{
		switch ($sucursal) {
			case 0:
				//LP
				return '1';
				break;
			case 6:
				//PTS
				return '3';
				break;
			case 5:
				//SCZ
				return '4';
				break;
		}
	}
	public function test()
	{
		/* $res = $this->input->post();
		echo json_encode($res); 
		return false; */
		$cabeceraSiat = $this->input->post('facturaSiat');
		$cabecera = $this->input->post('cabecera');
		$detalle = $this->input->post('detalle');
		$configuracion = $this->input->post('configuracion');

			$items = [];
			foreach ($detalle as $item) {
				array_push($items, [
					'idFactura' => '',
					'articulo' => $item['articulo_id'],
					'moneda' => '',
					'facturaCantidad' => $item['cantidad'],
					'facturaPUnitario' => $item['precioUnitario'],
					'ArticuloNombre' => $item['descripcion'],
					'ArticuloCodigo' => $item['codigoProducto'],
					'idEgresoDetalle' => $item['detalle_egreso_id'],
					'egreso_id' => $item['egreso_id']
				]);
			}
			$factura = new stdclass();
			$factura->lote = '0';
			$factura->almacen =  $this->getAlmacen($cabeceraSiat['codigoSucursal']);
			$factura->nFactura = $cabeceraSiat['numeroFactura'];
			$factura->fechaFac = date('Y-m-d',strtotime($cabeceraSiat['fechaEmision']));
			$factura->cliente = $cabeceraSiat['codigoCliente'];
			$factura->moneda = $cabeceraSiat['codigoMoneda'];
			$factura->total = $cabeceraSiat['montoTotal'];
			$factura->glosa = isset($cabecera['glosa']) ? $cabecera['glosa'] : '';
			//$factura->tipoPago = $cabeceraSiat['codigoMetodoPago'];
			$factura->codigoControl = '';
			$factura->autor= $cabecera['user_id'];
			$factura->tipoCambio=$this->Egresos_model->retornarTipoCambio();
			$factura->ClienteFactura=$cabeceraSiat['nombreRazonSocial'];
			$factura->ClienteNit=$cabeceraSiat['numeroDocumento'];
			$factura->codigoEmision = $configuracion['codigoEmision'];
			$factura->articulos = $items;

			$facturaSiat = new stdclass();
			$facturaSiat->cuf = $cabeceraSiat['cuf'];
			$facturaSiat->cufd = $cabeceraSiat['cufd'];
			$facturaSiat->codigoSucursal = $cabeceraSiat['codigoSucursal'];
			$facturaSiat->codigoPuntoVenta = $cabeceraSiat['codigoPuntoVenta'];
			$facturaSiat->fechaEmision = $cabeceraSiat['fechaEmision'];
			$facturaSiat->leyenda = $cabeceraSiat['leyenda'];
			$facturaSiat->pedido = isset($cabecera['clientePedido']) ? $cabecera['clientePedido'] : '';
			$facturaSiat->cafc = isset($cabeceraSiat['cafc']) ? $cabeceraSiat['cafc'] : '';
			$facturaSiat->numeroTarjeta = isset($cabeceraSiat['numeroTarjeta']) ? $cabeceraSiat['numeroTarjeta'] : '';
			$facturaSiat->codigoMetodoPago = isset($cabeceraSiat['codigoMetodoPago']) ? $cabeceraSiat['codigoMetodoPago'] : '';
			$facturaSiat->codigoRecepcion = $cabeceraSiat['codigoRecepcion'];
			$facturaSiat->codigoTipoDocumentoIdentidad = $cabeceraSiat['codigoTipoDocumentoIdentidad'];
			$facturaSiat->numeroDocumento = $cabeceraSiat['numeroDocumento'];
			$facturaSiat->complemento = isset($cabeceraSiat['complemento']) ? $cabeceraSiat['complemento'] : '';
			$facturaSiat->nombreRazonSocial = $cabeceraSiat['nombreRazonSocial'];

			$facturaSiat->montoTotal = $cabeceraSiat['montoTotal'];
			$facturaSiat->montoTotalMoneda = $cabeceraSiat['montoTotalMoneda'];
			$facturaSiat->tipoCambio = $cabeceraSiat['tipoCambio'];


		
			$idFactura = $this->Emitir_model->storeFacturaSiat($factura, $facturaSiat);

			echo json_encode($idFactura); 
			return false; 
	}
	public function consultaFacturasSiat()
	{
		//$this->accesoCheck(72);
		$this->titles('SiatFacturas','Siat Consulta Facturas','Facturas');
			
		$this->datos['foot_script'][]=base_url('assets/hergo/facturacion/consultaFacturasSiat.js') .'?'.rand();
		$this->setView('siat/facturas/consultaFacturasSiat');
	}
	public function getFacturasSiat()
	{
		if(!$this->input->is_ajax_request())
		die("PAGINA NO ENCONTRADA");
 
		$ini=$this->security->xss_clean($this->input->post("ini"));
		$fin=$this->security->xss_clean($this->input->post("fin"));
		$alm= $this->input->post("alm");
		$data=$this->Emitir_model->getFacturasSiat($ini, $fin, $alm); 
		echo json_encode($data);
	}
	public function getMotivosAnulacion()
	{
		$data=$this->Emitir_model->getMotivosAnulacion(); 
		echo json_encode($data);
	}
	public function getMotivosEvento()
	{
		$data=$this->Emitir_model->getMotivosEvento(); 
		echo json_encode($data);
	}
	public function anularFactura()
	{

		$data = $this->input->post();
		$factura_id  = $data['data']['factura_id'];
		$detalle  = isset($data['data']['detalleAnulacion']) ? $data['data']['detalleAnulacion'] : '';
		$almacen_id  = $data['data']['almacen_id'];
		$user_id  = $data['data']['user_id'];

		$res = $this->Emitir_model->anularFactura($factura_id, $detalle, $almacen_id, $user_id);

		echo json_encode($res); 
		return false;
	}
	public function consultaFacturasNoEnviadasSiat()
	{
		//$this->accesoCheck(73);
		$this->titles('SiatFacturasNoEnviadas','Siat Consulta No Enviadas Facturas','SIAT');
			
		$this->datos['foot_script'][]=base_url('assets/hergo/facturacion/consultaFacturasSiatNoEnviadas.js') .'?'.rand();
		$this->setView('siat/facturas/consultaFacturasSiatNoEnviadas');
	}
	public function getFacturasSiatNoEnviadas()
	{
		if(!$this->input->is_ajax_request())
		die("PAGINA NO ENCONTRADA");
 
		$ini=$this->security->xss_clean($this->input->post("ini"));
		$fin=$this->security->xss_clean($this->input->post("fin"));
		$alm= $this->input->post("alm");
		$data=$this->Emitir_model->getFacturasSiatNoEnviadas($ini, $fin, $alm); 
		echo json_encode($data);
	}
	public function updateCodigoRecepcion()
	{
		$cuf = $this->input->post()['cuf'];
		$codigoRecepcion = $this->input->post()['codigoRecepcion'];
		$res = $this->Emitir_model->updateCodigoRecepcion($cuf, $codigoRecepcion); 
		echo json_encode($res);
	}
	public function getCufdFecha()
	{
		$fechaHora = $this->input->post()['fechaHora'];
		$res = $this->Emitir_model->getCufdFecha($fechaHora); 
		echo json_encode($res);
	}
	public function dataEvento()
	{
		$cufs = $this->Emitir_model->getCufs();
		/* echo json_encode(count($cufs));
		return; */
		if (count($cufs)>0) {
			$inicio = $this->Emitir_model->getInicioEvento();
			$fin = $this->Emitir_model->getFinEvento();
			$cufdEvento = '';
			if ($inicio->cufd == $fin->cufd) {
				$cufdEvento = $inicio->cufd;
			}
			$res =[
				'cuis'=> $inicio->cuis,
				'codigoSucursal'=> $inicio->codigoSucursal,
				'codigoPuntoVenta'=> $inicio->codigoPuntoVenta,
				'codigoMotivoEvento' => '2',
				'cufdEvento' => $cufdEvento,
				'descripcion' => 'INACCESIBILIDAD AL SERVICIO WEB DE LA ADMINISTRACIÓN TRIBUTARIA',
				'fechaHoraInicioEvento' => $inicio->inicio,
				'fechaHoraFinEvento' => $fin->fin,
				'fechaEvento' => $inicio->fechaEvento,
				'cufs' => $cufs,
			];
			echo json_encode($res); 
		} else {
			echo(0);
		}		
	}

}