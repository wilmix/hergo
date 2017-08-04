<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Facturas extends CI_Controller
{
	private $datos;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->model("ingresos_model");
		$this->load->model("egresos_model");
		$this->load->helper('date');
		$this->load->helper('cookie');
		date_default_timezone_set("America/La_Paz");
		$this->cabeceras_css=array(
				base_url('assets/bootstrap/css/bootstrap.min.css'),
				base_url("assets/fa/css/font-awesome.min.css"),
				base_url("assets/dist/css/AdminLTE.min.css"),
				base_url("assets/dist/css/skins/skin-blue.min.css"),
				base_url("assets/hergo/estilos.css"),
				base_url('assets/plugins/table-boot/css/bootstrap-table.css'),
				base_url('assets/plugins/table-boot/plugin/select2.min.css'),
				base_url('assets/sweetalert/sweetalert.css'),

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
        		base_url('assets/sweetalert/sweetalert.min.js'),
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

			$this->datos['menu']="Facturas";
			$this->datos['opcion']="Consultar Facturas";
			$this->datos['titulo']="Consultar Facturas";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/facturas.js');
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

            
            //$this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            //$this->datos['tipoingreso']=$this->ingresos_model->retornar_tablaMovimiento("-");

			//$this->datos['ingresos']=$this->ingresos_model->mostrarIngresos();

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('Facturas/Facturas.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}

	public function EmitirFactura()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Facturas";
			$this->datos['opcion']="Emitir Facturas";
			$this->datos['titulo']="Emitir Facturas";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/facturas.js');
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

            
            //$this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            //$this->datos['tipoingreso']=$this->ingresos_model->retornar_tablaMovimiento("-");

			//$this->datos['ingresos']=$this->ingresos_model->mostrarIngresos();
            $this->datos["fecha"]=date('Y-m-d');
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('Facturas/emitirFactura.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
			/*borrar cookie facturacion*/
			
			if( isset( $_COOKIE['factsistemhergo'] ) ) {			     
			     delete_cookie("factsistemhergo");
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
			
			$tabla=$this->egresos_model->ListarparaFacturacion($ini,$fin,$alm,$tipo);
			
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
        	/***Retornar idcliente***/
			//$datosEgreso=$this->egresos_model->mostrarEgresos($idegreso);
        	//$fila=$datosEgreso->row();
        	//$idcliente=$fila->idcliente; 
        	/************************/
	      /*  if( isset( $_COOKIE['factsistemhergo'] ) ) 
	        {	
	        	$cookie=json_decode($this->desencriptar(get_cookie('factsistemhergo')));  
							
	        	if($cookie->cliente==$idcliente)// es el mismo cliente que ya se agrego en la tabla?
	        	{
	        		if(!in_array($idegreso, $cookie->egresos))
	        		{
	        			//no existe en el array entonces agregarlo	        			
	        			array_push($cookie->egresos,$idegreso);
	        			$egresoDetalle=$this->egresos_model->mostrarDetalle($idegreso)->result();
	        			$mensaje="Registro agregado correctamente";
	        			//return $egresoDetalle;
	        		}
	        		else
	        		{
	        			//existe entonces no se puede agregar el detalle	        			
	        			$egresoDetalle=FALSE;//return FALSE;
	        			$mensaje="Ya se agrego este registro";
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
				$egresoDetalle=$this->egresos_model->mostrarDetalle($idegreso)->result();
				$mensaje="Se agrego el primer registro en la tabla correctamente";
				$obj= new stdclass();
				$obj->egresos= array($idegreso);
				$obj->cliente=$idcliente;
				$cookie=$obj;
			}*/
			//$cookienew=json_encode($cookie);
			//$cookienew=$this->encriptar($cookienew);
			//set_cookie('factsistemhergo',$cookienew,'3600'); 	
			$egresoDetalle=$this->egresos_model->mostrarDetalle($idegreso)->result();
			$mensaje="Datos cargados correctamente";
			$obj2=new stdclass();
			$obj2->detalle=$egresoDetalle;
			$obj2->mensaje=$mensaje;
			echo json_encode($obj2);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function retornarTabla3()
	{
		
		
		if($this->input->is_ajax_request() && $this->input->post('idegresoDetalle') )
        {
        	$idegresoDetalle= addslashes($this->security->xss_clean($this->input->post('idegresoDetalle')));
        	$idegreso= addslashes($this->security->xss_clean($this->input->post('idegreso')));
        	$egresoDetalle=FALSE;
        	/***Retornar idcliente***/
			$datosEgreso=$this->egresos_model->mostrarEgresos($idegreso);//para obtener el cliente
        	$fila=$datosEgreso->row();
        	$idcliente=$fila->idcliente; 
        	$cliente=$fila->nombreCliente; 
        	$clienteNit=$fila->documento;
        	/************************/
	        if( isset( $_COOKIE['factsistemhergo'] ) ) // existe cookies?
	        {	
	        	$cookie=json_decode($this->desencriptar(get_cookie('factsistemhergo')));  
							
	        	if($cookie->cliente==$idcliente)// es el mismo cliente que ya se agrego en la tabla?
	        	{
	        		if(!in_array($idegresoDetalle, $cookie->egresos))
	        		{
	        			//no existe en el array entonces agregarlo	        			
	        			array_push($cookie->egresos,$idegresoDetalle);
	        			$egresoDetalle=$this->egresos_model->ObtenerDetalle($idegresoDetalle)->result();
	        			$mensaje="Registro agregado correctamente";
	        			//return $egresoDetalle;
	        		}
	        		else
	        		{
	        			//existe entonces no se puede agregar el detalle	        			
	        			$egresoDetalle=FALSE;//return FALSE;
	        			$mensaje="Ya se agrego este registro";
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
				$egresoDetalle=$this->egresos_model->ObtenerDetalle($idegresoDetalle)->result();
				$mensaje="Se agrego el primer registro en la tabla correctamente";
				$obj= new stdclass();
				$obj->egresos= array($idegresoDetalle);//solo agrega el unico egreso al ser el primero
				$obj->cliente=$idcliente;

				$cookie=$obj;
			}
			$cookienew=json_encode($cookie);
			$cookienew=$this->encriptar($cookienew);
			set_cookie('factsistemhergo',$cookienew,'3600'); 	
		
			$obj2=new stdclass();
			$obj2->detalle=$egresoDetalle;
			$obj2->mensaje=$mensaje;
			$obj2->cliente=$cliente;
			$obj2->clienteNit=$clienteNit;
			echo json_encode($obj2);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	private function encriptar($cadena){
	    $key='SistemaHergo';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	    $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
	    return $encrypted; //Devuelve el string encriptado
	 
	}
	 
	private function desencriptar($cadena){
	     $key='SistemaHergo';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	     $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($cadena), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	    return $decrypted;  //Devuelve el string desencriptado
	}
}

