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
		$sql="SET @row=0";
		$this->db->query($sql);
		$sql="SELECT @row := @row + 1 n,i.idIngresos,t.sigla, DATE_FORMAT(i.fechamov,'%d/%m/%Y') fechamov, p.nombreproveedor, i.nfact,
				(SELECT ROUND(SUM(d.total),2) from ingdetalle d where  d.idIngreso=i.idIngresos) total, i.estado,i.fecha, i.autor, i.moneda
			FROM ingresos i
			INNER JOIN tmovimiento  t
			ON i.tipomov = t.id
			INNER JOIN provedores p
			ON i.proveedor=p.idproveedor";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarDetalle($id)
	{
		$sql="SELECT a.CodigoArticulo, a.Descripcion, i.cantidad, i.punitario, ROUND(i.total,2) total
		FROM ingdetalle i
		INNER JOIN articulos a
		ON i.articulo = a.idArticulos
 		WHERE idIngreso=$id";
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
