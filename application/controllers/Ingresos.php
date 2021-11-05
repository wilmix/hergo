<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ingresos extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Ingresos_model");
		$this->load->model("Egresos_model");
	}
	public function index()
	{
		$this->accesoCheck(11);
		$this->titles('Ingresos','Consultas','Ingresos');
		$this->datos['foot_script'][]=base_url('assets/hergo/ingresos.js') .'?'.rand();
        $this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
        $this->datos['tipoingreso']=$this->Ingresos_model->retornar_tablaMovimiento("+");
		$this->datos['tipoPrefer']="2";
		$this->setView('ingresos/importaciones/ingresos');
	}
	public function importaciones()
	{
		$this->accesoCheck(13);
		$this->titles('Importaciones','Importaciones','Ingresos');
		$this->datos['foot_script'][]=base_url('assets/hergo/ingresosimportaciones.js') .'?'.rand();
		$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
		$this->datos['tingreso']=$this->Ingresos_model->retornar_tablaMovimiento("+");
		$this->datos['fecha']=date('Y-m-d');
		$this->datos['proveedor']=$this->Ingresos_model->retornar_tabla("provedores");
		$this->datos['articulo']=$this->Ingresos_model->retornar_tabla("articulos");
		$this->datos['opcion']="Importaciones";
		$this->datos['idingreso']=16;
		$this->setView('ingresos/importaciones/importaciones2');
	}
	public function compraslocales()
	{
		$this->accesoCheck(12);
		$this->titles('ComprasLocales','Compras Locales','Ingresos');
		$this->datos['foot_script'][]=base_url('assets/hergo/ingresosimportaciones.js') .'?'.rand();
		
		$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
		$this->datos['tingreso']=$this->Ingresos_model->retornar_tablaMovimiento("+");
		$this->datos['fecha']=date('Y-m-d');
		$this->datos['proveedor']=$this->Ingresos_model->retornar_tabla("provedores");
		$this->datos['articulo']=$this->Ingresos_model->retornar_tabla("articulos");
		$this->datos['opcion']="Compras locales";
		$this->datos['idingreso']=2;

		$this->setView('ingresos/importaciones/importaciones2.php');
	}
	public function anulacionEgresos()
	{
		$this->accesoCheck(14);
		$this->titles('Reingresos','Reingresos','Ingresos');
		$this->datos['foot_script'][]=base_url('assets/hergo/ingresosimportaciones.js') .'?'.rand();
		$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
		$this->datos['tingreso']=$this->Ingresos_model->retornar_tablaMovimiento("+");
		$this->datos['fecha']=date('Y-m-d');
		$this->datos['proveedor']=$this->Ingresos_model->retornar_tabla("provedores");
		$this->datos['articulo']=$this->Ingresos_model->retornar_tabla("articulos");
		$this->datos['idingreso']=5;
		
		$this->setView('ingresos/importaciones/importaciones2');
	}
	public function editarimportaciones($id=null)
	{
        if($id==null) redirect("error");

		$this->accesoCheck(70);
		$this->titles('ModificarIngresos','Modificar Ingresos','Ingresos');
		$this->datos['foot_script'][]=base_url('assets/hergo/ingresosimportaciones.js') .'?'.rand();
		
		$this->datos['dcab']=$this->mostrarIngresosEdicion($id);
        $this->datos['detalle']=$this->mostrarDetalleEditar($id);
		if($this->datos['dcab']->idtipomov==3) redirect("error");               
		if($this->datos['dcab']->moneda==2)
		{
			for ($i=0; $i < count($this->datos['detalle']) ; $i++) { 
				$this->datos['detalle'][$i]["totaldoc"]=$this->datos['detalle'][$i]["totaldoc"]/$this->datos['detalle'][$i]["tipocambio"];
				$this->datos['detalle'][$i]["punitario"]=$this->datos['detalle'][$i]["punitario"]/$this->datos['detalle'][$i]["tipocambio"];	            	
				$this->datos['detalle'][$i]["total"]=$this->datos['detalle'][$i]["total"]/$this->datos['detalle'][$i]["tipocambio"];	  
			}
		}
		$this->datos['almacen']=$this->Ingresos_model->retornar_tabla("almacenes");
		$this->datos['tingreso']=$this->Ingresos_model->retornar_tablaMovimiento("+");
		$this->datos['fecha']=date('Y-m-d');
		$this->datos['proveedor']=$this->Ingresos_model->retornar_tabla("provedores");
		$this->datos['articulo']=$this->Ingresos_model->retornar_tabla("articulos");
		
		$this->setView('ingresos/importaciones/importaciones2');
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
        	if($tin==3)
        		$res=$this->Ingresos_model->mostrarIngresosTraspasos($id=null,$ini,$fin,$alm,$tin);
        	else	
				$res=$this->Ingresos_model->mostrarIngresos($id=null,$ini,$fin,$alm,$tin);
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
        	
			$res=$this->Ingresos_model->mostrarIngresosDetalle($id=null,$ini,$fin,$alm,$tin);
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
        $res=$this->Ingresos_model->mostrarIngresos($id);
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
        	$moneda = addslashes($this->security->xss_clean($this->input->post('mon')));
			$res=$this->Ingresos_model->mostrarDetalle($id);
			$res=$res->result_array();
			
			
			if($moneda==2)//si es dolares dividimos por el tipo de cambio
            {

            	 for ($i=0; $i < count($res) ; $i++) { 
	            	$res[$i]["totaldoc"]=$res[$i]["totaldoc"]/$res[$i]["tipocambio"];
	            	$res[$i]["punitario"]=$res[$i]["punitario"]/$res[$i]["tipocambio"];	            	
	            	$res[$i]["total"]=$res[$i]["total"]/$res[$i]["tipocambio"];	  

	            }

            }
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
    public function mostrarDetalleEditar($id)
	{
        $res=$this->Ingresos_model->mostrarDetalle($id);
        $res=$res->result_array();
        return($res);
	}
	public function revisarStd()
	{
		$this->libacceso->acceso(39);

		if($this->input->is_ajax_request())
        {
        	$d = addslashes($this->security->xss_clean($this->input->post('d')));
        	$id = addslashes($this->security->xss_clean($this->input->post('id')));
        	/****REVISAR SI ES TRASPASO PARA PROCEDER CON LA SUMA DE COSTO ARTICULO tipo de movimiento 3**/
        	$res=$this->Ingresos_model->esTraspaso($id);
			if($res && $d==1)
			{
				//die("Es traspaso");
				$tabla=$this->retornaTablaIngresos($id);
				//$this->retornarcostoarticulo_tabla($tabla,$res->almacen,$res->moneda,true);
			}

			$res=$this->Ingresos_model->editarestado_model($d,$id);
			echo json_encode("{estado:ok}");
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function retornaTablaIngresos($idIngreso)
	{
		$tabla=array();
		$res=$this->Ingresos_model->retornarIngresosTabla($idIngreso);
		$i=0;

		foreach ($res->result_array() as $fila) 
		{
			$tabla[$i][0]="ninguno";
			$tabla[$i][1]=$fila['articulo'];
			$tabla[$i][2]=$fila['cantidad'];
			$tabla[$i][3]=$fila['punitario'];
			$tabla[$i][4]=$fila['total'];
		}
		/*echo "<pre>";
		print_r($tabla);
		echo "</pre>";
		die();*/
		return $tabla;
	}
	public function get_costo_articulo($codigo,$cant=0,$preciou=0,$idAlmacen,$_idArticulo=0)	//para tabla, si $_idArticulo==0 buscar id segun el codigo
	{		
		$cant=$cant==""?0:$cant;
		$preciou=$preciou==""?0:$preciou;
		$ncantidad=0;
    	$nprecionu=0;
    	$ntotal=0;
    	if($_idArticulo==0)
    		$idArticulo=$this->Ingresos_model->retornar_datosArticulo($codigo);
    	else
    		$idArticulo=$_idArticulo;
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
	public function retornarcostoarticulo_tabla($tabla,$idalmacen,$moneda,$_traspaso=false)//si $_traspaso == true es traspaso 
	{
		
		foreach ($tabla as $fila) 
		{	
			if($_traspaso)
				$aux=$this->get_costo_articulo($fila[0],$fila[2],$fila[3],$idalmacen,$fila[1]); // en tabla[1] se encuentra almacenado el idArticulo cuando es traspaso
			else
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
	public function retornarcostoarticulo($id,$idAlmacen)
	{
		$idArticulo=$this->Ingresos_model->retornar_datosArticulo($id);
		
		//$ca=$this->Ingresos_model->retornarcostoarticulo_model($idArticulo,$idAlmacen);
		$ca=$this->Ingresos_model->retornarCosto($idArticulo);
		$sa=$this->Ingresos_model->retornarSaldo($idArticulo,$idAlmacen);
		$obj=new StdClass();

		//$obj->nprecionu=$ca->punitarioSaldo;			
		if($ca)			
			$obj->nprecionu=$ca;
		else			
			$obj->nprecionu=0;
		//$obj->ncantidad=$sa->saldo;		
		if($sa)			
			$obj->ncantidad=$sa;
		else
			$obj->ncantidad=0;
		
		echo json_encode($obj);
		
	}
	public function retornarSaldoPrecioArticulo($id,$idAlmacen) /*retorna solo el saldo*/
	{
		$idArticulo=$this->Ingresos_model->retornar_datosArticulo($id);
		
		//$ca=$this->Ingresos_model->retornarcostoarticulo_model($idArticulo,$idAlmacen);
		$sa=$this->Ingresos_model->retornarSaldo($idArticulo,$idAlmacen);		
		$precio=$this->Egresos_model->retornarpreciorticulo_model($idArticulo);
		$obj=new StdClass();
		
		if($sa)
			//$obj->ncantidad=$sa->saldo;			
			$obj->ncantidad=$sa;		
		else			
			$obj->ncantidad=0;
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
    public function retornararticulosTest()
    {
        if($this->input->is_ajax_request() && $this->input->get('b'))
        {
			$b = $this->security->xss_clean($this->input->get('b'));
			$a = $this->security->xss_clean($this->input->get('a'));
        	$dato=$this->Ingresos_model->retornarArticulosBusquedaTest($b, $a);        	
			echo json_encode($dato->result_array());
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function searchArticulo()
    {
        if($this->input->is_ajax_request() && $this->input->post('articulo'))
        {
			$articulo = $this->security->xss_clean($this->input->post('articulo'));
			$fin = $this->security->xss_clean($this->input->post('fin'));
			$fin = date("Y-m-d",strtotime($fin));
			$ini = date("Y-m-d",strtotime($fin."- 6 month")); 
        	$dato=$this->Ingresos_model->searchArticulos($articulo,$ini,$fin);        	
			echo json_encode($dato->result_array());
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function searchProveedor()
    {
        if($this->input->is_ajax_request() && $this->input->post('search'))
        {
			$search = $this->security->xss_clean($this->input->post('search'));
        	$dato=$this->Ingresos_model->searchProveedores($search);        	
			echo json_encode($dato->result_array());
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
    public function retornarTodosArticulos()
    {
        if($this->input->is_ajax_request())
        {
        	
        	$dato=$this->Ingresos_model->retornarArticulos();        	

			echo json_encode($dato->result_array());
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function storeIngreso()
	{
		if($this->input->is_ajax_request())
        {
			$config = [
				"upload_path" => "./assets/img_ingresos/",
				"allowed_types" => "png|jpg|jpeg"
			];
			$this->load->library("upload",$config);
			if ($this->upload->do_upload('img_route')) {
				$img = array("upload_data" => $this->upload->data());
				$img_name = $img['upload_data']['file_name'];
			}
			else{
				//echo $this->upload->display_errors();
				$img_name = '';
			}
			$ingreso = new stdclass();
			$ingreso->almacen = $this->security->xss_clean($this->input->post('almacen_imp'));
        	$ingreso->tipomov = $this->security->xss_clean($this->input->post('tipomov_imp'));
			$ingreso->fechamov = $this->security->xss_clean($this->input->post('fechamov_imp'));
			$ingreso->fechamov = date('Y-m-d',strtotime($ingreso->fechamov));
        	$ingreso->moneda = $this->security->xss_clean($this->input->post('moneda_imp'));
        	$ingreso->proveedor = $this->security->xss_clean($this->input->post('idProveedor'));
        	$ingreso->ordcomp = $this->security->xss_clean($this->input->post('ordcomp_imp'));
			$ingreso->nfact = $this->security->xss_clean($this->input->post('nfact_imp'));
			$ingreso->tipoDoc = $this->security->xss_clean($this->input->post('tipoDoc'));
			$ingreso->obs = strtoupper($this->input->post('obs_imp'));
			$ingreso->flete = $this->security->xss_clean($this->input->post('flete'));
			$ingreso->img_route = $img_name;
			$ingreso->articulos=json_decode($this->security->xss_clean($this->input->post('tabla')));
			

			$tipocambio=$this->Ingresos_model->getTipoCambio($ingreso->fechamov);
			$ingreso->tipoCambio=$tipocambio->tipocambio;

			$ingreso->autor=$this->session->userdata('user_id');
			//$ingreso->fecha = date('Y-m-d H:i:s');

			$gestion= date("Y", strtotime($ingreso->fechamov));
			$ingreso->gestion = $gestion;
			$ingreso->nmov = $this->Ingresos_model->retornarNumMovimiento($ingreso->tipomov,$gestion,$ingreso->almacen);

			$id = $this->Ingresos_model->storeIngreso($ingreso);

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
			$idIngreso = $this->Ingresos_model->guardarmovimiento_model($datos);
			
        	if($idIngreso)
        	{
				echo json_encode($idIngreso);
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
    public function updateIngreso()
    {
		if($this->input->is_ajax_request())
        {
			$imgName = $this->input->post('img_name');
			$config = [
				"upload_path" => "./assets/img_ingresos/",
				"allowed_types" => "png|jpg|jpeg"
			];
			$this->load->library("upload",$config);
			if ($this->upload->do_upload('img_route')) {
				$img = array("upload_data" => $this->upload->data());
				$img_name = $img['upload_data']['file_name'];
			}
			else{
				//echo $this->upload->display_errors();
				$img_name = isset($imgName) ? $imgName : '';
			}
			$ingreso = new stdclass();
			$idIngresos = $this->security->xss_clean($this->input->post('idingresoimportacion'));
			$ingreso->fechamov = $this->security->xss_clean($this->input->post('fechamov_imp'));
			$ingreso->img_route = $img_name;
			$ingreso->fechamov = date('Y-m-d',strtotime($ingreso->fechamov));
			$ingreso->moneda = $this->security->xss_clean($this->input->post('moneda_imp'));
        	$ingreso->ordcomp = $this->security->xss_clean($this->input->post('ordcomp_imp'));
		    $ingreso->proveedor = $this->security->xss_clean($this->input->post('idProveedor'));
			$ingreso->nfact = $this->security->xss_clean($this->input->post('nfact_imp'));
			$ingreso->tipoDoc = $this->security->xss_clean($this->input->post('tipoDoc'));
			$ingreso->obs = strtoupper($this->security->xss_clean($this->input->post('obs_imp')));
			$ingreso->estado = 0;
			$ingreso->flete = $this->security->xss_clean($this->input->post('flete'));
			$ingreso->articulos=json_decode($this->security->xss_clean($this->input->post('tabla')));
			
			$gestionFechaIngreso = date("Y", strtotime($ingreso->fechamov)); 
			$gestionUpdate = $this->Ingresos_model->gestionUpdate($idIngresos)->gestion; 

			if ($gestionUpdate != $gestionFechaIngreso) {
				die(false);
			}

			$tipocambio=$this->Ingresos_model->getTipoCambio($ingreso->fechamov);
			$ingreso->tipoCambio = $tipocambio->tipocambio;
			$ingreso->autor=$this->session->userdata('user_id');
			//$ingreso->fecha = date('Y-m-d H:i:s');

			$id = $this->Ingresos_model->updateIngreso($idIngresos, $ingreso);
			$id = intval($id);

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
    public function anularmovimiento()
    {
    	if($this->input->is_ajax_request())
        {
            $datos['idingresoimportacion'] = $this->security->xss_clean($this->input->post('idingresoimportacion'));
        	$datos['obs_imp'] = $this->security->xss_clean($this->input->post('obs_imp'));

        	if($this->Ingresos_model->anularRecuperarMovimiento_model($datos))
				echo json_encode(true);
			else
				echo json_encode(false);
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

        	if($this->Ingresos_model->anularRecuperarMovimiento_model($datos,0))
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
