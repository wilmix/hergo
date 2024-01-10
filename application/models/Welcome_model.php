<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Welcome_model extends CI_Model  
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
    public function kardexByCode($alm,$mon,$ini,$fin) 
	{ 
		$sql="CALL kardexByCode('$alm','$mon','$ini','$fin');";
		mysqli_next_result( $this->db->conn_id );
		$query=$this->db->query($sql);	
		$res = $query->result();
		$query->next_result(); 
		$query->free_result(); 
		return $res;
	}
	public function update_date($id, $data)
	{
		$this->db->where('idIngresos', $id);
		$this->db->update('ingresos', $data);
	}
	public function kardex($almacenes, $articulo) {
		 // Formatear la cadena SQL con los valores proporcionados
		$sql = "CALL newKardex(" . $this->db->escape($almacenes) . ", '0', '2023-01-01', '2023-12-31', " . $this->db->escape($articulo) . ")";
    	$query = $this->db->query($sql, array($almacenes, $articulo));
        
		mysqli_next_result( $this->db->conn_id );
		$query=$this->db->query($sql);	
		$result = $query->result();
		$query->next_result(); 
		$query->free_result();

		if ($result) {
			// Construir la consulta adicional
			$this->db->select(' almacen_id,
								id, 
								fechakardex,
								idArticulo, 
								idDetalle, 
								cantidad, 
								punitario,
								_auxCOPP2 cpp,
								(cantidad * _auxCOPP2) totalCorrecto,
								(cantidad * punitario) as totalActual');
			$this->db->from('kardex3');
			$this->db->where('idDetalle IS NOT NULL');
			$this->db->where('tipo', 'ET');
			$this->db->where('operacion', '-');
	
			// Ejecutar la consulta adicional
			$kardex = $this->db->get();
		}

		return $kardex->result();
	}
	public function encontrarTraspaso($egreso_id) : array {
		$sql="	SELECT
					*
				FROM
					traspasos t
				WHERE
					t.idEgreso = '$egreso_id';";

        $traspaso=$this->db->query($sql);

		return $traspaso->result_array();
	}
	public function getEgreso($egreso_id, $articulo_id, $cantidad, $cpp) : array {
		$sql="	SELECT
					ed.idegreso,
					ed.idingdetalle egreso_id_detalle,
					ed.articulo,
					ed.cantidad,
					ed.punitario,
					ed.total,
					$cpp nuevo_punitario,
					($cpp * ed.cantidad) nuevo_total

				FROM
					egredetalle ed
				WHERE
					ed.idegreso = '$egreso_id'
					AND ed.articulo = '$articulo_id'
					AND ed.cantidad = '$cantidad';";

        $traspaso=$this->db->query($sql);

		return $traspaso->result();
	}
	public function getIngreso($ingreso_id, $articulo_id, $cantidad, $cpp) : array {
		$sql="	SELECT
					id.idIngreso,
					id.idingdetalle ingreso_id_detalle,
					id.articulo,
					id.cantidad,
					id.punitario,
					id.total,
					id.totaldoc,
					$cpp nuevo_punitario,
					($cpp * id.cantidad) nuevo_total
				FROM
					ingdetalle id
				WHERE
					id.idIngreso = '$ingreso_id'
					AND id.articulo = '$articulo_id'
					AND id.cantidad = '$cantidad';";

        $traspaso=$this->db->query($sql);

		return $traspaso->result();
	}
	public function actualizarEgresoDetalle($egreso_id, $articulo_id, $cantidad, $cpp) {
        $data = array(
            'punitario' => $cpp,
            'total' => $cpp * $cantidad
        );

        $this->db->where('idegreso', $egreso_id);
        $this->db->where('articulo', $articulo_id);
        $this->db->where('cantidad', $cantidad);
        $this->db->update('egredetalle', $data);
    }
	public function actualizarIngresoDetalle($ingreso_id, $articulo_id, $cantidad, $cpp) {
        $data = array(
            'punitario' => $cpp,
            'total' => $cpp * $cantidad,
			'totaldoc' => $cpp * $cantidad
        );

        $this->db->where('idIngreso', $ingreso_id);
        $this->db->where('articulo', $articulo_id);
        $this->db->where('cantidad', $cantidad);
        $this->db->update('ingdetalle', $data);
    }
	public function newKardex($almacenes, $articulo, $ini, $fin, $moneda) {
		// Formatear la cadena SQL con los valores proporcionados
	   $sql = "CALL newKardex(" . $this->db->escape($almacenes) . ", '$moneda', '$ini', '$fin', " . $this->db->escape($articulo) . ")";
	   $query = $this->db->query($sql, array($almacenes, $articulo));
	   
	   mysqli_next_result( $this->db->conn_id );
	   $query=$this->db->query($sql);	
	   $result = $query->result();
	   $query->next_result(); 
	   $query->free_result();

	   return $result;
   }
   function allCodigos($almacenes, $articulo, $ini, $fin, $moneda) {
		$result = $this->newKardex($almacenes, $articulo, $ini, $fin, $moneda);

		 if ($result) {
			$this->db->select('codigo');
			$this->db->from('kardex3');
			$this->db->where('idDetalle IS NULL');
			//$this->db->where("SUBSTRING(codigo,1,2) = 'AR'", NULL, FALSE);
			//$this->db->where('codigo', 'TM1100');
			$codigos = $this->db->get()->result();
		} 

		return $codigos;
   }
   function pruebaLogs() : array {
	try {
		// Tu código de consulta a la base de datos
		$this->db->select('*');
		$this->db->from('almacene');
		$almacenes = $this->db->get()->result();
		return $almacenes;
	} catch (Exception $e) {
		log_message('error', 'BBDD: ' . $e->getMessage());
	}
   }
   public function updateSaldos($ingresos, $egresos)
	{	
        $this->db->trans_start();
            $this->db->query("SET FOREIGN_KEY_CHECKS=0;");
            $this->db->query("TRUNCATE TABLE saldoarticulos;");   
            $this->db->query("SET FOREIGN_KEY_CHECKS=1;");

            foreach ($ingresos as $value) {
                $this->db->query("CALL actualizarTablaSaldoIngreso($value->idIngresos, $value->almacen);");
            }
            foreach ($egresos as $value) {
                $this->db->query("CALL actualizarTablaSaldoEgreso($value->idegresos, $value->almacen);");
            }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return  print_r('No se pudo realizar la actualizacion de saldos');
        } else {
            return print_r('Se realizó la actualizacion de saldos exitosamente');
        }
    }
}