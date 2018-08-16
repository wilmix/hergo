<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class DatosFactura_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function obtenerUltimoLote()
	{
		$sql="SELECT * from datosfactura ORDER BY idDatosFactura desc Limit 1";
		$query=$this->db->query($sql);
        if($query->num_rows()>0)
        {
            $fila=$query->row();
            return ($fila);
        }
        else
        {
            return 0;
        }
	}
	public function obtenerUltimoLote2($idAlmacen, $tipoFacturacion) 
	{
		/*
		Tipo de facturacion: 
			manual=1
			QR=0
		*/
		$sql="SELECT * 
			FROM datosfactura 
			WHERE almacen = $idAlmacen
			AND manual=$tipoFacturacion
			ORDER BY idDatosFactura desc Limit 1";
		$query=$this->db->query($sql);
        if($query->num_rows()>0)
        {
            $fila=$query->row();
            return ($fila);
        }
        else
        {
            return false;
        }
	}
	public function obtenerLote($lote) 
	{
		/*
		Tipo de facturacion: 
			manual=1
			QR=0
		*/
		$sql="SELECT * 
			FROM datosfactura 
			WHERE idDatosFactura=$lote";
		$query=$this->db->query($sql);
        if($query->num_rows()>0)
        {
            $fila=$query->row();
            return ($fila);
        }
        else
        {
            return false;
        }
	}
	public function actualizarEnUso($idDatosFactura)
	{
		$sql="UPDATE datosfactura set enUso=1 where idDatosFactura=$idDatosFactura";
       
		$query=$this->db->query($sql);
        
        return $query;
	}
	
}
