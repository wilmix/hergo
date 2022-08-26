<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Sincronizar_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
    public function storeActividades($data)
	{
            $this->db->insert_batch('siat_sincro_actividades', $data);
            /* $id=$this->db->insert_id();
            return $id; */
	}
    public function storeActividadesDocumentoSector($data)
	{
            $this->db->insert_batch('siat_sincro_actividades_documento_sector', $data);

	}
    public function storeListaLeyendasFactura($data)
	{
            $this->db->insert_batch('siat_sincro_leyenda_facturas', $data);

	}
    public function storeMensajesServicios($data)
	{
            $this->db->insert_batch('siat_sincro_mensajes_servicio', $data);

	}
    public function storeParametricas($data, $table)
	{
            $this->db->insert_batch($table, $data);

	}
    public function storeListaProductosServicios($data)
	{
        try{
            //return $data;
            $this->db->insert('siat_sincro_productos_servicios', $data);
        } catch(Exception  $e){
            echo $e->getMessage();
        }

	}
    public function getActividades()
    {
        $sql =	"   SELECT
                        ssa.codigoCaeb,
                        ssa.descripcion,
                        ssa.tipoActividad
                    FROM
                        siat_sincro_actividades ssa
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
    }
    public function getActividadesDocumentoSector()
    {
        $sql =	"SELECT
                    a.codigoActividad,
                    a.codigoDocumentoSector,
                    a.tipoDocumentoSector
                FROM
                    siat_sincro_actividades_documento_sector a 
                    ORDER BY a.id";
        $query=$this->db->query($sql);		
        return $query->result_array();
    }
    public function getListaLeyendasFactura()
    {
        $sql =	"SELECT
                    a.codigoActividad,
                    a.descripcionLeyenda
                FROM
                    siat_sincro_leyenda_facturas a 
                ORDER BY a.id";
        $query=$this->db->query($sql);		
        return $query->result_array();
    }
    public function getListaMensajesServicios()
    {
        $sql =	"SELECT
                    a.codigoClasificador,
                    a.descripcion
                FROM
                    siat_sincro_mensajes_servicio a 
                ORDER BY a.id";
        $query=$this->db->query($sql);		
        return $query->result_array();
    }
    public function getlistaProductosServicios()
    {
        $sql =	"SELECT
                    a.codigoActividad,
                    a.codigoProducto,
                    a.descripcionProducto
                FROM
                    siat_sincro_productos_servicios a 
                ORDER BY a.id";
        $query=$this->db->query($sql);		
        return $query->result_array();
    }
    public function getListaParametricas($tabla)
    {
        $sql =	"SELECT
                    a.codigoClasificador,
                    a.descripcion
                FROM
                    $tabla a 
                ORDER BY a.id";
        $query=$this->db->query($sql);		
        return $query->result_array();
    }


}
