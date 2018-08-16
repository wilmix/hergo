<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Reportes_model extends CI_Model  ////////////***** nombre del modelo 
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
	public function retornarArticulos() {
		$sql="SELECT idArticulos, CodigoArticulo,Descripcion
		FROM hergo2.articulos_activos
		WHERE SUBSTRING(CodigoArticulo,1,2)<>'SR'";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function retornarClientes3() {
		$sql="SELECT f.`cliente`, c.`nombreCliente`, c.`documento`
			FROM factura f
				INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
				WHERE YEAR(f.`fechaFac`) BETWEEN (YEAR(NOW())-3) AND YEAR(NOW())
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
	public function mostrarNEporFac($ini=null,$fin=null,$alm="") ///********* nombre de la funcion mostrar
	{ //cambiar la consulta
		$sql="SELECT e.nmov n,e.idEgresos,t.sigla, e.fechamov, c.nombreCliente, SUM(d.total) total,  e.estado,e.fecha, CONCAT(u.first_name,' ', u.last_name) autor, a.almacen, m.sigla monedasigla			FROM egresos e
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
            ON e.tipocambio=tc.id
			WHERE
			e.`estado`<>1
			AND t.id = 7
			AND e.fechamov 
			BETWEEN '$ini' AND '$fin'
			AND e.almacen LIKE '%$alm'
			GROUP BY e.idegresos
			ORDER BY c.nombreCliente";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarFacturasPendientesPago($ini=null,$fin=null,$alm="") ///********* nombre de la funcion mostrar
	{ //cambiar la consulta
		$sql="SELECT * FROM
		(SELECT f.`lote` lote, a.`almacen` alm, f.`fecha` fecha,f.`nFactura` nFac,c.`nombreCliente` cliente, 
			f.`total` totalFactura,NULL  ,f.`glosa`,  IFNULL((`f`.`total` - `p`.`montoPago`),'noPagado') pagada
		FROM factura f
		INNER JOIN clientes c ON c.`idCliente`= f.`cliente`
		INNER JOIN almacenes a ON a.`idalmacen` = f.`almacen`
		LEFT JOIN pagos p ON p.`idFactura` = f.`idFactura`
		WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin'
		AND IFNULL((`f`.`total` - `p`.`montoPago`),'noPagado')='noPagado' 
		AND f.`anulada` = 0 
		AND f.`pagada` = 0
		AND f.`almacen` LIKE '%$alm'
		UNION ALL
		SELECT f.`lote`, a.`almacen`, p.`fechaPago`,f.`nFactura`,c.`nombreCliente`, 
			  f.`total` , p.`montoPago` , p.`glosaPago`, IFNULL((`f`.`total` - `p`.`montoPago`),'noPagado') pagada
		FROM factura f
		INNER JOIN clientes c ON c.`idCliente`= f.`cliente`
		INNER JOIN almacenes a ON a.`idalmacen` = f.`almacen`
		LEFT JOIN pagos p ON p.`idFactura` = f.`idFactura`
		WHERE f.`fechaFac` BETWEEN '$ini' AND '$fin'
		AND IFNULL((`f`.`total` - `p`.`montoPago`),'noPagado') > 0
		AND f.`anulada` = 0 
		AND f.`pagada` = 0
		AND p.`almacen` LIKE '%$alm') AS noPagadas
		
		ORDER BY cliente, fecha";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarListaPrecios() ///********* nombre de la funcion mostrar
	{ //cambiar la consulta
		$sql="SELECT CodigoArticulo, Descripcion, unidad.Sigla, precio.precio AS Bolivianos, precio.precio/6.96 AS Dolares
			FROM articulos
			INNER JOIN unidad ON unidad.idUnidad=articulos.idUnidad
			INNER JOIN precio ON precio.idArticulo=articulos.idArticulos
			ORDER BY CodigoArticulo";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarSaldos() ///********* nombre de la funcion mostrar
	{ //cambiar la consulta
		$sql="SELECT * FROM hergo2.articulos_activos
		WHERE SUBSTRING(CodigoArticulo,1,2)<>'SR'";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarFacturacionClientes($ini=null,$fin=null,$alm="") ///********* nombre de la funcion mostrar
	{ //cambiar la consulta
		$sql="SELECT clientes.nombreCliente, SUM(total) AS total
			FROM factura
			INNER JOIN clientes ON clientes.idCliente = factura.cliente
			WHERE fechaFac BETWEEN '$ini' AND '$fin'
			AND factura.almacen LIKE '%$alm'
			AND factura.anulada = 0
			GROUP BY clientes.nombreCliente
			ORDER BY total DESC";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarVentasLineaMes($ini=null,$fin=null,$alm="") ///********* nombre de la funcion mostrar
	{ //cambiar la consulta
		$sql="	SELECT linea.Sigla, linea.Linea, sum(facturadetalle.facturaCantidad*facturadetalle.facturaPUnitario) as total
				from facturadetalle
				inner join articulos on articulos.idArticulos = facturadetalle.articulo
				inner join linea on linea.idLinea=articulos.idLinea
				inner join factura on factura.idFactura=facturadetalle.idFactura
				where fechaFac between '$ini' and '$fin'
				and factura.anulada = 0
				and factura.almacen LIKE '%$alm'
				group by linea
				order by sigla";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarLibroVentas($ini=null,$fin=null,$alm="") ///********* nombre de la funcion mostrar
	{ //cambiar la consulta
		$sql="SELECT fechaFac, nFactura, df.autorizacion, anulada, c.documento, c.nombreCliente,
		IF(f.`anulada`=1,0,f.`total`) sumaDetalle,
		IF(anulada = 1 , 0 , ROUND ((f.total*13/100),2))  AS debito, 
		IFNULL(codigoControl, 0) AS codigoControl , df.manual
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.idDatosFactura=f.lote
		INNER JOIN clientes AS c ON c.idCliente = f.cliente
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '$alm'
		ORDER BY  df.manual, nFactura
		";
		
		$query=$this->db->query($sql);		
		return $query;
	}
/*SELECT fechaFac, nFactura, df.autorizacion, anulada, c.documento, c.nombreCliente,
		IF(anulada=1 , 0 , ROUND((f.total,2)) AS sumaDetalle, 
		IF(anulada = 1 , 0 , ROUND ((f.total*13/100),2))  AS debito, 
		IFNULL(codigoControl, 0) AS codigoControl , df.manual
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.lote=f.lote
		INNER JOIN clientes AS c ON c.idCliente = f.cliente
		INNER JOIN facturadetalle AS fd ON fd.idFactura=f.idFactura
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		GROUP BY f.idFactura
		ORDER BY  df.manual, nFactura */
	public function mostrarLibroVentasTotales($ini=null,$fin=null,$alm="") ///********* nombre de la funcion mostrar
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
		SELECT IFNULL(NULL, 'Total') ,ROUND ((SUM(fd.facturaPUnitario*fd.facturaCantidad)),2) AS sumaTotal
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.idDatosFactura=f.lote
		INNER JOIN facturadetalle AS fd ON fd.idFactura=f.idFactura
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		AND anulada=0
		union
		/*Suma Debito*/
		SELECT IFNULL(NULL, 'debito') ,ROUND ((SUM(fd.facturaPUnitario*fd.facturaCantidad)*13/100),2) AS debito
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.idDatosFactura=f.lote
		INNER JOIN clientes AS c ON c.idCliente = f.cliente
		INNER JOIN facturadetalle AS fd ON fd.idFactura=f.idFactura
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		AND anulada=0
		 ";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarDiarioIngresos($ini=null,$fin=null,$alm="",$tin="") ///********* nombre de la funcion mostrar
	{ //cambiar la consulta
		$sql="SELECT alm.almacen, i.fechamov, i.tipomov,t.sigla, i.nmov, i.ordcomp, i.ningalm, p.nombreproveedor, 
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
		where i.fechamov BETWEEN '$ini' AND '$fin'
		and i.tipomov like '%$tin' AND i.almacen LIKE '%$alm'
		order by alm.almacen, i.nmov, id.articulo";
		
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarKardexIndividual($art="",$alm="") ///********* nombre de la funcion mostrar
	{ //cambiar la consulta
		$sql="call hergo2.testKardex2($art,$alm);";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function kardexIndividualCliente($cliente,$almacen="",$ini,$fin) {
		$sql="SELECT * FROM 
		(	SELECT    c.`idCliente`, c.`nombreCliente`,'$ini' fecha, '-' numDocumento, '1' almacen,  'SALDO INICIAL' detalle,  
					IFNULL((SELECT ROUND(SUM(`ed`.`total`), 2) `saldoTotalNE`
					FROM`egresos` `e`
						INNER JOIN `egredetalle` `ed` ON `ed`.`idegreso` = `e`.`idegresos`
					WHERE
						`e`.`fechamov` < '$ini'
						AND `e`.`estado` <> 1
						AND `e`.`anulado` = 0
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
		ORDER BY fecha";
		$query=$this->db->query($sql);		
		return $query;
	}




}
