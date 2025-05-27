<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Traspasos extends MY_Controller
{
	private $traspaso;
	public function __construct()
	{		
		parent::__construct();
		$this->load->model("Ingresos_model");
		$this->load->model("Egresos_model");
		$this->load->model("Cliente_model");
		$this->load->model("Traspasos_model");
		//$this->traspasos= new $this->Traspasos_model();
	}
	public function index()
	{
		$this->accesoCheck(19);
		$this->titles('Traspasos','Consulta','Traspasos');
		$this->datos['foot_script'][]=base_url('assets/hergo/traspasos.js') .'?'.rand();
		$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
		$this->datos['tipoingreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");			
		$this->setView('traspasos/traspasos');
	}
	public function traspasoEgreso()
	{
		$this->accesoCheck(20);
		$this->titles('FormTraspasos','Formulario','Traspasos');
		$this->datos['foot_script'][]=base_url('assets/hergo/formTraspasos.js') .'?'.rand();
		$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
        $this->datos['tegreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
		$this->datos['fecha']=date('Y-m-d');
		$this->datos['clientes']=$this->Ingresos_model->retornar_tabla("clientes");
		$this->datos['articulo']=$this->Ingresos_model->retornar_tabla("articulos");
			
		$this->setView('traspasos/traspasoEgreso');
	}
	public function motrarTraspasos()
	{
		if($this->input->is_ajax_request())
        {
        	//$almacen=//retornar almacen al que corresponde el usuario!!!!!
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//fecha fin
        	
        	$res=$this->Traspasos_model->listar($ini,$fin);
			//$res=$this->traspasos->listar($ini,$fin);
			$res=$res->result_array();			
			
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function guardarmovimiento()
    {
    	if($this->input->is_ajax_request())
        {
        	
        	$datos['almacen_ori'] = $this->security->xss_clean($this->input->post('almacen_ori'));
        	$datos['almacen_des'] = $this->security->xss_clean($this->input->post('almacen_des'));
        	$datos['tipomov_ne'] = 8; //egreso
        	$datos['tipomov_ni'] = 3; //ingreso
        	$datos['fechamov_ne'] = $this->security->xss_clean($this->input->post('fechamov_ne'));        	        
        	$datos['moneda_ne'] = $this->security->xss_clean($this->input->post('moneda_ne'));        	
        	$datos['pedido_ne'] = $this->security->xss_clean($this->input->post('pedido_ne'));        	
        	$datos['obs_ne'] = $this->security->xss_clean($this->input->post('obs_ne'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));

        	$totalTabla=$this->retornarTotal($datos['tabla']);

        	$egreso['almacen_ne'] = $datos['almacen_ori'];
	    	$egreso['tipomov_ne'] = $datos['tipomov_ne'];
	    	$egreso['fechamov_ne'] = $datos['fechamov_ne'];
	    	$egreso['fechapago_ne'] = null;
	    	$egreso['moneda_ne'] = $datos['moneda_ne'];
	    	$egreso['idCliente'] = 1801;
	    	$egreso['pedido_ne'] = $datos['pedido_ne'];
			$egreso['obs_ne'] = $datos['obs_ne'];
			$egreso['vendedor']=0;
	    	$egreso['tabla']=$this->convertirTablaEgresos($datos['tabla']);

	    	$ingreso['almacen_imp'] = $datos['almacen_des'];
        	$ingreso['tipomov_imp'] = $datos['tipomov_ni'];
        	$ingreso['fechamov_imp'] = $datos['fechamov_ne'];
        	$ingreso['moneda_imp'] = $datos['moneda_ne'];
        	$ingreso['proveedor_imp'] = 69;
        	$ingreso['ordcomp_imp'] = $datos['pedido_ne'];
        	$ingreso['nfact_imp'] = null;
        	$ingreso['ningalm_imp'] = null;
        	$ingreso['obs_imp'] = $datos['obs_ne'];
        	$ingreso['tabla']=$this->convertirTablaIngresos($datos['tabla']);


	    	$idEgreso=$this->transefernciaEgreso($egreso);
	    	$idIngreso=$this->transferenciaIngreso($ingreso);	    	
	    	$this->traspasos->idIngreso=$idIngreso;
			$this->traspasos->idEgreso=$idEgreso;
			$this->traspasos->estado=1;
			$this->traspasos->fecha=$datos['fechamov_ne'];
			$this->traspasos->total=$totalTabla;
        	if($this->traspasos->guardar())
        	{
        		//$this->retornarcostoarticulo_tabla($datos['tabla'],$datos['almacen_imp']);
				echo json_encode("true");
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
	public function storeTraspaso()
    {
    	if($this->input->is_ajax_request())
        {
			$ingreso = new stdclass();
			$ingreso->almacen = $this->security->xss_clean($this->input->post('almacen_des'));
        	$ingreso->tipomov = 3;
			$ingreso->fechamov = $this->security->xss_clean($this->input->post('fechamov_ne'));
			$ingreso->fechamov = date('Y-m-d',strtotime($ingreso->fechamov));
        	$ingreso->moneda = $this->security->xss_clean($this->input->post('moneda_ne'));
        	$ingreso->proveedor = 69;
        	$ingreso->ordcomp = $this->security->xss_clean($this->input->post('pedido_ne'));
			$ingreso->nfact = '';
			$ingreso->tipoDoc = 3;
			$ingreso->obs = $this->security->xss_clean($this->input->post('obs_ne'));
			$tipocambio=$this->Ingresos_model->getTipoCambio($ingreso->fechamov);
			$ingreso->tipoCambio=$tipocambio->tipocambio;
			$ingreso->autor=$this->session->userdata('user_id');
			$ingreso->fecha = date('Y-m-d H:i:s');
			$gestion= date("Y", strtotime($ingreso->fechamov));
			$ingreso->gestion = $gestion;
			$ingreso->fechaIngreso = $ingreso->fechamov;
			$ingreso->nmov = $this->Ingresos_model->retornarNumMovimiento($ingreso->tipomov,$gestion,$ingreso->almacen);
			$articulos=json_decode($this->security->xss_clean($this->input->post('tabla')));
			$ingreso->articulos=$this->convertirTablaIngresos($articulos);
			
			$egreso = new stdclass();
			$egreso->almacen = $this->security->xss_clean($this->input->post('almacen_ori'));
			$egreso->tipomov = 8;
			$egreso->fechamov = $this->security->xss_clean($this->input->post('fechamov_ne'));
			$egreso->fechamov = date('Y-m-d',strtotime($egreso->fechamov));
			$gestion = date('Y',strtotime($egreso->fechamov));
			$egreso->cliente = 1801;
			$egreso->moneda = $this->security->xss_clean($this->input->post('moneda_ne'));
			$egreso->obs = $this->security->xss_clean($this->input->post('obs_ne'));
			$egreso->plazopago = $egreso->fechamov;
			$egreso->plazopago = date('Y-m-d',strtotime($egreso->plazopago));
			$egreso->clientePedido = $this->security->xss_clean($this->input->post('pedido_ne'));       
			$egreso->vendedor = $this->session->userdata('user_id');
			$tabla=$this->convertirTablaEgresos($articulos);
			$tipocambio = $this->Ingresos_model->getTipoCambio($egreso->fechamov);
			$egreso->tipoCambio = $tipocambio->tipocambio;
			$egreso->autor = $this->session->userdata('user_id');
			$egreso->fecha = date('Y-m-d H:i:s');
			$egreso->gestion = $gestion;
			$egreso->nmov = $this->Egresos_model->retornarNumMovimiento($egreso->tipomov ,$gestion,$egreso->almacen);
			$egreso->articulos = json_decode(json_encode($tabla),false);

			$res = $this->Traspasos_model->storeTraspaso($ingreso,$egreso);

        	if($res)
        	{
        		//$this->retornarcostoarticulo_tabla($datos['tabla'],$datos['almacen_imp']);
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
	public function updateTraspaso()
	{
		if($this->input->is_ajax_request())
        {
			$egreso = new stdclass();
			$articulos=json_decode($this->security->xss_clean($this->input->post('tabla')));
			$tabla=$this->convertirTablaEgresos($articulos);
			$idEgreso = $this->security->xss_clean($this->input->post('idEgreso'));
			$egreso->fechamov = $this->security->xss_clean($this->input->post('fechamov_ne'));
			$egreso->fechamov = date('Y-m-d',strtotime($egreso->fechamov));
			$gestion = date('Y',strtotime($egreso->fechamov));
			$egreso->moneda = $this->security->xss_clean($this->input->post('moneda_ne'));
			$egreso->obs = $this->security->xss_clean($this->input->post('obs_ne'));
			$egreso->tipomov = '8';
			$egreso->plazopago = $this->security->xss_clean($this->input->post('fechamov_ne'));
			$egreso->plazopago = date('Y-m-d',strtotime($egreso->plazopago));
			$egreso->clientePedido = $this->security->xss_clean($this->input->post('pedido_ne'));       
			$egreso->vendedor = $this->session->userdata('user_id');
			$tipocambio = $this->Ingresos_model->getTipoCambio($egreso->fechamov);
			$egreso->tipoCambio = floatval($tipocambio->tipocambio);
			$egreso->autor = $this->session->userdata('user_id');
			$egreso->fecha = date('Y-m-d H:i:s');
			$egreso->gestion = $gestion;
			$egreso->articulos = json_decode(json_encode($tabla),false);

			$ingreso = new stdclass();
			$idIngresos = $this->security->xss_clean($this->input->post('idIngreso'));
			$ingreso->fechamov = $this->security->xss_clean($this->input->post('fechamov_ne'));
			$ingreso->fechamov = date('Y-m-d',strtotime($ingreso->fechamov));
			$ingreso->ordcomp = $this->security->xss_clean($this->input->post('pedido_ne'));
			$ingreso->moneda = $this->security->xss_clean($this->input->post('moneda_ne'));
			$ingreso->obs = $this->security->xss_clean($this->input->post('obs_ne'));
			$tipocambio=$this->Ingresos_model->getTipoCambio($ingreso->fechamov);
			$ingreso->tipoCambio = $tipocambio->tipocambio;
			$ingreso->autor=$this->session->userdata('user_id');
			$ingreso->fecha = date('Y-m-d H:i:s');
			$ingreso->articulos=$this->convertirTablaIngresos($articulos);
			$ingreso->gestion = $gestion;

			$updateTraspaso = $this->Traspasos_model->updateTraspaso($idIngresos, $ingreso,$idEgreso, $egreso);

			if($updateTraspaso)
        	{
				echo json_encode($updateTraspaso);
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
    public function actualizar()
    {

    	if($this->input->is_ajax_request())
        {
        		
        	$datos['almacen_ori'] = $this->security->xss_clean($this->input->post('almacen_ori'));
        	$datos['almacen_des'] = $this->security->xss_clean($this->input->post('almacen_des'));
        	$datos['tipomov_ne'] = 8; //egreso
        	$datos['tipomov_ni'] = 3; //ingreso
        	$datos['fechamov_ne'] = $this->security->xss_clean($this->input->post('fechamov_ne'));        	        
        	$datos['moneda_ne'] = $this->security->xss_clean($this->input->post('moneda_ne'));        	
        	$datos['pedido_ne'] = $this->security->xss_clean($this->input->post('pedido_ne'));        	
        	$datos['obs_ne'] = $this->security->xss_clean($this->input->post('obs_ne'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));
        	$datos['idegreso'] = $this->security->xss_clean($this->input->post('idEgreso'));
        	$datos['idingreso'] = $this->security->xss_clean($this->input->post('idIngreso'));


        	$totalTabla=$this->retornarTotal($datos['tabla']);


        	$egreso['idegreso'] = $datos['idegreso'];
        	$egreso['almacen_ne'] = $datos['almacen_ori'];
	    	$egreso['tipomov_ne'] = $datos['tipomov_ne'];
	    	$egreso['fechamov_ne'] = $datos['fechamov_ne'];
	    	$egreso['fechapago_ne'] = null;
	    	$egreso['moneda_ne'] = $datos['moneda_ne'];
	    	$egreso['idCliente'] = 1801;
	    	$egreso['pedido_ne'] = $datos['pedido_ne'];
	    	$egreso['obs_ne'] = $datos['obs_ne'];
	    	$egreso['vendedor']=0;
	    	$egreso['tabla']=$this->convertirTablaEgresos($datos['tabla']);

			$this->Egresos_model->actualizarmovimiento_model($egreso);

			$ingreso['idingresoimportacion']=$datos['idingreso'];
	    	$ingreso['almacen_imp'] = $datos['almacen_des'];
        	$ingreso['tipomov_imp'] = $datos['tipomov_ni'];
        	$ingreso['fechamov_imp'] = $datos['fechamov_ne'];
        	$ingreso['moneda_imp'] = $datos['moneda_ne'];
        	$ingreso['proveedor_imp'] = 69;
        	$ingreso['ordcomp_imp'] = $datos['pedido_ne'];
        	$ingreso['nfact_imp'] = null;
        	$ingreso['ningalm_imp'] = null;
        	$ingreso['obs_imp'] = $datos['obs_ne'];
        	$ingreso['tabla']=$this->convertirTablaIngresos($datos['tabla']);

        	$this->Ingresos_model->actualizarmovimiento_model($ingreso);

	    	$idEgreso=$datos['idegreso'];
	    	$idIngreso=$datos['idingreso'];	    	
	    	$this->traspasos->idIngreso=$idIngreso;
			$this->traspasos->idEgreso=$idEgreso;
			$this->traspasos->estado=1;
			$this->traspasos->fecha=$datos['fechamov_ne'];
			$this->traspasos->total=$totalTabla;
        	if($this->traspasos->actualizar())
        	{
        		//$this->retornarcostoarticulo_tabla($datos['tabla'],$datos['almacen_imp']);
				echo json_encode("true");
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
    private function transefernciaEgreso($datos)
    {
    	$idEgreso=$this->Egresos_model->guardarmovimiento_model($datos);    	    	
    	if($idEgreso)
    	{
    		//$this->actualizarCostoArticuloEgreso($datos['tabla'],$datos['almacen_ne']);			
    		return $idEgreso;
    	}
		else
		{				
			die("ERROR TRANFERENCIA EGRESO");
		}		
    }
    public function transferenciaIngreso($datos)
    {
    	$idIngreso=$this->Ingresos_model->guardarmovimiento_model($datos);
    	if($idIngreso)
    	{
    		//queda pendiente la actualizacion
    		//$this->actualizarCostoArticuloIngreso($datos['tabla'],$datos['almacen_imp'],$datos['moneda_imp']);				
    		return $idIngreso;
    	}
		else
		{				
			die("ERROR TRANFERENCIA INGRESO");
		}	
    }
    /*************FUNCIONES DE EGRESOS*************************/
    /* public function actualizarCostoArticuloEgreso($tabla,$idalmacen)
	{
		
		foreach ($tabla as $fila) 
		{	
			$aux=$this->get_costo_articuloEgreso($fila[0],$fila[2],$fila[4],$idalmacen);							
			$this->Ingresos_model->actualizartablacostoarticulo($aux->idArticulo,$aux->ncantidad,$aux->nprecionu,$idalmacen);
		}
		
	}
	public function get_costo_articuloEgreso($codigo,$cant=0,$preciou=0,$idAlmacen)	//para tabla
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
	} */
	/****************FIN**************************************/
    /*************FUNCIONES DE INGRESOS***********************/
    /* public function actualizarCostoArticuloIngreso($tabla,$idalmacen,$moneda)
	{		
		foreach ($tabla as $fila) 
		{	
			$aux=$this->get_costo_articulo($fila[0],$fila[2],$fila[3],$idalmacen);
			$preciounbitario=$aux->nprecionu;
			if($moneda==2)
			{
				$tipodecambiovalor=$this->Ingresos_model->retornarValorTipoCambio();            	
            	$tipodecambiovalor=$tipodecambiovalor->tipocambio;
            	$preciounbitario=$preciounbitario*$tipodecambiovalor;
			}
			$this->Ingresos_model->actualizartablacostoarticulo($aux->idArticulo,$aux->ncantidad,$preciounbitario,$idalmacen);
		}
		
	}
	public function get_costo_articulo($codigo,$cant=0,$preciou=0,$idAlmacen)	//para tabla
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
			$total=$cant*$preciou;
			$fila=$ca->row();    			
			$ncantidad=$cant+$fila->cantidad;
			$fila->total=$fila->cantidad*$fila->precioUnitario;
			$nprecionu=($fila->total+$total)/$ncantidad;    		
			$ntotal=$ncantidad*$nprecionu;
			
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
	} */
	/***********FIN**********************************************/
	private function convertirTablaIngresos($tabla)//convierte tabla a ingresos
	{
		$tablaIngresos= array();
		for ($i=0; $i < count($tabla) ; $i++) { 
			$tablaIngresos[$i][0]=$tabla[$i][0];
			$tablaIngresos[$i][1]=$tabla[$i][1];
			$tablaIngresos[$i][2]=$tabla[$i][2];
			$tablaIngresos[$i][3]=$tabla[$i][3];
			$tablaIngresos[$i][4]=$tabla[$i][4];
			$tablaIngresos[$i][5]=$tabla[$i][5];
			$tablaIngresos[$i][6]=$tabla[$i][4];
			$tablaIngresos[$i][7]=$tabla[$i][5];

		}
		return $tablaIngresos;	
	}
	private function convertirTablaEgresos($tabla)//convierte tabla a Egresos
	{
		$tablaIngresos= array();
		for ($i=0; $i < count($tabla) ; $i++) { 
			$tablaIngresos[$i]['idArticulos']=$tabla[$i][0];
			$tablaIngresos[$i]['CodigoArticulo']=$tabla[$i][1];
			$tablaIngresos[$i]['Descripcion']=$tabla[$i][2];
			$tablaIngresos[$i]['cantFact']=0;
			$tablaIngresos[$i]['cantidad']=$tabla[$i][3];
			$tablaIngresos[$i]['descuento']=0;			
			$tablaIngresos[$i]['punitario']=$tabla[$i][4];	
			$tablaIngresos[$i]['total']=$tabla[$i][5];	
		}
		return $tablaIngresos;	
	}
	private function retornarTotal($tabla)
	{
		$total=0;
		foreach ($tabla as $fila) {
			$total+=$fila[5];
		}
		return $total;
	}
	public function mostrarDetalle()
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
	public function edicion($id=null)
	{
        //if("si no esta autorizado a editar redireccionar o enviar error!!!!")
        if($id==null) redirect("error");
        if(!$this->Egresos_model->puedeeditar($id)) redirect("error");
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Egresos";
			$this->datos['opcion']="Editar Egresos";
			$this->datos['titulo']="Editar";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
            /*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
			/***************SELECT***********/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/select/bootstrap-select.min.js');
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/select/bootstrap-select.min.css');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/formTraspasos.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');


			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');
            $this->datos['dcab']=$this->mostrarEgresosEdicion($id);//datos cabecera
            $this->datos['detalle']=$this->mostrarDetalleEditar($id);
            $this->datos['dTransferencia']=$this->Traspasos_model->obtenerUltimoTraspaso($id);

            if($this->datos['dcab']->moneda==2)//si es dolares dividimos por el tipo de cambio
            {

            	$tipodecambiovalor=$this->Egresos_model->retornarValorTipoCambio($this->datos['dcab']->tipocambio);            	
            	$tipodecambiovalor=$tipodecambiovalor->tipocambio;
            	
	            for ($i=0; $i < count($this->datos['detalle']) ; $i++) { 
	            //	$this->datos['detalle'][$i]["totaldoc"]=$this->datos['detalle'][$i]["totaldoc"]/$tipodecambiovalor;
	            	$this->datos['detalle'][$i]["punitario"]=$this->datos['detalle'][$i]["punitario"]/$tipodecambiovalor;	            	
	            	$this->datos['detalle'][$i]["total"]=$this->datos['detalle'][$i]["total"]/$tipodecambiovalor;	  

	            }		
	           
            }
         
            $this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
            $this->datos['tegreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
		  	$this->datos['fecha']=date('Y-m-d');
		  	$this->datos['proveedor']=$this->Ingresos_model->retornar_tabla("provedores");
		  	$this->datos['articulo']=$this->Ingresos_model->retornar_tabla("articulos");
		  			  	//clientes

		  	$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
			$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");
			//user vendedor
			$this->datos['user']=$this->Egresos_model->retornar_tablaUsers("nombre");
		
			
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);			
			$this->load->view('traspasos/traspasoEgreso.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function modificarTraspaso($id=null)
	{
		$this->libacceso->acceso(20);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Traspasos";
			$this->datos['opcion']="Formulario de Traspasos";
			$this->datos['titulo']="Traspasos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
            /*************AUTOCOMPLETE**********/
            $this->datos['cabeceras_css'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.css');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/jQueryUI/jquery-ui.min.js');
			/***************SELECT***********/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/select/bootstrap-select.min.js');
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/select/bootstrap-select.min.css');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/formTraspasos.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');

			$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
            $this->datos['tegreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
		  	$this->datos['fecha']=date('Y-m-d');
		  	$this->datos['clientes']=$this->Ingresos_model->retornar_tabla("clientes");
			  $this->datos['articulo']=$this->Ingresos_model->retornar_tabla("articulos");
			  
			$this->datos['dcab']=$this->mostrarEgresosEdicion($id);
            $this->datos['detalle']=$this->mostrarDetalleEditar($id);
            $this->datos['dTransferencia']=$this->Traspasos_model->obtenerUltimoTraspaso($id);
			
			$this->datos['opcion']="Traspasos";

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('traspasos/traspasoEgreso.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
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
	public function anularTransferencia()
    {
    	if($this->input->is_ajax_request())
        {        

        	$datos['almacen_ori'] = $this->security->xss_clean($this->input->post('almacen_ori'));
        	$datos['almacen_des'] = $this->security->xss_clean($this->input->post('almacen_des'));
        	$datos['tipomov_ne'] = 8; //egreso
        	$datos['tipomov_ni'] = 3; //ingreso
        	$datos['fechamov_ne'] = $this->security->xss_clean($this->input->post('fechamov_ne'));        	        
        	$datos['moneda_ne'] = $this->security->xss_clean($this->input->post('moneda_ne'));        	
        	$datos['pedido_ne'] = $this->security->xss_clean($this->input->post('pedido_ne'));        	
        	$datos['obs_ne'] = $this->security->xss_clean($this->input->post('obs_ne'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));
        	$datos['idegreso'] = $this->security->xss_clean($this->input->post('idEgreso'));
        	$datos['idingreso'] = $this->security->xss_clean($this->input->post('idIngreso'));

        	$totalTabla=$this->retornarTotal($datos['tabla']);


        	$egreso['idegreso'] = $datos['idegreso'];
        	$egreso['almacen_ne'] = $datos['almacen_ori'];
	    	$egreso['tipomov_ne'] = $datos['tipomov_ne'];
	    	$egreso['fechamov_ne'] = $datos['fechamov_ne'];
	    	$egreso['fechapago_ne'] = null;
	    	$egreso['moneda_ne'] = $datos['moneda_ne'];
	    	$egreso['idCliente'] = 1801;
	    	$egreso['pedido_ne'] = $datos['pedido_ne'];
	    	$egreso['obs_ne'] = $datos['obs_ne'];
	    	$egreso['tabla']=$this->convertirTablaEgresos($datos['tabla']);

			
			$this->Egresos_model->anularRecuperarMovimiento_model($egreso,1);

			$ingreso['idingresoimportacion']=$datos['idingreso'];
	    	$ingreso['almacen_imp'] = $datos['almacen_des'];
        	$ingreso['tipomov_imp'] = $datos['tipomov_ni'];
        	$ingreso['fechamov_imp'] = $datos['fechamov_ne'];
        	$ingreso['moneda_imp'] = $datos['moneda_ne'];
        	$ingreso['proveedor_imp'] = 69;
        	$ingreso['ordcomp_imp'] = $datos['pedido_ne'];
        	$ingreso['nfact_imp'] = null;
        	$ingreso['ningalm_imp'] = null;
        	$ingreso['obs_imp'] = $datos['obs_ne'];
        	$ingreso['tabla']=$this->convertirTablaIngresos($datos['tabla']);

        	$this->Ingresos_model->anularRecuperarMovimiento_model($ingreso,1);

        	echo json_encode("true");
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function anularTraspaso(){
		if($this->input->is_ajax_request())
        {
			
			$datos['idegreso'] = $this->security->xss_clean($this->input->post('idEgreso'));       	
			$datos['obs_ne'] = $this->security->xss_clean($this->input->post('obs_ne'));
			$datos['idingresoimportacion'] = $this->security->xss_clean($this->input->post('idIngreso')); 
			$datos['obs_imp'] = $this->security->xss_clean($this->input->post('obs_ne'));

			$res = $this->Traspasos_model->anularTraspaso($datos);

			echo json_encode ($res);
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
    public function recuperarTransferencia()
    {
    	if($this->input->is_ajax_request())
        {
           $datos['almacen_ori'] = $this->security->xss_clean($this->input->post('almacen_ori'));
        	$datos['almacen_des'] = $this->security->xss_clean($this->input->post('almacen_des'));
        	$datos['tipomov_ne'] = 8; //egreso
        	$datos['tipomov_ni'] = 3; //ingreso
        	$datos['fechamov_ne'] = $this->security->xss_clean($this->input->post('fechamov_ne'));        	        
        	$datos['moneda_ne'] = $this->security->xss_clean($this->input->post('moneda_ne'));        	
        	$datos['pedido_ne'] = $this->security->xss_clean($this->input->post('pedido_ne'));        	
        	$datos['obs_ne'] = $this->security->xss_clean($this->input->post('obs_ne'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));
        	$datos['idegreso'] = $this->security->xss_clean($this->input->post('idEgreso'));
        	$datos['idingreso'] = $this->security->xss_clean($this->input->post('idIngreso'));

        	$totalTabla=$this->retornarTotal($datos['tabla']);


        	$egreso['idegreso'] = $datos['idegreso'];
        	$egreso['almacen_ne'] = $datos['almacen_ori'];
	    	$egreso['tipomov_ne'] = $datos['tipomov_ne'];
	    	$egreso['fechamov_ne'] = $datos['fechamov_ne'];
	    	$egreso['fechapago_ne'] = null;
	    	$egreso['moneda_ne'] = $datos['moneda_ne'];
	    	$egreso['idCliente'] = 1801;
	    	$egreso['pedido_ne'] = $datos['pedido_ne'];
	    	$egreso['obs_ne'] = $datos['obs_ne'];
	    	$egreso['tabla']=$this->convertirTablaEgresos($datos['tabla']);

			
			$this->Egresos_model->anularRecuperarMovimiento_model($egreso,0);

			$ingreso['idingresoimportacion']=$datos['idingreso'];
	    	$ingreso['almacen_imp'] = $datos['almacen_des'];
        	$ingreso['tipomov_imp'] = $datos['tipomov_ni'];
        	$ingreso['fechamov_imp'] = $datos['fechamov_ne'];
        	$ingreso['moneda_imp'] = $datos['moneda_ne'];
        	$ingreso['proveedor_imp'] = 69;
        	$ingreso['ordcomp_imp'] = $datos['pedido_ne'];
        	$ingreso['nfact_imp'] = null;
        	$ingreso['ningalm_imp'] = null;
        	$ingreso['obs_imp'] = $datos['obs_ne'];
        	$ingreso['tabla']=$this->convertirTablaIngresos($datos['tabla']);

        	$this->Ingresos_model->anularRecuperarMovimiento_model($ingreso,0);

        	echo json_encode("true");
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
}

