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
	public function mostrarIngresos()
	{
		
		$sql="SELECT i.nmov n,i.idIngresos,t.sigla,t.tipomov, DATE_FORMAT(i.fechamov,'%d/%m/%Y') fechamov, p.nombreproveedor, i.nfact,
				(SELECT FORMAT(SUM(d.total),2) from ingdetalle d where  d.idIngreso=i.idIngresos) total, i.estado,i.fecha, CONCAT(u.last_name,' ', u.first_name) autor, i.moneda, a.almacen, m.sigla monedasigla, i.ordcomp,i.ningalm
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
";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarDetalle($id)
	{
		$sql="SELECT a.CodigoArticulo, a.Descripcion, i.cantidad, FORMAT(i.punitario,2) punitario, FORMAT(i.total,2) total
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
}
