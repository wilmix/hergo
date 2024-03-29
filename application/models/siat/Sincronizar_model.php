<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Sincronizar_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
    public function sincronizacionesSiat($tabla_id)
	{
        $data = new stdclass();
        $data->sincro_tabla_id = $tabla_id;
        $this->db->insert('sincronizaciones_siat', $data);
	}
    public function storeActividades($data)
	{
        $this->db->trans_start();
            $this->deleteTable('siat_sincro_actividades');
            $this->db->insert_batch('siat_sincro_actividades', $data);
        $this->db->trans_complete();
	}
    public function storeData($data, string $table)
	{
        $this->db->trans_start();
            $this->deleteTable($table);
            $this->db->insert_batch($table, $data);
        $this->db->trans_complete();
	}
    public function storeActividadesDocumentoSector($data)
	{
        $this->db->trans_start();
            $this->deleteTable('siat_sincro_actividades_documento_sector');
            $this->db->insert_batch('siat_sincro_actividades_documento_sector', $data);
        $this->db->trans_complete();
	}
    public function storeListaLeyendasFactura($data)
	{
        $this->db->trans_start();
            $this->deleteTable('siat_sincro_leyenda_facturas');
            $this->db->insert_batch('siat_sincro_leyenda_facturas', $data);
        $this->db->trans_complete();
	}
    public function storeMensajesServicios($data)
	{
        $this->db->trans_start();
            $this->deleteTable('siat_sincro_mensajes_servicio');
            $this->db->insert_batch('siat_sincro_mensajes_servicio', $data);
        $this->db->trans_complete();
	}
    public function storeParametricas($data, $table)
	{
        $this->db->trans_start();
            $this->deleteTable($table);
            $this->db->insert_batch($table, $data);
        $this->db->trans_complete();
	}
    public function storeListaProductosServicios($data)
	{
        ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
        $this->db->trans_start();
            $this->deleteTable('siat_sincro_productos_servicios');
            foreach ($data as $value) {
                $this->db->insert('siat_sincro_productos_servicios', $value);
            }
            //$this->db->insert_batch('siat_sincro_productos_servicios', $data);
        $this->db->trans_complete();       
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
    public function deleteTable($table)
    {
        $sql =	"DELETE FROM $table";
        $query=$this->db->query($sql);
    }
    public function sincroCatalogosUltimas24Horas()
    {
        $sql =	"SELECT
                    MAX(ss.id) sincro_id,
                    sts.id,
                    sts.tabla,
                    DATE_FORMAT(MAX(ss.created_at), '%d/%m/%Y') created_at
                FROM
                    sincronizaciones_siat ss
                    INNER JOIN siat_tablas_sincro sts ON sts.id = ss.sincro_tabla_id
                WHERE
                    ss.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                GROUP BY
                    ss.sincro_tabla_id";
        $query=$this->db->query($sql);		
        return $query->result();
    }


}
