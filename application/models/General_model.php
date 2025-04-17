<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
    public function getTipoCambio($fecha)
    {
        $sql="SELECT * FROM tipocambio tc 
                WHERE  tc.`fecha` = '$fecha'
                ORDER BY id DESC LIMIT 1";
        $resultado=$this->db->query($sql);
        if($resultado->num_rows()>0)
        {
            $fila=$resultado->row();
            return ($fila);
        }
        else
        {
            return FALSE;
        }
    }
    public function searchItem($item,$alm)
    {
		$sql="  SELECT
                    a.idArticulos id,
                    CONCAT(a.CodigoArticulo, ' | ' , a.Descripcion) label,
                    a.CodigoArticulo codigo,
                    a.Descripcion descrip,
                    m.Marca marca,
                    u.Unidad uni,
                    a.precio,
                    a.precioDol,
                    sa.saldo,
                    a.Imagen img,
                    l.Linea linea 
                FROM
                    articulos a
                INNER JOIN unidad u on u.idUnidad = a.idUnidad
                inner join marca m on m.idMarca = a.idMarca
                inner join linea l on l.idLinea = a.idLinea
                left join saldoarticulos sa on sa.idArticulo = a.idArticulos and sa.idAlmacen = '$alm'
                where
                    a.CodigoArticulo like '%$item%'
                    or a.Descripcion like '%$item%'
                    AND a.EnUso = 1
                ORDER By
                    a.CodigoArticulo
                LIMIT
                    50";
		$query=$this->db->query($sql);
		return $query;
    }
    public function searchClientes($search)
    {
		$sql="  SELECT
                    c.`idCliente` id ,
                    CONCAT(c.`nombreCliente`, ' | ' , c.`documento`) label
                    
                FROM
                    clientes c
                where
                    nombreCliente LIKE '%$search%'
                    OR documento LIKE '%$search%'
                ORDER By nombreCliente asc
                LIMIT 50";
		$query=$this->db->query($sql);
		return $query;
    }
    public function getAlmacenes()
	{ 
    	$sql="      SELECT
                    a.idalmacen value,
                    a.almacen alm 
                    from almacenes a 
                        ";
        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getMonedas()
	{ 
    	$sql="      SELECT
                    m.id 'value',
                    m.moneda 
                    from moneda m";
        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getArticulos()
	{ 
    	$sql="      SELECT
                        a.idArticulos value,
                        concat(a.CodigoArticulo, ' | ', a.Descripcion) label
                    FROM
                        articulos a
                    WHERE
                        a.EnUso = 1";
        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getArticulo($id, $alm)
	{ 
    	$sql="      SELECT
                        a.idArticulos id,
                        a.CodigoArticulo codigo,
                        CONCAT(a.CodigoArticulo, ' | ' , a.Descripcion) label,
                        a.Descripcion descrip,
                        m.Marca marca,
                        m.Sigla marcaSigla,
                        u.Unidad uni,
                        a.precio,
                        a.precioDol,
                        sa.saldo,
                        a.Imagen img,
                        l.Linea linea
                    FROM
                        articulos a
                        INNER JOIN unidad u on u.idUnidad = a.idUnidad
                        inner join marca m on m.idMarca = a.idMarca
                        inner join linea l on l.idLinea = a.idLinea
                        left join saldoarticulos sa on sa.idArticulo = a.idArticulos
                        and sa.idAlmacen = '$alm'
                    where
                        a.idArticulos = '$id'";
        $query=$this->db->query($sql);	
		return $query->row();
    }
    public function getCantidadNotasEntregaPendientes($user)
	{ 
        //$user = 25;
    	$sql="  SELECT
                    count(distinct(e.idegresos)) pendientes
                FROM
                    egresos e
                WHERE
                    e.`estado` <> 1
                    AND e.tipomov = 7
                    AND e.anulado = 0
                    AND e.fechamov > '2010-01-01'
                    AND e.autor = $user
                ";
        $query=$this->db->query($sql);	
		return $query->row();
    }
    public function getGestionActual()
    {
        $sql="SELECT gestionActual FROM config LIMIT 1";
        $sql=$this->db->query($sql);
        return $sql->row();
    }
    
    public function getCufdStatus ()
    {
        $sql = "SELECT 
                    COUNT(*) as total_vigentes
                FROM
                    siat_cufd c
                    INNER JOIN (
                        SELECT
                            MAX(c.id) cufd_id_last
                        FROM
                            siat_cufd c
                        GROUP BY
                            c.cuis
                    ) last_cufd ON last_cufd.cufd_id_last = c.id
                    INNER JOIN siat_cuis cuis on cuis.cuis = c.cuis AND cuis.active = 1
                    INNER JOIN almacenes a ON a.siat_sucursal = cuis.sucursal
                WHERE
                    STR_TO_DATE(c.fechaVigencia,'%Y-%m-%dT%T') > NOW()";

            $query = $this->db->query($sql);
            $result = $query->row();
            
        return $result;
    }
}
