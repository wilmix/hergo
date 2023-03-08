<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Reportes_model extends CI_Model  
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
	public function retornar_almacen($id)
	{
		$sql="SELECT * from almacenes where idalmacen = $id";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornarArticulos() {
		$sql="SELECT idArticulos, CodigoArticulo,Descripcion
		FROM articulos_activos_kardex
		";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornarClientes3() {
		$sql="SELECT f.`cliente`, c.`nombreCliente`, c.`documento`
			FROM factura f
				INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
				WHERE YEAR(f.`fechaFac`) BETWEEN (YEAR(NOW())-5) AND YEAR(NOW())
				GROUP BY f.`cliente`
				ORDER BY c.`nombreCliente`";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornar_tablaMovimiento($tipo)
    {
        $sql="SELECT * from tmovimiento where operacion='$tipo'";

		$query=$this->db->query($sql);
		return $query;
    }
	public function mostrarNEporFac($ini,$fin,$alm,$idCliente) 
	{ 
		if ($idCliente == 'all') {
			$sql="SELECT e.`cliente`, e.nmov n,e.idEgresos,t.sigla, e.fechamov, c.nombreCliente, ROUND((SUM(d.`total`)) - (SUM(d.`cantFact` * d.`punitario`)),2) total,  e.estado,e.fecha, 
		CONCAT(u.first_name,' ', u.last_name) autor, a.almacen, m.sigla monedasigla, ROUND(ROUND((SUM(d.`total`)) - (SUM(d.`cantFact` * d.`punitario`)),2) /tc.`tipocambio`,2) totalDol
		FROM egresos e
					INNER JOIN egredetalle d
					ON e.idegresos=d.idegreso
					INNER JOIN tmovimiento t 
					ON e.tipomov = t.id 
					INNER JOIN clientes c 
					ON e.cliente=c.idCliente
					INNER JOIN users u 
					ON u.id=e.vendedor 
					INNER JOIN almacenes a 
					ON a.idalmacen=e.almacen 
					INNER JOIN moneda m 
					ON e.moneda=m.id 
					INNER JOIN tipocambio tc
					ON e.`fechamov` = tc.`fecha`
					WHERE
					e.`estado`<>1
					AND t.id = 7
					AND e.anulado = 0
					AND e.fechamov 
					BETWEEN '$ini' AND '$fin'
					AND e.almacen LIKE '%$alm'
					GROUP BY c.nombreCliente, e.idegresos WITH ROLLUP";
		} else {
			$sql="SELECT e.`cliente`, e.nmov n,e.idEgresos,t.sigla, e.fechamov, c.nombreCliente, ROUND((SUM(d.`total`)) - (SUM(d.`cantFact` * d.`punitario`)),2) total,  e.estado,e.fecha, 
			CONCAT(u.first_name,' ', u.last_name) autor, a.almacen, m.sigla monedasigla, ROUND(ROUND((SUM(d.`total`)) - (SUM(d.`cantFact` * d.`punitario`)),2) /tc.`tipocambio`,2) totalDol
			FROM egresos e
					INNER JOIN egredetalle d
					ON e.idegresos=d.idegreso
					INNER JOIN tmovimiento t 
					ON e.tipomov = t.id 
					INNER JOIN clientes c 
					ON e.cliente=c.idCliente
					INNER JOIN users u 
					ON u.id=e.autor 
					INNER JOIN almacenes a 
					ON a.idalmacen=e.almacen 
					INNER JOIN moneda m 
					ON e.moneda=m.id 
					INNER JOIN tipocambio tc
					ON e.`fechamov` = tc.`fecha`
					WHERE
					e.`estado`<>1
					AND t.id = 7
					AND e.anulado = 0
					AND e.fechamov 
					BETWEEN '$ini' AND '$fin'
					AND e.almacen LIKE '%$alm'
					AND e.`cliente` = '$idCliente'
					GROUP BY e.idegresos WITH ROLLUP";
		}
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarFacturasPendientesPago($almacen, $ini, $fin)
	{ 
		$sql="SELECT id, almacen, cliente, lote, nFactura, fechaFac,
		SUM(total) total,
		SUM(montoPagado) montoPagado, vendedor, plazopago,
		SUM(totalFacDol) totalFacDol,
		SUM(montoPagoDol) montoPagoDol
		FROM
		(
		SELECT f.`idFactura` id, a.`almacen`, f.`ClienteFactura` cliente, f.`lote`, f.`nFactura`, f.`fechaFac`, f.`total`, 
		IFNULL(pr.monto,0) montoPagado, CONCAT(u.`first_name`, ' ', u.`last_name`) vendedor, e.`plazopago`, ROUND(f.`total` /  tc.`tipocambio`,2) totalFacDol, IFNULL(ROUND(pr.montoDolares,2),0) montoPagoDol
		FROM factura f
		LEFT JOIN 
		(SELECT pf.`idPago`, pf.`idFactura`, SUM(pf.`monto`) monto,( SUM(pf.`monto`) / tcp.`tipocambio` ) montoDolares
		FROM pago_factura pf 
		INNER JOIN pago p ON pf.`idPago` = p.`idPago` AND p.`anulado` = 0
		INNER JOIN tipocambio tcp ON tcp.`fecha` = p.`fechaPago`
		GROUP BY pf.`idFactura`) pr
		ON f.`idFactura` = pr.idFactura
		INNER JOIN almacenes a ON a.`idalmacen` = f.`almacen`
		INNER JOIN factura_egresos fe ON fe.`idFactura` = f.`idFactura`
		INNER JOIN egresos e ON e.`idegresos` = fe.`idegresos`
		INNER JOIN users u ON u.`id` = e.`vendedor`
		INNER JOIN tipocambio tc ON tc.`fecha` = f.`fechaFac`
		WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin' 
		AND f.`anulada` = 0 
		AND f.`pagada` <>1 
		AND f.`almacen` LIKE '%$almacen'
		AND  f.`nFactura` > 0
		GROUP BY f.`idFactura` 
		ORDER BY f.`ClienteFactura`
		)tbla
		GROUP BY cliente, id WITH ROLLUP";
		
		$query=$this->db->query($sql);		
		return $query;
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
		$sql="	SELECT
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
					plazopago,
					SUM(totalFacDol) totalFacDol,
					SUM(montoPagoDol) montoPagoDol,
					diasCredito,
					DATE_ADD(fechaFac, INTERVAL diasCredito DAY) fechaVencimiento,
					IF(
						DATE_ADD(fechaFac, INTERVAL diasCredito DAY) < CURDATE(),
						'VENCIDA',
						'VIGENTE'
					) estado
				FROM
					(
						SELECT
							e.idegresos idEgresos,
							e.tipomov,
							tm.sigla,
							e.nmov,
							SUBSTRING(e.gestion, 3, 2) gestion,
							f.`idFactura`,
							a.`almacen`,
							f.`ClienteFactura` cliente,
							f.`lote`,
							f.`nFactura`,
							f.`fechaFac`,
							f.`total`,
							cl.diasCredito,
							IFNULL(pr.monto, 0) montoPagado,
							CONCAT(u.`first_name`, ' ', u.`last_name`) vendedor,
							e.`plazopago`,
							ROUND(f.`total` / tc.`tipocambio`, 2) totalFacDol,
							IFNULL(ROUND(pr.montoDolares, 2), 0) montoPagoDol
						FROM
							factura f
							LEFT JOIN (
								SELECT
									pf.`idPago`,
									pf.`idFactura`,
									SUM(pf.`monto`) monto,(SUM(pf.`monto`) / tcp.`tipocambio`) montoDolares
								FROM
									pago_factura pf
									INNER JOIN pago p ON pf.`idPago` = p.`idPago`
									AND p.`anulado` = 0
									INNER JOIN tipocambio tcp ON tcp.`fecha` = p.`fechaPago`
								GROUP BY
									pf.`idFactura`
							) pr ON f.`idFactura` = pr.idFactura
							INNER JOIN almacenes a ON a.`idalmacen` = f.`almacen`
							INNER JOIN factura_egresos fe ON fe.`idFactura` = f.`idFactura`
							INNER JOIN egresos e ON e.`idegresos` = fe.`idegresos`
							INNER JOIN users u ON u.`id` = e.`vendedor`
							INNER JOIN tipocambio tc ON tc.`fecha` = f.`fechaFac`
							inner join clientes cl on cl.idCliente = f.cliente
							INNER JOIN tmovimiento tm on tm.id = e.tipomov
						WHERE
							f.`fechaFac` BETWEEN '$ini'
							AND '$fin'
							AND f.`anulada` = 0
							AND f.`pagada` <> 1
							AND f.`almacen` LIKE '%$almacen%'
							AND e.tipomov LIKE '%$tipoEgreso%'
							AND f.`nFactura` > 0
						GROUP BY
							f.`idFactura`
						ORDER BY
							f.`ClienteFactura`
					) tbla
				GROUP BY
					cliente,
					idFactura WITH ROLLUP";
		$query=$this->db->query($sql);		
		return $query->result();
	}
	public function mostrarListaPrecios() 
	{ 
		$sql="SELECT
				CodigoArticulo,
				Descripcion,
				unidad.Sigla,
				precio AS Bolivianos,
				precioDol AS Dolares
			FROM
				articulos
				INNER JOIN unidad ON unidad.idUnidad = articulos.idUnidad
			WHERE
				EnUso = 1
			ORDER BY
				CodigoArticulo";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarSaldos() 
	{ 
		$sql="SELECT 	aa.`idArticulos` id,
						aa.`CodigoArticulo` codigo,
						aa.`Descripcion` descripcion,
						aa.`Sigla` uni,
						aa.cpp,
						aa.`laPaz`,
						aa.`elAlto`,
						aa.`potosi`,
						aa.`santacruz`,
						(aa.`laPaz` + aa.`elAlto` + aa.`potosi` + aa.`santacruz`) total,
						IFNULL(back.cantidad,0) backOrder,
						back.recepcion,
						back.estado,
						aa.`url`,
						aa.precio
						
					FROM articulos_activos aa
					LEFT JOIN
						(SELECT 
							pit.`articulo`, 
							a.`CodigoArticulo`, 
							a.`Descripcion`,
							u.`Sigla`,
							pit.`cantidad`,
							pit.`estado`,
							pit.`recepcion`
							FROM pedidos_items pit
							INNER JOIN articulos a ON a.`idArticulos` = pit.`articulo`
							INNER JOIN unidad u ON u.`idUnidad` = a.`idUnidad`
							WHERE pit.`status` = FALSE
						)back 
					ON back.articulo = aa.`idArticulos`
					ORDER BY aa.`CodigoArticulo`
		-- WHERE SUBSTRING(CodigoArticulo,1,2)<>'SR'
		";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarFacturacionClientes($ini,$fin,$alm) 
	{ 
		if ($alm=='') {
			$sql="SELECT IFNULL(nombreCliente,'TOTAL GENERAL') nombreCliente, SUM(total) total, SUM(totalDolares) totalDolares, id,almacen
			FROM
			(
			SELECT clientes.nombreCliente, SUM(total) AS total, ROUND((SUM(total) / tc.`tipocambio`),2) totalDolares, a.`almacen`, factura.`idFactura` id
						FROM factura
						INNER JOIN clientes ON clientes.idCliente = factura.cliente
						INNER JOIN tipocambio tc ON tc.`fecha` = factura.`fechaFac`
						INNER JOIN almacenes a ON a.`idalmacen` = factura.`almacen`
						WHERE fechaFac BETWEEN '$ini' AND '$fin'
						-- AND factura.almacen LIKE '%'
						AND factura.anulada = 0
						GROUP BY clientes.nombreCliente, factura.`almacen`
						ORDER BY total DESC
			)fcl
			GROUP BY nombreCliente , id WITH ROLLUP
			";
		} else {
		$sql="SELECT clientes.nombreCliente, SUM(total) AS total, ROUND((SUM(total) / tc.`tipocambio`),2) totalDolares, a.`almacen`, factura.`idFactura` id
			FROM factura
			INNER JOIN clientes ON clientes.idCliente = factura.cliente
			INNER JOIN tipocambio tc ON tc.`fecha` = factura.`fechaFac`
			inner join almacenes a on a.`idalmacen` = factura.`almacen`
			WHERE fechaFac BETWEEN '$ini' AND '$fin'
			AND factura.almacen LIKE '%$alm'
			AND factura.anulada = 0
			GROUP BY clientes.nombreCliente
			ORDER BY total DESC";
		}
		
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarVentasLineaMes($ini,$fin,$alm='')
	{ 
		$sql="SELECT  Linea, Sigla,
		SUM(CASE WHEN MONTH(fechaFac)=1 THEN total ELSE 0 END) AS ene,
		SUM(CASE WHEN MONTH(fechaFac)=2 THEN total ELSE 0 END) AS feb,
		SUM(CASE WHEN MONTH(fechaFac)=3 THEN total ELSE 0 END) AS mar,
		SUM(CASE WHEN MONTH(fechaFac)=4 THEN total ELSE 0 END) AS abr,
		SUM(CASE WHEN MONTH(fechaFac)=5 THEN total ELSE 0 END) AS may,
		SUM(CASE WHEN MONTH(fechaFac)=6 THEN total ELSE 0 END) AS jun,
		SUM(CASE WHEN MONTH(fechaFac)=7 THEN total ELSE 0 END) AS jul,
		SUM(CASE WHEN MONTH(fechaFac)=8 THEN total ELSE 0 END) AS ago,
		SUM(CASE WHEN MONTH(fechaFac)=9 THEN total ELSE 0 END) AS sep,
		SUM(CASE WHEN MONTH(fechaFac)=10 THEN total ELSE 0 END) AS ocb,
		SUM(CASE WHEN MONTH(fechaFac)=11 THEN total ELSE 0 END) AS nov,
		SUM(CASE WHEN MONTH(fechaFac)=12 THEN total ELSE 0 END) AS dic,
		SUM(total) total,
		SUM(CASE WHEN MONTH(fechaFac)=1 THEN dolares ELSE 0 END) AS eneD,
		SUM(CASE WHEN MONTH(fechaFac)=2 THEN dolares ELSE 0 END) AS febD,
		SUM(CASE WHEN MONTH(fechaFac)=3 THEN dolares ELSE 0 END) AS marD,
		SUM(CASE WHEN MONTH(fechaFac)=4 THEN dolares ELSE 0 END) AS abrD,
		SUM(CASE WHEN MONTH(fechaFac)=5 THEN dolares ELSE 0 END) AS mayD,
		SUM(CASE WHEN MONTH(fechaFac)=6 THEN dolares ELSE 0 END) AS junD,
		SUM(CASE WHEN MONTH(fechaFac)=7 THEN dolares ELSE 0 END) AS julD,
		SUM(CASE WHEN MONTH(fechaFac)=8 THEN dolares ELSE 0 END) AS agoD,
		SUM(CASE WHEN MONTH(fechaFac)=9 THEN dolares ELSE 0 END) AS sepD,
		SUM(CASE WHEN MONTH(fechaFac)=10 THEN dolares ELSE 0 END) AS ocbD,
		SUM(CASE WHEN MONTH(fechaFac)=11 THEN dolares ELSE 0 END) AS novD,
		SUM(CASE WHEN MONTH(fechaFac)=12 THEN dolares ELSE 0 END) AS dicD,
		SUM(dolares) totalD
		FROM detalleLinea
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND almacen LIKE '%$alm'
		GROUP BY Sigla WITH ROLLUP
		";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarLibroVentas($ini,$fin,$alm) 
	{
		$sql="SELECT fechaFac, nFactura, df.autorizacion, anulada, c.documento, c.nombreCliente,
		IF(f.`anulada`=1,0,f.`total`) sumaDetalle,
		IF(anulada = 1 , 0 , ROUND ((f.total*13/100),2))  AS debito, 
		IF(codigoControl='', 0,codigoControl ) AS codigoControl , df.manual, f.almacen idAlm, a.`almacen`, a.`ciudad`, IF(anulada = 0, 'V', 'A') VA
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.idDatosFactura=f.lote
		INNER JOIN clientes AS c ON c.idCliente = f.cliente
		INNER JOIN almacenes a ON a.`idalmacen` = f.`almacen`
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		ORDER BY  a.`idalmacen`,df.manual desc, nFactura
		";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarLibroVentasTotales($ini,$fin,$alm) 
	{ //cambiar la consulta
		$sql="SELECT IFNULL(NULL, 'TotalFacturas') AS titulo,COUNT(anulada) AS resultado
		FROM factura AS f
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		UNION
		/*validas*/
		SELECT IFNULL(NULL, 'validas') as titulo,COUNT(anulada) AS resultado
		FROM factura AS f
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		AND anulada=0
		union
		/*anuladas*/
		SELECT IFNULL(NULL, 'anuladas') ,COUNT(anulada) AS anuladogffg
		FROM factura AS f
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		AND anulada=1
		union
		/*contar manual*/
		SELECT IFNULL(NULL, 'manuales') ,COUNT(df.manual)
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.idDatosFactura=f.lote
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		AND df.manual=1
		union
		/*contar computarizadas*/
		SELECT IFNULL(NULL, 'computarizadas') ,COUNT(df.manual)
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.idDatosFactura=f.lote
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		AND df.manual=0
		union
		/*Suma Total*/
		SELECT IFNULL(NULL, 'Total'), ROUND(SUM(f.`total`),2)
		FROM factura f
		WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin'
		AND f.`almacen` LIKE '%$alm'
		AND f.`anulada` = 0

		union
		SELECT IFNULL(NULL, 'Total'), ROUND(SUM(f.`total` *13/100 ),2)
		FROM factura f
		WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin'
		AND f.`almacen` LIKE '%$alm'
		AND f.`anulada` = 0

		/*Suma Debito*/
		/*SELECT IFNULL(NULL, 'debito') ,ROUND ((SUM(fd.facturaPUnitario*fd.facturaCantidad)*13/100),2) AS debito
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.idDatosFactura=f.lote
		INNER JOIN clientes AS c ON c.idCliente = f.cliente
		INNER JOIN facturadetalle AS fd ON fd.idFactura=f.idFactura
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		AND anulada=0*/
		 ";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarDiarioIngresos($ini,$fin,$alm,$tin) 
	{ //cambiar la consulta
		$sql="SELECT alm.almacen, i.fechamov, i.tipomov,t.sigla, i.nmov, i.ordcomp, 
		IF(i.`tipomov` = 3,alt.`ciudad`,p.nombreproveedor) nombreproveedor,
		a.CodigoArticulo, a.Descripcion, u.Unidad,  id.cantidad, id.punitario, id.total, i.obs
		from ingresos i
		inner join provedores p
		on p.idproveedor=i.proveedor
		inner join ingdetalle id
		on id.idIngreso= i.idIngresos
		inner join articulos a
		on a.idArticulos= id.articulo
		inner join unidad u
		on u.idUnidad=a.idUnidad
		inner join tmovimiento t
		on t.id= i.tipomov
		inner join almacenes alm
		on alm.idalmacen = i.almacen
		LEFT JOIN traspasos tr ON tr.`idIngreso` = i.`idIngresos`
		LEFT JOIN egresos etr ON etr.`idegresos` =tr.`idEgreso`
		LEFT JOIN almacenes alt ON alt.`idalmacen` = etr.`almacen`
		where i.fechamov BETWEEN '$ini' AND '$fin'
		and i.tipomov like '%$tin' AND i.almacen LIKE '%$alm'  AND i.anulado = 0 AND i.`estado` = 1
		order by alm.almacen, i.nmov, a.CodigoArticulo";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarKardexIndividual($art,$alm) 
	{ 
		if ($alm=="") {
			$sql="call testKardexGeneral($art);";
		} else {
			$sql="call testKardex2($art,$alm);";
		}
		$query=$this->db->query($sql);		
		return $query;
	}
	public function showKardexIndividual($a,$b) 
	{ 
		
		$idArticulos = $this->getArticulosID($a, $b);
		
		foreach ($idArticulos as $id) {
			echo  'valor es '.$id;
		}

	
		return $idArticulos;
	}
	public function getArticulosID($a,$b) 
	{ 
	
			$sql="SELECT a.`idArticulos` id, a.`CodigoArticulo` codigo, a.`Descripcion` descrip, a.`Sigla` unidad
			FROM articulos_activos_kardex a
			WHERE a.`CodigoArticulo` BETWEEN '$a' AND '$b'
			ORDER BY a.`CodigoArticulo`;";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function kardexIndividualCliente($cliente,$almacen,$ini,$fin,$mon) {
		if ($mon == 0) {
			$sql="SELECT * FROM 
		(	SELECT    c.`idCliente`, c.`nombreCliente`,'$ini' fecha, '-' numDocumento, '1' almacen,  'SALDO INICIAL' detalle,  
					IFNULL((SELECT ROUND(SUM(`ed`.`total`), 2) `saldoTotalNE`
					FROM`egresos` `e`
						INNER JOIN `egredetalle` `ed` ON `ed`.`idegreso` = `e`.`idegresos`
					WHERE
						`e`.`fechamov` < '$ini'
						AND `e`.`estado` <> 1
						AND `e`.`anulado` = 0
						AND e.tipomov = 7
						AND `e`.`almacen` LIKE '%$almacen'
						AND `e`.`cliente` = '$cliente'
					GROUP BY e.`cliente`),0) saldoNE, 

					IFNULL((SELECT ROUND(SUM(`f`.`total`), 2) `saldoTotalFactura`
					FROM `factura` `f`
					WHERE
						`f`.`fechaFac` < '$ini'
						AND `f`.`anulada` = 0
						AND `f`.`almacen` LIKE '%$almacen'
						AND `f`.`cliente` = '$cliente'
					GROUP BY f.`cliente`),0) saldoTotalFactura, 

					IFNULL((SELECT ROUND(SUM(`pf`.`monto`), 2) AS `saldoTotalPago`
					FROM `pago_factura` `pf`
						INNER JOIN `pago` `p` ON `p`.`idPago` = `pf`.`idPago` AND `p`.`anulado` = 0
						INNER JOIN `factura` `f` ON `f`.`idFactura` = `pf`.`idFactura`	AND `f`.`anulada` = 0
					WHERE
						`p`.`fechaPago` < '$ini'
						AND `p`.`almacen` LIKE '%$almacen'
						AND f.cliente = '$cliente'
					GROUP BY f.cliente),0) saldoTotalPago
				FROM clientes c
				WHERE c.`idCliente` = '$cliente'
			UNION ALL
				SELECT c.`idCliente`, c.`nombreCliente`, f.fechaFac, f.nFactura, f.almacen,     
					IFNULL(f.glosa,'') detalle, 
					0 , ROUND(f.`total`,2) saldoTotalFactura, 0
				FROM factura f  
					INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
				WHERE  f.`fechaFac` BETWEEN '$ini' AND '$fin'
					AND f.`anulada` = 0
					AND f.almacen LIKE '%$almacen'
					AND c.`idCliente` = '$cliente'
			UNION ALL
				SELECT c.`idCliente`, c.`nombreCliente`,  e.`fechamov`, e.`nmov`, e.`almacen`, e.`obs`, ROUND(SUM(ed.`total`),2) saldoTotalNE , 0 , 0 
				FROM egresos e
					INNER JOIN egredetalle ed ON ed.`idegreso` = e.`idegresos`
					INNER JOIN clientes c ON c.`idCliente` = e.`cliente`
				WHERE  e.`fechamov` BETWEEN '$ini' AND '$fin'
					AND e.`estado` <> 1
					AND e.`anulado` = 0
					AND e.almacen LIKE '%$almacen'
					AND e.`cliente` = '$cliente'
					AND e.tipomov = 7
			UNION ALL 
				SELECT c.`idCliente`, c.`nombreCliente`, p.`fechaPago`, p.`numPago`, p.`almacen`, 
					CONCAT('Fac. ',f.`lote`,'-',f.nFactura,', ',p.`glosa`) glosa,
					0 , 0, ROUND(pf.`monto`,2) saldoTotalPago
				FROM pago_factura pf
					INNER JOIN pago p ON p.`idPago` = pf.`idPago` AND p.`anulado` = 0
					INNER JOIN factura f ON f.`idFactura` = pf.`idFactura` AND f.`anulada` = 0
					INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
				WHERE   p.`fechaPago` BETWEEN '$ini' AND '$fin'
					AND p.almacen LIKE '%$almacen'
					AND c.`idCliente` = '$cliente'
		) kardexClientes
		INNER JOIN (SELECT a.`idalmacen`, a.`almacen` FROM almacenes a) AS alm
		ON alm.`idalmacen` = kardexClientes.almacen
		ORDER BY kardexClientes.fecha, numDocumento";
		} elseif($mon == 1) {
			$sql="SELECT * FROM 
					(	SELECT    c.`idCliente`, c.`nombreCliente`,'$ini' fecha, '-' numDocumento, '1' almacen,  'SALDO INICIAL' detalle,  
						IFNULL((SELECT ROUND(SUM(`ed`.`total`)/tc.tipocambio, 2) `saldoTotalNE`
						FROM`egresos` `e`
						INNER JOIN `egredetalle` `ed` ON `ed`.`idegreso` = `e`.`idegresos`
						INNER JOIN tipocambio tc ON tc.fecha = e.fechamov
						WHERE
							`e`.`fechamov` < '$ini'
							AND `e`.`estado` <> 1
							AND `e`.`anulado` = 0
							AND e.tipomov = 7
							AND `e`.`almacen` LIKE '%$almacen'
							AND `e`.`cliente` = '$cliente'
						GROUP BY e.`cliente`),0) saldoNE, 
			
								IFNULL((SELECT ROUND(SUM(`f`.`total`)/tc.tipocambio, 2) `saldoTotalFactura`
								FROM `factura` `f`
								INNER JOIN tipocambio tc ON tc.fecha = f.fechaFac
								WHERE
									`f`.`fechaFac` < '$ini'
									AND `f`.`anulada` = 0
									AND `f`.`almacen` LIKE '%$almacen'
									AND `f`.`cliente` = '$cliente'
								GROUP BY f.`cliente`),0) saldoTotalFactura, 
			
								IFNULL((SELECT ROUND(SUM(`pf`.`monto`)/tc.tipocambio, 2) AS `saldoTotalPago`
								FROM `pago_factura` `pf`
									INNER JOIN `pago` `p` ON `p`.`idPago` = `pf`.`idPago` AND `p`.`anulado` = 0
									INNER JOIN `factura` `f` ON `f`.`idFactura` = `pf`.`idFactura`	AND `f`.`anulada` = 0
									 INNER JOIN tipocambio tc ON tc.fecha = f.fechaFac
								WHERE
									`p`.`fechaPago` < '$ini'
									AND `p`.`almacen` LIKE '%$almacen'
									AND f.cliente = '$cliente'
								GROUP BY f.cliente),0) saldoTotalPago
							FROM clientes c
							WHERE c.`idCliente` = '$cliente'
						UNION ALL
							SELECT c.`idCliente`, c.`nombreCliente`, f.fechaFac, f.nFactura, f.almacen,     
								IFNULL(f.glosa,'') detalle, 
								0 , ROUND(f.`total`/tc.tipocambio,2) saldoTotalFactura, 0
							FROM factura f  
								INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
								INNER JOIN tipocambio tc ON tc.fecha = f.fechaFac
							WHERE  f.`fechaFac` BETWEEN '$ini' AND '$fin'
								AND f.`anulada` = 0
								AND f.almacen LIKE '%$almacen'
								AND c.`idCliente` = '$cliente'
						UNION ALL
							SELECT c.`idCliente`, c.`nombreCliente`,  e.`fechamov`, e.`nmov`, e.`almacen`, e.`obs`, ROUND(SUM(ed.`total`) / tc.tipocambio,2) saldoTotalNE , 0 , 0 
							FROM egresos e
								INNER JOIN egredetalle ed ON ed.`idegreso` = e.`idegresos`
								INNER JOIN clientes c ON c.`idCliente` = e.`cliente`
								INNER JOIN tipocambio tc ON tc.fecha = e.fechamov
							WHERE  e.`fechamov` BETWEEN '$ini' AND '$fin'
								AND e.`estado` <> 1
								AND e.`anulado` = 0
								AND e.almacen LIKE '%$almacen'
								AND e.`cliente` = '$cliente'
								AND e.tipomov = 7
			
						UNION ALL 
							SELECT c.`idCliente`, c.`nombreCliente`, p.`fechaPago`, p.`numPago`, p.`almacen`, 
								CONCAT('Fac. ',f.`lote`,'-',f.nFactura,', ',p.`glosa`) glosa,
								0 , 0, ROUND(pf.`monto` / tc.tipocambio,2) saldoTotalPago
							FROM pago_factura pf
								INNER JOIN pago p ON p.`idPago` = pf.`idPago` AND p.`anulado` = 0
								INNER JOIN factura f ON f.`idFactura` = pf.`idFactura` AND f.`anulada` = 0
								INNER JOIN tipocambio tc ON tc.fecha = f.fechaFac
								INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
								WHERE   p.`fechaPago` BETWEEN '$ini' AND '$fin'
									AND p.almacen LIKE '%$almacen'
									AND c.`idCliente` = '$cliente'
					) kardexClientes
					INNER JOIN (SELECT a.`idalmacen`, a.`almacen` FROM almacenes a) AS alm
					ON alm.`idalmacen` = kardexClientes.almacen
					ORDER BY kardexClientes.fecha, numDocumento;
			";
		}
		
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarEstadoVentasCosto($alm,$ini,$fin,$mon)
	{ 
		$sql="SELECT id, 
		marca, descp,uni, lineaD,
		linea, codigo, cpp, ppVenta, saldo, SUM(saldoValorado) saldoValorado, cantVendida, SUM(totalCosto) totalCosto, SUM(totalVentas)totalVentas, SUM(utilidad)util, 
		cppDol, SUM(ppVentaDol)ppVentaDol, SUM(saldoValoradoDol)saldoValoradoDol, 
		SUM(totalCostoDol)totalCostoDol, SUM(totalVentasDol)totalVentasDol, SUM(utilDol)utilDol FROM
		(
			SELECT art.`idArticulos` id, 
				m.Marca marca,
				l.Sigla linea,
				l.Linea lineaD,
				art.CodigoArticulo codigo, 
				art.Descripcion descp,
				u.Unidad uni,
				
				(cpp),
				ROUND(IFNULL((ROUND(IFNULL(fact.totalVentas,0),2) / IFNULL(fact.cantVendida,0)),0),2) ppVenta,
				ROUND((IFNULL(SUM(ingreso),0) - IFNULL(fact.cantVendida,0) - IFNULL(trasp.traspaso,0) ),2) saldo,
				ROUND(ROUND((IFNULL(SUM(ingreso),0) - IFNULL(fact.cantVendida,0) -  IFNULL(trasp.traspaso,0) ),2) * (cpp),2) saldoValorado,
				IFNULL(fact.cantVendida,0) cantVendida, 
				ROUND((cpp) * ROUND(IFNULL(fact.cantVendida,0),2),2)totalCosto,
				ROUND(IFNULL(fact.totalVentas,0),2) totalVentas,
				ROUND(ROUND(IFNULL(fact.totalVentas,0),2)  - ((cpp) * ROUND(IFNULL(fact.cantVendida,0),2)),2) utilidad,
				
				/* dolares */ 
				cppDol,
				ROUND((IFNULL((ROUND(IFNULL(fact.totalVentasDol,0),2) / IFNULL(fact.cantVendida,0)),0)),2) ppVentaDol,
				ROUND(ROUND((IFNULL(SUM(ingreso),0) - IFNULL(fact.cantVendida,0) -  IFNULL(trasp.traspaso,0) ),2) * (cppDol),4) saldoValoradoDol,
				ROUND((ROUND(SUM(((cppDol))),4)) * ROUND(IFNULL(fact.cantVendida,0),2),2)totalCostoDol,
				IFNULL(ROUND(fact.totalVentasDol,2),0) totalVentasDol,
				((IFNULL(ROUND(fact.totalVentasDol,2),0)) - (ROUND((ROUND(SUM(((cppDol))),4)) * ROUND(IFNULL(fact.cantVendida,0),2),2))) utilDol
				
			FROM articulos art
					LEFT JOIN (SELECT
							id.`articulo` id, 
							ROUND(SUM(id.`total`)/SUM(id.`cantidad`),4) cpp,
							ROUND(SUM(((id.`cantidad` * id.`punitario`) / tc.tipocambio))/SUM(id.`cantidad`),4) cppDol,
							IFNULL(SUM(id.`cantidad`),0) ingreso
		
							FROM ingdetalle id
								INNER JOIN ingresos i	ON i.`idIngresos`=id.`idIngreso` 
										AND i.`almacen` LIKE CONCAT('%','$alm')
										AND i.`anulado`=0
										AND i.`estado`=1
										AND i.`fechamov` BETWEEN '$ini'  AND '$fin'
							 -- WHERE id.`articulo` = 2114
								INNER JOIN tipocambio tc ON tc.fecha = i.`fechamov`
		
							GROUP BY id.`articulo`)ingr ON ingr.id = art.`idArticulos`
					LEFT JOIN (SELECT  fd.`articulo`, SUM(fd.`facturaCantidad`) cantVendida, SUM(fd.`facturaCantidad`* fd.`facturaPUnitario`) totalVentas, SUM(fd.`facturaCantidad` * ((fd.`facturaPUnitario`) / (tc.tipocambio))) totalVentasDol
							FROM facturadetalle fd
							INNER JOIN factura f ON f.`idFactura` = fd.`idFactura`
							INNER JOIN tipocambio tc ON tc.`fecha` = f.`fechaFac`
							WHERE f.`fechaFac` BETWEEN '$ini'  AND '$fin'
					 -- AND fd.`articulo` = 2114
							AND f.anulada = 0
							AND f.almacen LIKE CONCAT('%','$alm')
							GROUP BY fd.`articulo`)fact ON fact.articulo = art.`idArticulos`
					LEFT JOIN (SELECT ed.articulo, SUM(ed.`cantidad`-ed.`cantFact`) ne
								FROM 	egredetalle ed
									INNER JOIN articulos art ON art.idArticulos = ed.articulo
									INNER JOIN egresos e ON e.`idegresos`=ed.`idegreso`
										AND e.`anulado`=0
										AND e.`tipomov`= 7
										AND e.`estado`<>1
										AND e.`almacen` LIKE CONCAT('%','$alm')
										AND e.`fechamov` <= '$fin'
									WHERE  ed.`cantidad`-ed.`cantFact` <> 0
					-- and ed.articulo = 2114
									GROUP BY ed.articulo) notaEnt ON notaEnt.articulo = art.`idArticulos`
					LEFT JOIN(SELECT ed.articulo, SUM(ed.`cantidad`) traspaso
							FROM egredetalle ed
								INNER JOIN articulos art ON art.idArticulos = ed.articulo
								INNER JOIN egresos e ON e.`idegresos`=ed.`idegreso`
									AND e.`almacen` LIKE CONCAT('%','$alm')
									AND e.`anulado`=0
									AND e.`tipomov` BETWEEN 8 AND 9
									AND e.`fechamov` BETWEEN '$ini'  AND '$fin'
					-- where ed.articulo = 2114
								GROUP BY ed.articulo)trasp ON trasp.articulo = art.`idArticulos`
					INNER JOIN linea l ON l.idLinea = art.idLinea
					INNER JOIN marca m ON m.idMarca = art.`idMarca`
					INNER JOIN unidad u ON u.idUnidad = art.`idUnidad`
			WHERE ingr.ingreso IS NOT NULL 
			OR fact.cantVendida IS NOT NULL
			OR notaEnt.ne IS NOT NULL
			OR trasp.traspaso IS NOT NULL
			GROUP BY codigo
		)tbwr
		GROUP BY linea, codigo WITH ROLLUP;
		";
		$query=$this->db->query($sql);		
		return $query;
	}
	
	public function showEstadoVentasCostoNew($alm,$ini,$fin,$mon)
	{ 
		$sql="CALL newKardex('$alm','$mon','$ini','$fin')
		";
		$query=$this->db->query($sql);		
		return $query;
	}


	public function mostrarEstadoVentasCostoXLS($alm)
	{ 
		$sql="SELECT sigla, linea, 
		idArticulo idArticulo, codigo, descrip, unidad, costo, ppVenta, SUM(saldo) saldo,
		SUM(saldoValorado) saldoValorado, cantidadVendida, SUM(totalCosto) totalCosto, SUM(totalVentas) totalVentas, SUM(utilidad) utilidad
		FROM
		(
		SELECT l.`Sigla` sigla, l.`Linea` linea, idArticulo, a.`CodigoArticulo` codigo, a.`Descripcion` descrip, u.`Unidad` unidad, a.`costoPromedioPonderado` costo, IFNULL(ppVenta,0) ppVenta , saldo,
		(a.`costoPromedioPonderado` * saldo) saldoValorado, cantidadVendida, (cantidadVendida * a.`costoPromedioPonderado`) totalCosto, (cantidadVendida * IFNULL(ppVenta,0)) totalVentas,
		((cantidadVendida * IFNULL(ppVenta,0)) - (cantidadVendida * a.`costoPromedioPonderado`)) utilidad
		FROM (
		SELECT *
		FROM
		(
		SELECT sa.idArticulo , (sa.saldo + sa.notaEntrega) saldo, sa.`facturado` cantidadVendida 
		FROM saldoarticulos sa
		WHERE sa.`idAlmacen` LIKE '%$alm'
		) sal
		LEFT JOIN ( SELECT * FROM (
			SELECT fd.articulo , (SUM(fd.`facturaPUnitario` * fd.facturaCantidad) ) / SUM(fd.`facturaCantidad`)  ppVenta
			FROM factura f
			INNER JOIN facturadetalle fd ON fd.`idFactura` = f.`idFactura`
			INNER JOIN articulos a ON a.idArticulos = fd.articulo
			WHERE YEAR(f.`fechaFac`)=YEAR(NOW()) AND f.`anulada` = 0 AND f.`almacen` LIKE '%$alm'
			GROUP BY fd.articulo
			)tbl) ppv ON ppv.articulo = idArticulo
		) todo
		INNER JOIN articulos a ON a.`idArticulos` = idArticulo
		INNER JOIN unidad u ON u.`idUnidad`= a.`idUnidad`
		INNER JOIN linea l ON l.`idLinea` = a.`idLinea`
		ORDER BY l.`Sigla`, a.`CodigoArticulo`
		) tbl
		GROUP BY sigla, codigo WITH ROLLUP	
		";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarSaldosActualesItems($alm)
	{ 
		if ($alm >0) {
			$sql = "SELECT 
			sigla, 
			linea, 
			IF(codigo IS NULL,'',codigo) codigo, 
			IF(codigo IS NULL,'',descripcion) descripcion, 
			IF(codigo IS NULL,'',unidad) unidad,
			IF(codigo IS NULL AND linea IS NULL,'TOTAL GENERAL: ',IF(codigo IS NULL,CONCAT('TOTAL ',linea), almacen)) almacen, 
			IF(codigo IS NULL,'',costo) costo, 
			IF(codigo IS NULL,'',saldo) saldo, 
			IF(codigo IS NULL,'',remision) remision, 
			IF(codigo IS NULL,'',saldoAlm) saldoAlm, 
			vTotal
			FROM (
			SELECT 
			IF(alm.almacen IS NULL,'',a.`CodigoArticulo`) codigo, 
			a.`Descripcion` descripcion, l.`Sigla` sigla,
			IF(l.`Linea` IS NULL,'TOTAL ',alm.`almacen`)almacen, 
			a.`costoPromedioPonderado` costo, 
			SUM((sa.`saldo` +  sa.`notaEntrega`)) saldo,
			SUM(sa.`notaEntrega`) remision, 
			SUM(`saldo`) saldoAlm,
			SUM(((sa.`saldo` +  sa.`notaEntrega`) * a.costoPromedioPonderado)) vTotal,
			IF(alm.`almacen` IS NULL,'',l.Linea) linea, u.`Unidad`
			FROM saldoarticulos sa
			INNER JOIN articulos a ON a.`idArticulos` = sa.`idArticulo`
			INNER JOIN almacenes alm ON alm.idalmacen = sa.idAlmacen
			INNER JOIN linea l ON l.idLinea = a.idLinea
			INNER JOIN unidad u ON u.`idUnidad` = a.`idUnidad`
			WHERE sa.`idAlmacen` = '$alm'
			GROUP BY    l.`Sigla`, a.`CodigoArticulo` WITH ROLLUP
			) tbl";
		} else {
		$sql="SELECT 	
		sigla,
		linea,  
		IF(almacen IS NULL,'', codigo) codigo,  
		IF(almacen IS NULL,'', descripcion) descripcion, 
		IF(almacen IS NULL,'', unidad) unidad,  
		CASE
			WHEN sigla IS NULL AND codigo IS NULL AND almacen IS NULL THEN CONCAT('TOTAL GENERAL')
			WHEN codigo IS NULL AND almacen IS NULL THEN CONCAT('TOTAL ', linea)
			WHEN almacen IS NULL THEN CONCAT('TOTAL ', codigo)
			ELSE almacen
		END almacen,
		IF(almacen IS NULL,'', costo) costo, 
		IF(almacen IS NULL,'', saldo) saldo, 
		IF(almacen IS NULL,'', remision) remision, 
		IF(almacen IS NULL,'', saldoAlm)saldoAlm, 
		vTotal
		FROM( 
			SELECT 
			l.`Sigla` sigla, u.unidad,
			a.`CodigoArticulo` codigo, 
			a.`Descripcion` descripcion,
			alm.almacen almacen, 
			a.`costoPromedioPonderado` costo, 
			SUM((sa.`saldo` +  sa.`notaEntrega`)) saldo, 
			SUM(sa.`notaEntrega`) remision, 
			SUM(sa.`saldo`) saldoAlm,
			SUM(((sa.`saldo` +  sa.`notaEntrega`) * a.costoPromedioPonderado)) vTotal,
			l.`Linea` linea
			FROM saldoarticulos sa
			INNER JOIN articulos a ON a.`idArticulos` = sa.`idArticulo`
			INNER JOIN almacenes alm ON alm.idalmacen = sa.idAlmacen
			INNER JOIN linea l ON l.idLinea = a.idLinea
			INNER JOIN unidad u ON u.idUnidad = a.idUnidad
			GROUP BY  l.`Sigla`, a.`CodigoArticulo`, alm.almacen WITH ROLLUP
		)tbl";
		}		
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarVentasClientesItems($ini, $fin, $alm)
	{ 
			$sql = "SELECT * , SUM(cantidadFactura)cantidad, SUM(cantidadFactura * precioUnitario) total
			FROM
			(
			SELECT  fd.`id`, fd.`articulo`, f.`almacen`, a.`idArticulos` idArt, a.`CodigoArticulo` codigo, a.`Descripcion` descripcion, f.`fechaFac`, f.`nFactura`, c.`documento`, c.`nombreCliente`, 
			fd.`facturaPUnitario` precioUnitario, 
			(fd.`facturaCantidad`) cantidadFactura,
			(fd.`facturaPUnitario` * fd.`facturaCantidad`) total , l.`Linea` linea, l.`Sigla` siglaLinea, 
			CONCAT(uv.`first_name`, ' ', uv.`last_name`) vendedor
						FROM
						facturadetalle fd
						INNER JOIN articulos a ON a.`idArticulos` = fd.`articulo`
						INNER JOIN factura f ON f.`idFactura` = fd.`idFactura`
						INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
						INNER JOIN factura_egresos fe ON fe.`idFactura`= f.`idFactura`
						INNER JOIN egresos e ON e.`idegresos` = fe.`idegresos`
						INNER JOIN users uv ON uv.`id` = e.`vendedor`
						INNER JOIN linea l ON l.`idLinea` = a.`idLinea`
						WHERE f.anulada = 0 
						AND f.`fechaFac` BETWEEN '$ini' AND '$fin'
						 AND f.`almacen` LIKE '%$alm'
						 GROUP BY fd.`id`
						ORDER BY codigo,nombreCliente
			)tbla GROUP BY codigo, nFactura WITH ROLLUP";

		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarReporteIngreso ($ini,$fin,$alm,$tin) 
	{ //cambiar la consulta
		$sql="SELECT id,
		codigo,
		siglaMov, 
		almacen, 
		tipomov,  
		provedor, 
		fecha, 
		fechamov, 
		nmov,  
		descripcion,
		uni, 
		mon, 
		SUM(cantidad) cantidad,
		punitario,
		SUM(total) total,
		SUM(totalDolares) totalDolares,
		nombreproveedor,
		nombreAlmacen
		FROM
		(
		SELECT id.`idIngdetalle` id, i.`almacen`,i.`tipomov`, tm.`tipomov` siglaMov, p.`nombreproveedor` provedor, i.`fechamov`, i.`nmov`, 
		a.`CodigoArticulo` codigo, a.`Descripcion` descripcion, 
		u.`Sigla` uni, m.`sigla` mon, id.`cantidad`, id.`punitario`,
		ROUND((id.`punitario` * id.`cantidad`),2) total, 
		ROUND((id.`punitario` /tc.`tipocambio`  * id.`cantidad`),2) totalDolares,
		i.`fecha`, p.`nombreproveedor`, al.`almacen` nombreAlmacen
		FROM ingdetalle id
		INNER JOIN ingresos i ON i.`idIngresos` = id.`idIngreso`
		INNER JOIN articulos a ON a.`idArticulos` = id.`articulo`
		INNER JOIN unidad u ON u.`idUnidad` = a.`idUnidad`
		INNER JOIN moneda m ON m.`id`= i.`moneda`
		INNER JOIN tmovimiento tm ON tm.`id` = i.`tipomov`
		INNER JOIN provedores p ON p.`idproveedor` = i.`proveedor`
		INNER JOIN tipocambio tc ON tc.fecha = i.fechamov
		INNER JOIN almacenes al ON al.`idalmacen` = i.`almacen`
		WHERE  i.`fechamov` BETWEEN '$ini' AND '$fin'
			AND i.`tipomov` LIKE '%$tin' 
			AND i.`almacen` LIKE '%$alm' 
			AND i.`anulado` = 0 
			AND i.`estado` = 1
		ORDER BY i.`almacen`, i.`tipomov`, provedor, nmov
		)ing
		-- GROUP BY  almacen,tipomov, provedor, id  WITH ROLLUP
		GROUP BY  nmov, id  WITH ROLLUP";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarReporteEgreso ($ini,$fin,$alm,$tin) 
	{ 
		$sql="SELECT id,
		codigo,
		siglaMov, 
		almacen, 
		tipomov,  
		cliente, 
		fecha, 
		fechamov, 
		nmov,  
		descripcion, 
		uni, 
		mon, 
		SUM(cantidad) cantidad,
		punitario,
		SUM(total) total,
		SUM(totalDolares) totalDolares,
		nombreAlmacen
		FROM
		(
			SELECT 	ed.`idingdetalle` id, e.`almacen`,e.`tipomov`, tm.`tipomov` siglaMov, 
			IF ( e.`tipomov` = 8,alt.`ciudad`,c.`nombreCliente` ) cliente, 
				e.`fechamov`, e.`nmov`, 
				a.`CodigoArticulo` codigo, a.`Descripcion` descripcion, 
				u.`Sigla` uni, m.`sigla` mon, ed.`cantidad` , ed.`punitario`,
				ROUND((ed.`punitario` * ed.`cantidad`),2) total, 
				ROUND((ed.`punitario` /tc.`tipocambio`  * ed.`cantidad`),2) totalDolares,
				e.`fecha` , al.`almacen` nombreAlmacen
			FROM egredetalle ed
				INNER JOIN egresos e ON e.`idegresos` = ed.`idegreso`
				INNER JOIN articulos a ON a.`idArticulos` = ed.`articulo`
				INNER JOIN unidad u ON u.`idUnidad` = a.`idUnidad`
				INNER JOIN moneda m ON m.`id`= e.`moneda`
				INNER JOIN tmovimiento tm ON tm.`id` = e.`tipomov`
				INNER JOIN clientes c ON c.`idCliente` = e.`cliente`
				INNER JOIN tipocambio tc ON tc.`fecha` = e.`fechamov`
				INNER JOIN almacenes al ON al.`idalmacen` = e.`almacen`
				LEFT JOIN traspasos tr ON tr.`idEgreso` = e.`idegresos`
				LEFT JOIN ingresos itr ON itr.`idIngresos` = tr.`idIngreso`
				LEFT JOIN almacenes alt ON alt.`idalmacen` = itr.`almacen`
				WHERE  e.`fechamov` BETWEEN '$ini' AND '$fin'
					AND e.`tipomov` LIKE '%$tin' 
					AND e.`almacen` LIKE '%$alm' 
					AND e.`anulado` = 0 
			ORDER BY e.`almacen`, e.`tipomov` , cliente, nmov
		)egr
		-- GROUP BY  almacen,tipomov, cliente, id  WITH ROLLUP
		GROUP BY  nmov, id  WITH ROLLUP";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarReporteFacturas ($ini, $fin, $alm) 
	{ 
		$sql="SELECT id,
		idAlm,
		almacen,
		fechaFac,
		lote,
		nFactura,
		codigo,
		descr,
		uni,
		pu,
		SUM(cantidad) cantidad,
		SUM(total) total,
		cliente,
		puDolares,
		SUM(totalDolares) totalDolares
		FROM
		(
		SELECT 	fd.`id`, f.`almacen` idAlm, al.`almacen`, f.`fechaFac`, f.`lote`, f.`nFactura`, fd.`ArticuloCodigo` codigo, fd.`ArticuloNombre` descr, u.`Sigla` uni, 
			ROUND(fd.`facturaPUnitario`,2) pu, fd.`facturaCantidad` cantidad, ROUND((fd.`facturaCantidad` * fd.`facturaPUnitario`),2) total, f.`ClienteFactura` cliente, ROUND(fd.`facturaPUnitario`/tc.`tipocambio`,2) puDolares,
			ROUND((fd.`facturaCantidad` * fd.`facturaPUnitario` / tc.`tipocambio`),2) totalDolares
		FROM facturadetalle fd
			INNER JOIN factura f ON f.`idFactura` = fd.`idFactura`
			INNER JOIN articulos a ON a.`idArticulos` = fd.`articulo`
			INNER JOIN unidad u ON u.`idUnidad` = a.`idUnidad`
			INNER JOIN moneda m ON m.`id`= f.`moneda`
			INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
			INNER JOIN tipocambio tc ON tc.`fecha` = f.`fechaFac`
			INNER JOIN almacenes al ON al.`idalmacen` = f.`almacen`
			WHERE  f.`fechaFac` BETWEEN '$ini' AND '$fin'
			AND f.`almacen` LIKE '%$alm' 
			AND f.`anulada` = 0 
		ORDER BY f.fechaFac,  f.`nFactura`, fd.`ArticuloCodigo`
		)fac
		GROUP BY  almacen,fechaFac, id  WITH ROLLUP";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function showReportePagos ($ini, $fin, $alm) 
	{ 
		$sql="SELECT pf.`id`, pf.`idPago`, pf.`idFactura`,
		p.`numPago`, p.`fechaPago`, c.`nombreCliente`,  f.`nFactura`, p.`glosa`, sum(pf.`monto`) total, f.`lote`
		from pago_factura pf
		inner join factura f on f.`idFactura` = pf.`idFactura`
		inner join pago p on p.`idPago` = pf.`idPago`
		inner join clientes c on c.`idCliente` = f.`cliente`
		where year(p.`fechaPago`) = 2018 
		and p.`anulado` = 0
		and p.`almacen` = 1
		group by  p.`numPago` desc, pf.`id` with rollup
		";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function showClienteItems ($ini, $fin, $alm) 
	{ 
		$sql="SELECT f.`almacen`, fd.`articulo` idArticulo, f.`nFactura`, f.`cliente`idCliente, f.`ClienteFactura`, fd.`ArticuloCodigo` codigo, 
		fd.`ArticuloNombre` descrip, u.`Sigla` uni, 
		ROUND(AVG(fd.`facturaPUnitario`),2) puni,
		SUM(fd.`facturaCantidad`) cantidad,
		SUM((fd.`facturaPUnitario`) * (fd.`facturaCantidad`)) total,
		ROUND(AVG(fd.`facturaPUnitario`),2) puni,
		ROUND(AVG(fd.`facturaPUnitario` / tc.`tipocambio`),2) puDolares,
		ROUND(SUM((fd.`facturaPUnitario`) * (fd.`facturaCantidad`) / tc.`tipocambio`),2) totalDolares
		FROM facturadetalle fd
		INNER JOIN factura f ON f.`idFactura` = fd.`idFactura`
		INNER JOIN articulos a ON a.`idArticulos` = fd.`articulo`
		INNER JOIN unidad u ON u.`idUnidad` = a.`idUnidad`
		INNER JOIN tipocambio tc ON tc.`fecha` = f.`fechaFac`
		WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin' 
		AND f.`anulada` = 0 AND f.`almacen` LIKE '%$alm' 
		GROUP BY f.`ClienteFactura`, fd.`ArticuloCodigo` WITH ROLLUP
		";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function showVentasTM ($ini, $fin, $alm) 
	{ 
		$sql="SELECT * 
		FROM
		(
			SELECT 
			SUBSTRING(fd.`ArticuloCodigo`,1,17) codigo, 
			SUBSTRING(fd.`ArticuloNombre`,1,40) descripcion, 
			SUBSTRING(f.`ClienteNit`,1,13) nit, 
			SUBSTRING(f.`ClienteFactura`,1,99) razon, 
			round(fd.`facturaCantidad`,2) cantidad,
			SUBSTRING(u.`Unidad`,1,5) unidad,  
			round(fd.`facturaPUnitario`,2) pu, 
			round((round(fd.`facturaCantidad`,2) * round(fd.`facturaPUnitario`,2)),2) total,
			'BOB' moneda, 
			f.`fechaFac` 
			fecha, 
			'' ubigeo,  
			f.`nFactura` 
			numDoc, 
			'FA' tipo,
			'NO DEFINIDO' nodef, 
			'NO DEFINIDO' vend, 
			'NO DEFINIDO' zona,
			al.`ciudad`,  
			'NO DEFINIDO', 
			'' regalo , 
			f.`almacen`,
			CONCAT(us.first_name, ' ' , us.last_name) vendedor
			FROM
            facturadetalle fd
            inner join factura f on f.`idFactura` = fd.`idFactura`
            INNER JOIN factura_egresos fe ON fe.idFactura = f.idFactura
            INNER JOIN egresos e ON e.idegresos = fe.idegresos
            INNER JOIN users us ON us.id = e.vendedor
            inner join articulos a on a.`idArticulos` = fd.`articulo`
            inner join marca m on m.`idMarca` = a.`idMarca`
            INNER JOIN unidad u ON a.`idUnidad` = u.`idUnidad`
            INNER JOIN almacenes al on al.`idalmacen` = f.`almacen`
			WHERE f.`anulada` = 0 AND  f.`almacen` LIKE '%$alm' AND
			f.`fechaFac` BETWEEN '$ini' AND '$fin' 
			GROUP BY fd.id
			ORDER BY f.`almacen`, f.`fechaFac`, fd.`ArticuloCodigo`, f.`ClienteFactura`
		) tbl
		WHERE SUBSTRING(codigo,1,2) = 'TM' OR SUBSTRING(codigo,1,2) = 'TS' 
		";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function showInventarioTM () 
	{ 
		$sql="SELECT * 
		FROM
		(
			SELECT SUBSTRING(a.`CodigoArticulo`,1,16) codigo, SUBSTRING(a.`Descripcion`,1,39) descripcion, ROUND(sa.`saldo`,2) cantidad, SUBSTRING(u.`Unidad`,1,5) Unidad, DATE_FORMAT(NOW(), '%Y-%m-%d') fecha, alm.`almacen`
			FROM saldoarticulos sa
			INNER JOIN articulos a ON a.`idArticulos` = sa.`idArticulo`
			INNER JOIN unidad u ON u.`idUnidad` = a.`idUnidad`
			INNER JOIN marca m ON m.`idMarca` = a.`idMarca`
			INNER JOIN almacenes alm ON alm.`idalmacen` = sa.`idAlmacen`
			WHERE sa.`saldo`<> 0
		ORDER BY a.`CodigoArticulo`
		) tbl
		WHERE SUBSTRING(codigo,1,2) = 'TM' OR SUBSTRING(codigo,1,2) = 'TS' 
		";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function showKardexAll ($almacen) 
	{ 
		$sql="SELECT *, IF(id IS NULL,9999999,fechakardex) auxOrd, alm.almacen alm, u.Unidad uni
		
		FROM
		(
		SELECT 
					idArticulo, idUnidad,
					codigo, id, descp, almacen, nombreproveedor, 
					fecha fechakardex, tipo, numMov, punitario, 
					SUM(ingreso) ing,
					SUM(factura) fac,
					SUM(ne) ne,
					SUM(traspaso) tr,
					FechaSis
					 FROM
					(
						/** ingresos **/
							SELECT 	
							art.idUnidad,
							id.`articulo` idArticulo,
							art.CodigoArticulo codigo,
							art.Descripcion descp,
							i.idIngresos id,
							i.`almacen`,
							IF(i.`tipomov`= 3, CONCAT('DE: ',a.`almacen`), p.`nombreproveedor`) nombreproveedor,
							IF(i.`tipomov`=1,0,i.`fechamov`) AS fecha,
							tm.`sigla` tipo,
							i.`nmov` AS numMov,
							id.`punitario`,
							id.`cantidad` ingreso,
							'' factura,
							'' ne,
							'' traspaso,
							tm.`operacion`,
							i.fecha AS FechaSis
							FROM ingdetalle id
								INNER JOIN articulos art on art.idArticulos = id.articulo
								INNER JOIN ingresos i	ON i.`idIngresos`=id.`idIngreso` 
										AND i.`almacen`=$almacen
										AND i.`anulado`=0
										AND i.`estado`=1
										AND YEAR(i.`fechamov`)=(select gestionActual from config)
								INNER JOIN tmovimiento tm ON tm.`id`=i.`tipomov`
								INNER JOIN provedores p ON p.`idproveedor`=i.`proveedor`
								LEFT JOIN traspasos t  ON t.`idIngreso` = i.`idIngresos`
							 	LEFT JOIN egresos e  ON t.`idEgreso` = e.`idegresos`
							 	LEFT JOIN almacenes a ON a.`idalmacen` = e.`almacen`
		
						UNION ALL 
						/** FACTURA **/
							SELECT 
							ar.idUnidad,
							fd.articulo,
							ar.CodigoArticulo,
							ar.Descripcion descp,
							f.idFactura,
							f.almacen,
								c.nombreCliente,
								fechaFac,
								'FAC' ,
								f.nFactura,
								fd.facturaPUnitario,
								'' ingreso,
								 fd.facturaCantidad factura,
								 '' ne,
								 '' traspaso,
								'-',
							f.fecha
							FROM    facturadetalle fd
							INNER JOIN articulos ar ON ar.`idArticulos` = fd.articulo
							INNER JOIN factura f ON f.`idFactura`=fd.`idFactura`
								AND f.`almacen`=$almacen
								AND f.`anulada`=0
								AND YEAR(f.`fechaFac`)=(select gestionActual from config)
							INNER JOIN clientes c ON c.idCliente=f.cliente
								
								
								
						UNION ALL 
						/** NOTA ENTREGA **/
							SELECT 	 
							art.idUnidad,
							ed.articulo,
							art.CodigoArticulo,
							art.Descripcion descp,
							e.idegresos,
							e.almacen,
								 c.nombreCliente,
								 `fechamov`,
								 tm.`sigla`,
								 e.`nmov`,
								 ed.`punitario`,
								 
								 '' ingreso,
								 '' factura,
								 ed.`cantidad`-ed.`cantFact` ne,
								 '' traspaso,
								 
								 tm.`operacion`,
							 e.fecha
							FROM 	egredetalle ed
								INNER JOIN articulos art on art.idArticulos = ed.articulo
								INNER JOIN egresos e ON e.`idegresos`=ed.`idegreso`
									AND e.`anulado`=0
									AND e.`tipomov`= 7
									AND e.`estado`<>1
									AND e.`almacen`=$almacen
								INNER JOIN tmovimiento tm ON tm.`id`=e.tipomov
								INNER JOIN clientes c ON c.idCliente=e.cliente
								WHERE  ed.`cantidad`-ed.`cantFact` <> 0
							
							
							
						UNION ALL 
						/** TRASPASO Y OTROS **/
							SELECT 	 
							art.idUnidad,
							ed.articulo,
							art.CodigoArticulo,
							art.Descripcion descp,
							 e.idegresos,
							 e.almacen,
								 IF(tm.`sigla` = 'EB', c.nombreCliente, CONCAT('A: ',a.`almacen`)) nombreCliente,
								 e.`fechamov`,
								 tm.`sigla`,
								 e.`nmov`,
								 ed.`punitario`,
								 '' ingreso,
								 '' factura,
								 '' ne,
								 ed.`cantidad` traspaso,
				
								 tm.`operacion`,
							 e.fecha
							FROM egredetalle ed
								INNER JOIN articulos art on art.idArticulos = ed.articulo
								INNER JOIN egresos e ON e.`idegresos`=ed.`idegreso`
									AND e.`almacen`=$almacen
									AND e.`anulado`=0
									AND e.`tipomov` BETWEEN 8 AND 9
									AND YEAR(e.`fechamov`)=(select gestionActual from config)
								INNER JOIN tmovimiento tm ON tm.`id`=e.tipomov
								INNER JOIN clientes c ON c.idCliente=e.cliente
								LEFT JOIN traspasos t  ON t.`idEgreso` = e.`idegresos`
								LEFT JOIN ingresos i ON i.`idIngresos`=t.`idIngreso`
								LEFT JOIN almacenes a ON a.`idalmacen` = i.`almacen`
					) AS tmp
				  -- where idArticulo between 51 and 61
					GROUP BY idArticulo, id WITH ROLLUP
					-- order by idArticulo, fechakardex
		)tttt
		INNER JOIN almacenes alm ON alm.idalmacen = tttt.almacen
	  INNER JOIN unidad u ON u.idUnidad = tttt.idUnidad
		ORDER BY codigo, auxOrd, id;";
		$query=$this->db->query($sql);		
		return $query;
	}
	
	public function showKardexAllModel($almacen)  
	{
			$res=$this->Reportes_model->showKardexAll($almacen); 
			$res=$res->result();

			//unset($res[0]);
			$saldoTotal = 0;
			$aux = 0;
			$cost = 0;
			$idArticulo = 0;
			$items = array();
			$titulo = new stdClass();
			$titulo->titulo = 'titulo';
			$cpp = 0;
			foreach ($res as $line) {
				if ($line->id == null) {
					$idArticulo = $line->idArticulo;
					$line->saldo = $line->ing - $line->fac - $line->ne - $line->tr;
					$line->nombreproveedor = 'TOTAL:';
					$line->numMov = '';
				
					$line->saldoTotal = 0;
					$saldoTotal = 0;
					$aux = 0;
					$cpp = 0;
				} else {
					$idArticulo = $line->idArticulo;
					$line->saldo = $line->ing - $line->fac - $line->ne - $line->tr + $aux;
					$line->out = $line->fac + $line->ne + $line->tr;
					$aux = $line->saldo;
					if ($line->ing > 0) {
						$line->saldoTotal = $saldoTotal + ($line->punitario * $line->ing);
						$saldoTotal = $line->saldoTotal;
						$cpp = $aux == 0 ? 0: $saldoTotal / $aux;
						$line->cpp = $cpp;
					} else {
						$line->saldoTotal = $saldoTotal - ($cpp * $line->out);
						$saldoTotal = $line->saldoTotal;
						$cpp = $aux == 0 ? 0: $saldoTotal / $aux;
						$line->cpp = $cpp;
					}


				}
				
			}
				return $res;
		
	}
	public function showKardexAllSN ($almacen) 
	{ 
		$sql="SELECT *, IF(id IS NULL,9999999,fechakardex) auxOrd, alm.almacen alm, u.Unidad uni
		
		FROM
		(
		SELECT 
					idArticulo, idUnidad,
					codigo, id, descp, almacen, nombreproveedor, 
					fecha fechakardex, tipo, numMov, punitario, 
					SUM(ingreso) ing,
					SUM(factura) fac,
					SUM(ne) ne,
					SUM(traspaso) tr,
					FechaSis
					 FROM
					(
						/** ingresos **/
							SELECT 	
							art.idUnidad,
							id.`articulo` idArticulo,
							art.CodigoArticulo codigo,
							art.Descripcion descp,
							i.idIngresos id,
							i.`almacen`,
							IF(i.`tipomov`= 3, CONCAT('DE: ',a.`almacen`), p.`nombreproveedor`) nombreproveedor,
							IF(i.`tipomov`=1,0,i.`fechaIngreso`) AS fecha,
							tm.`sigla` tipo,
							i.`nmov` AS numMov,
							id.`punitario`,
							id.`cantidad` ingreso,
							'' factura,
							'' ne,
							'' traspaso,
							tm.`operacion`,
							i.fecha AS FechaSis
							FROM ingdetalle id
								INNER JOIN articulos art on art.idArticulos = id.articulo
								INNER JOIN ingresos i	ON i.`idIngresos`=id.`idIngreso` 
										AND i.`almacen`=$almacen
										AND i.`anulado`=0
										AND i.`estado`=1
										AND YEAR(i.`fechamov`)=(select gestionActual from config)
								INNER JOIN tmovimiento tm ON tm.`id`=i.`tipomov`
								INNER JOIN provedores p ON p.`idproveedor`=i.`proveedor`
								LEFT JOIN traspasos t  ON t.`idIngreso` = i.`idIngresos`
							 	LEFT JOIN egresos e  ON t.`idEgreso` = e.`idegresos`
							 	LEFT JOIN almacenes a ON a.`idalmacen` = e.`almacen`
		
						UNION ALL 
						/** FACTURA **/
							SELECT 
							ar.idUnidad,
							fd.articulo,
							ar.CodigoArticulo,
							ar.Descripcion descp,
							f.idFactura,
							f.almacen,
								c.nombreCliente,
								fechaFac,
								'FAC' ,
								f.nFactura,
								fd.facturaPUnitario,
								'' ingreso,
								 fd.facturaCantidad factura,
								 '' ne,
								 '' traspaso,
								'-',
							f.fecha
							FROM    facturadetalle fd
							INNER JOIN articulos ar ON ar.`idArticulos` = fd.articulo
							INNER JOIN factura f ON f.`idFactura`=fd.`idFactura`
								AND f.`almacen`=$almacen
								AND f.`anulada`=0
								AND YEAR(f.`fechaFac`)=(select gestionActual from config)
							INNER JOIN clientes c ON c.idCliente=f.cliente
							
						UNION ALL 
						/** TRASPASO Y OTROS **/
							SELECT 	 
							art.idUnidad,
							ed.articulo,
							art.CodigoArticulo,
							art.Descripcion descp,
							 e.idegresos,
							 e.almacen,
								 IF(tm.`sigla` = 'EB', c.nombreCliente, CONCAT('A: ',a.`almacen`)) nombreCliente,
								 e.`fechamov`,
								 tm.`sigla`,
								 e.`nmov`,
								 ed.`punitario`,
								 '' ingreso,
								 '' factura,
								 '' ne,
								 ed.`cantidad` traspaso,
				
								 tm.`operacion`,
							 e.fecha
							FROM egredetalle ed
								INNER JOIN articulos art on art.idArticulos = ed.articulo
								INNER JOIN egresos e ON e.`idegresos`=ed.`idegreso`
									AND e.`almacen`=$almacen
									AND e.`anulado`=0
									AND e.`tipomov` BETWEEN 8 AND 9
									AND YEAR(e.`fechamov`)=(select gestionActual from config)
								INNER JOIN tmovimiento tm ON tm.`id`=e.tipomov
								INNER JOIN clientes c ON c.idCliente=e.cliente
								LEFT JOIN traspasos t  ON t.`idEgreso` = e.`idegresos`
								LEFT JOIN ingresos i ON i.`idIngresos`=t.`idIngreso`
								LEFT JOIN almacenes a ON a.`idalmacen` = i.`almacen`
					) AS tmp
					-- where idArticulo between 51 and 61
					GROUP BY idArticulo, id WITH ROLLUP
					-- order by idArticulo, fechakardex
		)tttt
		INNER JOIN almacenes alm ON alm.idalmacen = tttt.almacen
	  INNER JOIN unidad u ON u.idUnidad = tttt.idUnidad
		ORDER BY codigo, auxOrd, id;";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function showKardexAllModelSN($almacen)  
	{
			$res=$this->Reportes_model->showKardexAllSN($almacen); 
			$res=$res->result();

			//unset($res[0]);
			$saldoTotal = 0;
			$aux = 0;
			$cost = 0;
			$idArticulo = 0;
			$items = array();
			$titulo = new stdClass();
			$titulo->titulo = 'titulo';
			$cpp = 0;
			foreach ($res as $line) {
				if ($line->id == null) {
					$idArticulo = $line->idArticulo;
					$line->saldo = $line->ing - $line->fac - $line->tr;
					$line->nombreproveedor = 'TOTAL:';
					$line->numMov = '';
				
					$line->saldoTotal = 0;
					$saldoTotal = 0;
					$aux = 0;
					$cpp = 0;
				} else {
					$idArticulo = $line->idArticulo;
					$line->saldo = $line->ing - $line->fac - $line->tr + $aux;
					$line->out = $line->fac + $line->ne + $line->tr;
					$aux = $line->saldo;
					if ($line->ing > 0) {
						$line->saldoTotal = $saldoTotal + ($line->punitario * $line->ing);
						$saldoTotal = $line->saldoTotal;
						$cpp = $aux == 0 ? 0: $saldoTotal / $aux;
						$line->cpp = $cpp;
					} else {
						$line->saldoTotal = $saldoTotal - ($cpp * $line->out);
						$saldoTotal = $line->saldoTotal;
						$cpp = $aux == 0 ? 0: $saldoTotal / $aux;
						$line->cpp = $cpp;
					}


				}
				
			}
			return $res;
		
	}
	public function showGestionActual()
	{
		$sql="SELECT c.`gestionActual`
		FROM config c
		";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function reportPagos($ini, $end, $alm )
	{
		$sql="SELECT pf.`idPago`,pf.`idFactura`, pf.`monto` montoRaw,ap.`almacen` almPago,p.`fechaPago`,  p.`numPago`, c.`nombreCliente` clienteCab,
					af.`siglAlm` almFac,  f.`nFactura`, f.`fechaFac`, f.`ClienteFactura`, f.`ClienteNit`, pf.`monto`, p.`anulado`, f.`anulada`
				FROM pago_factura pf
				INNER JOIN pago p ON p.`idPago` = pf.`idPago`
				INNER JOIN factura f ON f.`idFactura` = pf.`idFactura`
				INNER JOIN clientes c ON c.`idCliente` = p.`cliente`
				INNER JOIN almacenes ap ON ap.`idalmacen` = p.`almacen`
				INNER JOIN almacenes af ON af.`idalmacen` = f.`almacen`
				WHERE p.`fechaPago` BETWEEN '$ini' AND '$end'
				AND p.`almacen` LIKE '%$alm'
				AND p.`anulado` = 0
				ORDER BY p.`numPago`
		";
		$query=$this->db->query($sql);		
		return $query;
	}


}
