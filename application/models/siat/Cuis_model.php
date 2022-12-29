<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Cuis_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
    public function store($cuis)
	{
            $this->db->insert('siat_cuis', $cuis);
            /* $id=$this->db->insert_id();
            return $id; */
	}
	public function editEstadoCuis($id)
	{
		$this->db->set('active', '0', FALSE);
		$this->db->where('id', $id);
		$this->db->update('siat_cuis');
	}
	public function cierrePuntoVenta($cuis, $codigoSucursal, $codigoPuntoVenta)
	{
		$this->db->set('active', '0', FALSE);
		$this->db->where('sucursal', $codigoSucursal);
		$this->db->where('codigoPuntoVenta', $codigoPuntoVenta);
		$this->db->update('siat_cuis');
	}
	public function search($cuis)
	{
		$sql =	"SELECT
					*
				FROM
					siat_cuis c
				where
					c.cuis = '$cuis'";
        $query=$this->db->query($sql);		
        return $query->row();
	}

}
