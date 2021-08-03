<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/codigoControl/CodigoControlV7.php";
class Facturas extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Ingresos_model");
		$this->load->model("Egresos_model");
		$this->load->model("Cliente_model");
		$this->load->model("Facturacion_model");
		$this->load->model("DatosFactura_model");
		$this->load->model("FacturaDetalle_model");
		$this->load->model("FacturaEgresos_model");
		$this->load->model("Almacen_model");
		$this->load->helper('cookie');

		$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/AllegedRC4.js');
		$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/Base64SIN.js');
		$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/ControlCode.js');
		$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/Verhoeff.js');
		$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/qrcode.min.js');

		$this->libAcc = new LibAcceso();
		$permisos = $this->libAcc->retornarSubMenus($_SESSION['accesoMenu']);
		$this->datos['permisoAnular'] = in_array(45, $permisos) ? 'true' : 'false';
	}
	
	public function index()
	{
		$this->accesoCheck(21);
		$this->titles('Facturas','Consultar','Facturas');
		$this->datos['foot_script'][]=base_url('assets/hergo/facturasConsulta.js') .'?'.rand();
		$this->datos['foot_script'][]=base_url('assets/hergo/vistaPreviaFacturacion/principal.js') .'?'.rand();
		$this->datos['almacen']=$this->Almacen_model->retornar_tabla("almacenes");
		$this->setView('facturas/facturas');
	}

	public function EmitirFactura()
	{
		$this->accesoCheck(22);
		$this->titles('EmitirFactura','Emitir','Facturas');
		$this->datos['foot_script'][]=base_url('assets/hergo/facturas.js') .'?'.rand();
		$this->datos['foot_script'][]=base_url('assets/hergo/vistaPreviaFacturacion/principal.js') .'?'.rand();
		$this->datos['almacen']=$this->Almacen_model->retornar_tabla("almacenes");
		$this->setView('facturas/emitirFactura');
		/*borrar cookie facturacion*/
		if( isset( $_COOKIE['factsistemhergo'] ) ) {			     
				delete_cookie("factsistemhergo");
		}
	}
	public function MostrarTablaConsultaFacturacion()
	{
		
		if($this->input->is_ajax_request() && $this->input->post('ini')&& $this->input->post('fin'))
        {
        	$ini = addslashes($this->security->xss_clean($this->input->post('ini')));
        	$fin = addslashes($this->security->xss_clean($this->input->post('fin')));
			$alm = addslashes($this->security->xss_clean($this->input->post('alm')));
			$alm = $this->datos['grupsOfUser'] == 'Nacional' ? $alm : $this->datos['id_Almacen_actual'];
        	$tipo = addslashes($this->security->xss_clean($this->input->post('tipo')));
			
			$tabla=$this->FacturaEgresos_model->Listar($ini,$fin,$alm,$tipo);
			
			echo json_encode($tabla);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function MostrarTablaFacturacion()
	{
		if($this->input->is_ajax_request() && $this->input->post('ini')&& $this->input->post('fin'))
        {
        	$ini = addslashes($this->security->xss_clean($this->input->post('ini')));
        	$fin = addslashes($this->security->xss_clean($this->input->post('fin')));
        	$alm = addslashes($this->security->xss_clean($this->input->post('alm')));
        	$tipo = addslashes($this->security->xss_clean($this->input->post('tipo')));
			
			$tabla=$this->Egresos_model->ListarparaFacturacion($ini,$fin,$alm,$tipo);
			
			echo json_encode($tabla);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

	public function retornarTabla2()
	{
		
		
		if($this->input->is_ajax_request() && $this->input->post('idegreso') )
        {
        	$idegreso= addslashes($this->security->xss_clean($this->input->post('idegreso')));
        	$egresoDetalle=FALSE;
			$egresoDetalle=$this->Egresos_model->mostrarDetalleFacturas($idegreso)->result();
			$datosEgreso=$this->Egresos_model->retornarEgreso($idegreso);
			if($datosEgreso->moneda==2)
			{
				for ($i=0; $i < count($egresoDetalle); $i++) 
				{ 
					$egresoDetalle[$i]->punitario=$egresoDetalle[$i]->punitario/$datosEgreso->tipocambio;
					$egresoDetalle[$i]->total=$egresoDetalle[$i]->total/$datosEgreso->tipocambio;
					
				}
			}
			$mensaje="Datos cargados correctamente";
			$obj2=new stdclass();
			$obj2->detalle=$egresoDetalle;
			$obj2->mensaje=$mensaje;
			$obj2->alm=$datosEgreso->almacen;
			$obj2->moneda=$datosEgreso->moneda;
			echo json_encode($obj2);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function eliminarElementoTabla3()
	{		
		if($this->input->is_ajax_request() && $this->input->post('idegresoDetalle'))
        {
        	$idegresoDetalle= addslashes($this->security->xss_clean($this->input->post('idegresoDetalle')));
        	if( isset( $_COOKIE['factsistemhergo'] ) ) // existe cookies?
	        {	
        		$cookie=json_decode((get_cookie('factsistemhergo'))); 
        		$egresosnew = array();
        		foreach ($cookie->egresos as $fila) {
        			if($fila!=$idegresoDetalle)
        				array_push($egresosnew,$fila);
        		}
        		$cookie->egresos=$egresosnew;
        	}
        	$cookienew=json_encode($cookie);
        	set_cookie('factsistemhergo',$cookienew,'3600'); 
        	echo json_encode("");
        }
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function eliminarTodosElementoTabla3()
	{
		if( isset( $_COOKIE['factsistemhergo'] ) ) {			     
			     delete_cookie("factsistemhergo");
			}
			echo json_encode("");
	}
	public function retornarTabla3()
	{			
		if($this->input->is_ajax_request() && $this->input->post('idegresoDetalle') )
        {
        	$idegresoDetalle= addslashes($this->security->xss_clean($this->input->post('idegresoDetalle')));
        	$idegreso= addslashes($this->security->xss_clean($this->input->post('idegreso')));
        	$egresoDetalle=FALSE;
        	/***Retornar idcliente***/
			$datosEgreso=$this->Egresos_model->mostrarEgresos($idegreso);//para obtener el cliente
        	$fila=$datosEgreso->row();
        	$idcliente=$fila->idcliente; 
        	$cliente=$fila->nombreCliente; 
        	$clienteNit=$fila->documento;
        	$clientePedido=$fila->clientePedido;
        	
        	/************************/
	        if( isset( $_COOKIE['factsistemhergo'] ) ) // existe cookies?
	        {	
	        	//$cookie=json_decode($this->desencriptar(get_cookie('factsistemhergo')));  
	        	$cookie=json_decode((get_cookie('factsistemhergo')));  
							
	        	if($cookie->cliente==$idcliente)// es el mismo cliente que ya se agrego en la tabla?
	        	{
	        		if(!in_array($idegresoDetalle, $cookie->egresos))
	        		{
	        			//no existe en el array entonces agregarlo	        			
	        			array_push($cookie->egresos,$idegresoDetalle);
	        			$egresoDetalle=$this->Egresos_model->ObtenerDetalle($idegresoDetalle)->result();
	        			$mensaje="Registro agregado correctamente";
	        			
	        			//return $egresoDetalle;
	        		}
	        		else
	        		{
	        			//existe entonces no se puede agregar el detalle	        			
	        			$egresoDetalle=FALSE;//return FALSE;
	        			$mensaje="Ya se agrego este registro";
	        			$cook=$cookie;
	        		}	        		
	        	}
	        	else
	        	{
	        		//es otro cliente no hacer nada	        		
	        		$egresoDetalle=FALSE;//return FALSE;
	        		$mensaje="No se pueden agregar registros de otro cliente";
	        		
	        	}
			}	
			else
			{
				//no existe cookie entonces crear nuevo
				//si no existe la tabla 2 esta vacia y no se selecciono ningun egreso, 
				$egresoDetalle=$this->Egresos_model->ObtenerDetalle($idegresoDetalle)->result();
				$mensaje="Se agrego el primer registro en la tabla correctamente";

				$obj= new stdclass();
				$obj->egresos= array($idegresoDetalle);//solo agrega el unico egreso al ser el primero
				$obj->cliente=$idcliente;


				$cookie=$obj;
			}
			$cookienew=json_encode($cookie);
			//$cookienew=$this->encriptar($cookienew);
			set_cookie('factsistemhergo',$cookienew,'3600'); 	
		
			$obj2=new stdclass();
			$obj2->detalle=$egresoDetalle;
			$obj2->mensaje=$mensaje;
			$obj2->cliente=$cliente;
			$obj2->clienteNit=$clienteNit;
			$obj2->clientePedido=$clientePedido;
			
			echo json_encode($obj2);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function retornarTabla3Cliente()
	{			
		if($this->input->is_ajax_request() && $this->input->post('idegresoDetalle') )
        {
        	$idegresoDetalle= addslashes($this->security->xss_clean($this->input->post('idegresoDetalle')));
        	$idegreso= addslashes($this->security->xss_clean($this->input->post('idegreso')));
        	$egresoDetalle=FALSE;
        	/***Retornar idcliente***/
			$datosEgreso=$this->Egresos_model->mostrarEgresos($idegreso);//para obtener el cliente
        	$fila=$datosEgreso->row();
        	$idcliente=$fila->idcliente; 
        	$cliente=$fila->nombreCliente; 
        	$clienteNit=$fila->documento;
        	$clientePedido=$fila->clientePedido;
        	
        
		
			$obj2=new stdclass();
			$obj2->detalle=$egresoDetalle;
			//$obj2->mensaje=$mensaje;
			$obj2->cliente=$cliente;
			$obj2->clienteNit=$clienteNit;
			$obj2->clientePedido=$clientePedido;
			$obj2->idCliente=$idcliente;
			
			echo json_encode($obj2);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function retornarTabla3Array()
	{			
		if($this->input->is_ajax_request() && $this->input->post('rows') )
        {

        	$datos= ($this->security->xss_clean($this->input->post('rows')));
        	$datos=json_decode($datos);  
        	$datosRetornar=array();
			/******verificamos si existe cookie*****/
	        	if( isset( $_COOKIE['factsistemhergo'] ) ) // existe cookies?
	        	{
	        		//$cookie=json_decode($this->desencriptar(get_cookie('factsistemhergo')));  
		        	$cookie=json_decode((get_cookie('factsistemhergo')));  
	        	}
	        	else
	        	{
	        		$cookie= new stdclass();
					$cookie->egresos= array();//solo agrega el unico egreso al ser el primero
					$cookie->cliente=0;
	        	}
	        	
	        	/************************/
        	foreach ($datos as $fila) 
        	{
        		$idegresoDetalle= $fila->idingdetalle;
        		$idegreso= $fila->idegreso;
        		$egresoDetalle=FALSE;
	        	/***Retornar idcliente***/
				$datosEgreso=$this->Egresos_model->mostrarEgresos($idegreso);//para obtener el cliente
	        	$fila=$datosEgreso->row();
	        	$idcliente=$fila->idcliente; 
	        	$cliente=$fila->nombreCliente; 
	        	$clienteNit=$fila->documento;
	        	$clientePedido=$fila->clientePedido;
	        	$idCliente=$fila->idcliente;
	        	
		      
	        	//$cookie=json_decode($this->desencriptar(get_cookie('factsistemhergo')));  
	        	//$cookie=json_decode((get_cookie('factsistemhergo')));  
				
				if($cookie->cliente==$idcliente || $cookie->cliente==0)// es el mismo cliente que ya se agrego en la tabla?
	        	{
	        		if(!in_array($idegresoDetalle, $cookie->egresos))
	        		{
	        			//no existe en el array entonces agregarlo	        			
	        			array_push($cookie->egresos,$idegresoDetalle);
	        			$egresoDetalle=$this->Egresos_model->ObtenerDetalle($idegresoDetalle)->result();
	        			$mensaje="Registro agregado correctamente";
	        			$cookie->cliente=$idcliente;
	        		//	var_dump($datosRetornar);
	        		//	var_dump($egresoDetalle);
	        			array_push($datosRetornar,$egresoDetalle);
	        			//return $egresoDetalle;
	        		}
	        		else
	        		{
	        			//existe entonces no se puede agregar el detalle	        			
	        			//$egresoDetalle=FALSE;//return FALSE;
	        			$mensaje="Algunos registros ya se agregaron";
	        			//$datosRetornar=$egresoDetalle;
	        		}	        		
	        	}
	        	else
	        	{
	        		//es otro cliente no hacer nada	        		
	        		$egresoDetalle=FALSE;//return FALSE;
	        		$mensaje="No se pueden agregar registros de otro cliente";
	        		$datosRetornar=$egresoDetalle;
	        	}	
        	}
        	$cookienew=json_encode($cookie);
				//$cookienew=$this->encriptar($cookienew);
        	
			set_cookie('factsistemhergo',$cookienew,'3600'); 
        	        		
		
			$obj2=new stdclass();
			$obj2->detalle=$datosRetornar;
			$obj2->mensaje=$mensaje;
			$obj2->cliente=$cliente;
			$obj2->clienteNit=$clienteNit;
			$obj2->clientePedido=$clientePedido;
			$obj2->idCliente=$idCliente;
			//$obj2->array=$datosRetornar;
			
			echo json_encode($obj2);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function tipoCambio()
	{
		//$tipoCambio=$this->Egresos_model->retornarValorTipoCambio();
		$fechaActual = $this->security->xss_clean($this->input->post('fechaActual'));
		$tipoCambio = $this->Ingresos_model->getTipoCambio($fechaActual);
		if ($tipoCambio) {
			$idAlmacenUsuario = $this->session->userdata['datosAlmacen']->idalmacen;
			$obj2=new stdclass();
			$obj2->tipoCambio=$tipoCambio->tipocambio;	
			$obj2->hoy = $tipoCambio->fecha;
			$obj2->idAlmacenUsuario = $idAlmacenUsuario;
			echo json_encode($obj2);
		} else {
			echo json_encode(false);
		}
		
	}
	public function guardarFactura()
	{
		if($this->input->is_ajax_request())
        {
        	$tipoFacturacion= ($this->security->xss_clean($this->input->post('tipoFacturacion')));
			$fechaFac=addslashes($this->security->xss_clean($this->input->post('fechaFac')));
			$idCliente = ($this->security->xss_clean($this->input->post('idCliente')));
			$cliente=$this->Cliente_model->obtenerCliente($idCliente);


        	//$idAlmacen= ($this->security->xss_clean($this->input->post('idAlmacen')));	//para seleccionar almacen si es administrador
        	$idAlmacen=$this->session->userdata('idalmacen');//si no es usuario administrador solo guarda segun su almacen asignado
        	

        	$datosFactura=$this->DatosFactura_model->obtenerUltimoLote2($idAlmacen, $tipoFacturacion);
			$ultimaFactura=$this->Facturacion_model->obtenerUltimoRegistro($idAlmacen,$datosFactura->idDatosFactura);
		/*	print_r($ultimaFactura);
			die();*/
        	/*VALIDAR FECHA*/
        	if(!$this->validarFechaLimite($datosFactura->fechaLimite, $fechaFac))
			{
				echo 0;
				die();
			}
			/*VALIDAR LIMITE FACTURA*/
			if(!$this->validarLimiteFactura($datosFactura->hasta, $ultimaFactura))
			{
				echo 0;
				die();
			}
			if($datosFactura->enUso==0)
			{				
				$numeroFactura=$datosFactura->desde;
				$this->DatosFactura_model->actualizarEnUso($datosFactura->idDatosFactura);
			}
			else
			{
				if($ultimaFactura)
					$numeroFactura=intval($ultimaFactura->nFactura)+1;
				else
					$numeroFactura=1;
			}
        	$factura=new stdclass();
        	$factura->lote=$datosFactura->idDatosFactura;
        	$factura->almacen=$idAlmacen;
        	$factura->nFactura=$numeroFactura;
			$factura->fechaFac= date('Y-m-d',strtotime($fechaFac));
        	$factura->cliente=$cliente->idCliente;
        	$factura->moneda= addslashes($this->security->xss_clean($this->input->post('moneda')));
        	$factura->total= addslashes($this->security->xss_clean($this->input->post('total')));
        	$factura->glosa=addslashes($this->security->xss_clean($this->input->post('observaciones')));;
        	$factura->pagada=0;
        	$factura->anulada=0;
        	$factura->codigoControl=addslashes($this->security->xss_clean($this->input->post('codigoControl')));;;
        	$factura->qr="null";
        	$factura->tipoCambio=$this->Egresos_model->retornarTipoCambio();
        	$factura->ClienteFactura=$cliente->nombreCliente;
        	$factura->ClienteNit=$cliente->documento;
        	$factura->autor=$this->session->userdata('user_id');
        	$factura->fecha=date('Y-m-d H:i:s');        	
        	$tabla= ($this->security->xss_clean($this->input->post('tabla')));
        	$tabla=json_decode($tabla);
        	//var_dump($tabla);
			$idFactura=$this->Facturacion_model->guardar($factura);


        	//$idFactura=1;

        	if($idFactura>0) //se registro correctamente => almacenar la tabla
        	{
				$detalle=array();
				$facturaEgreso=array();
	        	foreach ($tabla as $fila) 
	        	{

	        		$idArticulo=$this->Egresos_model->retornar_datosArticulo($fila->CodigoArticulo);
	        		/**********************PReparar tabla detalle*************/
	        		$registro = array(
	        			'idFactura' => $idFactura,
	        			'articulo'=>$idArticulo,
	        			'moneda'=>$factura->moneda,
	        			'facturaCantidad'=>$fila->cantidadReal,
	        			'facturaPUnitario'=>$fila->punitario,
	        			'nMovimiento'=>"",
	        			'movTipo'=>"",
	        			'ArticuloNombre'=>$fila->Descripcion,
	        			'ArticuloCodigo'=>$fila->CodigoArticulo,
	        			'idEgresoDetalle'=>$fila->idEgreDetalle,
	        			 );	
	        		array_push($detalle, $registro);   
	        		$factura_egresoRegistro=array(
	        			'idegresos'=>$fila->idegreso,
	        			'idFactura'=>$idFactura,
	        			);	
	        		array_push($facturaEgreso, $factura_egresoRegistro);
	        		$this->Egresos_model->actualizarCantFact($fila->idEgreDetalle,$fila->cantidadReal);
	        		$this->actualizarEstado($fila->idegreso);
	        	}
	        	$this->FacturaDetalle_model->guardar($detalle);
	        	$this->FacturaEgresos_model->guardarArray($facturaEgreso);

	        	echo $idFactura;
        	}
        	else
        	{
        		echo 0;
        	}

        	//var_dump($factura);

        	
        }
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

	public function storeFactura()
	{
		if($this->input->is_ajax_request())
        {
			$idAlmacen=$this->session->userdata('idalmacen');		
        	$tipoFacturacion = ($this->security->xss_clean($this->input->post('tipoFacturacion')));
			$fechaFactura = ($this->security->xss_clean($this->input->post('fechaFac')));
			$fechaFactura = date('Y-m-d',strtotime($fechaFactura));
			$numFacManual = ($this->security->xss_clean($this->input->post('numFacManual')));
			$idCliente = ($this->security->xss_clean($this->input->post('idCliente')));
			$moneda = ($this->security->xss_clean($this->input->post('moneda')));
			$total = ($this->security->xss_clean($this->input->post('total')));
			$observaciones = ($this->security->xss_clean($this->input->post('observaciones')));
			$tipoPago = ($this->security->xss_clean($this->input->post('tipoPago')));
			$articulos = ($this->security->xss_clean($this->input->post('tabla')));
			$articulos = (json_decode($articulos));
			$errores=[];
			$datosFactura=$this->DatosFactura_model->obtenerUltimoLote2($idAlmacen, $tipoFacturacion);
			$numero_autorizacion = $datosFactura->autorizacion;
			$clave = $datosFactura->llaveDosificacion;

			$ultimaFactura=$this->Facturacion_model->obtenerUltimoRegistro($idAlmacen,$datosFactura->idDatosFactura);
			$cliente=$this->Cliente_model->obtenerCliente($idCliente);

			foreach ($articulos as $item) {
				$porFacturar = $this->Facturacion_model->validarFacturada($item->idEgreDetalle);
				if ($porFacturar->porFacturar == 0 ) {
					array_push($errores, 'Ya se facturó');
				}
			}
			if(!$this->validarFechaLimite($datosFactura->fechaLimite, $fechaFactura))
			{
				array_push($errores, 'Error Limite emision');
			}
			if(!$this->validarLimiteFactura($datosFactura->hasta, $ultimaFactura))
			{
				array_push($errores, 'Error limite de la factura');
			}
			if ($datosFactura->manual == 1) {
				if(!$this->validarNumFacManual($datosFactura->desde,$datosFactura->hasta, $numFacManual))
				{
					array_push($errores, 'Error número de factura manual');
				}
				$numeroFactura = $numFacManual;
			} else {
				if ($ultimaFactura) {
					$numeroFactura = intval($ultimaFactura->nFactura)+1;
				} else {
					$numeroFactura = $datosFactura->desde;
				}
			}

			$numero_factura = $numeroFactura;
			$nit_cliente = trim($cliente->documento);
			$fecha_compra = date('Ymd',strtotime($fechaFactura));
			$monto_compra = round($total,0);
			$codigoControlGenerado = CodigoControlV7::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);

			$factura = new stdclass();
			$factura->lote = $datosFactura->idDatosFactura;
			$factura->almacen = $idAlmacen;
			$factura->nFactura = $numeroFactura;
			$factura->fechaFac = $fechaFactura;
			$factura->cliente = $idCliente;
			$factura->moneda = $moneda;
			$factura->total = $total;
			$factura->glosa = $observaciones;
			$factura->tipoPago = $tipoPago;
			$factura->codigoControl =  ($datosFactura->manual == 1) ? '' : $codigoControlGenerado;
			$factura->autor=$this->session->userdata('user_id');
        	//$factura->fecha=date('Y-m-d H:i:s'); 
			$factura->tipoCambio=$this->Egresos_model->retornarTipoCambio();
			$factura->ClienteFactura=$cliente->nombreCliente;
			$factura->ClienteNit=$cliente->documento;
			$factura->articulos = $articulos;
			
			if ($errores == []) {
				$idFactura = $this->Facturacion_model->storeFactura($factura);
				$res = new stdclass();
				$res->status = 'ok';
				$res->idFactura = $idFactura;
				echo json_encode($res); 
			} else {
				$res = new stdclass();
				$res->status = 'error';
				$res->errors = $errores;
				echo json_encode($res); 
			}
        }
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function sessionver()
	{
		var_dump($this->session);
	}
	public function actualizarEstado($idEgreso)//cambia el estado si esta pendiente o facturado
	{
		$estado=0;
		$cantidad=$this->Egresos_model->evaluarFacturadoTotal($idEgreso); //si es 0 facturado total si no parcial
		if(count($cantidad)==0)//Facturado
			$estado=1;
		else
			$estado=2;
		$this->Egresos_model->actualizarEstado($idEgreso,$estado);
		return $estado;
	}
	public function mostrarDetalleFactura()
	{
		echo json_encode($this->mostrarDetalleFacturaFunction());
	}
	public function mostrarDetalleFacturaFunction()
	{
		if($this->input->is_ajax_request())
        {
        	$idFactura= addslashes($this->security->xss_clean($this->input->post('idFactura')));
			$obj=new stdclass();
			$obj->data1=$this->Facturacion_model->obtenerFactura($idFactura);
			$obj->data2=$this->Facturacion_model->obtenerDetalleFactura($idFactura);
			$obj->data3=$this->Facturacion_model->obtenerPedido($idFactura);
			return($obj);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function anularFactura()
	{
		$this->libacceso->acceso(45);
		if($this->input->is_ajax_request())
        {

			$idFactura= addslashes($this->security->xss_clean($this->input->post('idFactura')));
			$msjAnular= addslashes($this->security->xss_clean($this->input->post('msj')));

			$factura = $this->Facturacion_model->obtenerFactura($idFactura);
			$pagada = $factura->pagada;
			$idAlmacen = $factura->idAlmacen;
			if ($pagada == 0) {
				$anularFactura = $this->Facturacion_model->anularFactura($idFactura, $msjAnular, $idAlmacen);
			} else {
				$anularFactura = false;
			}

			echo json_encode($anularFactura);


		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	private function actualizarRestarCantFact($idFactura)
	{
		$obj=new stdclass();		
		$facturaDetalle=$this->Facturacion_model->obtenerDetalleFactura($idFactura);		
		foreach ($facturaDetalle as $fila) 
		{
			
			if($fila["idEgresoDetalle"]!=null)
				$this->Egresos_model->actualizarRestarCantFact($fila["idEgresoDetalle"],$fila["facturaCantidad"]);		
		}		
		
	}
	public function consultarDatosFactura()
	{
		if($this->input->is_ajax_request())
        {
			$errores=array();
			$obj=new stdclass();
			$obj->response=true;
        	$idAlmacen = ($this->security->xss_clean($this->input->post('idAlmacen')));			
        	$tipoFacturacion = ($this->security->xss_clean($this->input->post('tipoFacturacion')));
			$fechaFactura = ($this->security->xss_clean($this->input->post('fechaFactura')));
			$numFacManual = ($this->security->xss_clean($this->input->post('numFacManual')));
			$idCliente = ($this->security->xss_clean($this->input->post('idCliente')));
			$montoBs = ($this->security->xss_clean($this->input->post('montoBs')));

			$resultado=$this->DatosFactura_model->obtenerUltimoLote2($idAlmacen, $tipoFacturacion);
			if(!$resultado)
			{
				$obj->response=false;
				$obj->resultado=null;
				array_push($errores, "No se tiene un dosificación activa para este almacen");
				$obj->error=$errores;
				echo json_encode($obj);
				die();
			}
			$ultimaFactura=$this->Facturacion_model->obtenerUltimoRegistro($idAlmacen,$resultado->idDatosFactura);
			
			
			if(!$resultado)
			{
				$obj->response=false;
				$obj->resultado=null;
				array_push($errores, "No se tiene un lote para este almacen");
			}
			//$obj->detalle=$resultado;
			if(!$this->validarFechaLimite($resultado->fechaLimite, $fechaFactura))
			{
				$obj->response=false;
				$obj->resultado=null;
				array_push($errores, "Error fecha limite de emision");
			}
			if(!$this->validarLimiteFactura($resultado->hasta, $ultimaFactura))
			{
				$obj->response=false;
				$obj->resultado=null;
				array_push($errores, "Error limite de facturas");
			}
			if ($resultado->manual == 1) {
				if(!$this->validarNumFacManual($resultado->desde,$resultado->hasta, $numFacManual))
				{
					$obj->response=false;
					$obj->resultado=null;
					array_push($errores, "Número de Factura manual NO ESTA EN RANGO");
				}
				if ($this->Facturacion_model->existeNumFactura($idAlmacen, $resultado->idDatosFactura, $numFacManual)) {
					$facMan = $this->Facturacion_model->existeNumFactura($idAlmacen, $resultado->idDatosFactura, $numFacManual);
					$obj->response=false;
					$obj->resultado=null;
					array_push($errores, "Número de Factura ". $facMan->nFactura . " fue emitida el ". date("d/m/Y", strtotime($facMan->fechaFac)));
				}
				$obj->nfac = $numFacManual;
			} else {
				if ($ultimaFactura) {
					$obj->nfac=intval($ultimaFactura->nFactura)+1;
				} else {
					$obj->nfac=$resultado->desde;
				}
				
			}
			$cliente=$this->Cliente_model->obtenerCliente($idCliente);
			$fechaFactura = date('Ymd',strtotime($fechaFactura));
			$montoBs = round($montoBs,0);
			$codigoControlGenerado = CodigoControlV7::generar($resultado->autorizacion, $obj->nfac, $cliente->documento, $fechaFactura, $montoBs,$resultado->llaveDosificacion);
			$obj->codigoControl=$codigoControlGenerado;
			$obj->nit=$cliente->documento;
			$obj->glosa01 = $resultado->glosa01;
			$obj->glosa02 = $resultado->glosa02;
			$obj->glosa03 = $resultado->glosa03;
			$obj->autorizacion = $resultado->autorizacion;
			$obj->fechaLimite = $resultado->fechaLimite;
			$obj->error=$errores;
			/*$obj = new stdclass();
			$obj->almacen = $idAlmacen;
			$obj->tipoFacturacion = $tipoFacturacion;
			$obj->fecha = $fechaFactura;
			$obj->numFacManual = $numFacManual;
			$obj->dosificacion = $resultado;
			$obj->ultimaFactura = $ultimaFactura;*/
			echo json_encode($obj);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	private function validarNumFacManual($desde, $hasta, $actual) 
	{
	    $nFin= intval($hasta);
		$nIni = intval($desde);
		$nActual = intval($actual);
	    return (($nActual <= $nFin && $nActual>=$nIni));
	}
	private function validarFechaLimite($fechalimite, $fechaactual) 
	{
	    $flimite = strtotime($fechalimite);
	    $factual = strtotime($fechaactual);
	    return (($factual <= $flimite));
	}
	private function validarLimiteFactura($hasta,$ultimaFactura)
	{
		if(!$ultimaFactura)
			return true;
		$actual=intval($ultimaFactura->nFactura)+1;
			return($actual<=$hasta);
	}
	public function mostrarDatosDetallesFactura()
	{
		$obj=new stdClass();
		$obj->datosFactura=$this->mostrarDatosFacturaFunction();
		$obj->detalleFactura=$this->mostrarDetalleFacturaFunction();
		$obj->response=true;
		echo json_encode($obj);
	}
	public function mostrarDatosFactura()
	{
		echo json_encode($this->mostrarDatosFacturaFunction());
	}
	public function mostrarDatosFacturaFunction()
	{
		if($this->input->is_ajax_request())
        {
        	$nFact= ($this->security->xss_clean($this->input->post('nFactura')));
        	$lote= ($this->security->xss_clean($this->input->post('lote')));
			$resultado=$this->DatosFactura_model->obtenerLote($lote);
			$errores=array();
			$obj=new stdclass();
			$obj->detalle=$resultado;
			$obj->nfac=$nFact;
			$obj->response=true;
			return($obj);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function datosAlmacen()
	{
		if($this->input->is_ajax_request())
        {
			$almacen=$_SESSION['datosAlmacen']; 
			echo json_encode($almacen);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function modificarFacturaManual()
	{
		$this->libacceso->acceso(47);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Facturas";
			$this->datos['opcion']="Modificar Factura";
			$this->datos['titulo']="Modificar Factura";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			$this->datos['foot_script']= $this->foot_script;

			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			
			//$this->datos['cabeceras_script'][]=base_url('assets/hergo/facturasConsulta.js');
			$this->datos['foot_script'][]=base_url('assets/hergo/updateFacturasManual.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/NumeroALetras.js');
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

 			/*************CODIGO CONTROL***************/
			$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/AllegedRC4.js');
			$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/Base64SIN.js');
			$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/ControlCode.js');
			$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/Verhoeff.js');
			/***********************************/
			/*************CODIGO QR***************/
			$this->datos['cabeceras_script'][]=base_url('assets/codigoControl/qrcode.min.js');
            
			$this->datos['almacen']=$this->Almacen_model->retornar_tabla("almacenes");
			$this->datos['lotes']=$this->FacturaEgresos_model->showLotes();
            //$this->datos['tipoingreso']=$this->ingresos_model->retornar_tablaMovimiento("-");

			//$this->datos['ingresos']=$this->ingresos_model->mostrarIngresos();

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('facturas/updateFacturaManual.php',$this->datos);
			$this->load->view('facturas/vistaPrevia.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);			
			$this->load->view('plantilla/footerscript.php',$this->datos);
	}
	public function showFactuasManuales()
	{
		if($this->input->is_ajax_request())
        {
        	$alm = addslashes($this->security->xss_clean($this->input->post('alm')));
			$lote = addslashes($this->security->xss_clean($this->input->post('lote')));
			
			
			$res=$this->FacturaEgresos_model->showFacturasManuales($alm,$lote);
			
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function updateFacturaManual()
	{
		if($this->input->is_ajax_request())
        {
			$id = addslashes($this->security->xss_clean($this->input->post('id')));
        	$num = addslashes($this->security->xss_clean($this->input->post('newNumFac')));
			$fecha = addslashes($this->security->xss_clean($this->input->post('newFechaFac')));
			$fechaLimite = addslashes($this->security->xss_clean($this->input->post('fechaLimite')));
			$desde = addslashes($this->security->xss_clean($this->input->post('desde')));
			$hasta = addslashes($this->security->xss_clean($this->input->post('hasta')));

			if(!$this->validarFechaLimite($fechaLimite, $fecha))
			{
				echo 'Error Limite emision';
				die();
			}
			if(!$this->validarNumFacManual($desde,$hasta, $num))
			{
				echo 'Error número de factura fuera de rango';
				die();
			}
			
			$data = array(
				'nFactura' => $num,
				'fechaFac' => $fecha,
			);
			$res=$this->FacturaEgresos_model->updateFacturaManual($id, $data);
			
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}


