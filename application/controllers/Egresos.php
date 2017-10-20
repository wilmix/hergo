<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Egresos extends CI_Controller
{
	private $datos;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("ingresos_model");
		$this->load->model("egresos_model");
		$this->load->model("cliente_model");
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
		$this->cabeceras_css=array(
				base_url('assets/bootstrap/css/bootstrap.min.css'),
				base_url("assets/fa/css/font-awesome.min.css"),
				base_url("assets/dist/css/AdminLTE.min.css"),
				base_url("assets/dist/css/skins/skin-blue.min.css"),
				base_url("assets/hergo/estilos.css"),
				base_url('assets/plugins/table-boot/css/bootstrap-table.css'),
				base_url('assets/plugins/table-boot/plugin/select2.min.css'),				
				base_url('assets/sweetalert/sweetalert2.min.css'),

			);
		$this->cabecera_script=array(
				base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'),
				base_url('assets/bootstrap/js/bootstrap.min.js'),
				base_url('assets/dist/js/app.min.js'),
				base_url('assets/plugins/validator/bootstrapvalidator.min.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table-export.js'),
				base_url('assets/plugins/table-boot/js/tableExport.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table-filter.js'),
				base_url('assets/plugins/table-boot/plugin/select2.min.js'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-select2-filter.js'),
        		base_url('assets/plugins/daterangepicker/moment.min.js'),
        		base_url('assets/plugins/slimscroll/slimscroll.min.js'),        		
        		base_url('assets/sweetalert/sweetalert2.min.js'),

			);
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
			if($this->session->userdata('foto')==NULL)
				$this->datos['foto']=base_url('assets/imagenes/ninguno.png');
			else
				$this->datos['foto']=base_url('assets/imagenes/').$this->session->userdata('foto');
	}
	
	public function index()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Egresos";
			$this->datos['opcion']="Consultas Egresos";
			$this->datos['titulo']="Egresos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/egresos.js');
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

            
            $this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            $this->datos['tipoingreso']=$this->ingresos_model->retornar_tablaMovimiento("-");


			//$this->datos['ingresos']=$this->ingresos_model->mostrarIngresos();

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('egresos/egresos.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function notaentrega()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Egresos";
			$this->datos['opcion']="Nota de Entrega";
			$this->datos['titulo']="Nota de Entrega";

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
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/notasEntrega.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/clientes.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');

			$this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            $this->datos['tegreso']=$this->ingresos_model->retornar_tablaMovimiento("-");
		  	$this->datos['fecha']=date('Y-m-d');
		  	$this->datos['clientes']=$this->ingresos_model->retornar_tabla("clientes");
		  	$this->datos['articulo']=$this->ingresos_model->retornar_tabla("articulos");
		  	$this->datos['user']=$this->egresos_model->retornar_tablaUsers("nombre");
		  	//clientes

		  	$this->datos['tipodocumento']=$this->cliente_model->retornar_tabla("documentotipo");			
			$this->datos['tipocliente']=$this->cliente_model->retornar_tabla("clientetipo");
			
			//$this->datos['opcion']="Compras locales";
			$this->datos['idegreso']=7;

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('egresos/notaentrega.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}

	//nuevo para VENTAS CAJA
	public function VentasCaja()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Egresos";
			$this->datos['opcion']="Ventas Caja";
			$this->datos['titulo']="Ventas Caja";

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
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/notasEntrega.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/clientes.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');

			$this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            $this->datos['tegreso']=$this->ingresos_model->retornar_tablaMovimiento("-");
		  	$this->datos['fecha']=date('Y-m-d');
		  	$this->datos['clientes']=$this->ingresos_model->retornar_tabla("clientes");
		  	$this->datos['articulo']=$this->ingresos_model->retornar_tabla("articulos");
			
			//$this->datos['opcion']="Compras locales";
			$this->datos['idegreso']=6;

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('egresos/ventasCaja.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}

		//nuevo para BAJA DE PRODUCTO
	public function BajaProducto()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Egresos";
			$this->datos['opcion']="Baja de Producto";
			$this->datos['titulo']="Baja de Producto";

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
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/notasEntrega.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');

			$this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            $this->datos['tegreso']=$this->ingresos_model->retornar_tablaMovimiento("-");
		  	$this->datos['fecha']=date('Y-m-d');
		  	$this->datos['clientes']=$this->ingresos_model->retornar_tabla("clientes");
		  	$this->datos['articulo']=$this->ingresos_model->retornar_tabla("articulos");
			
			//$this->datos['opcion']="Compras locales";
			$this->datos['idegreso']=9;

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('egresos/bajaProducto.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
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
        		$res=$this->egresos_model->mostrarEgresosTraspasos($id=null,$ini,$fin,$alm,$tin);
        	else        		
				$res=$this->egresos_model->mostrarEgresos($id=null,$ini,$fin,$alm,$tin);
			$res=$res->result_array();
			$res2=$this->AgregarFActurasResultado($res);
			
			echo json_encode($res2);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

	public function editarEgresos($id=null)//cambiar nombre a editar ingresos!!!!
	{
        //if("si no esta autorizado a editar redireccionar o enviar error!!!!")
        if($id==null) redirect("error");
        if(!$this->egresos_model->puedeeditar($id)) redirect("error");
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
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/notasentrega.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');


			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');
            $this->datos['dcab']=$this->mostrarEgresosEdicion($id);//datos cabecera
            $this->datos['detalle']=$this->mostrarDetalleEditar($id);
            if($this->datos['dcab']->idtipomov==8) redirect("error");
            if($this->datos['dcab']->moneda==2)//si es dolares dividimos por el tipo de cambio
            {

            	$tipodecambiovalor=$this->egresos_model->retornarValorTipoCambio($this->datos['dcab']->tipocambio);            	
            	$tipodecambiovalor=$tipodecambiovalor->tipocambio;
            	
	            for ($i=0; $i < count($this->datos['detalle']) ; $i++) { 
	            //	$this->datos['detalle'][$i]["totaldoc"]=$this->datos['detalle'][$i]["totaldoc"]/$tipodecambiovalor;
	            	$this->datos['detalle'][$i]["punitario"]=$this->datos['detalle'][$i]["punitario"]/$tipodecambiovalor;	            	
	            	$this->datos['detalle'][$i]["total"]=$this->datos['detalle'][$i]["total"]/$tipodecambiovalor;	  

	            }		
	           
            }
           /*echo "<pre>";
            print_r($this->datos['dcab']);
            echo "</pre>";
      		die();*/
            $this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            $this->datos['tegreso']=$this->ingresos_model->retornar_tablaMovimiento("-");
		  	$this->datos['fecha']=date('Y-m-d');
		  	$this->datos['proveedor']=$this->ingresos_model->retornar_tabla("provedores");
		  	$this->datos['articulo']=$this->ingresos_model->retornar_tabla("articulos");
		  			  	//clientes
		  	$this->datos['tipodocumento']=$this->cliente_model->retornar_tabla("documentotipo");			
			$this->datos['tipocliente']=$this->cliente_model->retornar_tabla("clientetipo");
			//user vendedor
			$this->datos['user']=$this->egresos_model->retornar_tablaUsers("nombre");		
			
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);			
			$this->load->view('egresos/notaentrega.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	 public function mostrarEgresosEdicion($id)
	{
        $res=$this->egresos_model->mostrarEgresos($id);
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
        $res=$this->egresos_model->mostrarDetalle($id);
        $res=$res->result_array();
        return($res);
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
			$fila['factura']=$this->egresos_model->retornar_facturas($fila['idEgresos']);
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
        	$idtipocambio = addslashes($this->security->xss_clean($this->input->post('tipocambio')));
			$res=$this->egresos_model->mostrarDetalle($id);
			$res=$res->result_array();
			
			/******evaluar moneda************/
			$obj=new StdClass();
			
			$tipocambio=$this->egresos_model->retornarValorTipoCambio($idtipocambio)->tipocambio;
			if($moneda==2)
			{
				
				$resultado = array();
				foreach ($res as $fila) 
				{
					
					$fila["punitario1"] = $fila["punitario1"]/$tipocambio;
				    $fila["punitario"] = $fila["punitario"]/$tipocambio;
				    $fila["total"] = $fila["total"]/$tipocambio;
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
			$res=$this->egresos_model->mostrarDetalle($id);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function retornararticulos()
    {
        if($this->input->is_ajax_request() && $this->input->get('b'))
        {
        	$b = $this->security->xss_clean($this->input->get('b'));
        	$dato=$this->ingresos_model->retornarClienteBusqueda($b);        	
			echo json_encode($dato->result_array());
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
        	
        	$datos['almacen_ne'] = $this->security->xss_clean($this->input->post('almacen_ne'));
        	$datos['tipomov_ne'] = $this->security->xss_clean($this->input->post('tipomov_ne'));
        	$datos['fechamov_ne'] = $this->security->xss_clean($this->input->post('fechamov_ne'));
        	$datos['fechapago_ne'] = $this->security->xss_clean($this->input->post('fechapago_ne'));
        	$datos['moneda_ne'] = $this->security->xss_clean($this->input->post('moneda_ne'));
        	$datos['idCliente'] = $this->security->xss_clean($this->input->post('idCliente'));
        	$datos['pedido_ne'] = $this->security->xss_clean($this->input->post('pedido_ne'));        	
        	$datos['obs_ne'] = $this->security->xss_clean($this->input->post('obs_ne'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));

        	/*echo "<pre>";
        	print_r($datos['tabla']);
        	echo "</pre>";*/

        	if($this->egresos_model->guardarmovimiento_model($datos))
        	{
        		$this->actualizarCostoArticuloEgreso($datos['tabla'],$datos['almacen_ne']);

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
    public function actualizarmovimiento()
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

        

        	if($this->egresos_model->actualizarmovimiento_model($datos))
				echo json_encode("true");
			else
				echo json_encode("false");
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
    public function retornarpreciorticulo($idarticulo,$idAlmacen)
	{
		$idArticulo=$this->ingresos_model->retornar_datosArticulo($idarticulo);		
		$saldo=$this->egresos_model->retornarsaldoarticulo_model($idArticulo,$idAlmacen);
		$precio=$this->egresos_model->retornarpreciorticulo_model($idArticulo);
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
	public function get_costo_articuloEgreso($codigo,$cant=0,$preciou=0,$idAlmacen)	//para tabla
	{		
		$cant=$cant==""?0:$cant;
		$preciou=$preciou==""?0:$preciou;
		$ncantidad=0;
    	$nprecionu=0;
    	$ntotal=0;
    	
    	$idArticulo=$this->ingresos_model->retornar_datosArticulo($codigo);
		$ca=$this->ingresos_model->retornarcostoarticulo_model($idArticulo,$idAlmacen);
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
	public function actualizarCostoArticuloEgreso($tabla,$idalmacen)
	{
		
		foreach ($tabla as $fila) 
		{	
			$aux=$this->get_costo_articuloEgreso($fila[0],$fila[2],$fila[4],$idalmacen);		
					
			$this->ingresos_model->actualizartablacostoarticulo($aux->idArticulo,$aux->ncantidad,$aux->nprecionu,$idalmacen);
		}		
	}
	public function anularmovimiento()
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
        	if($this->egresos_model->anularRecuperarMovimiento_model($datos,1))
				echo json_encode("true");
			else
				echo json_encode("false");
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}		
    }
    public function recuperarmovimiento()
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

        	if($this->egresos_model->anularRecuperarMovimiento_model($datos,0))
				echo json_encode("true");
			else
				echo json_encode("false");
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
        
}