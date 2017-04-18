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
	public function mostrarIngresos($id=null,$ini=null,$fin=null,$alm="",$tin="")
	{
		if($id==null) //no tiene id de entrada
        {
		  $sql="SELECT i.nmov n,i.idIngresos,t.sigla,t.tipomov, i.fechamov, p.nombreproveedor, i.nfact,
				(SELECT SUM(d.total) from ingdetalle d where  d.idIngreso=i.idIngresos) total, i.estado,i.fecha, CONCAT(u.first_name,' ', u.last_name) autor, i.moneda, a.almacen, m.sigla monedasigla, i.ordcomp,i.ningalm, i.obs, i.anulado
			FROM ingresos i
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
            WHERE i.fechamov BETWEEN '$ini' AND '$fin' and i.almacen like '%$alm' and t.id like '%$tin'
			ORDER BY i.idIngresos DESC
            ";

        }
        else
        {
            $sql="SELECT i.nmov n,i.idIngresos,t.sigla,t.tipomov,t.id as idtipomov, i.fechamov, p.nombreproveedor,p.idproveedor, i.nfact,
				(SELECT FORMAT(SUM(d.total),2) from ingdetalle d where  d.idIngreso=i.idIngresos) total, i.estado,i.fecha, CONCAT(u.first_name,' ', u.last_name) autor, i.moneda, m.id as idmoneda, a.almacen, a.idalmacen, m.sigla monedasigla, i.ordcomp,i.ningalm, i.obs, i.anulado
			FROM ingresos i
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
        //if($id==null) //no tiene id de entrada
        //{
         /* $sql="SELECT  i.fechamov, p.nombreproveedor, i.nfact, CONCAT(u.first_name,' ', u.last_name) autor, i.fecha
            FROM ingresos i
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
            WHERE i.fechamov BETWEEN '$ini' AND '$fin' and i.almacen like '%$alm' and t.id like '%$tin'
            ORDER BY i.idIngresos DESC
            ";
*/
       // }
        $sql="SELECT *
                FROM (SELECT i.idIngresos, i.fechamov, p.nombreproveedor, i.nfact, CONCAT(u.first_name,' ', u.last_name) autor, i.fecha FROM ingresos i INNER JOIN tmovimiento t ON i.tipomov = t.id INNER JOIN provedores p ON i.proveedor=p.idproveedor INNER JOIN users u ON u.id=i.autor INNER JOIN almacenes a ON a.idalmacen=i.almacen INNER JOIN moneda m ON i.moneda=m.id WHERE i.fechamov BETWEEN '$ini' AND '$fin' and i.almacen like '%$alm' and t.id like '%$tin' ORDER BY i.idIngresos DESC) tabla
                INNER JOIN ingdetalle id
                ON tabla.idIngresos=id.idIngreso
                INNER JOIN articulos ar
                ON ar.idArticulos=id.articulo";
        
        $query=$this->db->query($sql);
        return $query;
    }
	public function mostrarDetalle($id)
	{
		$sql="SELECT a.CodigoArticulo, a.Descripcion, i.cantidad,i.totaldoc, FORMAT(i.punitario,3) punitario, FORMAT(i.total,3) total
		FROM ingdetalle i
		INNER JOIN articulos a
		ON i.articulo = a.idArticulos
 		WHERE idIngreso=$id";
		$query=$this->db->query($sql);
		return $query;
	}
	public function editarestado_model($d, $id)
	{
		$sql="UPDATE ingresos SET estado='$d'WHERE idIngresos=$id";
		$query=$this->db->query($sql);
		return $query;
	}
	/*public function agregarArticulo_model($id,$codigo,$descripcion,$unidad,$marca,$linea,$parte,$posicion,$autoriza,$proser,$uso,$nom_imagen)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="INSERT INTO articulos (CodigoArticulo, Descripcion, NumParte, idUnidad, idMarca, idLinea, PosicionArancelaria, idRequisito, ProductoServicio, EnUso, detalleLargo, Autor, Fecha,Imagen) VALUES('$codigo','$descripcion','$parte','$unidad','$marca','$linea','$posicion','$autoriza','$proser','$uso','','$autor','$fecha','$nom_imagen')";
		$query=$this->db->query($sql);
	}
	public function editarArticulo_model($id,$codigo,$descripcion,$unidad,$marca,$linea,$parte,$posicion,$autoriza,$proser,$uso,$nom_imagen)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		if($nom_imagen=="")
			$sql="UPDATE articulos SET CodigoArticulo='$codigo', Descripcion='$descripcion', NumParte='$parte', idUnidad='$unidad', idMarca='$marca', idLinea='$linea', PosicionArancelaria='$posicion', idRequisito=$autoriza, ProductoServicio='$proser', EnUso='$uso', detalleLargo='???', Autor='$autor', Fecha='$fecha'  WHERE idArticulos=$id";
		else
			$sql="UPDATE articulos SET CodigoArticulo='$codigo', Descripcion='$descripcion', NumParte='$parte', idUnidad='$unidad', idMarca='$marca', idLinea='$linea', PosicionArancelaria='$posicion', idRequisito=$autoriza, ProductoServicio='$proser', EnUso='$uso', detalleLargo='???', Autor='$autor', Fecha='$fecha',Imagen='$nom_imagen'  WHERE idArticulos=$id";
		$query=$this->db->query($sql);
	}*/
    public function retornarArticulosBusqueda($b)
    {
		$sql="SELECT a.CodigoArticulo, a.Descripcion, u.Unidad
        FROM articulos a
        INNER JOIN unidad u
        ON a.idUnidad=u.idUnidad
        where CodigoArticulo like '$b%' or Descripcion like '$b%'";
		
		$query=$this->db->query($sql);
		return $query;
    }
    public function retornarcostoarticulo_model($id)
    {
        // quitar desc de la consulta para los ultimos datos de la tabla costoarticulo
        $sql="SELECT c.*
            FROM costoarticulos c
            WHERE c.idArticulo=$id
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
        $tipocambio=$this->retornarTipoCambio();

        
        $gestion= date("Y", strtotime($fechamov_imp));
       // echo $almacen_imp;
    	$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
        $nummov=$this->retornarNumMovimiento($tipomov_imp,$gestion,$almacen_imp);
    	$sql="INSERT INTO ingresos (almacen,tipomov,nmov,fechamov,proveedor,moneda,nfact,ningalm,ordcomp,obs,fecha,autor,tipocambio) VALUES('$almacen_imp','$tipomov_imp','$nummov','$fechamov_imp','$proveedor_imp','$moneda_imp','$nfact_imp','$ningalm_imp','$ordcomp_imp','$obs_imp','$fecha','$autor','$tipocambio')";
    	$query=$this->db->query($sql);
    	$idIngreso=$this->db->insert_id();
    	if($idIngreso>0)/**Si se guardo correctamente se guarda la tabla*/
    	{
            
    		foreach ($datos['tabla'] as $fila) {
    			//print_r($fila);
    			$idArticulo=$this->retornar_datosArticulo($fila[0]);
    			if($idArticulo)
    			{
    				$sql="INSERT INTO ingdetalle(idIngreso,nmov,articulo,moneda,cantidad,punitario,total,totaldoc) VALUES('$idIngreso','0','$idArticulo','$moneda_imp','$fila[2]','$fila[5]','$fila[6]','$fila[4]')";
    				$this->db->query($sql);
    			}
    		}
    		return true;
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


        //$sql="UPDATE ingresos SET almacen='$almacen_imp',tipomov='$tipomov_imp',fechamov='$fechamov_imp',proveedor='$proveedor_imp',moneda='$moneda_imp',nfact='$nfact_imp',ningalm='$ningalm_imp',ordcomp='$ordcomp_imp',obs='$obs_imp',fecha='$fecha',autor='$autor' where idIngresos='$idingresoimportacion'";

        $sql="UPDATE ingresos SET proveedor='$proveedor_imp',moneda='$moneda_imp',nfact='$nfact_imp',ningalm='$ningalm_imp',ordcomp='$ordcomp_imp',obs='$obs_imp',fecha='$fecha',autor='$autor' where idIngresos='$idingresoimportacion'";

    	$query=$this->db->query($sql);

        $sql="DELETE FROM ingdetalle where idIngreso='$idingresoimportacion'";

        $this->db->query($sql);

        foreach ($datos['tabla'] as $fila)
        {
            $idArticulo=$this->retornar_datosArticulo($fila[0]);
            if($idArticulo)
            {
               // $sql="INSERT INTO ingdetalle(idIngreso,articulo,moneda,cantidad,punitario,total) VALUES('$idingresoimportacion','$idArticulo','$moneda_imp','$fila[2]','$fila[3]','$fila[4]')";
                $sql="INSERT INTO ingdetalle(idIngreso,nmov,articulo,moneda,cantidad,punitario,total,totaldoc) VALUES('$idIngreso','0','$idArticulo','$moneda_imp','$fila[2]','$fila[5]','$fila[6]','$fila[4]')";
                $this->db->query($sql);
            }
        }
        return true;

    }
    public function anularRecuperarMovimiento_model($datos,$anuladorecuperado)
    {
        $idingresoimportacion=$datos['idingresoimportacion'];
        //$almacen_imp=$datos['almacen_imp'];
        $tipomov_imp=$datos['tipomov_imp'];
        //$fechamov_imp=$datos['fechamov_imp'];
        $moneda_imp=$datos['moneda_imp'];
        $proveedor_imp=$datos['proveedor_imp'];
        $ordcomp_imp=$datos['ordcomp_imp'];
        $nfact_imp=$datos['nfact_imp'];
        $ningalm_imp=$datos['ningalm_imp'];
        $obs_imp=$datos['obs_imp'];
        $autor=$this->session->userdata('user_id');
        $fecha = date('Y-m-d H:i:s');
        $sql="UPDATE ingresos SET proveedor='$proveedor_imp',moneda='$moneda_imp',nfact='$nfact_imp',ningalm='$ningalm_imp',ordcomp='$ordcomp_imp',obs='$obs_imp',fecha='$fecha',autor='$autor', anulado='$anuladorecuperado' where idIngresos='$idingresoimportacion'";
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
    public function actualizartablacostoarticulo($idArticulo,$cantidad,$costou,$total,$idalmacen)
    {
        $sql="INSERT INTO costoarticulos(idArticulo,idAlmacen,cantidad,total,precioUnitario) VALUES('$idArticulo','$idalmacen','$cantidad','$total','$costou')";
        $this->db->query($sql);
    }
}
