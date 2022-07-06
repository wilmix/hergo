<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class ApiModel extends CI_Model
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
	}
	public function showMenuItems()
	{
		$sql="  SELECT
                    'n1' nivel,
                    n1.id id,
                    n1.name menu,
                    '' parent 
                FROM
                    web_nivel1 n1
                UNION ALL
                SELECT
                    'n2' nivel,
                    n2.id id,
                    n2.name menu,
                    n2.id_nivel1 parent 
                FROM
                    web_nivel2 n2
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
	}
    public function prueba()
	{
		$sql=   "SELECT
                    n1.id n1_id,
                    n1.name n1,
                    n2.id n2_id,
                    n2.id_nivel1,
                    n2.name n2,
                    n3.id n3_id,
                    n3.name n3
                FROM
                    web_nivel1 n1
                LEFT JOIN web_nivel2 n2 ON n2.id_nivel1 = n1.id
                LEFT JOIN web_nivel3 n3 ON n3.id_nivel2 = n2.id 
                ";
		$query=$this->db->query($sql);		
		return $query->result_array();
	}
    public function nivel_1($is_service)
	{
		$sql=   "SELECT
                    n1.id,
                    n1.`name`,
                    n1.url
                FROM
                    web_nivel1 n1
                WHERE n1.is_active = 1
                AND n1.is_service = $is_service
                ORDER BY n1.`name` 
                ";
		$query=$this->db->query($sql);		
		return $query->result();
	}
    public function nivel_2($id_n1)
	{
		$sql=   "SELECT
                    n2.id,
                    n2.`name`,
                    n2.url
                FROM
                    web_nivel2 n2
                WHERE 
                    n2.id_nivel1 = $id_n1
                    AND n2.is_active = 1
                ORDER BY n2.name DESC 
                ";
		$query=$this->db->query($sql);		
		return $query->result();
	}
    public function nivel_3($id_n2)
	{
		$sql=   "SELECT
                    *
                FROM
                    web_nivel3 n3
                WHERE
                    n3.id_nivel2 = $id_n2
                    AND n3.is_active = 1
                ORDER BY n3.name
                ";
		$query=$this->db->query($sql);		
		return $query->result();
	}
    public function lineProducts($is_service)
	{
		$sql=   "SELECT
                    n.`name`,
                    n.description,
                    n.img,
                    n.url
                FROM
                    web_nivel1 n
                WHERE
                    n.is_active = 1
                    AND n.is_service = $is_service
                ";
		$query=$this->db->query($sql);		
		return $query->result();
        }
        public function services()
        {
            $sql=   "SELECT
                        n.`name`,
                        n.description,
                        n.img,
                        n.url
                    FROM
                        web_nivel1 n
                    WHERE
                        n.is_active = 1
                        AND n.is_service = 1
                    ";
            $query=$this->db->query($sql);		
            return $query->result();
        }
        public function item($id)
        {
            $sql=   "SELECT
                        a.articulo_id id,
                        a.titulo,
                        a.descripcion,
                        a.imagen,
                        a.fichaTecnica,
                        a.video 
                    FROM
                        web_articulos a
                    WHERE
                        a.id = $id
                    ";
            $query=$this->db->query($sql);		
            return $query->result();
        }
        public function list_items($n3)
        {
            $sql=   "SELECT
                        aw.id,
                        aw.articulo_id,
                        n1.id n1_id,
                        n1.`name` n1_title,
                        n1.url n1_url,
                        n2.id n2_id,
                        n2.`name` n2_title,
                        n2.url n2_url,
                        n3.id n3_id,
                        n3.`name` n3_title,
                        n3.url n3_url,
                        aw.titulo,
                        n2.url,
                        aw.imagen
                    FROM
                        web_articulos aw
                        INNER JOIN web_nivel1 n1 ON n1.id = aw.n1_id
                        INNER JOIN web_nivel2 n2 ON n2.id = aw.n2_id
                        INNER JOIN web_nivel3 n3 ON n3.id = aw.n3_id
                        INNER JOIN articulos a ON a.idArticulos = aw.articulo_id
                        
                    WHERE
                        n3.id = '$n3'
                        and a.web_catalogo = 1
                    ";
            $query=$this->db->query($sql);		
            return $query->result();
        }
        public function getSubList($n2)
        {
            $sql=   "SELECT
                        a.n3_id,
                        n2.`name` n2_name,
                        n3.`name` n3_name
                    FROM
                        web_articulos a
                        INNER JOIN web_nivel2 n2 ON n2.id = a.n2_id
                        INNER JOIN web_nivel3 n3 ON n3.id = a.n3_id
                    WHERE
                        n2.url = '$n2'
                    GROUP BY 
                        a.n3_id
                    ORDER BY n3.`name` DESC  
                    ";
            $query=$this->db->query($sql);		
            return $query->result();
        }
        public function getListN2($line)
        {
            $sql=   "SELECT
                        n2.id,
                        n1.`name` name_n1,
                        n2.`name` name_n2,
                        n2.url url_n2,
                        n2.description,
                        n2.img
                    FROM
                        web_nivel2 n2
                        INNER JOIN web_nivel1 n1 ON n1.id = n2.id_nivel1
                    WHERE
                        n1.url = '$line'
                        AND n2.is_active = 1
                                ";
            $query=$this->db->query($sql);		
            return $query->result();
        }
        public function factura($id)
        {
                $sql=  " SELECT
                            '1000991026' nitEmisor,
                            'HERGO LTDA' razonSocialEmisor,
                            a.ciudad municipio,
                            a.phone telefono,
                            f.nFactura numeroFactura,
                            '' cuf,
                            '' cufd,
                            a.siat_sucursal codigoSucursal,
                            a.address direccion,
                            '1' codigoPuntoVenta,
                            SUBSTRING(DATE_FORMAT(NOW(3), '%Y-%m-%dT%H:%i:%s.%f'),1,23) fechaEmision,
                            -- f.fechaFac fechaEmision,
                            c.nombreCliente nombreRazonSocial,
                            c.idDocumentoTipo codigoTipoDocumentoIdentidad,
                            c.documento numeroDocumento,
                            '' complemento,
                            c.idCliente codigoCliente,
                            f.tipoPago codigoMetodoPago,
                            '' numeroTarjeta,
                            ROUND(SUM(fd.facturaCantidad * fd.facturaPUnitario),2) montoTotal,
                            ROUND(SUM(fd.facturaCantidad * fd.facturaPUnitario),2) montoTotalSujetoIva,
                            '1' codigoMoneda,
                            '1' tipoCambio,
                            ROUND(SUM(fd.facturaCantidad * fd.facturaPUnitario),2) montoTotalMoneda,
                            NULL montoGiftCard,
                            0 descuentoAdicional,
                            '' codigoExcepcion,
                            '' cafc,
                            'leyenda' leyenda,
                            CONCAT(SUBSTRING(upper(u.first_name), 1, 1),SUBSTRING(upper(u.last_name), 1, 9)) usuario,
                            1 codigoDocumentoSector,
                            -- SUBSTRING(DATE_FORMAT(NOW(3), '%Y-%m-%dT%H:%i:%s.%f'),1,23) fechaSiat,
                            SUBSTRING(DATE_FORMAT(NOW(3), '%Y%m%d%H%i%s%f'),1,17) fechaMod11Siat

                            
                        FROM
                            factura f
                            INNER JOIN almacenes a ON a.idalmacen = f.almacen
                            INNER JOIN clientes c ON c.idCliente = f.cliente
                            inner join facturadetalle fd on fd.idFactura = f.idFactura
                            inner join users u ON u.id = f.autor
                        
                        WHERE
                        f.idFactura = '$id'
                ";
		$query=$this->db->query($sql);		
		return $query->result();
	}
    public function facturaDetalle($id)
	{
		$sql=   " SELECT
                        '465000' actividadEconomica,
                        99100 codigoProductoSin,
                        fd.ArticuloCodigo codigoProducto,
                        fd.ArticuloNombre descripcion,
                        fd.facturaCantidad cantidad,
                        u.siat_codigo unidadMedida,
                        ROUND(fd.facturaPUnitario,2) precioUnitario,
                        0 montoDescuento,
                        ROUND(fd.facturaPUnitario * fd.facturaCantidad, 2) subTotal,
                        a.NumParte numeroSerie,
                        NULL numeroImei
                        
                        
                    FROM
                        facturadetalle fd
                        INNER JOIN factura f on f.idFactura = fd.idFactura
                        INNER JOIN articulos a on a.idArticulos = fd.articulo
                        INNER JOIN unidad u on u.idUnidad = a.idUnidad
                    WHERE
                        f.idFactura = '$id'
                ";
		$query=$this->db->query($sql);		
		return $query->result();
	}
    public function promos()
    {
        $sql="  SELECT
                    *
                FROM
                    web_promos wp
                WHERE
                    wp.is_active = 1
            ";
            $query=$this->db->query($sql);		
            return $query->result();
    }
    public function search($search)
    {
        $sql="  SELECT
                    *
                FROM
                    web_articulos wa
                WHERE
                    wa.titulo LIKE '%$search%'
                    OR wa.descripcion LIKE '%$search%'
                    
            ";
            $query=$this->db->query($sql);		
            return $query->result();
    }
    public function listaFacturas($almacen)
    {
        $sql="  SELECT
                    f.idFactura id
                FROM
                    factura f
                    INNER JOIN facturadetalle fd ON f.idFactura = fd.idFactura
                WHERE
                    f.almacen = '1'
                    -- and f.nFactura BETWEEN 10 and 1000
                    AND year(f.fechaFac) = '2021'
                    AND MONTH(f.fechaFac) BETWEEN 8
                    AND 9
                    
                GROUP BY
                    f.idFactura
                    ORDER BY f.nFactura
                LIMIT
                    10
            ";
            $query=$this->db->query($sql);		
            return $query->result();
    }
    public function cuis($sucursal, $pos)
    {
        $sql="  SELECT
                    *
                FROM
                    siat_cuis
                WHERE
                    siat_cuis.active
                    AND siat_cuis.sucursal = '$sucursal'
                    AND siat_cuis.codigoPuntoVenta = '$pos'
            ";
            $query=$this->db->query($sql);		
            return $query->result();
    }
}
