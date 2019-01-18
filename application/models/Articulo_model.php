<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Articulo_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		//date_default_timezone_set("America/La_Paz");
	}
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarArticulos()
	{
		$sql="SELECT a.idArticulos, a.CodigoArticulo, a.Descripcion, a.NumParte, u.Unidad, m.Marca, l.Linea, a.PosicionArancelaria, 
		r.Requisito, a.ProductoServicio, a.detalleLargo, a.EnUso,a.Imagen, a.Fecha , Concat(us.first_name,' ',us.last_name) as autor, precio
			FROM articulos a
			INNER JOIN unidad u
			ON a.idUnidad = u.idUnidad
			INNER JOIN marca m
			ON a.idMarca = m.idMarca
			INNER JOIN linea l
			ON a.idLinea = l.idLinea
			INNER JOIN requisito r
			ON a.idRequisito = r.idRequisito
            INNER JOIN users us
			ON a.Autor = us.id			
			ORDER BY a.idArticulos desc";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function agregarArticulo_model($id,$codigo,$descripcion,$unidad,$marca,$linea,$parte,$posicion,$autoriza,$proser,$uso,$nom_imagen,$precio)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$sql="INSERT INTO 
		articulos (CodigoArticulo, Descripcion, NumParte, idUnidad, idMarca, 
		idLinea, PosicionArancelaria, idRequisito, ProductoServicio, EnUso, 
		detalleLargo, Autor, Fecha,Imagen,precio) 
		VALUES('$codigo','$descripcion','$parte','$unidad','$marca',
		'$linea','$posicion','$autoriza','$proser','$uso',
		'','$autor','$fecha','$nom_imagen', '$precio')";
		$query=$this->db->query($sql);
	}
	public function editarArticulo_model($id,$codigo,$descripcion,$unidad,$marca,$linea,$parte,$posicion,$autoriza,$proser,$uso,$nom_imagen,$precio)
	{
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		if($nom_imagen=="") {
			$sql="UPDATE articulos SET CodigoArticulo='$codigo', Descripcion='$descripcion', NumParte='$parte', idUnidad='$unidad', 
			idMarca='$marca', idLinea='$linea', PosicionArancelaria='$posicion', idRequisito='$autoriza', ProductoServicio='$proser', 
			EnUso='$uso', detalleLargo='???', Autor='$autor', Fecha='$fecha', precio='$precio' WHERE idArticulos=$id";
		}	
		else {
			$sql="UPDATE articulos SET CodigoArticulo='$codigo', Descripcion='$descripcion', NumParte='$parte', idUnidad='$unidad', 
			idMarca='$marca', idLinea='$linea', PosicionArancelaria='$posicion', idRequisito='$autoriza', ProductoServicio='$proser', 
			EnUso='$uso', detalleLargo='???', Autor='$autor', Fecha='$fecha',Imagen='$nom_imagen', precio='$precio' WHERE idArticulos=$id";
		}
		$query=$this->db->query($sql);		
	}
}
