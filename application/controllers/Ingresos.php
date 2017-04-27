<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ingresos extends CI_Controller
{
	private $datos;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("ingresos_model");
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

			$this->datos['menu']="Ingresos";
			$this->datos['opcion']="Consultas";
			$this->datos['titulo']="Ingresos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/ingresos.js');
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');


            $this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            $this->datos['tipoingreso']=$this->ingresos_model->retornar_tablaMovimiento("+");


			//$this->datos['ingresos']=$this->ingresos_model->mostrarIngresos();

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			//$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('ingresos/importaciones/ingresos.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}

	public function consultadetalle()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Ingresos";
			$this->datos['opcion']="Consultas Detalle";
			$this->datos['titulo']="ConsultaDetalle";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/consultadetalle.js');
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

            $this->datos['cabeceras_script'][]=base_url('assets/hergo/ingresodetalle.js');
            $this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            $this->datos['tipoingreso']=$this->ingresos_model->retornar_tablaMovimiento("+");

			//$this->datos['ingresos']=$this->ingresos_model->mostrarIngresos();

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('ingresos/importaciones/consultadetalle.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}


	public function importaciones()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Ingresos";
			
			$this->datos['titulo']="Importaciones";

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
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/ingresosimportaciones.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

			
			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');

            $this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            $this->datos['tingreso']=$this->ingresos_model->retornar_tablaMovimiento("+");
		  	$this->datos['fecha']=date('Y-m-d');
		  	$this->datos['proveedor']=$this->ingresos_model->retornar_tabla("provedores");
		  	$this->datos['articulo']=$this->ingresos_model->retornar_tabla("articulos");
		
			$this->datos['opcion']="Importaciones";
			$this->datos['idingreso']=16;
			


			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('ingresos/importaciones/importaciones2.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}

	//FORMULARIO PARA COMPRAS LOCALES
	public function compraslocales()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Ingresos";
			$this->datos['opcion']="Compras Locales";
			$this->datos['titulo']="Compras Locales";

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
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/ingresosimportaciones.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');


			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');

            $this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            $this->datos['tingreso']=$this->ingresos_model->retornar_tablaMovimiento("+");
		  	$this->datos['fecha']=date('Y-m-d');
		  	$this->datos['proveedor']=$this->ingresos_model->retornar_tabla("provedores");
		  	$this->datos['articulo']=$this->ingresos_model->retornar_tabla("articulos");
			
			$this->datos['opcion']="Compras locales";
			$this->datos['idingreso']=2;

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('ingresos/importaciones/importaciones2.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function anulacionEgresos()
	{
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Ingresos";
			//$this->datos['opcion']="Compras Locales";
			$this->datos['titulo']="Anulacion Egresos";

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
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/ingresosimportaciones.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');


			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');

            $this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            $this->datos['tingreso']=$this->ingresos_model->retornar_tablaMovimiento("+");
		  	$this->datos['fecha']=date('Y-m-d');
		  	$this->datos['proveedor']=$this->ingresos_model->retornar_tabla("provedores");
		  	$this->datos['articulo']=$this->ingresos_model->retornar_tabla("articulos");
			
			$this->datos['opcion']="Anulacion egresos";
			$this->datos['idingreso']=5;;

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('ingresos/importaciones/importaciones2.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	
    public function editarimportaciones($id=null)//cambiar nombre a editar ingresos!!!!
	{
        //if("si no esta autorizado a editar redireccionar o enviar error!!!!")
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Ingresos";
			$this->datos['opcion']="Importaciones";
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
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/ingresosimportaciones.js');
            /**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');


			$this->datos['cabeceras_css'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.css');
			$this->datos['cabeceras_script'][]=base_url('assets/BootstrapToggle/bootstrap-toggle.min.js');
            $this->datos['dcab']=$this->mostrarIngresosEdicion($id);//datos cabecera
            $this->datos['detalle']=$this->mostrarDetalleEditar($id);
            /*echo "<pre>";
            print_r($this->datos['dcab']);
            echo "<pre>";*/

            $this->datos['almacen']=$this->ingresos_model->retornar_tabla("almacenes");
            $this->datos['tingreso']=$this->ingresos_model->retornar_tablaMovimiento("+");
		  	$this->datos['fecha']=date('Y-m-d');
		  	$this->datos['proveedor']=$this->ingresos_model->retornar_tabla("provedores");
		  	$this->datos['articulo']=$this->ingresos_model->retornar_tabla("articulos");
		
			
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('ingresos/importaciones/importaciones2.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function mostrarIngresos()
	{
		if($this->input->is_ajax_request())
        {
        	//$almacen=//retornar almacen al que corresponde el usuario!!!!!
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//fecha fin
        	$alm=$this->security->xss_clean($this->input->post("a"));//almacen
        	$tin=$this->security->xss_clean($this->input->post("ti"));//tipo de ingreso
        	
			$res=$this->ingresos_model->mostrarIngresos($id=null,$ini,$fin,$alm,$tin);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function mostrarIngresosDetalle()
	{
		if($this->input->is_ajax_request())
        {
        	//$almacen=//retornar almacen al que corresponde el usuario!!!!!
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//fecha fin
        	$alm=$this->security->xss_clean($this->input->post("a"));//almacen
        	$tin=$this->security->xss_clean($this->input->post("ti"));//tipo de ingreso
        	
			$res=$this->ingresos_model->mostrarIngresosDetalle($id=null,$ini,$fin,$alm,$tin);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
    public function mostrarIngresosEdicion($id)
	{
        $res=$this->ingresos_model->mostrarIngresos($id);
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
	public function mostrarDetalle()
	{
		if($this->input->is_ajax_request() && $this->input->post('id'))
        {
        	$id = addslashes($this->security->xss_clean($this->input->post('id')));
			$res=$this->ingresos_model->mostrarDetalle($id);
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}

    public function mostrarDetalleEditar($id)
	{
        $res=$this->ingresos_model->mostrarDetalle($id);
        $res=$res->result_array();
        return($res);
	}
	public function revisarStd()
	{

		if($this->input->is_ajax_request())
        {
        	$d = addslashes($this->security->xss_clean($this->input->post('d')));
        	$id = addslashes($this->security->xss_clean($this->input->post('id')));
			$res=$this->ingresos_model->editarestado_model($d,$id);

			echo json_encode("{estado:ok}");
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	//actualizar tabla costoarticulo
	public function get_costo_articulo($codigo,$cant=0,$preciou=0,$idAlmacen)	//para tabla
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
			$total=$cant*$preciou;
			$fila=$ca->row();    			
			$ncantidad=$cant+$fila->cantidad;
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
	}
	public function retornarcostoarticulo_tabla($tabla,$idalmacen)
	{
		foreach ($tabla as $fila) 
		{	
			$aux=$this->get_costo_articulo($fila[0],$fila[2],$fila[3],$idalmacen);
			$this->ingresos_model->actualizartablacostoarticulo($aux->idArticulo,$aux->ncantidad,$aux->nprecionu,$aux->ntotal,$idalmacen);
		}
		
	}
	public function retornarcostoarticulo($id,$idAlmacen)
	{
		$idArticulo=$this->ingresos_model->retornar_datosArticulo($id);
		
		$ca=$this->ingresos_model->retornarcostoarticulo_model($idArticulo,$idAlmacen);
		$obj=new StdClass();
		//print_r($obj);
		if($ca)
		{
			$fila=$ca->row();    					
			
			$obj->ncantidad=$fila->cantidad;
			$obj->nprecionu=$fila->precioUnitario;
			$obj->ntotal=$fila->total;	
		}
		else
		{
			$obj->ncantidad=0;
			$obj->nprecionu=0;
			$obj->ntotal=0;
		}
		
		echo json_encode($obj);
		//return $obj;
	}
    public function retornararticulos()
    {
        if($this->input->is_ajax_request() && $this->input->get('b'))
        {
        	$b = $this->security->xss_clean($this->input->get('b'));
        	$dato=$this->ingresos_model->retornarArticulosBusqueda($b);        	

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
        	$datos['almacen_imp'] = $this->security->xss_clean($this->input->post('almacen_imp'));
        	$datos['tipomov_imp'] = $this->security->xss_clean($this->input->post('tipomov_imp'));
        	$datos['fechamov_imp'] = $this->security->xss_clean($this->input->post('fechamov_imp'));
        	$datos['moneda_imp'] = $this->security->xss_clean($this->input->post('moneda_imp'));
        	$datos['proveedor_imp'] = $this->security->xss_clean($this->input->post('proveedor_imp'));
        	$datos['ordcomp_imp'] = $this->security->xss_clean($this->input->post('ordcomp_imp'));
        	$datos['nfact_imp'] = $this->security->xss_clean($this->input->post('nfact_imp'));
        	$datos['ningalm_imp'] = $this->security->xss_clean($this->input->post('ningalm_imp'));
        	$datos['obs_imp'] = $this->security->xss_clean($this->input->post('obs_imp'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));

        	if($this->ingresos_model->guardarmovimiento_model($datos))
        	{
        		$this->retornarcostoarticulo_tabla($datos['tabla'],$datos['almacen_imp']);
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
            $datos['idingresoimportacion'] = $this->security->xss_clean($this->input->post('idingresoimportacion'));
        	$datos['almacen_imp'] = $this->security->xss_clean($this->input->post('almacen_imp'));
        	$datos['tipomov_imp'] = $this->security->xss_clean($this->input->post('tipomov_imp'));
        	$datos['fechamov_imp'] = $this->security->xss_clean($this->input->post('fechamov_imp'));
        	$datos['moneda_imp'] = $this->security->xss_clean($this->input->post('moneda_imp'));
        	$datos['proveedor_imp'] = $this->security->xss_clean($this->input->post('proveedor_imp'));
        	$datos['ordcomp_imp'] = $this->security->xss_clean($this->input->post('ordcomp_imp'));
        	$datos['nfact_imp'] = $this->security->xss_clean($this->input->post('nfact_imp'));
        	$datos['ningalm_imp'] = $this->security->xss_clean($this->input->post('ningalm_imp'));
        	$datos['obs_imp'] = $this->security->xss_clean($this->input->post('obs_imp'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));

        	if($this->ingresos_model->actualizarmovimiento_model($datos))
				echo json_encode("true");
			else
				echo json_encode("false");
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
    public function anularmovimiento()
    {
    	if($this->input->is_ajax_request())
        {
            $datos['idingresoimportacion'] = $this->security->xss_clean($this->input->post('idingresoimportacion'));
        	$datos['almacen_imp'] = $this->security->xss_clean($this->input->post('almacen_imp'));
        	$datos['tipomov_imp'] = $this->security->xss_clean($this->input->post('tipomov_imp'));
        	$datos['fechamov_imp'] = $this->security->xss_clean($this->input->post('fechamov_imp'));
        	$datos['moneda_imp'] = $this->security->xss_clean($this->input->post('moneda_imp'));
        	$datos['proveedor_imp'] = $this->security->xss_clean($this->input->post('proveedor_imp'));
        	$datos['ordcomp_imp'] = $this->security->xss_clean($this->input->post('ordcomp_imp'));
        	$datos['nfact_imp'] = $this->security->xss_clean($this->input->post('nfact_imp'));
        	$datos['ningalm_imp'] = $this->security->xss_clean($this->input->post('ningalm_imp'));
        	$datos['obs_imp'] = $this->security->xss_clean($this->input->post('obs_imp'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));

        	if($this->ingresos_model->anularRecuperarMovimiento_model($datos,1))
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
            $datos['idingresoimportacion'] = $this->security->xss_clean($this->input->post('idingresoimportacion'));
        	$datos['almacen_imp'] = $this->security->xss_clean($this->input->post('almacen_imp'));
        	$datos['tipomov_imp'] = $this->security->xss_clean($this->input->post('tipomov_imp'));
        	$datos['fechamov_imp'] = $this->security->xss_clean($this->input->post('fechamov_imp'));
        	$datos['moneda_imp'] = $this->security->xss_clean($this->input->post('moneda_imp'));
        	$datos['proveedor_imp'] = $this->security->xss_clean($this->input->post('proveedor_imp'));
        	$datos['ordcomp_imp'] = $this->security->xss_clean($this->input->post('ordcomp_imp'));
        	$datos['nfact_imp'] = $this->security->xss_clean($this->input->post('nfact_imp'));
        	$datos['ningalm_imp'] = $this->security->xss_clean($this->input->post('ningalm_imp'));
        	$datos['obs_imp'] = $this->security->xss_clean($this->input->post('obs_imp'));
        	$datos['tabla']=json_decode($this->security->xss_clean($this->input->post('tabla')));

        	if($this->ingresos_model->anularRecuperarMovimiento_model($datos,0))
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
