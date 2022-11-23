<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Cufd_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
    public function store($cufd)
	{
            $this->db->insert('siat_cufd', $cufd);
            /* $id=$this->db->insert_id();
            return $id; */
	}
    public function getCufdList()
    {
        $sql =	"SELECT
                    c.id,
                    a.almacen,
                    CONCAT('Sucursal - ',a.siat_sucursal) sucrusal,
                    cuis.codigoPuntoVenta,
                    c.codigo,
                    c.codigoControl,
                    a.siat_sucursal,
                    c.cuis,
                    c.created_at ini,
                    STR_TO_DATE(c.fechaVigencia,'%Y-%m-%dT%T') fin,
                    IF(STR_TO_DATE(c.fechaVigencia,'%Y-%m-%dT%T') > NOW(), 'VIGENTE', 'CADUCO') estado 
                FROM
                    siat_cufd c
                    INNER JOIN (
                        SELECT
                            MAX(c.id) cufd_id_last
                        FROM
                            siat_cufd c
                        GROUP BY
                            c.cuis
                    ) last_cufd ON last_cufd.cufd_id_last = c.id
                    INNER JOIN siat_cuis cuis on cuis.cuis = c.cuis AND cuis.active = 1
                    INNER JOIN almacenes a ON a.siat_sucursal = cuis.sucursal
        ";
        $query=$this->db->query($sql);		
        return $query->result();
    }
    public function getCufdLast()
    {
        $sql =	"SELECT
                    c.id,
                    a.almacen,
                    CONCAT('Sucursal - ',a.siat_sucursal) sucrusal,
                    cuis.codigoPuntoVenta,
                    c.codigo,
                    c.codigoControl,
                    a.siat_sucursal,
                    c.cuis,
                    c.created_at ini,
                    STR_TO_DATE(c.fechaVigencia,'%Y-%m-%dT%T') fin,
                    IF(STR_TO_DATE(c.fechaVigencia,'%Y-%m-%dT%T') > NOW(), 'VIGENTE', 'CADUCO') estado 
                FROM
                    siat_cufd c
                    INNER JOIN (
                        SELECT
                            MAX(c.id) cufd_id_last
                        FROM
                            siat_cufd c
                        GROUP BY
                            c.cuis
                    ) last_cufd ON last_cufd.cufd_id_last = c.id
                    INNER JOIN siat_cuis cuis on cuis.cuis = c.cuis AND cuis.active = 1
                    INNER JOIN almacenes a ON a.siat_sucursal = cuis.sucursal
        ";
        $query=$this->db->query($sql);		
        return $query->result();
    }


}
