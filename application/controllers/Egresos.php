<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Egresos extends CI_Controller
{
	private $datos;
	public function __construct()
	{
		parent::__construct();
		$this->load->library('LibAcceso');
		
		$this->load->helper('url');
		
		$this->load->model("Ingresos_model");
		$this->load->model("Egresos_model");
		$this->load->model("Cliente_model");
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
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.css'),
				base_url('assets/plugins/daterangepicker/daterangepicker.css')	

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
				base_url('assets/busqueda/underscore-min.js'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.js'),
				base_url('assets/plugins/daterangepicker/daterangepicker.js'),
				base_url('assets/plugins/daterangepicker/locale/es.js')


			);
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;

		$this->datos['user_id_actual']=$this->session->userdata['user_id'];
		$this->datos['nombre_actual']=$this->session->userdata['nombre'];
		$this->datos['almacen_actual']=$this->session->userdata['datosAlmacen']->almacen;
		$this->datos['id_Almacen_actual']=$this->session->userdata['datosAlmacen']->idalmacen;
			if($this->session->userdata('foto')==NULL)
				$this->datos['foto']=base_url('assets/imagenes/ninguno.png');
			else
				$this->datos['foto']=base_url('assets/imagenes/').$this->session->userdata('foto');
	}
	
	public function index()
	{
		$this->libacceso->acceso(15);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Egresos";
			$this->datos['opcion']="Consultas Egresos";
			$this->datos['titulo']="Egresos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/egresos.js');
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

            
            $this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
			$this->datos['tipoingreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
			
			


			//$this->datos['ingresos']=$this->Ingresos_model->mostrarIngresos();

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
		$this->libacceso->acceso(17);
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
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/modalClientesEgreso.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');
			/**************EDITABLE***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-table-editable.js');
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.css');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.js');

			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');

			$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
            $this->datos['tegreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
		  	$this->datos['fecha']=date('Y-m-d');
		  	//$this->datos['clientes']=$this->Ingresos_model->retornar_tabla("clientes");
		  	$this->datos['articulo']=$this->Ingresos_model->retornar_tabla("articulos");
		  	$this->datos['user']=$this->Egresos_model->retornar_tablaUsers("nombre");
		  	//clientes

		  	$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
			$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");
			
			//$this->datos['opcion']="Compras locales";
			$this->datos['idegreso']=7;
			
			//$this->datos['almacen_actual']=$this->session->userdata['datosAlmacen'];
			/*echo '<pre>';
			echo ($this->session->userdata['datosAlmacen']->idalmacen);
			echo ($this->session->userdata['datosAlmacen']->almacen);
			echo '</pre>';*/

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			//$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('egresos/notaentrega.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}

	public function VentasCaja()
	{
		$this->libacceso->acceso(16);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Egresos";
			$this->datos['opcion']="Ventas caja";
			$this->datos['titulo']="Ventas caja";

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
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/modalClientesEgreso.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');

			$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
            $this->datos['tegreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
		  	$this->datos['fecha']=date('Y-m-d');
		  	//$this->datos['clientes']=$this->Ingresos_model->retornar_tabla("clientes");
		  	$this->datos['articulo']=$this->Ingresos_model->retornar_tabla("articulos");
		  	$this->datos['user']=$this->Egresos_model->retornar_tablaUsers("nombre");
		  	//clientes

		  	$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
			$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");
						
			$this->datos['idegreso']=6;

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			//$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('egresos/notaentrega.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}

	public function BajaProducto()
	{
		$this->libacceso->acceso(18);
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

			$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
            $this->datos['tegreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");
		  	$this->datos['fecha']=date('Y-m-d');
		  	$this->datos['clientes']=$this->Ingresos_model->retornar_tabla("clientes");
		  	$this->datos['articulo']=$this->Ingresos_model->retornar_tabla("articulos");
		  	$this->datos['user']=$this->Egresos_model->retornar_tablaUsers("nombre");
		  	$this->datos['auxIdCliente']=1801; //cliente hergo
		  	//clientes

		  	$this->datos['tipodocumento']=$this->Cliente_model->retornar_tabla("documentotipo");			
			$this->datos['tipocliente']=$this->Cliente_model->retornar_tabla("clientetipo");
			
			//$this->datos['opcion']="Compras locales";
			$this->datos['idegreso']=9;

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			//$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('egresos/notaentrega.php',$this->datos);
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
        $this->libacceso->acceso(43);
        if($id==null) redirect("error");
        //if(!$this->Egresos_model->puedeeditar($id)) redirect("error");
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Egresos";
			$this->datos['opcion']="Modificar Egresos";
			$this->datos['titulo']="Modificar";

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
			/**************EDITABLE***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-table-editable.js');
			$this->datos['cabeceras_css'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.css');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/table-boot/plugin/bootstrap-editable.js');


			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');
            $this->datos['dcab']=$this->mostrarEgresosEdicion($id);//datos cabecera
            $this->datos['detalle']=$this->mostrarDetalleEditar($id);
            if($this->datos['dcab']->idtipomov==8) redirect("error");
            if($this->datos['dcab']->idtipomov==9) $this->datos['auxIdCliente']=1801; //cliente hergo
            if($this->datos['dcab']->moneda==2)//si es dolares dividimos por el tipo de cambio
            {

            	$tipodecambiovalor=$this->Ingresos_model->getTipoCambio($this->datos['dcab']->fechamov);            	
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
			//$this->load->view('plantilla/headercontainer.php',$this->datos);			
			$this->load->view('egresos/notaentrega.php',$this->datos);
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
					
					$fila["punitario1"] = $fila["punitario11"]/$tipocambio;
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
			$egreso->tipoCambio = $tipocambio->tipocambio;

			$egreso->autor = $this->session->userdata('user_id');
			$egreso->fecha = date('Y-m-d H:i:s');

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
			$egreso->fecha = date('Y-m-d H:i:s');

			$gestion = date("Y", strtotime($egreso->fechamov));
			$egreso->gestion = $gestion;
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
    public function retornarpreciorticulo($idarticulo,$idAlmacen)
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
	}
	public function actualizarCostoArticuloEgreso($tabla,$idalmacen)
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
        	$datos['tipomov_ne'] = $this->security->xss_clean($this->input->post('tipomov_ne'));        	
        	$datos['fechapago_ne'] = $this->security->xss_clean($this->input->post('fechapago_ne'));
        	$datos['moneda_ne'] = $this->security->xss_clean($this->input->post('moneda_ne'));
        	$datos['idCliente'] = $this->security->xss_clean($this->input->post('idCliente'));
        	$datos['pedido_ne'] = $this->security->xss_clean($this->input->post('pedido_ne'));        	
        	$datos['obs_ne'] = $this->security->xss_clean($this->input->post('obs_ne'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));
        	if($this->Egresos_model->anularRecuperarMovimiento_model($datos,1))
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
        
}