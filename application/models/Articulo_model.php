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
		$sql="SELECT
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
					a.ImagenUrl,
					a.Fecha,
					a.idUnidad,
					a.idMarca,
					a.idLinea,
					a.web_catalogo,
					a.codigoCaeb,
					a.codigoProducto codigoProductoSiat,
					CONCAT(ssa.codigoCaeb, ' | ', ssa.descripcion) actividadCaeb,
					CONCAT(sps.codigoProducto, ' | ', sps.descripcionProducto) codigoDescProductoSiat,
					CONCAT(us.first_name, ' ', us.last_name) AS autor,
					precio
				FROM
					articulos a
					INNER JOIN unidad u ON a.idUnidad = u.idUnidad
					INNER JOIN marca m ON a.idMarca = m.idMarca
					INNER JOIN linea l ON a.idLinea = l.idLinea
					INNER JOIN requisito r ON a.idRequisito = r.idRequisito
					INNER JOIN users us ON a.Autor = us.id
					LEFT JOIN siat_sincro_actividades ssa ON ssa.codigoCaeb = a.codigoCaeb
					LEFT JOIN siat_sincro_productos_servicios sps ON sps.codigoProducto = a.codigoProducto
				WHERE
					a.EnUso LIKE '%$uso'
				GROUP BY a.idArticulos
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
	public function getCodigosSiat($codigoActividad)
	{
		$sql="SELECT
				*
			FROM
				siat_sincro_productos_servicios ps
			WHERE
				ps.codigoActividad = '$codigoActividad'
				";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function getArticuloById($id)
	{
		$sql = "SELECT
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
					a.ImagenUrl,
					a.Fecha,
					a.idUnidad,
					a.idMarca,
					a.idLinea,
					a.web_catalogo,
					a.codigoCaeb,
					a.codigoProducto codigoProductoSiat,
					CONCAT(ssa.codigoCaeb, ' | ', ssa.descripcion) actividadCaeb,
					CONCAT(sps.codigoProducto, ' | ', sps.descripcionProducto) codigoDescProductoSiat,
					CONCAT(us.first_name, ' ', us.last_name) AS autor,
					precio
				FROM
					articulos a
					INNER JOIN unidad u ON a.idUnidad = u.idUnidad
					INNER JOIN marca m ON a.idMarca = m.idMarca
					INNER JOIN linea l ON a.idLinea = l.idLinea
					INNER JOIN requisito r ON a.idRequisito = r.idRequisito
					INNER JOIN users us ON a.Autor = us.id
					LEFT JOIN siat_sincro_actividades ssa ON ssa.codigoCaeb = a.codigoCaeb
					LEFT JOIN siat_sincro_productos_servicios sps ON sps.codigoProducto = a.codigoProducto
				WHERE
					a.idArticulos = $id";
		
		$query = $this->db->query($sql);	
		return $query->row();
	}
	/**
	 * Obtiene un artículo por su ID
	 * 
	 * Versión simplificada que devuelve solo los campos básicos del artículo
	 * 
	 * @param int $id ID del artículo
	 * @return object Datos del artículo
	 */
	public function getById($id)
	{
		$this->db->select('idArticulos, Imagen, ImagenUrl');
		$this->db->from('articulos');
		$this->db->where('idArticulos', $id);
		$query = $this->db->get();
		
		return $query->row();
	}
}
