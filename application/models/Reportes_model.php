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
	public function retornarArticulos()
	{
		$sql="SELECT a.idArticulos, a.CodigoArticulo, a.Descripcion
		FROM articulos a";
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
		$sql="SELECT idArticulos, CodigoArticulo, Descripcion, unidad.Sigla, IFNULL(lpz.`saldo`,'-') laPaz, IFNULL(ea.`saldo`,'-') elAlto, IFNULL(pts.`saldo`,'-') potosi, IFNULL(scz.`saldo`,'-')santacruz
		FROM articulos a
		INNER JOIN unidad ON unidad.idUnidad=a.idUnidad
		LEFT JOIN saldoarticulos lpz ON lpz.`idArticulo`= a.`idArticulos` AND lpz.`idAlmacen`=1
		LEFT JOIN saldoarticulos ea ON ea.`idArticulo`= a.`idArticulos` AND ea.`idAlmacen`=2
		LEFT JOIN saldoarticulos pts ON pts.`idArticulo`= a.`idArticulos` AND pts.`idAlmacen`=3
		LEFT JOIN saldoarticulos scz ON scz.`idArticulo`= a.`idArticulos` AND scz.`idAlmacen`=4
		WHERE lpz.`saldo` IS NOT NULL 
		ORDER BY CodigoArticulo";
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
		IF(anulada=1 , 0 , ROUND(SUM(fd.facturaPUnitario*fd.facturaCantidad),2)) AS sumaDetalle, IF(anulada = 1 , 0 , ROUND ((SUM(fd.facturaPUnitario*fd.facturaCantidad)*13/100),2))  AS debito, 
		IFNULL(codigoControl, 0) AS codigoControl , df.manual
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.lote=f.lote
		INNER JOIN clientes AS c ON c.idCliente = f.cliente
		INNER JOIN facturadetalle AS fd ON fd.idFactura=f.idFactura
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		GROUP BY f.idFactura
		ORDER BY  df.manual, nFactura";
		
		$query=$this->db->query($sql);		
		return $query;
	}
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
		INNER JOIN datosfactura AS df ON df.lote=f.lote
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		AND df.manual=1
		union
		/*contar computarizadas*/
		SELECT IFNULL(NULL, 'computarizadas') ,COUNT(df.manual)
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.lote=f.lote
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		AND df.manual=0
		union
		/*Suma Total*/
		SELECT IFNULL(NULL, 'Total') ,ROUND ((SUM(fd.facturaPUnitario*fd.facturaCantidad)),2) AS sumaTotal
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.lote=f.lote
		INNER JOIN facturadetalle AS fd ON fd.idFactura=f.idFactura
		WHERE fechaFac BETWEEN '$ini' AND '$fin'
		AND f.almacen LIKE '%$alm'
		AND anulada=0
		union
		/*Suma Debito*/
		SELECT IFNULL(NULL, 'debito') ,ROUND ((SUM(fd.facturaPUnitario*fd.facturaCantidad)*13/100),2) AS debito
		FROM factura AS f
		INNER JOIN datosfactura AS df ON df.lote=f.lote
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




}
