<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Egresos_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function mostrarEgresos($id=null,$ini=null,$fin=null,$alm="",$tin="")
	{
		if($id==null) //no tiene id de entrada
        {
		  $sql="SELECT *, 
            CASE
                WHEN moneda = 2 THEN ROUND(SUM(totalDol),2)
                WHEN moneda = 1 THEN ROUND(ROUND(SUM(total1),2) / tipocambiovalor,2)
            END totalsus,
            CASE
                WHEN moneda = 2 THEN ROUND((SUM(totalDol) * ROUND(tipocambiovalor,2)),2)
                WHEN moneda = 1 THEN ROUND(SUM(total1),2)
            END total,
            CASE
                WHEN anulado = 1 THEN 'ANULADO'
                WHEN sigla = 'ET' THEN 'TRASPASO'
                WHEN sigla = 'EB' THEN 'BAJA PRODUCTO'
                WHEN estado = 0 THEN 'NO FACTURADO'
                WHEN estado = 1 THEN '*FACTURADO*'
                WHEN estado = 2 THEN 'PARCIAL'	
            END estadoF, 
            diasCredito,          
            estado
            FROM(
                SELECT 
                    d.idingdetalle,
                    e.nmov n,
                    e.idEgresos,
                    t.sigla,
                    t.tipomov,
                    e.fechamov,
                    c.nombreCliente,
                    ROUND((d.`punitario` * d.`cantidad`), 2) total1,
                    ROUND((d.`punitario` * d.`cantidad` / tc.`tipocambio`), 2) totalDol,
                    e.estado,
                    e.fecha,
                    CONCAT(u.first_name, ' ', u.last_name) vendedor,
                    e.moneda,
                    a.almacen,
                    m.sigla monedasigla,
                    e.obs,
                    e.anulado,
                    e.plazopago,
                    e.clientePedido,
                    c.idcliente,
                    c.documento,
                    e.tipocambio,
                    tc.tipocambio tipocambiovalor,
                    f.nFactura,
                    GROUP_CONCAT(DISTINCT f.nfactura SEPARATOR '-') factura,
                    e.almacen idAlmacen,
                    ne.tiempoCredito diasCredito,
                    ne.tipoNota,
                    CONCAT(ua.first_name, ' ', ua.last_name) autor
                FROM 
                    egresos e 
                    INNER JOIN egredetalle d ON e.idegresos = d.idegreso 
                    INNER JOIN tmovimiento t ON e.tipomov = t.id 
                    INNER JOIN clientes c ON e.cliente = c.idCliente 
                    INNER JOIN users u ON u.id = e.vendedor 
                    INNER JOIN users ua ON ua.id = e.autor
                    INNER JOIN almacenes a ON a.idalmacen = e.almacen 
                    INNER JOIN moneda m ON e.moneda = m.id 
                    INNER JOIN tipocambio tc ON e.fechamov = tc.fecha 
                    LEFT JOIN factura_egresos fe ON e.idegresos = fe.idegresos 
                    LEFT JOIN factura f ON f.idFactura = fe.idFactura AND f.anulada = 0 
                    LEFT JOIN notaentregasinfo ne ON ne.egresos_id = e.idegresos 
                WHERE 
                    e.fechamov BETWEEN '$ini' AND '$fin'
                    AND e.almacen like '%$alm'
                    AND t.id like '%$tin' 
                GROUP BY 
                    d.idingdetalle  
                ) tabla    
            GROUP BY tabla.idegresos
            ORDER BY tabla.idEgresos DESC				
            ";
           
        }
        else
        {            
             $sql=" SELECT
                        e.nmov n,
                        e.idEgresos,
                        t.sigla,
                        t.tipomov,
                        e.fechamov,
                        t.id as idtipomov,
                        c.nombreCliente,
                        c.idcliente,
                        sum(d.total) total,
                        e.estado,
                        e.fecha,
                        CONCAT(u.first_name, ' ', u.last_name) autor,
                        e.moneda,
                        a.almacen,
                        a.idalmacen,
                        m.sigla monedasigla,
                        m.id as idmoneda,
                        e.obs,
                        e.anulado,
                        e.plazopago,
                        e.clientePedido,
                        c.documento,
                        e.tipocambio,
                        total / tc.tipocambio totalsus,
                        e.vendedor,
                        CONCAT(uv.first_name, ' ', uv.last_name) nVendedor,
                        tc.tipocambio,
                        c.direccion,
                        c.telefono,
                        c.fax,
                        c.email,
                        ades.idalmacen almacen_destino_id,
                        ades.`almacen` almDes,
                        ing.`nmov` nIng,
                        a.`direccion` almDirec,
                        a.`Telefonos` almFono,
                        nei.tipoNota,
                        nei.tiempoCredito
                    FROM
                        egresos e
                        INNER JOIN egredetalle d on e.idegresos = d.idegreso
                        INNER JOIN tmovimiento t ON e.tipomov = t.id
                        INNER JOIN clientes c ON e.cliente = c.idCliente
                        INNER JOIN users u ON u.id = e.autor
                        INNER JOIN users uv ON uv.id = e.vendedor
                        INNER JOIN almacenes a ON a.idalmacen = e.almacen
                        INNER JOIN moneda m ON e.moneda = m.id
                        INNER JOIN tipocambio tc ON e.fechamov = tc.fecha
                        LEFT JOIN traspasos tr ON tr.`idEgreso` = e.`idegresos`
                        LEFT JOIN ingresos ing ON ing.`idIngresos` = tr.`idIngreso`
                        LEFT JOIN almacenes ades ON ades.idalmacen = ing.`almacen`
                        LEFT JOIN notaentregasinfo nei ON nei.egresos_id = e.idegresos
                    WHERE        
                        idEgresos = $id
                    ORDER BY e.idEgresos DESC
                    LIMIT
                        1   
            ";
        }
		$query=$this->db->query($sql);
		return $query;
	}
    public function mostrarEgresosTraspasos($id=null,$ini=null,$fin=null,$alm="",$tin="")
    {
        if($id==null) //no tiene id de entrada
        {
          $sql="SELECT e.nmov n,e.idEgresos,t.sigla,t.tipomov, e.fechamov, c.nombreCliente, sum(d.total) total, e.fecha, CONCAT(u.first_name,' ', u.last_name) autor,
           e.moneda, a.almacen, m.sigla monedasigla, e.obs, e.anulado, e.plazopago, e.clientePedido,c.idcliente,c.documento,e.tipocambio, sum(d.total)/tc.tipocambio totalsus, 
           a1.almacen destino, e.almacen idAlmacen,
           CASE
                WHEN e.anulado = 1 THEN 'ANULADO'
                WHEN t.sigla = 'ET' THEN 'TRASPASO'
                WHEN t.sigla = 'EB' THEN 'BAJA PRODUCTO'
            END estado
            FROM egresos e
            INNER JOIN egredetalle d
            on e.idegresos=d.idegreso
            INNER JOIN tmovimiento t 
            ON e.tipomov = t.id 
            INNER JOIN clientes c 
            ON e.cliente=c.idCliente
            INNER JOIN users u 
            ON u.id=e.autor 
            INNER JOIN almacenes a 
            ON a.idalmacen=e.almacen 
            INNER JOIN moneda m 
            ON e.moneda=m.id 
            INNER JOIN tipocambio tc
            ON e.fechamov=tc.fecha
              INNER JOIN traspasos t1
              ON t1.idEgreso=e.idEgresos
              INNER JOIN ingresos i
              ON t1.idIngreso=i.idIngresos
              INNER JOIN almacenes a1
              ON a1.idalmacen=i.almacen
            WHERE DATE(e.fechamov)
            BETWEEN '$ini' AND '$fin' and e.almacen like '%$alm' and t.id like '%$tin'
            Group By e.idegresos
            ORDER BY e.idEgresos DESC  
            ";

        }
        else/*REVISAR!!!!!!!!!!!!!!!!!!SELECT i.nmov n,i.idIngresos,t.sigla,t.tipomov,t.id as idtipomov, i.fechamov, p.nombreproveedor,p.idproveedor, i.nfact,
                (SELECT FORMAT(SUM(d.total),2) from ingdetalle d where  d.idIngreso=i.idIngresos) total, i.estado,i.fecha, CONCAT(u.first_name,' ', u.last_name) autor, i.moneda, m.id as idmoneda, a.almacen, a.idalmacen, m.sigla monedasigla, i.ordcomp,i.ningalm, i.obs, i.anulado,i.tipocambio
            FROM ingresos i*/
        {            
             $sql="SELECT e.nmov n,e.idEgresos,t.sigla,t.tipomov, e.fechamov,t.id as idtipomov, c.nombreCliente,c.idcliente, sum(d.total) total,  e.estado,e.fecha, CONCAT(u.first_name,' ', u.last_name) autor, e.moneda, a.almacen, a.idalmacen, m.sigla monedasigla, m.id as idmoneda, e.obs, e.anulado, e.plazopago, e.clientePedido,c.documento,e.tipocambio,total/tc.tipocambio totalsus
            FROM egresos e
            INNER JOIN egredetalle d
            on e.idegresos=d.idegreso
            INNER JOIN tmovimiento t 
            ON e.tipomov = t.id 
            INNER JOIN clientes c 
            ON e.cliente=c.idCliente
            INNER JOIN users u 
            ON u.id=e.autor 
            INNER JOIN almacenes a 
            ON a.idalmacen=e.almacen 
            INNER JOIN moneda m 
            ON e.moneda=m.id 
            INNER JOIN tipocambio tc
            ON e.fechamov=tc.fecha
            WHERE idEgresos=$id
            ORDER BY e.idEgresos DESC
            LIMIT 1   
            ";
        }

        $query=$this->db->query($sql);
        return $query;
    }
	public function mostrarDetalle($id)//lista todos los detalles de un egreso
	{
		$sql="SELECT a.idArticulos,e.codigoProducto CodigoArticulo, e.descripcion Descripcion, e.cantidad, 
        e.punitario punitario11, 
        tc.`tipocambio`,
        e.descuento, e.idingdetalle, 
        e.idegreso, u.Sigla, round(e.cantidad-e.cantFact,2) cantidadReal, round(e.cantFact,2) cantFact,
        case
		when eg.moneda = 2 THEN ROUND(e.`punitario` / tc.`tipocambio`,2)
		when eg.moneda = 1 THEN round(e.punitario,2)
        end punitario,
        case
		when eg.moneda = 2 THEN round((ROUND(e.`punitario` / tc.`tipocambio`,2) * e.`cantidad`),2)
		when eg.moneda = 1 THEN round(e.total,2)
        end total
		FROM egredetalle e
		INNER JOIN articulos a
		ON e.articulo = a.idArticulos
        INNER JOIN unidad u
        ON a.idUnidad=u.idUnidad
        INNER JOIN egresos eg
		ON eg.`idegresos` = e.`idegreso`
		INNER JOIN tipocambio tc
		ON eg.`fechamov` = tc.`fecha`
         WHERE e.idegreso=$id
         ORDER BY a.CodigoArticulo";

		$query=$this->db->query($sql);
		return $query;
	}
    public function retornarEgreso($id)
    {
        $sql="SELECT *
        FROM egresos e 
        INNER JOIN tipocambio tc
        ON e.fechamov=tc.fecha       
        WHERE e.idEgresos=$id";     

        $query=$this->db->query($sql);
        if($query->num_rows()>0)
        {
            $fila=$query->row();
            return $fila;
        }
        else
        {

            return false;
        }
    }
    public function mostrarDetalleFacturas($id)//lista todos los detalles de un egreso
    {
        $sql="SELECT e.articulo id, 
        -- a.CodigoArticulo, 
        -- a.Descripcion, 
        e.cantidad, FORMAT(e.punitario,3) punitario11, 
        e.codigoProducto CodigoArticulo,
        e.descripcion Descripcion,
        e.punitario punitario, ((e.cantidad-e.cantFact) * ROUND(e.punitario,2) ) total, e.descuento, e.idingdetalle, e.idegreso, u.Sigla, (e.cantidad-e.cantFact) cantidadReal
        FROM egredetalle e
        INNER JOIN articulos a
        ON e.articulo = a.idArticulos
        INNER JOIN unidad u
        ON a.idUnidad=u.idUnidad
        WHERE e.idegreso=$id
        and e.cantidad-cantFact>0
        ORDER BY a.CodigoArticulo"; //esta linea omite mostrar registros con la cantidad de facturas completa

        $query=$this->db->query($sql);
        return $query;
    }
    public function ObtenerDetalle($id)//obtiene por idingdetalle // deberia ser egredetalle
    {
        $sql="SELECT a.CodigoArticulo, a.Descripcion, e.cantidad, FORMAT(e.punitario,3) punitario11,e.punitario, e.total total, e.descuento, e.idingdetalle, e.idegreso, u.Sigla,(e.cantidad-e.cantFact) cantidadReal
        FROM egredetalle e
        INNER JOIN articulos a
        ON e.articulo = a.idArticulos
        INNER JOIN unidad u
        ON a.idUnidad=u.idUnidad
        WHERE e.idingdetalle=$id
        and e.cantidad-cantFact>0"; //esta linea omite mostrar registros con la cantidad de facturas completa

        $query=$this->db->query($sql);
        return $query;
    }
    /*public function mostrarEgresosDetalle($id=null,$ini=null,$fin=null,$alm="",$tin="")
    {       
        $sql="SELECT *
                FROM (SELECT i.nmov n,i.idIngresos, i.fechamov, p.nombreproveedor, i.nfact, CONCAT(u.first_name,' ', u.last_name) autor, i.fecha,t.tipomov,a.almacen, m.sigla monedasigla, i.ordcomp,i.ningalm FROM ingresos i INNER JOIN tmovimiento t ON i.tipomov = t.id INNER JOIN provedores p ON i.proveedor=p.idproveedor INNER JOIN users u ON u.id=i.autor INNER JOIN almacenes a ON a.idalmacen=i.almacen INNER JOIN moneda m ON i.moneda=m.id WHERE i.fechamov BETWEEN '$ini' AND '$fin' and i.almacen like '%$alm' and t.id like '%$tin' ORDER BY i.idIngresos DESC) tabla
                INNER JOIN ingdetalle id
                ON tabla.idIngresos=id.idIngreso
                INNER JOIN articulos ar
                ON ar.idArticulos=id.articulo                
                ";
        die($sql);
        $query=$this->db->query($sql);
        return $query;
    }*/
	public function guardarmovimiento_model($datos)
    {

		$almacen_ne=$datos['almacen_ne'];
    	$tipomov_ne=$datos['tipomov_ne'];
    	$fechamov_ne=$datos['fechamov_ne'];
    	$fechapago_ne=$datos['fechapago_ne'];
    	$moneda_ne=$datos['moneda_ne'];
    	$idCliente=$datos['idCliente'];
    	$pedido_ne=$datos['pedido_ne'];
        $idUsuarioVendedor=$datos['vendedor'];
    	$obs_ne=$datos['obs_ne'];
    
        $tipocambio=$this->retornarTipoCambio();      

        $tipocambiov=$this->retornarValorTipoCambio();
  
        $tipocambiovalor=$tipocambiov->tipocambio;




        
        $gestion= date("Y", strtotime($fechamov_ne));
       // echo $almacen_imp;
    	$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
        if(date("Y-m-d",strtotime($fecha))==$fechamov_ne) //si son iguales le agrega la hora
            $fechamov_ne=$fecha;            
        $nummov=$this->retornarNumMovimiento($tipomov_ne,$gestion,$almacen_ne);
    	$sql="INSERT INTO egresos (almacen,tipomov,nmov,fechamov,cliente,moneda,obs,tipocambio,autor,fecha,plazopago,clientePedido,vendedor) 
        VALUES('$almacen_ne','$tipomov_ne','$nummov',STR_TO_DATE('$fechamov_ne','%d-%m-%Y'),'$idCliente','$moneda_ne','$obs_ne','$tipocambio','$autor','$fecha',STR_TO_DATE('$fechapago_ne','%d-%m-%Y'),'$pedido_ne','$idUsuarioVendedor')";
    	$query=$this->db->query($sql);
    	$idEgreso=$this->db->insert_id();
      // var_dump($idEgreso);
       // die();
    	if($idEgreso>0)/**Si se guardo correctamente se guarda la tabla*/
    	{
            
    		foreach ($datos['tabla'] as $fila) {
    			//print_r($fila);
    			$idArticulo=$this->retornar_datosArticulo($fila[0]);    			
              
                $totalbs=$fila[4];
                $punitariobs=$fila[3];
    			if($idArticulo)
    			{
                    if($moneda_ne==2) //convertimos en bolivianos si la moneda es dolares
                    {
                        $totalbs=$totalbs*$tipocambiovalor;                       
                        $punitariobs=$punitariobs*$tipocambiovalor;
                    
                    }
    			//	$sql="INSERT INTO egredetalle(idegreso,nmov,articulo,moneda,cantidad,punitario,total,descuento) VALUES('$idEgreso','0','$idArticulo','$moneda_ne','$fila[2]','$fila[3]','$fila[5]','$fila[4]')";
                    $sql="INSERT INTO egredetalle(idegreso,nmov,articulo,moneda,cantidad,punitario,total,descuento) 
                    VALUES('$idEgreso','0','$idArticulo','$moneda_ne','$fila[2]','$punitariobs','$totalbs','$fila[5]')";
    				$this->db->query($sql);
    			}
                // $sql="INSERT INTO ingdetalle(idIngreso,articulo,moneda,cantidad,punitario,total) VALUES('$idingresoimportacion','$idArticulo','$moneda_imp','$fila[2]','$fila[3]','$fila[4]')";
               
                
    		}
    		//return true;
            return $idEgreso;
    	}
    	else
    	{
    		return false;
    	}
    }
    public function retornarNumMovimiento($tipo,$gestion,$almacen)
    {
        $sql="SELECT nmov from egresos WHERE YEAR(fechamov)= '$gestion' and almacen='$almacen' and tipomov='$tipo' ORDER BY nmov DESC LIMIT 1";
        
        $resultado=$this->db->query($sql);
        if($resultado->num_rows()>0)
        {
            $fila=$resultado->row();
            return ($fila->nmov)+1;
        }
        else
        {

            return 1;
        }
    }
    public function retornarTipoCambio()/*retorna el ultimo tipo de cambio*/
    {
        //$sql="SELECT nmov from ingresos WHERE YEAR(fechamov)= '$gestion' and almacen='$almacen' and tipomov='$tipo' ORDER BY nmov DESC LIMIT 1";
        $sql="SELECT id from tipocambio ORDER BY id DESC LIMIT 1";

        $resultado=$this->db->query($sql);
        if($resultado->num_rows()>0)
        {
            $fila=$resultado->row();
            return ($fila->id);
        }
        else
        {
            return 1;
        }
    }
     public function retornar_datosArticulo($dato)
    {
    	$sql="SELECT idArticulos from articulos where CodigoArticulo='$dato' LIMIT 1";
    	$query=$this->db->query($sql);
    	if($query->num_rows()>0)
    	{
    		$fila=$query->row();
    		return $fila->idArticulos;
    	}
    	else
    	{

    		return false;
    	}
    }
    public function retornar_facturas($id_egreso)
    {
        $sql="SELECT f.nFactura
            from factura_egresos fe
            inner join factura f
            on fe.idFactura=f.idFactura
            where fe.idegresos=$id_egreso
            Group by fe.idFactura";
        $query=$this->db->query($sql);
        $res=$query->result_array();
        return $res;
    }
	public function actualizarmovimiento_model($datos)
    {
        

        $idegreso=$datos['idegreso'];
        $tipomov_ne=$datos['tipomov_ne'];
        $fechapago_ne=$datos['fechapago_ne'];
        $moneda_ne=$datos['moneda_ne'];
        $idCliente=$datos['idCliente'];
        $pedido_ne=$datos['pedido_ne'];
        $idUsuarioVendedor=$datos['vendedor'];
        $obs_ne=$datos['obs_ne'];
       
        

        $autor=$this->session->userdata('user_id');
        $fecha = date('Y-m-d H:i:s');

        //$idtipocambio=$this->retornaridtipocambio($idingresoimportacion);
        $tipocambio=$this->retornarValorTipoCambio();
        $tipocambioid=$tipocambio->id;
        $tipocambiovalor=$tipocambio->tipocambio;
        //$sql="UPDATE ingresos SET almacen='$almacen_imp',tipomov='$tipomov_imp',fechamov='$fechamov_imp',proveedor='$proveedor_imp',moneda='$moneda_imp',nfact='$nfact_imp',ningalm='$ningalm_imp',ordcomp='$ordcomp_imp',obs='$obs_imp',fecha='$fecha',autor='$autor' where idIngresos='$idingresoimportacion'";
        $sql="UPDATE egresos SET tipomov='$tipomov_ne',plazopago='$fechapago_ne',moneda='$moneda_ne',cliente='$idCliente',clientePedido='$pedido_ne',obs='$obs_ne',fecha='$fecha',autor='$autor',vendedor='$idUsuarioVendedor' where idEgresos='$idegreso'";
        $query=$this->db->query($sql);

        $sql="DELETE FROM egredetalle where idegreso='$idegreso'";

        $this->db->query($sql);
       /* echo "<pre>";
        print_r($datos['tabla']);
        echo "</pre>";*/
       // die($tipocambiovalor);
        foreach ($datos['tabla'] as $fila)
        {
            $idArticulo=$this->retornar_datosArticulo($fila[0]);
            if($idArticulo)
            {
               // $sql="INSERT INTO ingdetalle(idIngreso,articulo,moneda,cantidad,punitario,total) VALUES('$idingresoimportacion','$idArticulo','$moneda_imp','$fila[2]','$fila[3]','$fila[4]')";
                $totalbs=$fila[5];
                $punitariobs=$fila[3];
                //$totaldoc=$fila[4];
                if($moneda_ne==2) //convertimos en bolivianos si la moneda es dolares
                {
                    $totalbs=$totalbs*$tipocambiovalor;
                    //echo $totalbs." ";
                    $punitariobs=$punitariobs*$tipocambiovalor;
                   // echo $punitariobs." ";

                   // $totaldoc=$totaldoc*$tipocambiovalor;


                   // echo $totaldoc." ";
                }
         
                $sql="INSERT INTO egredetalle(idegreso,nmov,articulo,cantidad,punitario,total,descuento) VALUES('$idegreso','0','$idArticulo','$fila[2]','$punitariobs','$totalbs','$fila[4]')";
                $this->db->query($sql);
            }
        }
        return true;

    }
    public function retornarValorTipoCambio($id=null)/*retorna el ultimo tipo de cambio*/
    {
        if($id==null)
            $sql="SELECT * from tipocambio ORDER BY id DESC LIMIT 1";
        else//si no retorna segun el id
            $sql="SELECT * from tipocambio where id = '$id' ORDER BY id DESC LIMIT 1";
        //die($sql);
        $resultado=$this->db->query($sql);
        if($resultado->num_rows()>0)
        {
            $fila=$resultado->row();
            return ($fila);
        }
        else
        {
            return 1;
        }
    }
    public function puedeeditar($id)
    {
       
        $sql="SELECT estado from egresos where idegresos = '$id'"; 
        
        $resultado=$this->db->query($sql);
        if($resultado->num_rows()>0)
        {
            $fila=$resultado->row();

            if($fila->estado==1) // no esta facturado???
                return false;
            else
                return true;
        }
        else
        {            
            return false;
        }
    }
    public function retornarsaldoarticulo_model($id,$idAlmacen)
    {
        // quitar desc de la consulta para los ultimos datos de la tabla costoarticulo
        $sql="SELECT c.*
            FROM costoarticulos c
            WHERE c.idArticulo=$id AND c.idAlmacen=$idAlmacen
            ORDER By c.idtabla desc 
            limit 1 
            ";
 
        $query=$this->db->query($sql);
        if($query->num_rows()>0)
        {                   
            return $query;    
        }
        else
        {
            return false;
        }        
    }
    
    public function retornarpreciorticulo_model($idArticulo)
    {
        // quitar desc de la consulta para los ultimos datos de la tabla costoarticulo
        $sql="SELECT *
            FROM precio p
            WHERE p.idArticulo=$idArticulo             
            limit 1 
            ";
        $query=$this->db->query($sql);
        if($query->num_rows()>0)
        {                   
            return $query;    
        }
        else
        {
            return false;
        }        
    }
    public function ListarparaFacturacion($ini,$fin,$alm,$tipo)
    {        
        $sql="SELECT e.nmov n,e.idEgresos,t.sigla,t.tipomov, e.fechamov, c.nombreCliente, sum(d.total) total,  e.estado,e.fecha, 
            CONCAT(u.first_name,' ', u.last_name) autor, e.moneda, a.almacen, m.sigla monedasigla, -- e.obs, 
            e.anulado, e.plazopago, 
            e.clientePedido,c.idcliente,sum(d.total)/tc.tipocambio totalsus , tc.tipocambio
            FROM egresos e
            INNER JOIN egredetalle d
            on e.idegresos=d.idegreso
            INNER JOIN tmovimiento t 
            ON e.tipomov = t.id 
            INNER JOIN clientes c 
            ON e.cliente=c.idCliente
            INNER JOIN users u 
            ON u.id=e.vendedor 
            INNER JOIN almacenes a 
            ON a.idalmacen=e.almacen 
            INNER JOIN moneda m 
            ON e.moneda=m.id 
            INNER JOIN tipocambio tc
            ON tc.fecha=e.fechamov
            WHERE DATE(e.fechamov)
            BETWEEN '$ini' AND '$fin' and (e.estado=0 or e.estado=2) and e.anulado!=1";        
        if($alm>0)         
            $sql.=" and e.almacen=$alm";                
        if($tipo>0)
        {

            $sql.=" and e.tipomov=$tipo";
        }
        else
        {
            $sql.=" and (e.tipomov=6 or e.tipomov=7)";                        
        }
            $sql.=" Group By e.idegresos
            ORDER BY e.idEgresos DESC";
        
        $query=$this->db->query($sql);
        if($query->num_rows() > 0 )
            return $query->result();
        else
            return false;
    }
    public function actualizarCantFact($idIngDetalle,$cantFacturado)
    {
         $sql="UPDATE egredetalle
            set cantFact=cantFact+$cantFacturado
            WHERE idingdetalle=$idIngDetalle          
            ";
        $query=$this->db->query($sql);        
    }
    public function actualizarRestarCantFact($idIngDetalle,$cantFacturado)
    {
         $sql="UPDATE egredetalle
            set cantFact=cantFact-$cantFacturado
            WHERE idingdetalle=$idIngDetalle          
            ";
        $query=$this->db->query($sql);        
    }
    public function evaluarFacturadoTotal($idEgreso)
    {
        $sql="SELECT * from egredetalle where idegreso=$idEgreso and (cantidad-cantFact >0)";
        $query=$this->db->query($sql);
        return $query->result();
    }
    public function actualizarEstado($idEgreso,$estado)
    {
         $sql="UPDATE egresos
            set estado=$estado
            WHERE idEgresos=$idEgreso          
            ";
        $query=$this->db->query($sql);        
    }
    public function getCantidadFacturadaActualizarEgreso($idEgreso)
    {
         $sql="SELECT SUM(ed.`cantidad`) cant, SUM(ed.`cantFact`) cantFact
                FROM egredetalle ed 
                WHERE ed.`idegreso` = $idEgreso";
        $query=$this->db->query($sql);  
        if($query->num_rows() > 0 )
            return $query->row();
        else
            return false;      
    }
    public function getGestionActual()
    {
         $sql="SELECT gestionActual FROM config LIMIT 1";
        $query=$this->db->query($sql);  
        if($query->num_rows() > 0 )
            return $query->row();
        else
            return false;      
    }
    public function gestionUpdate($id)
    {
         $sql="SELECT e.`gestion`
         FROM egresos e
         WHERE  e.`idegresos` = '$id' 
         LIMIT 1";
        $query=$this->db->query($sql);  
        if($query->num_rows() > 0 )
            return $query->row();
        else
            return false;      
    }

    public function anularRecuperarMovimiento_model($datos)
    {        

        $idegreso=$datos['idegreso'];
        $obs_ne=$datos['obs_ne'];               

        $autor=$this->session->userdata('user_id');
        $fecha = date('Y-m-d H:i:s');

        $sql="UPDATE egresos 
        SET obs= UPPER('$obs_ne'),
            fecha=NOW(),
            autor='$autor', 
            anulado='1' 
        where idEgresos='$idegreso'";
        $query=$this->db->query($sql);
        return true;
    }
    public function retornar_tablaUsers()
    {
        $sql="SELECT id, CONCAT(first_name, ' ', last_name) AS nombre, almacen 
        FROM users
        WHERE active = 1
        AND is_seller = 1
        ORDER BY nombre";
        $query=$this->db->query($sql);
        return $query;
    }
    public function storeEgreso($egreso, $notaEntrega)
	{	
        $this->db->trans_start();
            $this->db->insert("egresos", $egreso);
            $idEgreso=$this->db->insert_id();

            if ($egreso->tipomov == 7) {
				$notaEntrega->egresos_id = $idEgreso;
                $this->db->insert("notaentregasinfo", $notaEntrega);
			}
            $egresoDetalle = array();
                foreach ($egreso->articulos as $fila) {
                    $detalle=new stdclass();
                    $detalle->idegreso = $idEgreso;
                    $detalle->articulo = $fila->idArticulos;
                    $detalle->moneda = $egreso->moneda;
                    $detalle->cantidad = $fila->cantidad;
                    $detalle->codigoProducto = strtoupper($fila->CodigoArticulo);
                    $detalle->descripcion = strtoupper($fila->Descripcion);
                    if ($egreso->moneda == 2) {
                        $detalle->punitario= $fila->punitario * $egreso->tipoCambio;
                        $detalle->total=$fila->total * $egreso->tipoCambio;
                        $detalle->descuento=$fila->descuento;
                        
                    }	elseif ($egreso->moneda == 1) {
                        $detalle->punitario= $fila->punitario;
                        $detalle->total=$fila->total;
                        $detalle->descuento=$fila->descuento;
                    }
                    array_push($egresoDetalle,$detalle);	
                }
            $this->db->insert_batch("egredetalle", $egresoDetalle);
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        } else {
            
            return $idEgreso;
        }
    }
    public function storeNotaEntrega($notaEntrega)
	{	
            $this->db->insert("notaentregasinfo", $notaEntrega);
            $id=$this->db->insert_id();
            return $id;
    }
    public function uptadeNotaEntregaInfo($notaEntregaTipo)
	{	
        $this->db->where('egresos_id', $notaEntregaTipo->egresos_id);
        $this->db->update('notaentregasinfo', $notaEntregaTipo);
    }
    
    public function updateEgreso($id, $egreso, $notaEntregaTipo)
	{	
        $this->db->trans_start();
            $this->db->where('idegresos', $id);
            $this->db->update('egresos', $egreso);

            if ($egreso->tipomov == 7) {
                $this->uptadeNotaEntregaInfo($notaEntregaTipo);
            }

            $this->db->where('idegreso', $id);
            $this->db->delete('egredetalle');

            $egresoDetalle = array();
                foreach ($egreso->articulos as $fila) {
                    $detalle=new stdclass();
                    $detalle->idegreso = $id;
                    $detalle->articulo = $fila->idArticulos;
                    $detalle->moneda = $egreso->moneda;
                    $detalle->cantidad = $fila->cantidad;
                    $detalle->cantFact = $fila->cantFact;
                    $detalle->descripcion = strtoupper($fila->Descripcion);
                    $detalle->codigoProducto = strtoupper($fila->CodigoArticulo);
                    if ($egreso->moneda == 2) {
                        $detalle->punitario= $fila->punitario * $egreso->tipoCambio;
                        $detalle->total=$fila->total * $egreso->tipoCambio;
                        $detalle->descuento=$fila->descuento;
                        
                    }	elseif ($egreso->moneda == 1) {
                        $detalle->punitario= $fila->punitario;
                        $detalle->total=$fila->total;
                        $detalle->descuento=$fila->descuento;
                    }
                    array_push($egresoDetalle,$detalle);	
                }
            $this->db->insert_batch("egredetalle", $egresoDetalle);
            
            $dato=$this->getCantidadFacturadaActualizarEgreso($id);  
			if ($dato->cantFact == 0){
                $estado = 0;
            } else if ($dato->cant == $dato->cantFact) {
                $estado = 1;
            } else if ( $dato->cant > $dato->cantFact){
                $estado = 2;
			}
			$this->actualizarEstado($id, $estado); 
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        } else {
            
            return $id;
        }
    }
    public function saldoDeudorVencidas($idCliente)
    {
         $sql=" SELECT
                    fechaFac,
                    SUM(montoTotal) saldoDeudor
                FROM
                    (
                        SELECT
                            f.idFactura factura_id,
                            f.fechaFac,
                            fs.montoTotal
                        FROM
                            factura f
                            LEFT JOIN factura_siat fs ON f.idFactura = fs.factura_id
                            INNER JOIN clientes c ON c.idCliente = f.cliente
                            INNER JOIN factura_egresos fe ON fe.idFactura = f.idFactura
                            LEFT JOIN notaentregasinfo ni ON ni.egresos_id = fe.idegresos
                        WHERE
                            f.anulada = 0
                            AND f.pagada <> 1
                            AND c.idCliente = $idCliente
                            AND DATE_ADD(f.fecha, INTERVAL ni.tiempoCredito DAY) < CURDATE()
                        GROUP BY
                            f.idFactura
                    ) facturasVencidas;";
        $query=$this->db->query($sql);
        return $query;        
    }

    public function saldoDeudorTotal($idCliente)
    {
         $sql=" SELECT
                    fechaFac,(SUM(total) - SUM(montoPagado)) saldoDeudor
                FROM
                    (
                        SELECT
                            f.`idFactura` id,
                            a.`almacen`,
                            f.`ClienteFactura` cliente,
                            f.`lote`,
                            f.`nFactura`,
                            f.`fechaFac`,
                            f.`total`,
                            IFNULL(pr.monto, 0) montoPagado,
                            CONCAT(u.`first_name`, ' ', u.`last_name`) vendedor,
                            e.`plazopago`
                        FROM
                            factura f
                            LEFT JOIN (
                                SELECT
                                    pf.`idPago`,
                                    pf.`idFactura`,
                                    SUM(pf.`monto`) monto
                                FROM
                                    pago_factura pf
                                    INNER JOIN pago p ON pf.`idPago` = p.`idPago`
                                    AND p.`anulado` = 0
                                GROUP BY
                                    pf.`idFactura`
                            ) pr ON f.`idFactura` = pr.idFactura
                            INNER JOIN almacenes a ON a.`idalmacen` = f.`almacen`
                            INNER JOIN factura_egresos fe ON fe.`idFactura` = f.`idFactura`
                            INNER JOIN egresos e ON e.`idegresos` = fe.`idegresos`
                            INNER JOIN users u ON u.`id` = e.`vendedor`
                        WHERE
                            f.`anulada` = 0
                            AND f.`pagada` <> 1
                            AND f.`cliente` = $idCliente
                            AND f.`nFactura` > 0
                        GROUP BY
                            f.`idFactura`
                        ORDER BY
                            f.`fechaFac`
                    ) tb;";
        $query=$this->db->query($sql);
        return $query;        
    }

    public function checkSaldoArticulo($idArticulo, $idAlmacen)
    {
         $sql=" SELECT
                    *
                FROM
                    saldoarticulos sa
                WHERE
                    sa.idArticulo = $idArticulo
                    AND sa.idAlmacen = $idAlmacen;";
        $query=$this->db->query($sql);
        return $query->row();        
    }
}
