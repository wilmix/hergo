<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Unidad_model extends CI_Model
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
	public function unidadesMedidaSiat()
	{
		$sql="  SELECT
                    u.codigoClasificador,
                    u.descripcion
                FROM
                    siat_sincro_unidad_medida u
				ORDER BY u.descripcion
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
    }
	public function unidadesSiat()
	{
		$sql="  SELECT
					u.idUnidad,
					u.Unidad,
					u.Sigla,
					siat.codigoClasificador siat_codigo,
					siat.descripcion siat_unidadMedida
				FROM
					unidad u 
					LEFT JOIN siat_sincro_unidad_medida siat ON u.siat_codigo = siat.codigoClasificador
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
    }
	public function store($data)
	{
		return $this->db->insert('unidad', $data);	
	}
	public function update($id, $data)
	{
		$this->db->where('idUnidad', $id);
        return $this->db->update('unidad', $data);
	}
}
