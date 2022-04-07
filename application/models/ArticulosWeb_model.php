<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class ArticulosWeb_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		//date_default_timezone_set("America/La_Paz");
	}
	public function show($table)
	{
		if ($table == 'web_nivel1') {
			$sql="  SELECT * , '$table' level
					FROM $table
					";
		} else if($table == 'web_nivel2'){
			$sql=" SELECT
						n2.*,
    					n1.name label, 
						'$table' level
					FROM
						web_nivel2 n2
						INNER JOIN web_nivel1 n1 ON n1.id = n2.id_nivel1
					";
		} else if($table == 'web_nivel3'){
			$sql=" SELECT
						n3.*,
    					n2.name label, 
						'$table' level
					FROM
						web_nivel3 n3
						INNER JOIN web_nivel2 n2 ON n2.id = n3.id_nivel2
					";
		}
		
		$query=$this->db->query($sql);		
		return $query->result_array();
	}
	public function store($item, $table)
	{
            $this->db->insert($table, $item);
            /* $id=$this->db->insert_id();
            return $id; */
	}
	public function update($table, $id, $item)
	{
		$this->db->where('id', $id);
		$res = $this->db->update($table, $item);
		return $res;
	}
	public function showItems()
	{
		$sql =	"SELECT
					a.idArticulos articulo_id_sis,
					a.CodigoArticulo codigo_sis,
					a.Descripcion descripcion_sis,
					a.EnUso is_active_sis,
					a.Imagen img_sis,
					a.web_catalogo,
					wa.titulo,
					wa.descripcion,
					n1.name n1,
					n2.name n2,
					n3.name n3,
					CONCAT(uc.first_name, ' ', uc.last_name) created_by,
					CONCAT(uu.first_name, ' ', uu.last_name) updated_by,
					wa.created_at,
					wa.updated_at
				FROM
					web_articulos wa
					RIGHT JOIN articulos a ON a.idArticulos = wa.articulo_id 
					LEFT JOIN web_nivel1 n1 ON n1.id = wa.n1_id
					LEFT JOIN web_nivel2 n2 ON n2.id = wa.n2_id
					LEFT JOIN web_nivel3 n3 ON n3.id = wa.n3_id
					LEFT JOIN users uc ON uc.id = wa.created_by
    				LEFT JOIN users uu ON uu.id = wa.updated_by
				WHERE a.web_catalogo = 1
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
	}
	public function getDataLevels($table)
	{
		$sql="  SELECT
					n.id,
					n.name label
				FROM
					$table n
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
	}
}
