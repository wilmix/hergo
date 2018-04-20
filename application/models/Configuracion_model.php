<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracion_model extends CI_Model
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
    public function mostrarDatosFactura(){
			$sql="SELECT idDatosFactura, a.almacen, nit , autorizacion, desde, hasta, fechaLimite, enUso, manual, llaveDosificacion, glosa01, glosa02, glosa03, df.fecha, CONCAT(u.first_name,' ',u.last_name) AS autor
			FROM datosfactura df 
			INNER JOIN almacenes a ON a.idalmacen= df.almacen 
			LEFT JOIN users u ON df.autor=u.id
			ORDER BY idDatosFactura DESC";
			$query=$this->db->query($sql);		
			return $query;
	}
	public function agregarDatosFactura_model($id_lote,$almacen,$autorizacion,$desde,$hasta,$fechaLimite,$tipo,$llave,$leyenda1,$leyenda2,$leyenda3,$uso){
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$nit = 1000991026;
		$sql="INSERT INTO datosfactura 
		(almacen, nit, autorizacion, desde, hasta, fechaLimite, enUso, manual, llaveDosificacion, glosa01, glosa02, glosa03, fecha, autor )
		VALUES
		('$almacen','$nit', '$autorizacion', '$desde', '$hasta', '$fechaLimite', '$uso', '$tipo', '$llave', '$leyenda1', '$leyenda2', '$leyenda3', '$fecha',' $autor')";
		$query=$this->db->query($sql);
	}
	public function editarDatosFactura_model($id_lote,$almacen,$autorizacion,$desde,$hasta,$fechaLimite,$tipo,$llave,$leyenda1,$leyenda2,$leyenda3,$uso){
		$autor=$this->session->userdata('user_id');
		$fecha = date('Y-m-d H:i:s');
		$nit = 1000991026;
		$sql="UPDATE datosfactura SET 
		almacen='$almacen' , autorizacion='$autorizacion' , desde='$desde' , hasta='$hasta' , fechaLimite='$fechaLimite' ,enUso='$uso' ,manual='$tipo' , llaveDosificacion='$llave' , glosa01='$leyenda1' , glosa02='$leyenda2' , glosa03='$leyenda3', fecha='$fecha', autor='$autor'
		WHERE idDatosFactura = $id_lote";
		$query=$this->db->query($sql);
	}
}
