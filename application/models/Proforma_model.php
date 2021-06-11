<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proforma_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
    public function getProformas($ini, $fin, $alm)
	{ 
    	$sql="      SELECT
                        p.id,
                        a.almacen,
                        p.fecha,
                        p.num,
                        c.nombreCliente,
                        m.sigla,
                        p.condicionesPago,
                        p.validezOferta,
                        p.lugarEntrega,
                        p.total,
                        pt.tipo,
                        p.porcentajeDescuento,
                        concat(u.first_name, ' ', u.last_name) autor,
                        u.email,
                        p.created_at
                    FROM
                        proforma p
                        INNER JOIN almacenes a ON a.idalmacen = p.almacen
                        INNER JOIN clientes c on c.idCliente = p.cliente
                        inner join moneda m on m.id = p.moneda
                        inner join users u on u.id = p.autor
                        INNER JOIN 	proforma_tipo pt on pt.id = p.tipo
                    WHERE
                    p.fecha BETWEEN '$ini' and '$fin'
                    and p.almacen = '$alm'
            ";

        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getProforma($id)
	{ 
    	$sql="      SELECT
                        p.id,
                        a.almacen,
                        a.direccion almDirec,
                        a.Telefonos almTel,
                        a.ciudad ciudadAlm,
                        p.fecha,
                        p.num,
                        c.nombreCliente clienteNombre,
                        c.direccion clienteDirec,
                        c.telefono clienteTelefono,
                        c.email clienteEmail,
                        m.sigla,
                        p.condicionesPago,
                        p.validezOferta,
                        p.lugarEntrega,
                        p.tiempoEntrega,
                        p.garantia,
                        p.total,
                        m.moneda,
                        pt.tipo,
                        p.porcentajeDescuento,
                        p.descuento,
                        concat(u.first_name, ' ', u.last_name) autorNombre,
                        u.email autorEmail,
                        u.phone autorPhone,
                        p.glosa,
                        p.created_at
                    FROM
                        proforma p
                        INNER JOIN almacenes a ON a.idalmacen = p.almacen
                        INNER JOIN clientes c on c.idCliente = p.cliente
                        inner join moneda m on m.id = p.moneda
                        inner join users u on u.id = p.autor
                        INNER JOIN 	proforma_tipo pt on pt.id = p.tipo
                    WHERE
                    p.id = '$id'
            ";

        $query=$this->db->query($sql);	
		return $query->row();
    }
    public function getProformaItems($id)
	{ 
    	$sql="      SELECT
                        a.CodigoArticulo codigo,
                        u.Unidad uni,
                        pit.marca marca,
                        pit.descripcion,
                        pit.cantidad,
                        pit.tiempoEntrega,
                        pit.industria,
                        pit.precio,
                        pit.total,
                        a.Imagen img
                        
                    FROM
                        proforma_items pit
                        INNER join articulos a on a.idArticulos = pit.articulo_id
                        inner join unidad u on u.idUnidad = a.idUnidad
                        INNER join marca m on m.idMarca = a.idMarca
                        
                    WHERE
                        pit.proforma_id = '$id'
            ";

        $query=$this->db->query($sql);	
		return $query->result();
    }


    public function storeProforma($id, $proforma)
	{	
        if ($id) {
            $this->db->trans_start();
                $this->db->where('id', $id);
                $this->db->update('proforma', $proforma);
                
                $this->db->where('proforma_id', $id);
                $this->db->delete('proforma_items');
                $this->storeItems($id, $proforma);

            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        } else {
            $this->db->trans_start();
                $this->db->insert("proforma", $proforma);
                $id=$this->db->insert_id();

                $this->storeItems($id, $proforma);
            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        }
    }
   
  
    public function storeItems($id, $proforma)
    {
        $itemsArray = array();
        foreach ($proforma->items as $fila) {
            $item=new stdclass();
            $item->proforma_id = $id;
            $item->articulo_id = $fila->id;
            $item->descripcion = strtoupper($fila->descrip);
            $item->tiempoEntrega = strtoupper($fila->tiempoEntrega);
            $item->marca = strtoupper($fila->marca);
            $item->industria = strtoupper($fila->industria);
            $item->cantidad = round($fila->cantidad,2);
            $item->precio = round($fila->precioLista,2);
            $item->total = round($fila->total,2);

            array_push($itemsArray,$item);	
        }
        $this->db->insert_batch("proforma_items", $itemsArray);
    }
    


    public function getNumMov($gestion,$alm)
    {
        $sql="  SELECT
                    p.num + 1 AS numDoc
                FROM
                    proforma p
                WHERE
                    YEAR(p.fecha) = '$gestion'
                    AND p.almacen = '$alm'
                ORDER BY
                    p.num DESC
                LIMIT 1";
        
		$numDoc=$this->db->query($sql);
		
		return $numDoc->row() ? $numDoc->row()->numDoc : 1;
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

    public function getTipos()
	{ 
    	$sql="      SELECT
                        pt.id value,
                        pt.tipo tipo 
                    from
                        proforma_tipo pt
            ";
        $query=$this->db->query($sql);	
		return $query->result_array();
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


}
