<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Admin_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function showPrecioArticulos()
	{
		$sql="  SELECT
                    a.idArticulos id,
                    a.CodigoArticulo codigo,
                    u.Sigla uni,
                    a.Descripcion descrip, 
                    a.precio precioBob,
                    a.precioDol,
                    ROUND(a.costo / (0.84 - (a.porcentaje/100)),2) sugerido,
                    a.costo,
                    a.porcentaje
                FROM
                    articulos a
                    inner JOIN unidad u on u.idUnidad = a.idUnidad
                WHERE
                    a.EnUso = 1
                ORDER BY a.idArticulos";
		
		$query=$this->db->query($sql);		
		return $query;
	}
    public function update($id, $item)
	{	
        $this->db->trans_start();
            $this->db->where('idArticulos', $id);
            $this->db->update('articulos', $item);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        } else {
            
            return $id;
        }
    }

}