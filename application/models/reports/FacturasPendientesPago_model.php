<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class FacturasPendientesPago_model extends CI_Model  
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function facturasPendientesPago($almacen, $ini, $fin){
		$res = $this->mostrarFacturasPendientesPago($almacen, $ini, $fin)->result();
		$aux = 0;
		$auxD = 0;
		foreach ($res as $line) {
				if ($line->id == NULL && $line->cliente == NULL) {
					$line->lote = '';
					$line->nFactura = '';
					$line->fechaFac = '';
					$line->vendedor = '';
					$line->almacen = '';
					$line->cliente = 'TOTAL GENERAL';
					$line->saldo = $line->total - $line->montoPagado;
					$line->saldoDol = $line->totalFacDol - $line->montoPagoDol;
				} elseif ($line->id == NULL) {
					$line->lote = '';
					$line->nFactura = '';
					$line->fechaFac = '';
					$line->vendedor = '';
					$line->almacen = '';
					$line->saldo = $line->total - $line->montoPagado;
					$line->saldoDol = $line->totalFacDol - $line->montoPagoDol;
				} else {
					$line->cliente = $line->cliente;
					$line->saldo = $aux + $line->total - $line->montoPagado;
					$line->saldoDol = $auxD + $line->totalFacDol - $line->montoPagoDol;
				}
				$aux = $line->id == NULL ? 0 : $aux + $line->total - $line->montoPagado;
				$auxD = $line->id == NULL ? 0 : $auxD + $line->totalFacDol - $line->montoPagoDol;
		}
		return $res;
	}
	public function getFacturasPendientesPago($almacen, $ini, $fin,$tipoEgreso)
	{
		$sql="  SELECT
                    idEgresos,
                    tipomov,
                    concat(sigla, '-', nmov, '/', gestion) egreso,
                    idFactura,
                    almacen,
                    cliente,
                    lote,
                    nFactura,
                    fechaFac,
                    SUM(total) total,
                    SUM(montoPagado) montoPagado,
                    vendedor,
                    diasCredito,
                    DATE_ADD(fechaFac, INTERVAL diasCredito DAY) fechaVencimiento,
                    IF(
                        DATE_ADD(fechaFac, INTERVAL diasCredito DAY) < CURDATE(),
                        'VENCIDA',
                        'VIGENTE'
                    ) estado
                FROM
                    (
                        select
                            e.idegresos idEgresos,
                            e.tipomov,
                            tm.sigla,
                            e.nmov,
                            SUBSTRING(e.gestion, 3, 2) gestion,
                            f.idFactura,
                            a.almacen,
                            f.ClienteFactura cliente,
                            f.lote,
                            f.nFactura,
                            f.fechaFac,
                            f.total,
                            c.diasCredito,
                            tbl.totalPagado montoPagado,
                            CONCAT(u.first_name, ' ', u.last_name) vendedor,
                            round(f.total - tbl.totalPagado, 2) diff,
                            tbl.id,
                            f.pagada
                        from
                            factura f
                            inner join factura_egresos fe on fe.idFactura = f.idFactura
                            inner join egresos e on e.idegresos = fe.idegresos
                            inner join tmovimiento tm on tm.id = e.tipomov
                            inner join almacenes a on a.idalmacen = f.almacen
                            inner join clientes c on c.idCliente = f.cliente
                            inner join users u ON u.id = e.vendedor
                            left join (
                                select
                                    pf.id,
                                    pf.idFactura,
                                    sum(pf.monto) totalPagado
                                from
                                    pago_factura pf
                                group by
                                    pf.idFactura
                            ) tbl on tbl.idFactura = f.idFactura
                        WHERE
                            f.`fechaFac` BETWEEN '$ini'	AND '$fin'
                            AND f.anulada = 0
                            AND f.`almacen` LIKE '%$almacen%'
							AND e.tipomov LIKE '%$tipoEgreso%'
                        GROUP by
                            f.ClienteFactura,
                            f.idFactura
                        HAVING
                            diff is null
                            or diff > 0
                        ORDER BY
                            f.ClienteFactura
                    ) tbla
                GROUP BY
                    cliente,
                    idFactura WITH ROLLUP";
		$query=$this->db->query($sql);		
		return $query->result();
	}
}							