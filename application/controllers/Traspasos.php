<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Traspasos extends CI_Controller
{
	private $traspaso;
	public function __construct()
	{		
		parent::__construct();
			/*******/
			$this->load->library('LibAcceso');
			
			/*******/
		$this->load->helper('url');
		$this->load->model("Ingresos_model");
		$this->load->model("Egresos_model");
		$this->load->model("Cliente_model");
		$this->load->helper('date');
		$this->load->model("Traspasos_model");
		$this->traspasos= new $this->Traspasos_model();
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
				base_url('assets/plugins/daterangepicker/daterangepicker.js'),
				base_url('assets/plugins/daterangepicker/locale/es.js')   		
			);
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;
			if($this->session->userdata('foto')==NULL)
				$this->datos['foto']=base_url('assets/imagenes/ninguno.png');
			else
				$this->datos['foto']=base_url('assets/imagenes/').$this->session->userdata('foto');
	}
	
	public function index()
	{
		$this->libacceso->acceso(19);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');

			$this->datos['menu']="Traspasos";
			$this->datos['opcion']="Consultas Traspasos";
			$this->datos['titulo']="Consultas Traspasos";

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

	        /*************DATERANGEPICKER**********/
	        $this->datos['cabeceras_css'][]=base_url('assets/plugins/daterangepicker/daterangepicker.css');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/daterangepicker.js');
	        $this->datos['cabeceras_script'][]=base_url('assets/plugins/daterangepicker/locale/es.js');
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/traspasos.js');
			/**************INPUT MASK***************/
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.js');
			$this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js');
            $this->datos['cabeceras_script'][]=base_url('assets/plugins/inputmask/jquery.inputmask.js');

            
            $this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
            $this->datos['tipoingreso']=$this->Ingresos_model->retornar_tablaMovimiento("-");			

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('traspasos/traspasos.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function traspasoEgreso()
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
			
			$this->datos['opcion']="Traspasos";
			$this->datos['idegreso']=7;

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('traspasos/traspasoEgreso.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
	}
	public function motrarTraspasos()
	{
		if($this->input->is_ajax_request())
        {
        	//$almacen=//retornar almacen al que corresponde el usuario!!!!!
        	$ini=$this->security->xss_clean($this->input->post("i"));//fecha inicio
        	$fin=$this->security->xss_clean($this->input->post("f"));//fecha fin
        	
        	//$res=$this->Traspasos_model->listar($ini,$fin);
			$res=$this->traspasos->listar($ini,$fin);
			$res=$res->result_array();			
			
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	/*private calcularTotal($datos)
	{
		$tipocambio=$this->retornarValorTipoCambio();
		foreach ($datos['tabla'] as $fila) {
    			//print_r($fila);    			
                $totalbs=$fila[6];
                $punitariobs=$fila[5];
                $totaldoc=$fila[4];
                if($moneda_imp==2) //convertimos en bolivianos si la moneda es dolares
                {
                    $totalbs=$totalbs*$tipocambiovalor;
                    $punitariobs=$punitariobs*$tipocambiovalor;
                    $totaldoc=$totaldoc*$tipocambiovalor;
                }
    			
    		}
	}*/
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
    public function actualizarCostoArticuloEgreso($tabla,$idalmacen)
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
	}
	/****************FIN**************************************/
    /*************FUNCIONES DE INGRESOS***********************/
    public function actualizarCostoArticuloIngreso($tabla,$idalmacen,$moneda)
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
	}
	/***********FIN**********************************************/
	private function convertirTablaIngresos($tabla)//convierte tabla a ingresos
	{
		$tablaIngresos= array();
		for ($i=0; $i < count($tabla) ; $i++) { 
			$tablaIngresos[$i][0]=$tabla[$i][0];
			$tablaIngresos[$i][1]=$tabla[$i][1];
			$tablaIngresos[$i][2]=$tabla[$i][2];
			$tablaIngresos[$i][3]=$tabla[$i][3];
			$tablaIngresos[$i][4]=0;
			$tablaIngresos[$i][5]=$tabla[$i][3];
			$tablaIngresos[$i][6]=$tabla[$i][4];
		}
		return $tablaIngresos;	
	}
	private function convertirTablaEgresos($tabla)//convierte tabla a Egresos
	{
		$tablaIngresos= array();
		for ($i=0; $i < count($tabla) ; $i++) { 
			$tablaIngresos[$i][0]=$tabla[$i][0];
			$tablaIngresos[$i][1]=$tabla[$i][1];
			$tablaIngresos[$i][2]=$tabla[$i][2];
			$tablaIngresos[$i][3]=$tabla[$i][3];
			$tablaIngresos[$i][4]=0;
			$tablaIngresos[$i][5]=$tabla[$i][4];			
			$tablaIngresos[$i][6]=$tabla[$i][6];	
		}
		return $tablaIngresos;	
	}
	private function retornarTotal($tabla)
	{
		$total=0;
		foreach ($tabla as $fila) {
			$total+=$fila[4];
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
	public function edicion($id=null)//cambiar nombre a editar ingresos!!!!
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

