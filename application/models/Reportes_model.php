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
			e.`estado`=0
			AND t.id = 7
			AND e.fechamov 
			BETWEEN '$ini' AND '$fin'
			AND e.almacen LIKE '%$alm'
			GROUP BY e.idegresos
			ORDER BY c.nombreCliente";
		
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
		$sql="SELECT CodigoArticulo, Descripcion, unidad.Sigla
			FROM articulos
			INNER JOIN unidad ON unidad.idUnidad=articulos.idUnidad
			INNER JOIN precio ON precio.idArticulo=articulos.idArticulos
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
			AND factura.anulada = 1
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
		$sql="SELECT fechaFac, nFactura, df.autorizacion, anulada, c.documento,/*clienteNit,*/c.nombreCliente, /*      ClienteFactura,*/ total AS totalFactura, SUM(fd.facturaPUnitario*fd.facturaCantidad) AS sumaDetalle,   total*13/100 AS debito, IFNULL(codigoControl, 0) AS codigoControl , df.manual
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


}
