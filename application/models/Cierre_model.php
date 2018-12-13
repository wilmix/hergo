<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cierre_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
		//date_default_timezone_set("America/La_Paz");
    }
    public function showPendientes($gestion)
	{
		$sql="SELECT 'INGRESOS' mov, a.`almacen`, UPPER(tm.`tipomov`) tipo, i.`fechamov` fecha, i.`nmov`, p.`nombreproveedor` nombre
        FROM ingresos i
        INNER JOIN almacenes a ON a.`idalmacen` = i.`almacen`
        INNER JOIN tmovimiento tm ON tm.`id` = i.`tipomov`
        INNER JOIN provedores p ON p.`idproveedor` = i.`proveedor`
        WHERE i.`estado` = 0
        AND i.`anulado` = 0
        AND YEAR(i.`fechamov`) = $gestion
        UNION ALL
        SELECT 'EGRESOS',a.`almacen`, UPPER(tm.`tipomov`), e.`fechamov`, e.`nmov`, c.`nombreCliente`
        FROM egresos e 
        INNER JOIN almacenes a ON a.`idalmacen` = e.`almacen`
        INNER JOIN tmovimiento tm ON tm.`id` = e.`tipomov`
        INNER JOIN clientes c ON c.`idCliente` = e.`cliente`
        WHERE e.`tipomov` = 6
        AND e.`anulado` = 0 
        AND e.`estado` <> 1
        AND YEAR(e.`fechamov`) = $gestion";

		$query=$this->db->query($sql);
		return $query;
    }
    
    public function showNegativos()
	{
		$sql="SELECT sa.`idArticulo` id,  a.`almacen`,ar.`CodigoArticulo` codigo,ar.`Descripcion`,  sa.`ingresos`, sa.`notaEntrega`, sa.`facturado`, sa.`otros`, sa.`saldo` saldoAlm
        ,ROUND((sa.`ingresos` - sa.`facturado` - sa.otros),2) saldo
        FROM saldoarticulos sa
        INNER JOIN almacenes a ON a.`idalmacen` = sa.`idAlmacen`
        INNER JOIN articulos ar ON ar.`idArticulos` = sa.`idArticulo`
        WHERE ROUND((sa.`ingresos` - sa.`facturado` - sa.otros),4) < 0
        ORDER BY almacen, codigo";

		$query=$this->db->query($sql);
		return $query;
    }
    public function showIdAlmacenes()
	{
		$sql="SELECT a.`idalmacen` id, a.`almacen`
        FROM almacenes a
        ORDER BY a.`idalmacen`
        ";
		$query=$this->db->query($sql);
		return $query;
    }
    public function itemsSaldos($alm)
	{
		$sql="SELECT 
        sa.`idArticulo` '0', a.`CodigoArticulo` '1', a.`Descripcion` '2', ROUND(SUM((sa.`saldo` +  sa.`notaEntrega`)),4) '3', 
        IFNULL(a.`costoPromedioPonderado`,0) '4',
        ((sa.`saldo` +  sa.`notaEntrega`)) * IFNULL(a.`costoPromedioPonderado`,0) '5',
        IFNULL(a.`costoPromedioPonderado`,0) '6',
        ((sa.`saldo` +  sa.`notaEntrega`)) * IFNULL(a.`costoPromedioPonderado`,0) '7'
        FROM saldoarticulos sa
        INNER JOIN articulos a ON a.`idArticulos` = sa.`idArticulo`
        WHERE sa.`idAlmacen` = '$alm'
        GROUP BY a.`CodigoArticulo`";
		$query=$this->db->query($sql);
		return $query;
	}

}
