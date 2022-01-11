<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ingresos_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";

		$query=$this->db->query($sql);
		return $query;
	}
    public function retornar_tablaMovimiento($tipo)
    {
        $sql="SELECT * from tmovimiento where operacion='$tipo'";

		$query=$this->db->query($sql);
		return $query;
    }
    public function gestionUpdate($id)
    {
         $sql="SELECT i.`gestion`
            FROM ingresos i
            WHERE i.`idIngresos` = '$id'
            LIMIT 1";
        $query=$this->db->query($sql);  
        if($query->num_rows() > 0 )
            return $query->row();
        else
            return false;      
    }
	public function mostrarIngresos($id=null,$ini=null,$fin=null,$alm="",$tin="")
	{
		if($id==null)
        {
		    $sql="SELECT i.nmov n,i.idIngresos,t.sigla,t.tipomov, i.fechamov, p.nombreproveedor,
           (SUM(id.totaldoc) + IFNULL(i.flete,0)) total,SUM(id.total) totalSis, i.fecha, UPPER(CONCAT(u.first_name,' ', u.last_name,'')) autor, 
            i.moneda, a.almacen, m.sigla monedasigla, i.ordcomp, i.obs, i.anulado,
            i.tipocambio, 
            tc.tipocambio valorTipoCambio, 
            SUM(id.total)/tc.tipoCambio totalsus, 
            t.sigla, i.almacen idAlmacen,
            CASE
                WHEN i.anulado = 1 THEN 'ANULADO'
                WHEN i.estado = 0 THEN 'PENDIENTE'
                WHEN i.estado = 1 THEN 'APROBADO'
            END estado,
            CASE
                    WHEN i.tipoDoc = 1 THEN i.nfact
                    WHEN i.tipoDoc = 2 THEN 'SIN FACTURA'
                    WHEN i.tipoDoc = 3 THEN 'EN TRANSITO'
            END tipoDoc , i.tipomov idTipoMov
            FROM ingresos i
                    INNER JOIN ingdetalle id
                    ON i.idingresos=id.idingreso
                    INNER JOIN tmovimiento  t
                    ON i.tipomov = t.id
                    INNER JOIN provedores p
                    ON i.proveedor=p.idproveedor
                    INNER JOIN users u
                    ON u.id=i.autor
                    INNER JOIN almacenes a
                    ON a.idalmacen=i.almacen
                    INNER JOIN moneda m
                    ON i.moneda=m.id
                    INNER JOIN tipocambio tc
                    ON i.fechamov=tc.fecha
                    WHERE i.fechamov BETWEEN '$ini' AND '$fin'
                    and (i.gestion) = (SELECT gestionActual FROM `config`)
                    AND i.almacen LIKE '%$alm' AND t.id LIKE '%$tin'
                    GROUP BY i.idIngresos 
            ORDER BY i.nmov DESC
            ";
            
        }
        else
        {
            $sql="SELECT i.nmov n,i.idIngresos,t.sigla,t.tipomov,t.id as idtipomov, i.fechamov, p.nombreproveedor,p.idproveedor, i.nfact, i.flete, i.img_route,
				SUM(id.total) total, i.estado,i.fecha, CONCAT(u.first_name,' ', u.last_name) autor, i.moneda, m.id as idmoneda, a.almacen, 
                a.idalmacen, m.sigla monedasigla, i.ordcomp, i.obs, i.anulado,i.tipocambio, tc.tipocambio valorTipoCambio, 
                SUM(id.total)/tc.tipoCambio totalsus,i.tipoDoc
			FROM ingresos i
            INNER JOIN ingdetalle id
            on i.idingresos=id.idingreso
			INNER JOIN tmovimiento  t
			ON i.tipomov = t.id
			INNER JOIN provedores p
			ON i.proveedor=p.idproveedor
			INNER JOIN users u
			ON u.id=i.autor
			INNER JOIN almacenes a
			ON a.idalmacen=i.almacen
			INNER JOIN moneda m
			ON i.moneda=m.id
            INNER JOIN tipocambio tc
            ON i.fechamov = tc.fecha
            WHERE idIngresos=$id
			ORDER BY i.idIngresos DESC
            LIMIT 1
            ";
            

        }
        
		$query=$this->db->query($sql);
		return $query;
	}
    public function mostrarIngresosTraspasos($id=null,$ini=null,$fin=null,$alm="",$tin="")
    {
        if($id==null) //no tiene id de entrada
        {
          /*$sql="SELECT i.nmov n,i.idIngresos,t.sigla,t.tipomov, i.fechamov, p.nombreproveedor, i.nfact,
                (SELECT SUM(d.total) from ingdetalle d where  d.idIngreso=i.idIngresos) as total, i.estado,i.fecha, CONCAT(u.first_name,' ', u.last_name) autor, i.moneda, a.almacen, m.sigla monedasigla, i.ordcomp,i.ningalm, i.obs, i.anulado,i.tipocambio, tc.tipocambio valorTipoCambio, total*valorTipoCambio totalsus*/
            $sql="SELECT i.nmov n,i.idIngresos,t.sigla,t.tipomov, i.fechamov, p.nombreproveedor, i.nfact, 
            SUM(id.total) total, i.fecha, CONCAT(u.first_name,' ', u.last_name) autor, i.moneda, a.almacen, 
            m.sigla monedasigla, i.ordcomp, i.obs, i.anulado,i.tipocambio, tc.tipocambio valorTipoCambio,
            SUM(id.total)/tc.tipoCambio totalsus, a1.almacen origen,
            CASE
                WHEN i.anulado = 1 THEN 'ANULADO'
                WHEN i.estado = 0 THEN 'PENDIENTE'
                WHEN i.estado = 1 THEN 'APROBADO'
		    END estado

            FROM ingresos i
            INNER JOIN ingdetalle id
            on i.idingresos=id.idingreso
            INNER JOIN tmovimiento  t
            ON i.tipomov = t.id
            INNER JOIN provedores p
            ON i.proveedor=p.idproveedor
            INNER JOIN users u
            ON u.id=i.autor
            INNER JOIN almacenes a
            ON a.idalmacen=i.almacen
            INNER JOIN moneda m
            ON i.moneda=m.id
            INNER JOIN tipocambio tc
            ON i.fechamov=tc.fecha
            INNER JOIN traspasos t1
            ON t1.idIngreso=i.idIngresos
            INNER JOIN egresos e
            ON t1.idEgreso=e.idegresos
            INNER JOIN almacenes a1
            ON a1.idalmacen=e.almacen
            WHERE DATE(i.fechamov) BETWEEN '$ini' AND '$fin' and i.almacen like '%$alm' and t.id like '%$tin'
            Group By i.idIngresos 
            ORDER BY i.idIngresos DESC
            ";
            
        }
        else
        {
            $sql="SELECT i.nmov n,i.idIngresos,t.sigla,t.tipomov,t.id as idtipomov, i.fechamov, p.nombreproveedor,p.idproveedor, i.nfact,
                SUM(id.total) total as total, i.estado,i.fecha, CONCAT(u.first_name,' ', u.last_name) autor, i.moneda, m.id as idmoneda, a.almacen, a.idalmacen, m.sigla monedasigla, i.ordcomp,i.ningalm, i.obs, i.anulado,i.tipocambio, tc.tipocambio valorTipoCambio, SUM(id.total)/tc.tipoCambio totalsus
            FROM ingresos i
            INNER JOIN ingdetalle id
            on i.idingresos=id.idingreso
            INNER JOIN tmovimiento  t
            ON i.tipomov = t.id
            INNER JOIN provedores p
            ON i.proveedor=p.idproveedor
            INNER JOIN users u
            ON u.id=i.autor
            INNER JOIN almacenes a
            ON a.idalmacen=i.almacen
            INNER JOIN moneda m
            ON i.moneda=m.id
            INNER JOIN tipocambio tc
            ON i.tipocambio=tc.id
            WHERE idIngresos=$id
            ORDER BY i.idIngresos DESC
            LIMIT 1
            ";
        }
        
        $query=$this->db->query($sql);
        return $query;
    }
    public function mostrarIngresosDetalle($id=null,$ini=null,$fin=null,$alm="",$tin="")
    {       
        $sql="SELECT *
                FROM (SELECT i.nmov n,i.idIngresos, i.fechamov, p.nombreproveedor, 
                i.nfact, CONCAT(u.first_name,' ', u.last_name) autor, i.fecha,t.tipomov,a.almacen,
                 m.sigla monedasigla, i.ordcomp,i.ningalm 
                 FROM ingresos i 
                INNER JOIN tmovimiento t ON i.tipomov = t.id 
                INNER JOIN provedores p ON i.proveedor=p.idproveedor 
                INNER JOIN users u ON u.id=i.autor 
                INNER JOIN almacenes a ON a.idalmacen=i.almacen 
                INNER JOIN moneda m ON i.moneda=m.id 
                WHERE DATE(i.fechamov) BETWEEN '$ini' AND '$fin' and i.almacen like '%$alm' and t.id like '%$tin' ORDER BY i.idIngresos DESC) tabla
                INNER JOIN ingdetalle id
                ON tabla.idIngresos=id.idIngreso
                INNER JOIN articulos ar
                ON ar.idArticulos=id.articulo                
                ";
    
        $query=$this->db->query($sql);
        return $query;
    }
	public function mostrarDetalle($id)
	{
		$sql="SELECT a.idArticulos idArticulo, a.CodigoArticulo, a.Descripcion, id.cantidad,id.totaldoc, id.punitario punitario, i.flete, i.img_route,
        id.total total, u.Unidad, tc.`tipocambio`, ROUND(a.`costoPromedioPonderado`,2) cpp, (id.totaldoc/id.cantidad) cuDoc
		FROM ingdetalle id
		INNER JOIN articulos a
		ON id.articulo = a.idArticulos
        INNER JOIN unidad u
        ON u.idUnidad = a.idUnidad
        inner join ingresos i
        on i.`idIngresos` = id.`idIngreso`
        inner join tipocambio tc
        on tc.`fecha` = i.`fechamov`
        WHERE idIngreso=$id
        ORDER BY a.CodigoArticulo";
		$query=$this->db->query($sql);
		return $query;
	}
	public function editarestado_model($d, $id)
	{
		$sql="UPDATE ingresos SET estado='$d', aprobado=NOW() WHERE idIngresos=$id";
		$query=$this->db->query($sql);
		return $query;
	}
	public function esTraspaso($id)
    {
        $sql="SELECT * FROM ingresos where idIngresos=$id"; 
        //die($sql)       ;
        $query=$this->db->query($sql);
        if($query->num_rows()>0)
        {
            $fila=$query->row();
            if($fila->tipomov==3)
                return $fila;
            else
                return false;
        }
        else
        {

            return false;
        }        
    }
    public function retornarArticulosBusquedaTest($b, $a)
    {
		$sql="SELECT a.`idArticulos` id, a.CodigoArticulo codigo, a.Descripcion descripcion, u.Unidad unidad, 
        ROUND(IFNULL(a.`costoPromedioPonderado`,0),2) cpp,
        ROUND(IFNULL(sa.`saldo`,0),2) saldo,
        ROUND(IFNULL(a.precio, 0),2) precio,
        sa.`idAlmacen`
        FROM articulos_enUso a
        INNER JOIN unidad u ON a.idUnidad=u.idUnidad
        LEFT JOIN saldoarticulos sa ON sa.`idArticulo` = a.`idArticulos` AND sa.`idAlmacen` = '$a'
        WHERE a.CodigoArticulo LIKE '$b%' OR a.Descripcion LIKE '$b%'
        ORDER BY CodigoArticulo ASC LIMIT 25";
		$query=$this->db->query($sql);
		return $query;
    }
    public function searchArticulos($articulo, $ini, $fin)
    {
		$sql="SELECT ast.id, ast.codigo, ast.numParte, ast.descripcion,ast.descripFabrica, ast.unidad, ast.saldo, 
        ast.precio, ast.cpp, ast.img, ast.posicionArancel,SUM(fd.`facturaCantidad`) rotacion, CONCAT(ast.codigo, ' | ', ast.descripcion) label
        FROM facturadetalle fd
        INNER JOIN factura f ON f.`idFactura` = fd.`idFactura` AND f.`anulada` = 0 AND f.`fechaFac` BETWEEN '$ini' AND '$fin'
        RIGHT JOIN articulos_saldo_total ast ON ast.`id` = fd.`articulo`
        WHERE ast.`codigo` LIKE '$articulo%' OR ast.descripcion LIKE '$articulo%'
        GROUP BY ast.id
        LIMIT 20";
		$query=$this->db->query($sql);
		return $query;
    }
    public function searchProveedores($search)
    {
		$sql="SELECT p.`idproveedor` id, p.`nombreproveedor` label
        FROM provedores p
        WHERE p.`nombreproveedor` LIKE '$search%'
        LIMIT 20";
		$query=$this->db->query($sql);
		return $query;
    }
    public function retornarArticulos()
    {
        $sql="SELECT a.CodigoArticulo, a.Descripcion, u.Unidad
        FROM articulos a
        INNER JOIN unidad u
        ON a.idUnidad=u.idUnidad       
        ";
        //where CodigoArticulo like '$b%' or Descripcion like '$b%' ORDER By CodigoArticulo asc
        
        $query=$this->db->query($sql);
        return $query;
    }
     public function retornarClienteBusqueda($b)
    {
        $sql="SELECT c.`idCliente`, c.`nombreCliente`, c.`documento`
        FROM clientes c     
        where nombreCliente like '%$b%' or documento like '%$b%' ORDER By nombreCliente asc
        LIMIT 50";
        
        $query=$this->db->query($sql);
        return $query;
    }
    public function retornarcostoarticulo_model($id,$idAlmacen)
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
    public function guardarmovimiento_model($datos)
    {

		$almacen_imp=$datos['almacen_imp'];
        $tipomov_imp=$datos['tipomov_imp'];
     
    	$fechamov_imp=$datos['fechamov_imp'];
    	$moneda_imp=$datos['moneda_imp'];
    	$proveedor_imp=$datos['proveedor_imp'];
    	$ordcomp_imp=$datos['ordcomp_imp'];
    	$nfact_imp=$datos['nfact_imp'];
    	$ningalm_imp=$datos['ningalm_imp'];
        
    	$obs_imp=$datos['obs_imp'];
        $tipocambio=$this->retornarValorTipoCambio();
        $tipocambioid=$tipocambio->id;
        $tipocambiovalor=$tipocambio->tipocambio;
        
        $gestion= date("Y", strtotime($fechamov_imp));
       // echo $almacen_imp;
    	$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
        if(date("Y-m-d",strtotime($fecha))==$fechamov_imp) //si son iguales le agrega la hora
            $fechamov_imp=$fecha;            
        $nummov=$this->retornarNumMovimiento($tipomov_imp,$gestion,$almacen_imp);
    	$sql="INSERT INTO ingresos (almacen,tipomov,nmov,fechamov,proveedor,moneda,nfact,ningalm,ordcomp,obs,fecha,autor,tipocambio) 
        VALUES('$almacen_imp','$tipomov_imp','$nummov',STR_TO_DATE('$fechamov_imp','%d-%m-%Y, %h:%i:%s %p'),'$proveedor_imp','$moneda_imp','$nfact_imp','$ningalm_imp','$ordcomp_imp','$obs_imp','$fecha','$autor','$tipocambioid')";
    	$query=$this->db->query($sql);
    	$idIngreso=$this->db->insert_id();
        

    	if($idIngreso>0)/**Si se guardo correctamente se guarda la tabla*/
    	{            
    		foreach ($datos['tabla'] as $fila) {
    			//print_r($fila);
    			$idArticulo=$this->retornar_datosArticulo($fila[0]);
                $totalbs=$fila[6];
                $punitariobs=$fila[5];
                $totaldoc=$fila[4];
                if($moneda_imp==2) //convertimos en bolivianos si la moneda es dolares
                {
                    $totalbs=$totalbs*$tipocambiovalor;
                    $punitariobs=$punitariobs*$tipocambiovalor;
                    $totaldoc=$totaldoc*$tipocambiovalor;
                }
    			if($idArticulo)
    			{
    				$sql="INSERT INTO ingdetalle(idIngreso,nmov,articulo,moneda,cantidad,punitario,total,totaldoc) 
                    VALUES('$idIngreso','$nummov','$idArticulo','$moneda_imp','$fila[2]','$punitariobs','$totalbs','$totaldoc')";
    				$this->db->query($sql);

               /*     $costoArticulo=new stdclass();
                    
                    $costoArticulo->idArticulo=$idArticulo
                    $costoArticulo->idAlmacen=$almacen_imp;
                    $costoArticulo->cantidad=;
                    $costoArticulo->precioUnitario= calcular;
                    $costoArticulo->total=;
                    $costoArticulo->idEgresoDetalle= ;
                    $costoArticulo->idIngresoDetalle= ;
                    $costoArticulo->anulado= 0;*/
    			}
    		}
    		//return true;
            return $idIngreso;
    	}
    	else
    	{
    		return false;
    	}
    }
    public function actualizarmovimiento_model($datos)
    {
		$idingresoimportacion=$datos['idingresoimportacion'];
        $almacen_imp=$datos['almacen_imp'];
    	$tipomov_imp=$datos['tipomov_imp'];
    	$fechamov_imp=$datos['fechamov_imp'];
    	$moneda_imp=$datos['moneda_imp'];
    	$proveedor_imp=$datos['proveedor_imp'];
    	$ordcomp_imp=$datos['ordcomp_imp'];
    	$nfact_imp=$datos['nfact_imp'];
    	$ningalm_imp=$datos['ningalm_imp'];
    	$obs_imp=$datos['obs_imp'];

    	$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');

        //$idtipocambio=$this->retornaridtipocambio($idingresoimportacion);
        $tipocambio=$this->retornarValorTipoCambio();
        $tipocambioid=$tipocambio->id;
        $tipocambiovalor=$tipocambio->tipocambio;
        //$sql="UPDATE ingresos SET almacen='$almacen_imp',tipomov='$tipomov_imp',fechamov='$fechamov_imp',proveedor='$proveedor_imp',moneda='$moneda_imp',nfact='$nfact_imp',ningalm='$ningalm_imp',ordcomp='$ordcomp_imp',obs='$obs_imp',fecha='$fecha',autor='$autor' where idIngresos='$idingresoimportacion'";
        $sql="UPDATE ingresos SET proveedor='$proveedor_imp',nfact='$nfact_imp',ningalm='$ningalm_imp',ordcomp='$ordcomp_imp',obs='$obs_imp',fecha='$fecha',autor='$autor' where idIngresos='$idingresoimportacion'";
    	$query=$this->db->query($sql);

        $sql="DELETE FROM ingdetalle where idIngreso='$idingresoimportacion'";

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
                $totalbs=$fila[6];
                $punitariobs=$fila[5];
                $totaldoc=$fila[4];
                if($moneda_imp==2) //convertimos en bolivianos si la moneda es dolares
                {
                    $totalbs=$totalbs*$tipocambiovalor;
                   // echo $totalbs." ";
                    $punitariobs=$punitariobs*$tipocambiovalor;
                   // echo $punitariobs." ";
                    $totaldoc=$totaldoc*$tipocambiovalor;
                  //  echo $totaldoc." ";
                }
         
                $sql="INSERT INTO ingdetalle(idIngreso,nmov,articulo,cantidad,punitario,total,totaldoc) VALUES('$idingresoimportacion','0','$idArticulo','$fila[2]','$punitariobs','$totalbs','$totaldoc')";
                $this->db->query($sql);
            }
        }
        return true;

    }
    public function updateIngreso($id, $ingreso)
    {
        $this->db->trans_start();    
            $this->db->where('idIngresos', $id);
            $this->db->update('ingresos', $ingreso);

            $this->db->where('idIngreso', $id);
            $this->db->delete('ingdetalle');

            $ingresoDetalle = array();
                    foreach ($ingreso->articulos as $fila) {
                        $detalle=new stdclass();
                        $detalle->idIngreso = $id;
                        $detalle->articulo = $fila[0];
                        $detalle->moneda = $ingreso->moneda;
                        $detalle->cantidad = $fila[3];
                        if ($ingreso->moneda == 2) {
                            $detalle->punitario= $fila[6] * $ingreso->tipoCambio;
                            $detalle->total=$fila[7] * $ingreso->tipoCambio;
                            $detalle->totaldoc=$fila[5] * $ingreso->tipoCambio;
                            
                        }	elseif ($ingreso->moneda == 1) {
                            $detalle->punitario= $fila[6];
                            $detalle->total=$fila[7];
                            $detalle->totaldoc=$fila[5];
                        }
                        array_push($ingresoDetalle,$detalle);	
                    }
                $this->db->insert_batch("ingdetalle", $ingresoDetalle);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return false;
        } else {
            return $id;
        }
    }
    public function anularRecuperarMovimiento_model($datos)
    {
        $idingresoimportacion=$datos['idingresoimportacion'];
        $obs_imp=strtoupper($datos['obs_imp']);
        $autor=$this->session->userdata('user_id');
        $fecha = date('Y-m-d H:i:s');
        $sql="UPDATE ingresos 
        SET obs='$obs_imp',fecha=NOW(),autor='$autor', anulado='1', estado=0 
        where idIngresos='$idingresoimportacion'";
        $query=$this->db->query($sql);
        
        return true;

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
    public function retornarNumMovimiento($tipo,$gestion,$almacen)
    {
        $sql="SELECT nmov from ingresos WHERE YEAR(fechamov)= '$gestion' and almacen='$almacen' and tipomov='$tipo' ORDER BY nmov DESC LIMIT 1";
        //echo $sql;
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
    public function retornarTipoCambio()
    {
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
    public function retornarValorTipoCambio($fecha=null)
    {
        $id='';
        if($id==null)
            $sql="SELECT * from tipocambio ORDER BY id DESC LIMIT 1";
        else
            $sql="SELECT * from tipocambio tc
            where tc.`fecha` = '$fecha'
            ORDER BY id DESC LIMIT 1";
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
    public function getTipoCambio($fecha)
    {
        $sql="SELECT * FROM tipocambio tc 
                WHERE  tc.`fecha` = '$fecha'
                ORDER BY id DESC LIMIT 1";
        $resultado=$this->db->query($sql);
        if($resultado->num_rows()>0)
        {
            $fila=$resultado->row();
            return ($fila);
        }
        else
        {
            return FALSE;
        }
    }
    public function actualizartablacostoarticulo($idArticulo,$cantidad,$costou,$idalmacen)
    {
        $sql="INSERT INTO costoarticulos(idArticulo,idAlmacen,cantidad,precioUnitario) VALUES('$idArticulo','$idalmacen','$cantidad','$costou')";
        $this->db->query($sql);
    }
    public function retornarFechaIngreso($id)
    {
        $sql="SELECT fechamov from ingresos where idIngresos=$id LIMIT 1";
        
        $resultado=$this->db->query($sql);
        /*if($resultado->num_rows()>0)
        {
            $fila=$resultado->row();
            return ($fila->fechamov);
        }
        else
        {
            return 0;
        }*/
    }
    public function retornarIngresosTabla($idIngreso)
    {
         $sql="SELECT * FROM ingdetalle WHERE idIngreso=$idIngreso";
 
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
    public function retornarCosto($idArticulo) /*retorna tabla*/
    {
        $sql="SELECT costoPromedioPonderado from articulos where idArticulos=$idArticulo LIMIT 1";        
        $resultado=$this->db->query($sql);
        if($resultado->num_rows()>0)
        {
            $fila=$resultado->row();
            return ($fila->costoPromedioPonderado);
        }
        else
        {
            return 0;
        }
        
    }
    public function retornarSaldo($idArticulo,$idAlmacen) /*retorna tabla*/
    {
        $sql="SELECT saldo from saldoarticulos where idArticulo=$idArticulo and idAlmacen=$idAlmacen LIMIT 1";
        
        $resultado=$this->db->query($sql);
        if($resultado->num_rows()>0)
        {
            $fila=$resultado->row();
            return ($fila->saldo);
        }
        else
        {
            return 0;
        }
        
    }
    public function storeIngreso($ingreso)
	{	
        $this->db->trans_start();
            $this->db->insert("ingresos", $ingreso);
            $idIngreso=$this->db->insert_id();
            $ingresoDetalle = array();
                foreach ($ingreso->articulos as $fila) {
                    $detalle=new stdclass();
                    $detalle->idIngreso = $idIngreso;
                    $detalle->articulo = $fila[0];
                    $detalle->moneda = $ingreso->moneda;
                    $detalle->cantidad = $fila[3];
                    if ($ingreso->moneda == 2) {
                        $detalle->punitario= $fila[6] * $ingreso->tipoCambio;
                        $detalle->total=$fila[7] * $ingreso->tipoCambio;
                        $detalle->totaldoc=$fila[5] * $ingreso->tipoCambio;
                        
                    }	elseif ($ingreso->moneda == 1) {
                        $detalle->punitario= $fila[6];
                        $detalle->total=$fila[7];
                        $detalle->totaldoc=$fila[5];
                    }
                    array_push($ingresoDetalle,$detalle);	
                }
            $this->db->insert_batch("ingdetalle", $ingresoDetalle);
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        } else {
            
            return $idIngreso;
        }
	}
    public function getGestionActual()
    {
        $sql="SELECT gestionActual FROM config LIMIT 1";
        
        $sql=$this->db->query($sql);
        return $sql->row();
    }

    
}
