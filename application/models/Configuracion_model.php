<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracion_model extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
    }
    public function mostrarDatosFactura()
	{
		$sql="SELECT lote, a.almacen, nit , autorizacion, desde, hasta, fechaLimite, manual, llaveDosificacion, glosa01, glosa02, glosa03
        FROM datosfactura df
        INNER JOIN almacenes a ON a.idalmacen= df.almacen
        ORDER BY lote DESC";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	










}
