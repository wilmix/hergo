<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Egresos extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model("Ingresos_model");
		$this->load->model("Egresos_model");
		$this->load->model("Cliente_model");

	}
	
	public function index()
	{
		$this->accesoCheck(15);
		$this->titles('Egresos','Consultas Egresos','Egresos',);
		$this->libacceso->acceso(15);

		$this->datos['foot_script'][]=base_url('assets/hergo/egresos.js') .'?'.rand();

		$this->datos['tipoPrefer']="7";
        $this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
		$this->datos['tipoingreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");

		$this->setView('egresos/egresos');
			
			
	}
	public function notaentrega()
	{
		$this->accesoCheck(17);
		$this->titles('NotaEntrega','Nota de Entrega','Egresos');

		$this->datos['foot_script'][]=base_url('assets/hergo/notasEntrega.js') .'?'.rand();

		$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
		$this->datos['tegreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
		$this->datos['fecha']=date('Y-m-d');
		$this->datos['user']=$this->Egresos_model->retornar_tablaUsers("nombre");
		$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
		$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");
		$this->datos['idegreso']=7;
		
		$this->setView('egresos/notaentrega');

	}

	public function VentasCaja()
	{
		$this->accesoCheck(16);
		$this->titles('VentaCaja','Ventas Caja','Egresos');
		
		$this->datos['foot_script'][]=base_url('assets/hergo/notasEntrega.js') .'?'.rand();

		$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
        $this->datos['tegreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
		$this->datos['fecha']=date('Y-m-d');

	  	$this->datos['user']=$this->Egresos_model->retornar_tablaUsers("nombre");
	  	$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
		$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");
						
		$this->datos['idegreso']=6;

		$this->setView('egresos/notaentrega');

	}

	public function BajaProducto()
	{
		$this->accesoCheck(18);
		$this->titles('Baja','Baja de Producto','Egresos');
		
		$this->datos['foot_script'][]=base_url('assets/hergo/notasEntrega.js') .'?'.rand();
		
		$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
		$this->datos['tegreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
		//$this->datos['fecha']=date('Y-m-d');
		$this->datos['clientes']=$this->Ingresos_model->retornar_tabla("clientes");
		$this->datos['user']=$this->Egresos_model->retornar_tablaUsers("nombre");
		//$this->datos['auxIdCliente']=1801; //cliente hergo
		$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
		$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");
		$this->datos['idegreso']=9;

		$this->setView('egresos/notaentrega');
	}
		
	public function mostrarEgresos()
	{
		if($this->input->is_ajax_request())
        {
        	//$almacen=//retornar almacen al que corresponde el usuario!!!!!
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//fecha fin
        	$alm=$this->security->xss_clean($this->input->post("a"));//almacen
        	$tin=$this->security->xss_clean($this->input->post("ti"));//tipo de ingreso
        	
        	if($tin==8)
        		$res=$this->Egresos_model->mostrarEgresosTraspasos($id=null,$ini,$fin,$alm,$tin);
        	else        		
				$res=$this->Egresos_model->mostrarEgresos($id=null,$ini,$fin,$alm,$tin);
			$res=$res->result_array();
		//	$res2=$this->AgregarFActurasResultado($res);
			
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

	public function editarEgresos($id=null)
	{
		$this->accesoCheck(17);
		if(!$this->Egresos_model->puedeeditar($id)) redirect("error");

		$this->datos['foot_script'][]=base_url('assets/hergo/notasEntrega.js');

		$this->datos['dcab']=$this->mostrarEgresosEdicion($id);//datos cabecera
		$titulo = $this->datos['dcab']->tipomov . ' # '  . $this->datos['dcab']->n;
		$this->titles('Modificar',$titulo,'Modificar');
		//echo $titulo;die();
		$this->datos['detalle']=$this->mostrarDetalleEditar($id);
		if($this->datos['dcab']->idtipomov==8) redirect("error");
		if($this->datos['dcab']->idtipomov==9) $this->datos['auxIdCliente']=1801; //cliente hergo
		if($this->datos['dcab']->moneda==2)//si es dolares dividimos por el tipo de cambio
		{
			$tipodecambiovalor=$this->Ingresos_model->getTipoCambio($this->datos['dcab']->fechamov);            	
			$tipodecambiovalor=$tipodecambiovalor->tipocambio;
			
			for ($i=0; $i < count($this->datos['detalle']) ; $i++) { 
				$this->datos['detalle'][$i]["punitario"]=$this->datos['detalle'][$i]["punitario"]/$tipodecambiovalor;	            	
				$this->datos['detalle'][$i]["total"]=$this->datos['detalle'][$i]["total"]/$tipodecambiovalor;	  
			}		
			
		}
		/* echo "<pre>";
			print_r($this->datos['dcab']);
		echo "</pre>";
		die(); */
		$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
		$this->datos['tegreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
		$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
		$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");
		$this->datos['user']=$this->Egresos_model->retornar_tablaUsers("nombre");		

		$this->setView('egresos/notaentrega');
	}
	public function mostrarEgresosEdicion($id)
	{
        $res=$this->Egresos_model->mostrarEgresos($id);
        if($res->num_rows()>0)
    	{
    		$fila=$res->row();
    		return $fila;
    	}
        else
        {
            return(false);
        }
	}
	public function mostrarDetalleEditar($id)
	{
        $res=$this->Egresos_model->mostrarDetalle($id);
        $res=$res->result_array();
        return($res);
	}
	public function mostrarDetalleEditarPost()
	{
		$id = addslashes($this->security->xss_clean($this->input->post('id')));
		if (!$id) {
			$res = [];
		} else {
			$res=$this->Egresos_model->mostrarDetalle($id);
        	$res=$res->result_array();
		}
		
		echo json_encode($res);
	}
	public function AgregarFActurasResultado($tabla) //agrega la columna facturas a la tabla egreso
	{
		/*$tabla2 = array();
		foreach ($tabla as $fila) {
			$factura = array("factura" => "nuevo1111111111111111");
			array_push($fila, $factura);
			array_push($tabla2, $fila);			
		}
		return $tabla2;*/
		$tabla2 = array();
		

		foreach ($tabla as $fila) {
			$fila['factura']=$this->Egresos_model->retornar_facturas($fila['idEgresos']);
			array_push($tabla2, $fila);
			
		}
		return $tabla2;
	}
	public function mostrarDetalle()
	{
	
		if($this->input->is_ajax_request() && $this->input->post('id'))
        {
        	$id = addslashes($this->security->xss_clean($this->input->post('id')));
        	$moneda = addslashes($this->security->xss_clean($this->input->post('moneda')));
        	$tipocambio = addslashes($this->security->xss_clean($this->input->post('tipocambio')));
			$res=$this->Egresos_model->mostrarDetalle($id);
			$res=$res->result_array();
			
			/******evaluar moneda************/
			$obj=new StdClass();

			if($moneda==2)
			{
				
				$resultado = array();
				foreach ($res as $fila) 
				{
					
					$fila["punitario1"] = $fila["punitario11"];
				    $fila["punitario"] = $fila["punitario"];
				    $fila["total"] = $fila["total"];
				    array_push($resultado, $fila);
				}
				$res=$resultado;
				
			}
			$obj->tipocambio=$tipocambio;
			$obj->resultado=$res;
			
			/********************************/
			echo json_encode($obj);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function retornarcliente()
	{
		if($this->input->is_ajax_request() && $this->input->post('id'))
        {
        	$id = addslashes($this->security->xss_clean($this->input->post('id')));
			$res=$this->Egresos_model->mostrarDetalle($id);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function retornarClientes()
    {
        if($this->input->is_ajax_request() && $this->input->get('b'))
        {
        	$b = $this->security->xss_clean($this->input->get('b'));
        	$dato=$this->Ingresos_model->retornarClienteBusqueda($b);        	
			echo json_encode($dato->result_array());
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	

    public function guardarmovimiento() //xxxxxxx
    {
    	if($this->input->is_ajax_request())
        {
        	
        	$datos['almacen_ne'] = $this->security->xss_clean($this->input->post('almacen_ne'));
        	$datos['tipomov_ne'] = $this->security->xss_clean($this->input->post('tipomov_ne'));
        	$datos['fechamov_ne'] = $this->security->xss_clean($this->input->post('fechamov_ne'));
        	$datos['fechapago_ne'] = $this->security->xss_clean($this->input->post('fechapago_ne'));
        	$datos['moneda_ne'] = $this->security->xss_clean($this->input->post('moneda_ne'));
        	$datos['idCliente'] = $this->security->xss_clean($this->input->post('idCliente'));
        	$datos['pedido_ne'] = $this->security->xss_clean($this->input->post('pedido_ne'));        	
        	$datos['obs_ne'] = $this->security->xss_clean($this->input->post('obs_ne'));
        	$datos['vendedor'] = $this->security->xss_clean($this->input->post('idUsuarioVendedor'));
			$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));
			//$idEgreso=$this->Egresos_model->guardarmovimiento_model($datos);

        	if($datos)
        	{
				echo json_encode($datos);			
        	}
			else
			{				
				echo json_encode("false");
			}
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function storeEgreso()
	{
		if($this->input->is_ajax_request())
        {
			$egreso = new stdclass();
			$egreso->almacen = $this->security->xss_clean($this->input->post('almacen_ne'));
			$egreso->tipomov = $this->security->xss_clean($this->input->post('tipomov_ne'));
			if ($egreso->almacen != $this->datos['id_Almacen_actual'] && !$this->ion_auth->is_admin()) {
					echo 'almacen'.$egreso->almacen.' usu'.$this->datos['id_Almacen_actual'].' admin '.$this->ion_auth->is_admin().$egreso->tipomov;
					return false;
			} 			
			$egreso->fechamov = $this->security->xss_clean($this->input->post('fechamov_ne'));
			$egreso->fechamov = date('Y-m-d',strtotime($egreso->fechamov));
			$gestion = date('Y',strtotime($egreso->fechamov));
			$egreso->cliente = $this->security->xss_clean($this->input->post('idCliente'));
			$egreso->moneda = $this->security->xss_clean($this->input->post('moneda_ne'));
			$egreso->obs = $this->security->xss_clean($this->input->post('obs_ne'));
			$egreso->plazopago = $this->security->xss_clean($this->input->post('fechapago_ne'));
			$egreso->plazopago = date('Y-m-d',strtotime($egreso->plazopago));
			$egreso->plazopago = $egreso->plazopago == '' ? $egreso->fechamov : $egreso->plazopago;
			$egreso->clientePedido = $this->security->xss_clean($this->input->post('pedido_ne'));       
			$egreso->vendedor = $this->security->xss_clean($this->input->post('idUsuarioVendedor'));
			
			$tipocambio = $this->Ingresos_model->getTipoCambio($egreso->fechamov);
			$egreso->tipoCambio = $tipocambio->tipocambio;

			$egreso->autor = $this->session->userdata('user_id');
			//$egreso->fecha = date('Y-m-d H:i:s');

			$gestion = date("Y", strtotime($egreso->fechamov));
			$egreso->gestion = $gestion;
			$egreso->nmov = $this->Egresos_model->retornarNumMovimiento($egreso->tipomov ,$gestion,$egreso->almacen);
			$egreso->articulos = json_decode($this->security->xss_clean($this->input->post('tabla')));

			$id = $this->Egresos_model->storeEgreso($egreso);

			if($id)
        	{
				echo json_encode($id);
        	}
			else
			{				
				echo json_encode(false);
			}			
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function updateEgreso()
	{
		if($this->input->is_ajax_request())
        {
			$egreso = new stdclass();
			$idEgreso = $this->security->xss_clean($this->input->post('idegreso'));
			$egreso->fechamov = $this->security->xss_clean($this->input->post('fechamov_ne'));
			$egreso->fechamov = date('Y-m-d',strtotime($egreso->fechamov));
			$gestion = date('Y',strtotime($egreso->fechamov));
			$egreso->cliente = $this->security->xss_clean($this->input->post('idCliente'));
			$egreso->moneda = $this->security->xss_clean($this->input->post('moneda_ne'));
			$egreso->obs = $this->security->xss_clean($this->input->post('obs_ne'));
			$egreso->plazopago = $this->security->xss_clean($this->input->post('fechapago_ne'));
			$egreso->plazopago = date('Y-m-d',strtotime($egreso->plazopago));
			$egreso->clientePedido = $this->security->xss_clean($this->input->post('pedido_ne'));       
			$egreso->vendedor = $this->security->xss_clean($this->input->post('idUsuarioVendedor'));
			
			$tipocambio = $this->Ingresos_model->getTipoCambio($egreso->fechamov);
			$egreso->tipoCambio = floatval($tipocambio->tipocambio);

			$egreso->autor = $this->session->userdata('user_id');
			//$egreso->fecha = date('Y-m-d H:i:s');
			$gestionFechaEgreso = date("Y", strtotime($egreso->fechamov)); 

			$gestionUpdate = $this->Egresos_model->gestionUpdate($idEgreso)->gestion; 
			$gestionActual = $this->Egresos_model->getGestionActual()->gestionActual;
			//echo json_encode($gestionEgreso. '  -  actual ' . $gestionUpdate);
			//return false;
			if ($gestionUpdate != $gestionFechaEgreso) {
				echo json_encode (false);
				die (false);
			}
			//$egreso->gestion = $gestion;
			$egreso->articulos = json_decode($this->security->xss_clean($this->input->post('tabla')));

			$id = $this->Egresos_model->updateEgreso($idEgreso, $egreso);

			$res = new stdclass();
			$res->id = $id;
			$res->egreso = $egreso;
			if($id)
        	{
				echo json_encode($res);
        	}
			else
			{				
				echo json_encode(false);
			}			
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
    public function actualizarmovimiento()//xxxxxxx
    {
    	if($this->input->is_ajax_request())
        {
        	
            $datos['idegreso'] = $this->security->xss_clean($this->input->post('idegreso'));        	
        	$datos['tipomov_ne'] = $this->security->xss_clean($this->input->post('tipomov_ne'));        	
        	$datos['fechapago_ne'] = $this->security->xss_clean($this->input->post('fechapago_ne'));
        	$datos['moneda_ne'] = $this->security->xss_clean($this->input->post('moneda_ne'));
        	$datos['idCliente'] = $this->security->xss_clean($this->input->post('idCliente'));
        	$datos['pedido_ne'] = $this->security->xss_clean($this->input->post('pedido_ne'));        	
        	$datos['obs_ne'] = $this->security->xss_clean($this->input->post('obs_ne'));
        	$datos['vendedor'] = $this->security->xss_clean($this->input->post('idUsuarioVendedor'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));

        

        	if($this->Egresos_model->actualizarmovimiento_model($datos))
				echo json_encode("true");
			else
				echo json_encode("false");
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
    public function retornarpreciorticulo($idarticulo,$idAlmacen) //xxxxxxx
	{
		$idArticulo=$this->Ingresos_model->retornar_datosArticulo($idarticulo);		
		$saldo=$this->Egresos_model->retornarsaldoarticulo_model($idArticulo,$idAlmacen);
		$precio=$this->Egresos_model->retornarpreciorticulo_model($idArticulo);
		$obj=new StdClass();
		
		if($saldo)
		{
			$fila=$saldo->row();    								
			$obj->ncantidad=$fila->cantidad;						
		}
		else
		{
			$obj->ncantidad=0;			
		}
		if($precio)
		{
			$fila=$precio->row();    								
			$obj->precio=$fila->precio;						
		}
		else
		{
			$obj->precio=0;			
		}
		
		echo json_encode($obj);
	}
	public function get_costo_articuloEgreso($codigo,$cant=0,$preciou=0,$idAlmacen)	//xxxxxxx
	{		
		$cant=$cant==""?0:$cant;
		$preciou=$preciou==""?0:$preciou;
		$ncantidad=0;
    	$nprecionu=0;
    	$ntotal=0;
    	
    	$idArticulo=$this->Ingresos_model->retornar_datosArticulo($codigo);
		$ca=$this->Ingresos_model->retornarcostoarticulo_model($idArticulo,$idAlmacen);
		$obj=new StdClass();
		if($ca)
		{			
			$fila=$ca->row();    			
			$ncantidad=$fila->cantidad-$cant;			
			$nprecionu=$fila->precioUnitario;   					
			
			$obj->ncantidad=$ncantidad;
			$obj->nprecionu=$nprecionu;
			$obj->ntotal=$ntotal;
			$obj->idArticulo=$idArticulo;
		}
		else
		{
			$obj->ncantidad=0;
			$obj->nprecionu=0;
			$obj->ntotal=0;
			$obj->idArticulo=$idArticulo;
		}
		
		return $obj;
	}
	public function actualizarCostoArticuloEgreso($tabla,$idalmacen) //xxxxxxx
	{
		
		foreach ($tabla as $fila) 
		{	
			$aux=$this->get_costo_articuloEgreso($fila[0],$fila[2],$fila[4],$idalmacen);		
					
			$this->Ingresos_model->actualizartablacostoarticulo($aux->idArticulo,$aux->ncantidad,$aux->nprecionu,$idalmacen);
		}		
	}
	public function anularmovimiento()
    {
    	if($this->input->is_ajax_request())
        {
            $datos['idegreso'] = $this->security->xss_clean($this->input->post('idegreso'));        	
			$datos['obs_ne'] = $this->security->xss_clean($this->input->post('obs_ne'));
			$id = intval( $datos['idegreso']);
			$egreso = $this->Egresos_model->mostrarEgresos($id)->row();
			$almacen = $egreso->idalmacen;
			if ($almacen != $this->datos['id_Almacen_actual'] && !$this->ion_auth->is_admin()) {
				echo 'error almacen usuario';
				return false;
			}

			$anular = $this->Egresos_model->anularRecuperarMovimiento_model($datos);
        	if($anular)
				echo json_encode($anular);
			else
				echo json_encode(false);
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}		
	}
    public function recuperarmovimiento()//xxxxxxx
    {
    	if($this->input->is_ajax_request())
        {
            $datos['idegreso'] = $this->security->xss_clean($this->input->post('idegreso'));        	
        	$datos['tipomov_ne'] = $this->security->xss_clean($this->input->post('tipomov_ne'));        	
        	$datos['fechapago_ne'] = $this->security->xss_clean($this->input->post('fechapago_ne'));
        	$datos['moneda_ne'] = $this->security->xss_clean($this->input->post('moneda_ne'));
        	$datos['idCliente'] = $this->security->xss_clean($this->input->post('idCliente'));
        	$datos['pedido_ne'] = $this->security->xss_clean($this->input->post('pedido_ne'));        	
        	$datos['obs_ne'] = $this->security->xss_clean($this->input->post('obs_ne'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));

        	if($this->Egresos_model->anularRecuperarMovimiento_model($datos,0))
				echo json_encode("true");
			else
				echo json_encode("false");
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
    public function consultarTipoCambio()
	{
		if($this->input->is_ajax_request())
        {
			$fecha = $this->security->xss_clean($this->input->post('fecha'));
			$fecha = date('Y-m-d',strtotime($fecha));
			$tipocambio = $this->Ingresos_model->getTipoCambio($fecha);
			if($tipocambio)
        	{
				echo json_encode($tipocambio);
        	}
			else
			{				
				echo json_encode(false);
			}			
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}    
}