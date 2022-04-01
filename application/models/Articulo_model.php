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
	public function mostrarArticulos($uso)
	{
		$sql="	SELECT
					a.idArticulos,
					a.CodigoArticulo,
					a.Descripcion,
					a.detalleLargo descripcionFabrica,
					a.NumParte,
					u.Unidad,
					m.Marca,
					l.Linea,
					a.PosicionArancelaria,
					r.Requisito,
					a.ProductoServicio,
					a.detalleLargo,
					a.EnUso,
					a.Imagen,
					a.Fecha,
					a.idUnidad,
					a.idMarca,
					a.idLinea,
					a.web_catalogo,
					CONCAT(us.first_name, ' ', us.last_name) AS autor,
					precio
				FROM
					articulos a
					INNER JOIN unidad u ON a.idUnidad = u.idUnidad
					INNER JOIN marca m ON a.idMarca = m.idMarca
					INNER JOIN linea l ON a.idLinea = l.idLinea
					INNER JOIN requisito r ON a.idRequisito = r.idRequisito
					INNER JOIN users us ON a.Autor = us.id
				WHERE
					a.EnUso LIKE '%$uso'
				ORDER BY
					a.idArticulos DESC";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function store($item)
	{
		return $this->db->insert('articulos', $item);
	}
	public function update($id, $item)
	{
		$this->db->where('idArticulos', $id);
		$res = $this->db->update('articulos', $item);
		return $res;
	}
}
